<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Oauth;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Exception;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Redirect to Webex for login and consent on defined application scopes.
     *
     * @param Request $request
     * @return mixed
     *
     * @throws ValidationException
     */
    public function webexOauthRedirect(Request $request)
    {
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            $this->sendLockoutResponse($request);
        }

        $signed_url = URL::temporarySignedRoute('auth.webex', now()->addMinutes(10), [
            'id' => $request->session()->getId()
        ]);

        if (!$request->hasValidSignature()) {
            $msg = 'The login link is invalid, expired or already been used.';
            //TODO: Check if already in use.

            return $request->missing(['signature', 'expires', 'id']) ?
                view('auth.login', ['link' => $signed_url]) :
                view('auth.login', ['link' => $signed_url])
                    ->withErrors(['link' => $msg]);
        } else {
            abort_if($request->missing(['signature', 'expires', 'id']), 401);
            $request->session()->setId($request->get('id'));

            // by default, all Webex integrations have the spark:kms scope
            return Socialite::driver('webex')
                ->setScopes(['spark:all', 'spark:kms'])
                ->redirect();
        }
    }

    /**
     * Handle OAuth response from the Webex side.
     *
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    public function webexOauthCallback(Request $request)
    {
        $timestamp = now()->timestamp;
        $webex_provider = Socialite::driver('webex');

        try {
            $webex_identity = (array)$webex_provider->user();
            $webex_identity_validator = Validator::make($webex_identity, [
                'name' => ['nullable', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255'],
                'expiresIn' => ['required', 'integer'],
                'id' => ['required'],
                'refreshToken' => ['required'],
                'token' => ['required']
            ]);
            $validated_webex_identity = $webex_identity_validator->validated();
        } catch (Exception $e) {
            return redirect()
                ->route('login')
                ->withErrors(['email' => "Could not retrieve Webex OAuth access code"]);
        }

        $oauth_identity = [
            'provider' => 'webex',
            'access_token' => $validated_webex_identity['token'],
            'name' => $validated_webex_identity['name'],
            'email' => $validated_webex_identity['email'],
            'expires_at' => $validated_webex_identity['expiresIn'] + $timestamp,
            'id' => $validated_webex_identity['id'],
            'refresh_token' => $validated_webex_identity['refreshToken'],
            'timestamp' => $timestamp,
        ];

        $user = DB::transaction(function () use ($request, $oauth_identity) {
            $user = $this->upsertUser($oauth_identity);
            $this->upsertDevice($request->session()->getId(), $request->getHost(), $user);
            $this->upsertOauth($oauth_identity, $user);

            return $user;
        });

        $this->guard()->login($user);

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendLoginResponse($request);
    }

    /**
     * Upsert a record into the `users` table.
     *
     * @param array $identity
     * @return mixed
     */
    protected function upsertUser(array $identity)
    {
        return User::updateOrCreate(['email' => $identity['email']], [
            'name' => $identity['name'],
            'email_verified_at' => $identity['timestamp'],
        ]);
    }

    /**
     * Upsert a record into the `devices` table.
     *
     * @param User $user
     * @return mixed
     */
    private function upsertDevice(string $id, string $name, $user)
    {
        return Device::updateOrCreate(['id' => $id], [
            'name' => $name,
            'user_id' => $user->id,
        ]);
    }

    /**
     * Upsert a record into the `oauths` table.
     *
     * @param array $identity
     * @param User $user
     * @return mixed
     */
    protected function upsertOauth(array $identity, User $user)
    {
        return Oauth::updateOrCreate([
            'provider' => $identity['provider'],
            'id' => $identity['id']
        ], [
            'access_token' => $identity["access_token"],
            'expires_at' => $identity["expires_at"],
            'refresh_token' => $identity["refresh_token"],
            'user_id' => $user->id,
        ]);
    }

    /**
     * Handle a login request to the application.
     *
     */
    public function login(Request $request)
    {
        abort_if(!$request->hasValidSignature(), 401);
        abort_if($request->missing(['signature', 'expires', 'id']), 401);

        $device = Device::find($request->get('id'));

        if ($device) {
            $device->authorized_at = now();
            $device->save();

            $this->guard()->login($device->user);

            // If the login attempt was unsuccessful we will increment the number of attempts
            // to login and redirect the user back to the login form. Of course, when this
            // user surpasses their maximum number of attempts they will get locked out.
            $this->incrementLoginAttempts($request);

            return $this->sendLoginResponse($request);
        }

        return $this->sendFailedLoginResponse($request);
    }
}

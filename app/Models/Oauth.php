<?php

namespace App\Models;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Oauth
 *
 * @property string $id
 * @property string $provider
 * @property mixed $access_token
 * @property \Illuminate\Support\Carbon $expires_at
 * @property mixed $refresh_token
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Oauth newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Oauth newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Oauth query()
 * @method static \Illuminate\Database\Eloquent\Builder|Oauth whereAccessToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Oauth whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Oauth whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Oauth whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Oauth whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Oauth whereRefreshToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Oauth whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Oauth whereUserId($value)
 * @mixin \Eloquent
 */
class Oauth extends Model
{
    use HasFactory;

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'id',
        'provider',
        'access_token',
        'expires_at',
        'refresh_token',
        'user_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Encrypts a value if not already encrypted by the application.
     *
     * @param $value
     * @return mixed|string
     */
    protected function encryptToken($value)
    {
        try {
            decrypt($value);
        } catch (DecryptException $e) {
            $value = encrypt($value);
        }

        return $value;
    }

    /**
     * Accessor that decrypts `access_token`.
     *
     * @param $value
     * @return mixed
     */
    public function getAccessTokenAttribute($value)
    {
        return decrypt($value);
    }

    /**
     * Mutator that ensures `access_token` is encrypted.
     *
     * @param $value
     */
    public function setAccessTokenAttribute($value)
    {
        $this->attributes['access_token'] = $this->encryptToken($value);
    }

    /**
     * Accessor that decrypts `refresh_token`.
     *
     * @param $value
     * @return mixed
     */
    public function getRefreshTokenAttribute($value)
    {
        return decrypt($value);
    }

    /**
     * Mutator that ensures `refresh_token` is encrypted.
     *
     * @param $value
     */
    public function setRefreshTokenAttribute($value)
    {
        $this->attributes['refresh_token'] = $this->encryptToken($value);
    }

    /**
     * Retrieve the user for the oauth record.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

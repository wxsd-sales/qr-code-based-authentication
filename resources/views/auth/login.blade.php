@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if ($link ?? '')
                <div class="alert alert-primary text-justify lead" role="alert">
                    Seamlessly authenticate with Webex on systems with limited web or input
                    capability — Smart TVs, In-Car Displays or Apps running on
                    RoomOS devices (in kiosk mode).
                </div>
            @endif
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>
                <div class="card-body">
                    <div class="col">
                        @if (session('status'))
                            <span class="valid-feedback is-valid d-block" role="alert">
                                QR code based authentication complete!
                            </span>
                        @endif
                        @error('link')
                        <span class="invalid-feedback is-invalid d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="col">
                        @if ($link ?? '')
                            <div class="row">
                                <div class="col-lg-7">
                                    <div class="mx-auto align-self-center" style="width: 244px;">
                                        <canvas id="canvas"></canvas>
                                    </div>
                                </div>
                                <div class="col-lg-5 align-self-center">
                                    <p>Can't scan the QR code?</p>
                                    <code id="link">{{ $link }}</code>
                                </div>
                            </div>
                        @endif
                        <hr class="hr-text" data-content="OR">
                        @if (Route::has('auth.webex'))
                            <a class="btn btn-primary btn-block"
                               href="{{ route('auth.webex') }}">
                                {{ __('Login with Webex (OAuth 2.0)') }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const canvas = document.getElementById('canvas');

            function isAuthorized() {
                window.axios.post(document.getElementById('link').innerText)
                    .then(response => {
                        console.info(response)
                        location.reload();
                    })
                    .catch(error => {
                        console.error(error)
                    })
            }

            function checkAuthorized() {
                setInterval(isAuthorized, 5000)
            }

            if(canvas) {
                window.qrcode.toCanvas(canvas, document.getElementById('link').innerText, function (error) {
                    if (error) console.error(error)
                })

                checkAuthorized()
            }

        })
    </script>
@endsection

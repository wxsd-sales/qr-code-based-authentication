@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <div class="col">
                    @if ($link ?? '')
                        <div class="alert alert-primary text-justify font-weight-bold" role="alert">
                            Seamlessly authenticate with Webex on systems with limited web or input
                            capability like: Smart TVs, In-Car Displays or Apps running on
                            RoomOS devices.
                        </div>
                        <hr>
                        <label for="link" class="d-none">Login Link</label>
                        <textarea id="link" class="form-control" rows="3" disabled>{{ $link }}</textarea>
                        <div class="mx-auto" style="width: 200px;">
                            <canvas id="canvas"></canvas>
                        </div>
                    @elseif (Route::has('auth.webex'))
                        <a class="btn btn-primary btn-block" href="{{ route('auth.webex') }}">
                            {{ __('Login with Webex (OAuth 2.0)') }}
                        </a>
                    @endif
                    </div>
                    <div class="col">
                    @error('link')
                        <span class="invalid-feedback is-invalid d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
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
            var canvas = document.getElementById('canvas')

            function isAuthorized() {
                window.axios.post(document.getElementById('link').value)
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
                window.qrcode.toCanvas(canvas, document.getElementById('link').value, function (error) {
                    if (error) console.error(error)
                })

                checkAuthorized()
            }

        })
    </script>
@endsection

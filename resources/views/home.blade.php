@extends('layouts.app')

@section('css')
{{--    <link rel="stylesheet" href="https://code.s4d.io/widget-recents/production/main.css">--}}

{{--    <style>--}}
{{--        #my-space-widget {--}}
{{--            height:1024px--}}
{{--        }--}}
{{--    </style>--}}
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="alert alert-success text-justify lead" role="alert">
                    {{ __('You are logged in!') }}
                </div>

                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div class="form-group row">
                            <label for="access_token"
                                   class="col-md-4 col-form-label text-md-right">{{ __('Access Token') }}</label>

                            <div class="col-md-6">
                                <input id="access_token" type="email" class="form-control" name="access_token"
                                       value="{{ auth()->user()->oauths[0]['access_token'] }}" required
                                       disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="refresh_token"
                                   class="col-md-4 col-form-label text-md-right">{{ __('Refresh Token') }}</label>

                            <div class="col-md-6">
                                <input id="refresh_token" type="email" class="form-control" name="refresh_token"
                                       value="{{ auth()->user()->oauths[0]['refresh_token'] }}" required
                                       disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="expires_at"
                                   class="col-md-4 col-form-label text-md-right">{{ __('Expires At') }}</label>

                            <div class="col-md-6">
                                <input id="expires_at" type="email" class="form-control" name="refresh_token"
                                       value="{{ auth()->user()->oauths[0]['expires_at'] }}" required
                                       disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid my-5">
        <div class="row justify-content-center">
            <div id="my-space-widget" class="col-md-12"></div>
        </div>
    </div>
@endsection

@section('js')
{{--    <script src="https://code.s4d.io/widget-recents/production/bundle.js"></script>--}}
{{--    <script>--}}
{{--        // Grab DOM element where widget will be attached--}}
{{--        var widgetEl = document.getElementById('my-space-widget');--}}

{{--        // Initialize a new Space widget--}}
{{--        webex.widget(widgetEl).recentsWidget({--}}
{{--            accessToken: '{{ auth()->user()->oauths[0]['access_token'] }}'--}}
{{--        });--}}
{{--    </script>--}}
@endsection

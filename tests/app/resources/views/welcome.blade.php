<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }

            .top-right a.active {
                color: red;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="content">
                @php
                    $sessionName = config('flipbox-sdk.locale.session');
                    $currentLocale = session($sessionName, config('app.locale', config('app.fallback_locale')));
                @endphp
                <div class="top-right links">
                    <a
                        dusk="en-link" href="{{ url('/lang/en') }}"
                        active="{{ $currentLocale === 'en' ? 'yes' : 'no' }}"
                        class="{{ $currentLocale === 'en' ? 'active' : '' }}"
                    >
                        EN
                    </a>

                    <a
                        dusk="id-link" href="{{ url('/lang/id') }}"
                        active="{{ $currentLocale === 'id' ? 'yes' : 'no' }}"
                        class="{{ $currentLocale === 'id' ? 'active' : '' }}"
                    >
                        ID
                    </a>
                </div>

                <div class="title m-b-md">
                    Flipbox CMS SDK
                </div>


                <div class="directive-{{ $currentLocale }}">
                    <h3>Directive: {{ $currentLocale }}</h3>

                    <ul>
                    @foreach ($expectations[$currentLocale] as $key => $result)
                        @php
                            $default = '';

                            if (is_array($result)) {
                                ['result' => $result, 'default' => $default] = $result;
                            }
                        @endphp

                        <li key="{{ $key }}" default="{{ $default }}">@translate($key, $default)</li>
                    @endforeach
                    </ul>
                </div>

                <hr>

                <div class="function-{{ $currentLocale }}">
                    <h3>Function: {{ $currentLocale }}</h3>

                    <ul>
                    @foreach ($expectations[$currentLocale] as $key => $result)
                        @php
                            $default = '';

                            if (is_array($result)) {
                                ['result' => $result, 'default' => $default] = $result;
                            }
                        @endphp

                        <li key="{{ $key }}" default="{{ $default }}">{{ translate($key, $default) }}</li>
                    @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </body>
</html>

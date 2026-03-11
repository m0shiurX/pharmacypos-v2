@extends('layouts.auth2')
@section('title', __('lang_v1.login'))
@inject('request', 'Illuminate\Http\Request')
@section('content')
    @php
        $username = old('username');
        $password = null;
        if (config('app.env') == 'demo') {
            $username = 'admin';
            $password = '123456';

            $demo_types = [
                'all_in_one' => 'admin',
                'super_market' => 'admin',
                'pharmacy' => 'admin-pharmacy',
                'electronics' => 'admin-electronics',
                'services' => 'admin-services',
                'restaurant' => 'admin-restaurant',
                'superadmin' => 'superadmin',
                'woocommerce' => 'woocommerce_user',
                'essentials' => 'admin-essentials',
                'manufacturing' => 'manufacturer-demo',
            ];

            if (!empty($_GET['demo_type']) && array_key_exists($_GET['demo_type'], $demo_types)) {
                $username = $demo_types[$_GET['demo_type']];
            }
        }
    @endphp
    <div class="tw-flex tw-items-center tw-justify-center tw-min-h-[80vh] tw-w-full">
        <div class="tw-w-full tw-mx-auto" style="max-width: 420px;">
            <div class="tw-p-8 md:tw-p-10 tw-rounded-2xl tw-bg-white tw-shadow-xl tw-ring-1 tw-ring-gray-100">
                {{-- Header --}}
                <div class="tw-text-center tw-mb-8">
                    <div class="tw-inline-flex tw-items-center tw-justify-center tw-w-14 tw-h-14 tw-rounded-full tw-bg-emerald-50 tw-mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="tw-w-7 tw-h-7 tw-text-emerald-600" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M5 13a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2z" />
                            <path d="M8 11v-4a4 4 0 1 1 8 0v4" />
                            <path d="M12 16m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                        </svg>
                    </div>
                    <h1 class="tw-text-2xl tw-font-bold tw-text-gray-900">
                        @lang('lang_v1.welcome_back')
                    </h1>
                    <p class="tw-text-sm tw-text-gray-500 tw-mt-1">
                        @lang('lang_v1.login_to_your') {{ config('app.name', 'PharmacyPOS') }}
                    </p>
                </div>

                <form method="POST" action="{{ route('login') }}" id="login-form">
                    {{ csrf_field() }}

                    {{-- Username --}}
                    <div class="tw-mb-5">
                        <label for="username" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-1.5">@lang('lang_v1.username')</label>
                        <div class="tw-relative">
                            <div class="tw-absolute tw-inset-y-0 tw-left-0 tw-pl-3.5 tw-flex tw-items-center tw-pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="tw-w-5 tw-h-5 tw-text-gray-400" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                    <path d="M16 12v1.5a2.5 2.5 0 0 0 5 0v-1.5a9 9 0 1 0 -5.5 8.28" />
                                </svg>
                            </div>
                            <input
                                class="tw-w-full tw-border tw-border-gray-300 tw-outline-none tw-h-12 tw-bg-gray-50 tw-rounded-xl tw-pl-11 tw-pr-4 tw-text-sm tw-font-medium tw-text-gray-900 placeholder:tw-text-gray-400 focus:tw-border-emerald-500 focus:tw-ring-2 focus:tw-ring-emerald-500/20 focus:tw-bg-white tw-transition-all"
                                id="username" type="text" name="username" required autofocus
                                placeholder="@lang('lang_v1.username')" value="{{ $username }}" />
                        </div>
                        @if ($errors->has('username'))
                            <p class="tw-text-red-500 tw-text-xs tw-mt-1">{{ $errors->first('username') }}</p>
                        @endif
                    </div>

                    {{-- Password --}}
                    <div class="tw-mb-5">
                        <div class="tw-flex tw-items-center tw-justify-between tw-mb-1.5">
                            <label for="password" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">@lang('lang_v1.password')</label>
                            @if (config('app.env') != 'demo')
                                <a href="{{ route('password.request') }}"
                                    class="tw-text-xs tw-font-semibold tw-text-emerald-600 hover:tw-text-emerald-700"
                                    tabindex="-1">@lang('lang_v1.forgot_your_password')</a>
                            @endif
                        </div>
                        <div class="tw-relative">
                            <div class="tw-absolute tw-inset-y-0 tw-left-0 tw-pl-3.5 tw-flex tw-items-center tw-pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="tw-w-5 tw-h-5 tw-text-gray-400" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M5 13a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2z" />
                                    <path d="M8 11v-4a4 4 0 1 1 8 0v4" />
                                    <path d="M12 16m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                </svg>
                            </div>
                            <input
                                class="tw-w-full tw-border tw-border-gray-300 tw-outline-none tw-h-12 tw-bg-gray-50 tw-rounded-xl tw-pl-11 tw-pr-12 tw-text-sm tw-font-medium tw-text-gray-900 placeholder:tw-text-gray-400 focus:tw-border-emerald-500 focus:tw-ring-2 focus:tw-ring-emerald-500/20 focus:tw-bg-white tw-transition-all"
                                id="password" type="password" name="password" value="{{ $password }}" required
                                placeholder="@lang('lang_v1.password')" />
                            <button type="button" id="show_hide_icon" class="tw-absolute tw-inset-y-0 tw-right-0 tw-pr-3.5 tw-flex tw-items-center tw-text-gray-400 hover:tw-text-gray-600 tw-transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye tw-w-5 tw-h-5" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                    <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                </svg>
                            </button>
                        </div>
                        @if ($errors->has('password'))
                            <p class="tw-text-red-500 tw-text-xs tw-mt-1">{{ $errors->first('password') }}</p>
                        @endif
                    </div>

                    {{-- Remember me --}}
                    <div class="tw-flex tw-items-center tw-mb-6">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}
                            class="tw-w-4 tw-h-4 tw-rounded tw-border-gray-300 tw-text-emerald-600 focus:tw-ring-emerald-500">
                        <label for="remember" class="tw-ml-2 tw-text-sm tw-text-gray-600">@lang('lang_v1.remember_me')</label>
                    </div>

                    @if(config('constants.enable_recaptcha'))
                    <div class="tw-mb-5">
                        <div class="g-recaptcha" data-sitekey="{{ config('constants.google_recaptcha_key') }}"></div>
                        @if ($errors->has('g-recaptcha-response'))
                            <p class="tw-text-red-500 tw-text-xs tw-mt-1">{{ $errors->first('g-recaptcha-response') }}</p>
                        @endif
                    </div>
                    @endif

                    {{-- Login Button --}}
                    <button type="submit"
                        class="tw-w-full tw-h-12 tw-rounded-xl tw-text-sm tw-font-semibold tw-text-white tw-bg-emerald-600 hover:tw-bg-emerald-700 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-emerald-500 focus:tw-ring-offset-2 active:tw-bg-emerald-800 tw-transition-all tw-duration-200 tw-shadow-sm hover:tw-shadow-md">
                        @lang('lang_v1.login')
                    </button>
                </form>
            </div>

            {{-- Footer --}}
            <p class="tw-text-center tw-text-xs tw-text-white/70 tw-mt-6">
                &copy; {{ date('Y') }} {{ config('app.name', 'PharmacyPOS') }}
            </p>
        </div>
    </div>

@stop
@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#show_hide_icon').off('click');
            $('.change_lang').click(function() {
                window.location = "{{ route('login') }}?lang=" + $(this).attr('value');
            });
            $('a.demo-login').click(function(e) {
                e.preventDefault();
                $('#username').val($(this).data('admin'));
                $('#password').val("{{ $password }}");
                $('form#login-form').submit();
            });

            $('#show_hide_icon').on('click', function(e) {
                e.preventDefault();
                const passwordInput = $('#password');
                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    $(this).html('<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye-off tw-w-5 tw-h-5" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.585 10.587a2 2 0 0 0 2.829 2.828"/><path d="M16.681 16.673a8.717 8.717 0 0 1 -4.681 1.327c-3.6 0 -6.6 -2 -9 -6c1.272 -2.12 2.712 -3.678 4.32 -4.674m2.86 -1.146a9.055 9.055 0 0 1 1.82 -.18c3.6 0 6.6 2 9 6c-.666 1.11 -1.379 2.067 -2.138 2.87"/><path d="M3 3l18 18"/></svg>');
                } else {
                    passwordInput.attr('type', 'password');
                    $(this).html('<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye tw-w-5 tw-h-5" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"/><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"/></svg>');
                }
            });
        })
    </script>
@endsection

@inject('request', 'Illuminate\Http\Request')
<!-- Main Header -->

<div
    class="  tw-transition-all tw-duration-5000 tw-border-b tw-bg-gradient-to-r tw-from-@if(!empty(session('business.theme_color'))){{session('business.theme_color')}}@else{{'green'}}@endif-800 tw-to-@if(!empty(session('business.theme_color'))){{session('business.theme_color')}}@else{{'green'}}@endif-900 tw-shrink-0 lg:tw-h-15 tw-border-green-500/30 no-print">
    <div class="tw-px-5 tw-py-3">
        <div class="tw-flex tw-items-start tw-justify-between tw-gap-6 lg:tw-items-center">
            <div class="tw-flex tw-items-center tw-gap-3">
                <button type="button" 
                    class="small-view-button xl:tw-w-20 lg:tw-hidden tw-inline-flex tw-items-center tw-justify-center tw-text-sm tw-font-medium tw-text-white tw-transition-all tw-duration-200 tw-bg-@if(!empty(session('business.theme_color'))){{session('business.theme_color')}}@else{{'green'}}@endif-800 hover:tw-bg-@if(!empty(session('business.theme_color'))){{session('business.theme_color')}}@else{{'green'}}@endif-700 tw-p-1.5 tw-rounded-lg tw-ring-1 hover:tw-text-white tw-ring-white/10">
                    <span class="tw-sr-only">
                        Sidebar Menu
                    </span>
                    <svg aria-hidden="true" class="tw-size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M4 6l16 0" />
                        <path d="M4 12l16 0" />
                        <path d="M4 18l16 0" />
                    </svg>
                </button>

                <button type="button"
                    class="side-bar-collapse tw-hidden lg:tw-inline-flex tw-items-center tw-justify-center tw-text-sm tw-font-medium tw-text-white tw-transition-all tw-duration-200 tw-bg-@if(!empty(session('business.theme_color'))){{session('business.theme_color')}}@else{{'green'}}@endif-800 hover:tw-bg-@if(!empty(session('business.theme_color'))){{session('business.theme_color')}}@else{{'green'}}@endif-700 tw-p-1.5 tw-rounded-lg tw-ring-1 hover:tw-text-white tw-ring-white/10">
                    <span class="tw-sr-only">
                        Collapse Sidebar
                    </span>
                    <svg aria-hidden="true" class="tw-size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" />
                        <path d="M15 4v16" />
                        <path d="M10 10l-2 2l2 2" />
                    </svg>
                </button>
            </div>


            {{-- Showing active package for SaaS Superadmin --}}
            @if(Module::has('Superadmin'))
                @includeIf('superadmin::layouts.partials.active_subscription')
            @endif

            {{-- When using superadmin, this button is used to switch users --}}
            @if(!empty(session('previous_user_id')) && !empty(session('previous_username')))
                <a href="{{route('sign-in-as-user', session('previous_user_id'))}}" class="btn btn-flat btn-danger m-8 btn-sm mt-10"><i class="fas fa-undo"></i> @lang('lang_v1.back_to_username', ['username' => session('previous_username')] )</a>
            @endif


            <div class="tw-flex tw-flex-wrap tw-items-center tw-justify-end tw-gap-3">
                @if (Module::has('Essentials'))
                    @includeIf('essentials::layouts.partials.header_part')
                @endif
                <details class="tw-dw-dropdown tw-relative tw-inline-block tw-text-left">
                    <summary
                        class="tw-inline-flex tw-transition-all tw-ring-1 tw-ring-white/10 hover:tw-text-white tw-cursor-pointer tw-duration-200 tw-bg-@if(!empty(session('business.theme_color'))){{session('business.theme_color')}}@else{{'green'}}@endif-800 hover:tw-bg-@if(!empty(session('business.theme_color'))){{session('business.theme_color')}}@else{{'green'}}@endif-700 tw-py-1.5 tw-px-3 tw-rounded-lg tw-items-center tw-justify-center tw-text-sm tw-font-medium tw-text-white tw-gap-1">
                        <svg aria-hidden="true" class="tw-size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                            <path d="M9 12h6" />
                            <path d="M12 9v6" />
                        </svg>
                    </summary>
                    <ul class="tw-dw-menu tw-dw-dropdown-content tw-dw-z-[1] tw-dw-bg-base-100 tw-dw-rounded-box tw-w-48 tw-absolute tw-left-0 tw-z-10 tw-mt-2 tw-origin-top-right tw-bg-white tw-rounded-lg tw-shadow-lg tw-ring-1 tw-ring-gray-200 focus:tw-outline-none"
                        role="menu" tabindex="-1">
                        <div class="tw-p-2" role="none">
                            @can('product.create')
                            <a href="{{ action([\App\Http\Controllers\ProductController::class, 'create']) }}"
                                class="tw-flex tw-items-center tw-gap-2 tw-px-3 tw-py-2 tw-text-sm tw-font-medium tw-text-gray-600 tw-transition-all tw-duration-200 tw-rounded-lg hover:tw-text-gray-900 hover:tw-bg-gray-100"
                                role="menuitem" tabindex="-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="tw-w-5 tw-h-5" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M12 3l8 4.5v9l-8 4.5l-8 -4.5v-9l8 -4.5"/>
                                    <path d="M12 12l8 -4.5"/><path d="M12 12v9"/><path d="M12 12l-8 -4.5"/>
                                </svg>
                                @lang('product.add_product')
                            </a>
                            @endcan
                            @can('direct_sell.access')
                            <a href="{{ action([\App\Http\Controllers\SellController::class, 'create']) }}"
                                class="tw-flex tw-items-center tw-gap-2 tw-px-3 tw-py-2 tw-text-sm tw-font-medium tw-text-gray-600 tw-transition-all tw-duration-200 tw-rounded-lg hover:tw-text-gray-900 hover:tw-bg-gray-100"
                                role="menuitem" tabindex="-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="tw-w-5 tw-h-5" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M12 3v12"/><path d="M16 11l-4 4l-4 -4"/>
                                    <path d="M3 12a9 9 0 0 0 18 0"/>
                                </svg>
                                @lang('lang_v1.wholesale')
                            </a>
                            @endcan
                            @can('expense.add')
                            <a href="{{ action([\App\Http\Controllers\ExpenseController::class, 'create']) }}"
                                class="tw-flex tw-items-center tw-gap-2 tw-px-3 tw-py-2 tw-text-sm tw-font-medium tw-text-gray-600 tw-transition-all tw-duration-200 tw-rounded-lg hover:tw-text-gray-900 hover:tw-bg-gray-100"
                                role="menuitem" tabindex="-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="tw-w-5 tw-h-5" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16l-3 -2l-2 2l-2 -2l-2 2l-2 -2l-3 2"/>
                                    <path d="M14.8 8a2 2 0 0 0 -1.8 -1h-2a2 2 0 1 0 0 4h2a2 2 0 1 1 0 4h-2a2 2 0 0 1 -1.8 -1"/>
                                    <path d="M12 6v10"/>
                                </svg>
                                @lang('expense.add_expense')
                            </a>
                            @endcan
                            @can('purchase.create')
                            <a href="{{ action([\App\Http\Controllers\PurchaseController::class, 'create']) }}"
                                class="tw-flex tw-items-center tw-gap-2 tw-px-3 tw-py-2 tw-text-sm tw-font-medium tw-text-gray-600 tw-transition-all tw-duration-200 tw-rounded-lg hover:tw-text-gray-900 hover:tw-bg-gray-100"
                                role="menuitem" tabindex="-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="tw-w-5 tw-h-5" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M12 3v12"/><path d="M16 11l-4 4l-4 -4"/>
                                    <path d="M3 12a9 9 0 0 0 18 0"/>
                                </svg>
                                @lang('purchase.add_purchase')
                            </a>
                            @endcan
                        </div>
                    </ul>

                </details>


                {{-- data-toggle="popover" remove this for on hover show --}}

                <button id="btnCalculator" title="@lang('lang_v1.calculator')" data-content='@include('layouts.partials.calculator')'
                    type="button" data-trigger="click" data-html="true" data-placement="bottom" 
                    class="tw-hidden md:tw-inline-flex tw-items-center tw-justify-center tw-text-sm tw-font-medium tw-text-white tw-transition-all tw-duration-200 tw-bg-@if(!empty(session('business.theme_color'))){{session('business.theme_color')}}@else{{'green'}}@endif-800 hover:tw-bg-@if(!empty(session('business.theme_color'))){{session('business.theme_color')}}@else{{'green'}}@endif-700 tw-p-1.5 tw-rounded-lg tw-ring-1 hover:tw-text-white tw-ring-white/10">
                    <span class="tw-sr-only" aria-hidden="true">
                        Calculator
                    </span>
                    <svg aria-hidden="true" class="tw-size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M4 3m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" />
                        <path d="M8 7m0 1a1 1 0 0 1 1 -1h6a1 1 0 0 1 1 1v1a1 1 0 0 1 -1 1h-6a1 1 0 0 1 -1 -1z" />
                        <path d="M8 14l0 .01" />
                        <path d="M12 14l0 .01" />
                        <path d="M16 14l0 .01" />
                        <path d="M8 17l0 .01" />
                        <path d="M12 17l0 .01" />
                        <path d="M16 17l0 .01" />
                    </svg>
                </button>

                @if (in_array('pos_sale', $enabled_modules))
                    @can('sell.create')
                        <a href="{{ action([\App\Http\Controllers\SellPosController::class, 'create']) }}"
                            class="sm:tw-inline-flex tw-transition-all tw-duration-200 tw-gap-2 tw-bg-@if(!empty(session('business.theme_color'))){{session('business.theme_color')}}@else{{'green'}}@endif-800 hover:tw-bg-@if(!empty(session('business.theme_color'))){{session('business.theme_color')}}@else{{'green'}}@endif-700 tw-py-1.5 tw-px-3 tw-rounded-lg tw-items-center tw-justify-center tw-text-sm tw-font-medium tw-ring-1 tw-ring-white/10 hover:tw-text-white tw-text-white">
                            <svg aria-hidden="true" class="tw-size-5 tw-hidden md:tw-block" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M4 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                                <path d="M14 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                                <path d="M4 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                                <path d="M14 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                            </svg>
                            @lang('sale.pos_sale')
                        </a>
                    @endcan
                @endif
                @if (Module::has('Repair'))
                    @includeIf('repair::layouts.partials.header')
                @endif
                @can('profit_loss_report.view')
                    <button type="button" type="button" id="view_todays_profit" title="{{ __('home.todays_profit') }}"
                        data-toggle="tooltip" data-placement="bottom"
                        class="tw-hidden sm:tw-inline-flex tw-items-center tw-ring-1 tw-ring-white/10 tw-justify-center tw-text-sm tw-font-medium tw-text-white hover:tw-text-white tw-transition-all tw-duration-200 tw-bg-@if(!empty(session('business.theme_color'))){{session('business.theme_color')}}@else{{'green'}}@endif-800 hover:tw-bg-@if(!empty(session('business.theme_color'))){{session('business.theme_color')}}@else{{'green'}}@endif-700 tw-p-1.5 tw-rounded-lg">
                        <span class="tw-sr-only">
                            Today's Profit
                        </span>
                        <svg aria-hidden="true" class="tw-size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                            <path d="M3 6m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" />
                            <path d="M18 12l.01 0" />
                            <path d="M6 12l.01 0" />
                        </svg>
                    </button>
                @endcan

                <button type="button"
                    class="tw-hidden lg:tw-inline-flex tw-transition-all tw-ring-1 tw-ring-white/10 tw-duration-200 tw-bg-@if(!empty(session('business.theme_color'))){{session('business.theme_color')}}@else{{'green'}}@endif-800 hover:tw-bg-@if(!empty(session('business.theme_color'))){{session('business.theme_color')}}@else{{'green'}}@endif-700 tw-py-1.5 tw-px-3 tw-rounded-lg tw-items-center tw-justify-center tw-text-sm tw-font-medium tw-text-white hover:tw-text-white tw-font-mono">
                    {{ @format_date('now') }}
                </button>

                @include('layouts.partials.header-notifications')



                <details class="tw-dw-dropdown tw-relative tw-inline-block tw-text-left">
                    <summary data-toggle="popover"
                        class="tw-dw-m-1 tw-inline-flex tw-transition-all tw-ring-1 tw-ring-white/10 tw-cursor-pointer tw-duration-200 tw-bg-@if(!empty(session('business.theme_color'))){{session('business.theme_color')}}@else{{'green'}}@endif-800 hover:tw-bg-@if(!empty(session('business.theme_color'))){{session('business.theme_color')}}@else{{'green'}}@endif-700 tw-py-1.5 tw-px-3 tw-rounded-lg tw-items-center tw-justify-center tw-text-sm tw-font-medium tw-text-white hover:tw-text-white tw-gap-1">
                        <span class="tw-hidden md:tw-block">{{ Auth::User()->first_name }} {{ Auth::User()->last_name }}</span>

                        <svg  xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="tw-size-5"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 10m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855" /></svg>

                        
                        
                    </summary>

                    <ul class="tw-p-2 tw-w-48 tw-absolute tw-right-0 tw-z-10 tw-mt-2 tw-origin-top-right tw-bg-white tw-rounded-lg tw-shadow-lg tw-ring-1 tw-ring-gray-200 focus:tw-outline-none"
                        role="menu" tabindex="-1">
                        <div class="tw-px-4 tw-pt-3 tw-pb-1" role="none">
                            <p class="tw-text-sm" role="none">
                                @lang('lang_v1.signed_in_as')
                            </p>
                            <p class="tw-text-sm tw-font-medium tw-text-gray-900 tw-truncate" role="none">
                                {{ Auth::User()->first_name }} {{ Auth::User()->last_name }}
                            </p>
                        </div>

                        <li>
                            <a href="{{ action([\App\Http\Controllers\UserController::class, 'getProfile']) }}"
                                class="tw-flex tw-items-center tw-gap-2 tw-px-3 tw-py-2 tw-text-sm tw-font-medium tw-text-gray-600 tw-transition-all tw-duration-200 tw-rounded-lg hover:tw-text-gray-900 hover:tw-bg-gray-100"
                                role="menuitem" tabindex="-1">
                                <svg aria-hidden="true" class="tw-w-5 tw-h-5" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                    <path d="M12 10m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                    <path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855" />
                                </svg>
                                @lang('lang_v1.profile')
                            </a>
                        </li>
                        <li class="tw-border-t tw-border-gray-100 tw-my-1"></li>
                        <li class="tw-px-3 tw-pt-1 tw-pb-0.5">
                            <span class="tw-text-[10px] tw-font-semibold tw-uppercase tw-tracking-wider tw-text-gray-400">@lang('lang_v1.language')</span>
                        </li>
                        @foreach (config('constants.langs') as $langKey => $langVal)
                            @php $isActiveLang = session('user.language', config('app.locale')) == $langKey; @endphp
                            <li>
                                <form method="POST" action="{{ route('change-language') }}">
                                    @csrf
                                    <input type="hidden" name="language" value="{{ $langKey }}">
                                    <button type="submit"
                                        class="tw-flex tw-items-center tw-justify-between tw-px-3 tw-py-2 tw-text-sm tw-font-medium tw-w-full tw-text-left tw-transition-all tw-duration-200 tw-rounded-lg hover:tw-bg-gray-100 {{ $isActiveLang ? 'tw-text-emerald-700 tw-bg-emerald-50' : 'tw-text-gray-600 hover:tw-text-gray-900' }}">
                                        <span>{{ $langVal['full_name'] }}</span>
                                        @if($isActiveLang)
                                            <svg xmlns="http://www.w3.org/2000/svg" class="tw-w-4 tw-h-4 tw-text-emerald-600" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" />
                                            </svg>
                                        @endif
                                    </button>
                                </form>
                            </li>
                        @endforeach
                        <li class="tw-border-t tw-border-gray-100 tw-my-1"></li>
                        <li>
                            <a href="{{ action([\App\Http\Controllers\Auth\LoginController::class, 'logout']) }}"
                                class="tw-flex tw-items-center tw-gap-2 tw-px-3 tw-py-2 tw-text-sm tw-font-medium tw-text-gray-600 tw-transition-all tw-duration-200 tw-rounded-lg hover:tw-text-gray-900 hover:tw-bg-gray-100"
                                role="menuitem" tabindex="-1">
                                <svg aria-hidden="true" class="tw-w-5 tw-h-5" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path
                                        d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                                    <path d="M9 12h12l-3 -3" />
                                    <path d="M18 15l3 -3" />
                                </svg>
                                @lang('lang_v1.sign_out')
                            </a>
                        </li>
                    </ul>
                </details>
            </div>
        </div>
    </div>
</div>

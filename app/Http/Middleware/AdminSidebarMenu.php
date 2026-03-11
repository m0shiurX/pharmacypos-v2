<?php

namespace App\Http\Middleware;

use App\Utils\ModuleUtil;
use Closure;
use Menu;

class AdminSidebarMenu
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->ajax()) {
            return $next($request);
        }

        Menu::create('admin-sidebar-menu', function ($menu) {
            $enabled_modules = !empty(session('business.enabled_modules')) ? session('business.enabled_modules') : [];

            $common_settings = !empty(session('business.common_settings')) ? session('business.common_settings') : [];
            $pos_settings = !empty(session('business.pos_settings')) ? json_decode(session('business.pos_settings'), true) : [];

            $is_admin = auth()->user()->hasRole('Admin#' . session('business.id')) ? true : false;

            $menu->url(action([\App\Http\Controllers\HomeController::class, 'index']), __('home.home'), ['icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="tw-size-5 tw-shrink-0" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M5 12l-2 0l9 -9l9 9l-2 0" />
            <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
            <path d="M10 12h4v4h-4z" />
          </svg>', 'active' => request()->segment(1) == 'home'])->order(5);

            //Contacts dropdown
            if (auth()->user()->can('supplier.view') || auth()->user()->can('customer.view') || auth()->user()->can('supplier.view_own') || auth()->user()->can('customer.view_own')) {
                $menu->dropdown(
                    __('contact.contacts'),
                    function ($sub) {
                        if (auth()->user()->can('supplier.view') || auth()->user()->can('supplier.view_own')) {
                            $sub->url(
                                action([\App\Http\Controllers\ContactController::class, 'index'], ['type' => 'supplier']),
                                __('report.supplier'),
                                ['icon' => '', 'active' => request()->input('type') == 'supplier']
                            );
                        }
                        if (auth()->user()->can('customer.view') || auth()->user()->can('customer.view_own')) {
                            $sub->url(
                                action([\App\Http\Controllers\ContactController::class, 'index'], ['type' => 'customer']),
                                __('report.customer'),
                                ['icon' => '', 'active' => request()->input('type') == 'customer']
                            );
                        }
                    },
                    ['icon' => '<svg aria-hidden="true" class="tw-size-5 tw-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M20 6v12a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2z"></path>
                    <path d="M10 16h6"></path>
                    <path d="M13 11m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                    <path d="M4 8h3"></path>
                    <path d="M4 12h3"></path>
                    <path d="M4 16h3"></path>
                  </svg>', 'id' => 'tour_step4']
                )->order(15);
            }

            //Products & Inventory dropdown
            if (
                auth()->user()->can('product.view') || auth()->user()->can('product.create') ||
                auth()->user()->can('brand.view') || auth()->user()->can('unit.view') ||
                auth()->user()->can('category.view') || auth()->user()->can('brand.create') ||
                auth()->user()->can('unit.create') || auth()->user()->can('category.create') ||
                (in_array('stock_transfers', $enabled_modules) && (auth()->user()->can('purchase.view') || auth()->user()->can('view_own_purchase'))) ||
                (in_array('stock_adjustment', $enabled_modules) && (auth()->user()->can('purchase.view') || auth()->user()->can('view_own_purchase')))
            ) {
                $menu->dropdown(
                    __('sale.products'),
                    function ($sub) use ($enabled_modules) {
                        if (auth()->user()->can('product.view')) {
                            $sub->url(
                                action([\App\Http\Controllers\ProductController::class, 'index']),
                                __('lang_v1.list_products'),
                                ['icon' => '', 'active' => request()->segment(1) == 'products' && request()->segment(2) == '']
                            );
                        }

                        if (auth()->user()->can('product.create')) {
                            $sub->url(
                                action([\App\Http\Controllers\ProductController::class, 'create']),
                                __('product.add_product'),
                                ['icon' => '', 'active' => request()->segment(1) == 'products' && request()->segment(2) == 'create']
                            );
                        }
                        if (auth()->user()->can('category.view') || auth()->user()->can('category.create')) {
                            $sub->url(
                                action([\App\Http\Controllers\TaxonomyController::class, 'index']) . '?type=product',
                                __('category.categories'),
                                ['icon' => '', 'active' => request()->segment(1) == 'taxonomies' && request()->get('type') == 'product']
                            );
                        }
                        if (auth()->user()->can('unit.view') || auth()->user()->can('unit.create')) {
                            $sub->url(
                                action([\App\Http\Controllers\UnitController::class, 'index']),
                                __('unit.units'),
                                ['icon' => '', 'active' => request()->segment(1) == 'units']
                            );
                        }
                        if (auth()->user()->can('brand.view') || auth()->user()->can('brand.create')) {
                            $sub->url(
                                action([\App\Http\Controllers\BrandController::class, 'index']),
                                __('brand.brands'),
                                ['icon' => '', 'active' => request()->segment(1) == 'brands']
                            );
                        }

                        //Stock Transfers
                        if (in_array('stock_transfers', $enabled_modules) && (auth()->user()->can('purchase.view') || auth()->user()->can('view_own_purchase'))) {
                            $sub->url(
                                action([\App\Http\Controllers\StockTransferController::class, 'index']),
                                __('lang_v1.list_stock_transfers'),
                                ['icon' => '', 'active' => request()->segment(1) == 'stock-transfers']
                            );
                        }

                        //Stock Adjustment
                        if (in_array('stock_adjustment', $enabled_modules) && (auth()->user()->can('purchase.view') || auth()->user()->can('view_own_purchase'))) {
                            $sub->url(
                                action([\App\Http\Controllers\StockAdjustmentController::class, 'index']),
                                __('stock_adjustment.list'),
                                ['icon' => '', 'active' => request()->segment(1) == 'stock-adjustments']
                            );
                        }
                    },
                    ['icon' => '<svg aria-hidden="true" class="tw-size-5 tw-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M12 3l8 4.5v9l-8 4.5l-8 -4.5v-9l8 -4.5"></path>
                    <path d="M12 12l8 -4.5"></path>
                    <path d="M8.2 9.8l7.6 -4.6"></path>
                    <path d="M12 12v9"></path>
                    <path d="M12 12l-8 -4.5"></path>
                  </svg>', 'id' => 'tour_step5']
                )->order(20);
            }

            //Purchase dropdown
            if (in_array('purchases', $enabled_modules) && (auth()->user()->can('purchase.view') || auth()->user()->can('purchase.create') || auth()->user()->can('purchase.update'))) {
                $menu->dropdown(
                    __('purchase.purchases'),
                    function ($sub) {
                        if (auth()->user()->can('purchase.view') || auth()->user()->can('view_own_purchase')) {
                            $sub->url(
                                action([\App\Http\Controllers\PurchaseController::class, 'index']),
                                __('purchase.list_purchase'),
                                ['icon' => '', 'active' => request()->segment(1) == 'purchases' && request()->segment(2) == null]
                            );
                        }
                        if (auth()->user()->can('purchase.create')) {
                            $sub->url(
                                action([\App\Http\Controllers\PurchaseController::class, 'create']),
                                __('purchase.add_purchase'),
                                ['icon' => '', 'active' => request()->segment(1) == 'purchases' && request()->segment(2) == 'create']
                            );
                        }
                        if (auth()->user()->can('purchase.update')) {
                            $sub->url(
                                action([\App\Http\Controllers\PurchaseReturnController::class, 'index']),
                                __('lang_v1.list_purchase_return'),
                                ['icon' => '', 'active' => request()->segment(1) == 'purchase-return']
                            );
                        }
                    },
                    ['icon' => '<svg aria-hidden="true" class="tw-size-5 tw-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M12 3v12"></path>
                    <path d="M16 11l-4 4l-4 -4"></path>
                    <path d="M3 12a9 9 0 0 0 18 0"></path>
                  </svg>', 'id' => 'tour_step6']
                )->order(25);
            }
            //Sell dropdown
            if ($is_admin || auth()->user()->hasAnyPermission(['sell.view', 'sell.create', 'direct_sell.access', 'view_own_sell_only', 'view_commission_agent_sell', 'access_shipping', 'access_own_shipping', 'access_commission_agent_shipping', 'access_sell_return', 'direct_sell.view', 'direct_sell.update', 'access_own_sell_return'])) {
                $menu->dropdown(
                    __('sale.sale'),
                    function ($sub) use ($enabled_modules, $is_admin, $pos_settings) {
                        if ($is_admin || auth()->user()->hasAnyPermission(['sell.view', 'sell.create', 'direct_sell.access', 'direct_sell.view', 'view_own_sell_only', 'view_commission_agent_sell', 'access_shipping', 'access_own_shipping', 'access_commission_agent_shipping'])) {
                            $sub->url(
                                action([\App\Http\Controllers\SellController::class, 'index']),
                                __('lang_v1.all_sales'),
                                ['icon' => '', 'active' => request()->segment(1) == 'sells' && request()->segment(2) == null]
                            );
                        }
                        if (auth()->user()->can('sell.create')) {
                            if (in_array('pos_sale', $enabled_modules)) {
                                $sub->url(
                                    action([\App\Http\Controllers\SellPosController::class, 'create']),
                                    __('sale.pos_sale'),
                                    ['icon' => '', 'active' => request()->segment(1) == 'pos' && request()->segment(2) == 'create']
                                );
                            }
                            if (auth()->user()->can('direct_sell.access')) {
                                $sub->url(
                                    action([\App\Http\Controllers\SellController::class, 'create']),
                                    __('lang_v1.wholesale'),
                                    ['icon' => '', 'active' => request()->segment(1) == 'sells' && request()->segment(2) == 'create']
                                );
                            }
                        }

                        if (in_array('add_sale', $enabled_modules) && ($is_admin || auth()->user()->hasAnyPermission(['draft.view_all', 'draft.view_own']))) {
                            $sub->url(
                                action([\App\Http\Controllers\SellController::class, 'getDrafts']),
                                __('lang_v1.list_drafts'),
                                ['icon' => '', 'active' => request()->segment(1) == 'sells' && request()->segment(2) == 'drafts']
                            );
                        }

                        if (auth()->user()->can('access_sell_return') || auth()->user()->can('access_own_sell_return')) {
                            $sub->url(
                                action([\App\Http\Controllers\SellReturnController::class, 'index']),
                                __('lang_v1.list_sell_return'),
                                ['icon' => '', 'active' => request()->segment(1) == 'sell-return' && request()->segment(2) == null]
                            );
                        }
                    },
                    ['icon' => '<svg aria-hidden="true" class="tw-size-5 tw-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M12 15v-12"></path>
                    <path d="M16 7l-4 -4l-4 4"></path>
                    <path d="M3 12a9 9 0 0 0 18 0"></path>
                  </svg>', 'id' => 'tour_step7']
                )->order(30);
            }


            //Expenses & Accounts dropdown
            if (
                (in_array('expenses', $enabled_modules) && (auth()->user()->can('all_expense.access') || auth()->user()->can('view_own_expense'))) ||
                (auth()->user()->can('account.access') && in_array('account', $enabled_modules))
            ) {
                $menu->dropdown(
                    __('expense.expenses'),
                    function ($sub) use ($enabled_modules) {
                        if (in_array('expenses', $enabled_modules) && (auth()->user()->can('all_expense.access') || auth()->user()->can('view_own_expense'))) {
                            $sub->url(
                                action([\App\Http\Controllers\ExpenseController::class, 'index']),
                                __('lang_v1.list_expenses'),
                                ['icon' => '', 'active' => request()->segment(1) == 'expenses' || request()->segment(1) == 'import-expense' && request()->segment(2) == null]
                            );

                            if (auth()->user()->can('expense.add') || auth()->user()->can('expense.edit')) {
                                $sub->url(
                                    action([\App\Http\Controllers\ExpenseCategoryController::class, 'index']),
                                    __('expense.expense_categories'),
                                    ['icon' => '', 'active' => request()->segment(1) == 'expense-categories']
                                );
                            }
                        }

                        //Payment Accounts
                        if (auth()->user()->can('account.access') && in_array('account', $enabled_modules)) {
                            $sub->url(
                                action([\App\Http\Controllers\AccountController::class, 'index']),
                                __('account.list_accounts'),
                                ['icon' => '', 'active' => request()->segment(1) == 'account' && request()->segment(2) == 'account']
                            );
                        }
                    },
                    ['icon' => ' <svg aria-hidden="true" class="tw-size-5 tw-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16l-3 -2l-2 2l-2 -2l-2 2l-2 -2l-3 2"></path>
                    <path d="M14.8 8a2 2 0 0 0 -1.8 -1h-2a2 2 0 1 0 0 4h2a2 2 0 1 1 0 4h-2a2 2 0 0 1 -1.8 -1"></path>
                    <path d="M12 6v10"></path>
                  </svg>']
                )->order(45);
            }

            //Reports dropdown
            if (
                auth()->user()->can('purchase_n_sell_report.view') || auth()->user()->can('contacts_report.view')
                || auth()->user()->can('stock_report.view') || auth()->user()->can('tax_report.view')
                || auth()->user()->can('trending_product_report.view') || auth()->user()->can('sales_representative.view') || auth()->user()->can('register_report.view')
                || auth()->user()->can('expense_report.view')
            ) {
                $menu->dropdown(
                    __('report.reports'),
                    function ($sub) use ($enabled_modules, $is_admin) {
                        if (auth()->user()->can('profit_loss_report.view')) {
                            $sub->url(
                                action([\App\Http\Controllers\ReportController::class, 'getProfitLoss']),
                                __('report.profit_loss'),
                                ['icon' => '', 'active' => request()->segment(2) == 'profit-loss']
                            );
                        }
                        if ((in_array('purchases', $enabled_modules) || in_array('add_sale', $enabled_modules) || in_array('pos_sale', $enabled_modules)) && auth()->user()->can('purchase_n_sell_report.view')) {
                            $sub->url(
                                action([\App\Http\Controllers\ReportController::class, 'getPurchaseSell']),
                                __('report.purchase_sell_report'),
                                ['icon' => '', 'active' => request()->segment(2) == 'purchase-sell']
                            );
                        }
                        if (auth()->user()->can('stock_report.view')) {
                            $sub->url(
                                action([\App\Http\Controllers\ReportController::class, 'getStockReport']),
                                __('report.stock_report'),
                                ['icon' => '', 'active' => request()->segment(2) == 'stock-report']
                            );
                            if (session('business.enable_product_expiry') == 1) {
                                $sub->url(
                                    action([\App\Http\Controllers\ReportController::class, 'getStockExpiryReport']),
                                    __('report.stock_expiry_report'),
                                    ['icon' => '', 'active' => request()->segment(2) == 'stock-expiry']
                                );
                            }
                        }
                        if (auth()->user()->can('tax_report.view')) {
                            $sub->url(
                                action([\App\Http\Controllers\ReportController::class, 'getTaxReport']),
                                __('report.tax_report'),
                                ['icon' => '', 'active' => request()->segment(2) == 'tax-report']
                            );
                        }
                        if (in_array('expenses', $enabled_modules) && auth()->user()->can('expense_report.view')) {
                            $sub->url(
                                action([\App\Http\Controllers\ReportController::class, 'getExpenseReport']),
                                __('report.expense_report'),
                                ['icon' => '', 'active' => request()->segment(2) == 'expense-report']
                            );
                        }
                        if (auth()->user()->can('sales_representative.view')) {
                            $sub->url(
                                action([\App\Http\Controllers\ReportController::class, 'getSalesRepresentativeReport']),
                                __('report.sales_representative'),
                                ['icon' => '', 'active' => request()->segment(2) == 'sales-representative-report']
                            );
                        }
                        if ($is_admin) {
                            $sub->url(
                                action([\App\Http\Controllers\ReportController::class, 'activityLog']),
                                __('lang_v1.activity_log'),
                                ['icon' => '', 'active' => request()->segment(2) == 'activity-log']
                            );
                        }
                    },
                    ['icon' => '<svg aria-hidden="true" class="tw-size-5 tw-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M8 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h5.697"></path>
                    <path d="M18 14v4h4"></path>
                    <path d="M18 11v-4a2 2 0 0 0 -2 -2h-2"></path>
                    <path d="M8 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z"></path>
                    <path d="M18 18m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path>
                    <path d="M8 11h4"></path>
                    <path d="M8 15h3"></path>
                  </svg>', 'id' => 'tour_step8']
                )->order(55);
            }


            //Booking menu
            if (in_array('booking', $enabled_modules) && (auth()->user()->can('crud_all_bookings') || auth()->user()->can('crud_own_bookings'))) {
                $menu->url(action([\App\Http\Controllers\Restaurant\BookingController::class, 'index']), __('restaurant.bookings'), ['icon' => '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-check"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M11.5 21h-5.5a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v6" /><path d="M16 3v4" /><path d="M8 3v4" /><path d="M4 11h16" /><path d="M15 19l2 2l4 -4" /></svg>', 'active' => request()->segment(1) == 'bookings'])->order(65);
            }

            //Kitchen menu
            if (in_array('kitchen', $enabled_modules)) {
                $menu->url(action([\App\Http\Controllers\Restaurant\KitchenController::class, 'index']), __('restaurant.kitchen'), ['icon' => '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-flame"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12c2 -2.96 0 -7 -1 -8c0 3.038 -1.773 4.741 -3 6c-1.226 1.26 -2 3.24 -2 5a6 6 0 1 0 12 0c0 -1.532 -1.056 -3.94 -2 -5c-1.786 3 -2.791 3 -4 2z" /></svg>', 'active' => request()->segment(1) == 'modules' && request()->segment(2) == 'kitchen'])->order(70);
            }

            //Service Staff menu
            if (in_array('service_staff', $enabled_modules)) {
                $menu->url(action([\App\Http\Controllers\Restaurant\OrderController::class, 'index']), __('restaurant.orders'), ['icon' => '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="18"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-baseline-density-medium"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h16" /><path d="M4 12h16" /><path d="M4 4h16" /></svg>', 'active' => request()->segment(1) == 'modules' && request()->segment(2) == 'orders'])->order(75);
            }

            //User management dropdown
            if (auth()->user()->can('user.view') || auth()->user()->can('user.create') || auth()->user()->can('roles.view')) {
                $menu->dropdown(
                    __('user.user_management'),
                    function ($sub) {
                        if (auth()->user()->can('user.view')) {
                            $sub->url(
                                action([\App\Http\Controllers\ManageUserController::class, 'index']),
                                __('user.users'),
                                ['icon' => '', 'active' => request()->segment(1) == 'users']
                            );
                        }
                        if (auth()->user()->can('roles.view')) {
                            $sub->url(
                                action([\App\Http\Controllers\RoleController::class, 'index']),
                                __('user.roles'),
                                ['icon' => '', 'active' => request()->segment(1) == 'roles']
                            );
                        }
                        if (auth()->user()->can('user.create')) {
                            $sub->url(
                                action([\App\Http\Controllers\SalesCommissionAgentController::class, 'index']),
                                __('lang_v1.sales_commission_agents'),
                                ['icon' => '', 'active' => request()->segment(1) == 'sales-commission-agents']
                            );
                        }
                    },
                    ['icon' => '<svg aria-hidden="true" class="tw-size-5 tw-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path>
                    <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    <path d="M21 21v-2a4 4 0 0 0 -3 -3.85"></path>
                  </svg>',]
                )->order(80);
            }

            //Settings Dropdown
            if (
                auth()->user()->can('business_settings.access') ||
                auth()->user()->can('invoice_settings.access') ||
                auth()->user()->can('tax_rate.view') ||
                auth()->user()->can('tax_rate.create') ||
                auth()->user()->can('backup') ||
                auth()->user()->can('send_notifications')
            ) {
                $menu->dropdown(
                    __('business.settings'),
                    function ($sub) use ($enabled_modules) {
                        if (auth()->user()->can('business_settings.access')) {
                            $sub->url(
                                action([\App\Http\Controllers\BusinessController::class, 'getBusinessSettings']),
                                __('business.business_settings'),
                                ['icon' => '', 'active' => request()->segment(1) == 'business', 'id' => 'tour_step2']
                            );
                        }
                        if (auth()->user()->can('invoice_settings.access')) {
                            $sub->url(
                                action([\App\Http\Controllers\InvoiceSchemeController::class, 'index']),
                                __('invoice.invoice_settings'),
                                ['icon' => '', 'active' => in_array(request()->segment(1), ['invoice-schemes', 'invoice-layouts'])]
                            );
                        }

                        if (auth()->user()->can('tax_rate.view') || auth()->user()->can('tax_rate.create')) {
                            $sub->url(
                                action([\App\Http\Controllers\TaxRateController::class, 'index']),
                                __('tax_rate.tax_rates'),
                                ['icon' => '', 'active' => request()->segment(1) == 'tax-rates']
                            );
                        }

                        //Notification Templates
                        if (auth()->user()->can('send_notifications')) {
                            $sub->url(
                                action([\App\Http\Controllers\NotificationTemplateController::class, 'index']),
                                __('lang_v1.notification_templates'),
                                ['icon' => '', 'active' => request()->segment(1) == 'notification-templates']
                            );
                        }

                        //Backup
                        if (auth()->user()->can('backup')) {
                            $sub->url(
                                action([\App\Http\Controllers\BackUpController::class, 'index']),
                                __('lang_v1.backup'),
                                ['icon' => '', 'active' => request()->segment(1) == 'backup']
                            );
                        }
                    },
                    ['icon' => '<svg aria-hidden="true" class="tw-size-5 tw-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z"></path>
                    <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0"></path>
                  </svg>', 'id' => 'tour_step3']
                )->order(85);
            }
        });

        //Add menus from modules
        $moduleUtil = new ModuleUtil;
        $moduleUtil->getModuleData('modifyAdminMenu');

        return $next($request);
    }
}

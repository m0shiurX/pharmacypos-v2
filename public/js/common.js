//This file contains all common functionality for the application
$(document).on('submit', 'form', function (e) {
    if (!__is_online()) {
        e.preventDefault();
        toastr.error(LANG.not_connected_to_a_network);
        return false;
    }

    $(this).find('button[type="submit"]').attr('disabled', true);
});
$(document).ready(function () {
    window.addEventListener('online', updateOnlineStatus);
    window.addEventListener('offline', updateOnlineStatus);

    $.ajaxSetup({
        beforeSend: function (jqXHR, settings) {
            if (!__is_online()) {
                toastr.error(LANG.not_connected_to_a_network);
                return false;
            }
            if (settings.url.indexOf('http') === -1) {
                settings.url = base_path + settings.url;
            }
        },
    });

    __register_ajax_failure_handler();
    __start_session_heartbeat();

    update_font_size();
    if ($('#status_span').length) {
        var status = $('#status_span').attr('data-status');
        if (status === '1') {
            toastr.success($('#status_span').attr('data-msg'));
        } else if (status == '' || status === '0') {
            toastr.error($('#status_span').attr('data-msg'));
        }
    }

    //Default setting for select2
    $.fn.select2.defaults.set('minimumResultsForSearch', 6);
    if ($('html').attr('dir') == 'rtl') {
        $.fn.select2.defaults.set('dir', 'rtl');
    }
    $.fn.datepicker.defaults.todayHighlight = true;
    $.fn.datepicker.defaults.autoclose = true;
    $.fn.datepicker.defaults.format = datepicker_date_format;

    //Toastr setting
    toastr.options.preventDuplicates = true;
    toastr.options.timeOut = '3000';

    //Play notification sound on success, error and warning
    toastr.options.onShown = function () {
        if ($(this).hasClass('toast-success')) {
            var audio = $('#success-audio')[0];
            if (audio !== undefined) {
                audio.play();
            }
        } else if ($(this).hasClass('toast-error')) {
            var audio = $('#error-audio')[0];
            if (audio !== undefined) {
                audio.play();
            }
        } else if ($(this).hasClass('toast-warning')) {
            var audio = $('#warning-audio')[0];
            if (audio !== undefined) {
                audio.play();
            }
        }
    };

    //Default setting for jQuey validator
    jQuery.validator.setDefaults({
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') && element.parent().hasClass('input-group')) {
                error.insertAfter(element.parent());
            } else if (element.hasClass('select2')) {
                error.insertAfter(element.next('span.select2-container'));
            } else if (element.parent().hasClass('input-group')) {
                error.insertAfter(element.parent());
            } else if (element.parent().hasClass('multi-input')) {
                error.insertAfter(element.closest('.multi-input'));
            } else if (element.parent().hasClass('input_inline')) {
                error.insertAfter(element.parent());
            } else if (element.hasClass('upload-element')) {
                error.insertAfter(element.closest('.input-group'));
            } else {
                error.insertAfter(element);
            }
        },

        invalidHandler: function () {
            toastr.error(LANG.some_error_in_input_field);
        },
    });

    jQuery.validator.addMethod(
        'max-value',
        function (value, element, param) {
            var is_draft = false;
            var is_pos_form = $('form#add_pos_sell_form').length > 0 || $('form#edit_pos_sell_form').length > 0;
            if (
                is_pos_form &&
                $(element).hasClass('pos_quantity') &&
                $('select#status').length &&
                $('select#status').val() !== 'final'
            ) {
                is_draft = true;
            }
            return is_draft || this.optional(element) || !(param < __number_uf(value));
        },
        function (params, element) {
            return $(element).data('msg-max-value');
        }
    );

    jQuery.validator.addMethod('abs_digit', function (value, element) {
        return this.optional(element) || Number.isInteger(Math.abs(__number_uf(value)));
    });

    //Set global currency to be used in the application
    __currency_symbol = $('input#__symbol').val();
    __currency_thousand_separator = $('input#__thousand').val();
    __currency_decimal_separator = $('input#__decimal').val();
    __currency_symbol_placement = $('input#__symbol_placement').val();
    if ($('input#__precision').length > 0) {
        __currency_precision = $('input#__precision').val();
    } else {
        __currency_precision = 2;
    }

    if ($('input#__quantity_precision').length > 0) {
        __quantity_precision = $('input#__quantity_precision').val();
    } else {
        __quantity_precision = 2;
    }

    //Set page level currency to be used for some pages. (Purchase page)
    if ($('input#p_symbol').length > 0) {
        __p_currency_symbol = $('input#p_symbol').val();
        __p_currency_thousand_separator = $('input#p_thousand').val();
        __p_currency_decimal_separator = $('input#p_decimal').val();
    }

    __currency_convert_recursively($(document), $('input#p_symbol').length);

    // Simple function to remove currency symbol and HTML tags from string
    function __remove_currency_symbol(str) {
        // DataTables can pass numbers, objects, null - convert all to string
        if (typeof str !== 'string') {
            str = String(str);
        }

        // HTML REMOVAL: Simple regex to remove HTML tags
        str = str.replace(/<[^>]*>/g, '');

        // Check 1: Variable exists, Check 2: Has value, Check 3: Symbol present in string
        if (typeof __currency_symbol !== 'undefined' && __currency_symbol && str.includes(__currency_symbol)) {
            // SIMPLE REPLACEMENT: Replace all occurrences of currency symbol with empty string
            str = str.split(__currency_symbol).join('');
        }

        return str.trim();
    }

    var buttons = [
        // {
        //     extend: 'copy',
        //     text: '<i class="fa fa-files-o" aria-hidden="true"></i> ' + LANG.copy,
        //     className: 'btn-sm',
        //     exportOptions: {
        //         columns: ':visible',
        //     },
        //     footer: true,
        // },
        {
            extend: 'csv',
            text: '<i class="fa fa-file-csv" aria-hidden="true"></i> ' + LANG.export_to_csv,
            className: 'tw-dw-btn-xs  tw-dw-btn tw-dw-btn-outline tw-my-2',
            exportOptions: {
                columns: ':visible',
                format: {
                    body: function (data, row, column, node) {
                        // Remove currency symbol from the cell data
                        return __remove_currency_symbol(data);
                    },
                    footer: function (data, row, column, node) {
                        // Remove currency symbol from the footer data
                        return __remove_currency_symbol(data);
                    }
                }
            },
            footer: true,
        },
        {
            extend: 'excel',
            text: '<i class="fa fa-file-excel" aria-hidden="true"></i> ' + LANG.export_to_excel,
            className: 'tw-dw-btn-xs  tw-dw-btn tw-dw-btn-outline tw-my-2',
            exportOptions: {
                columns: ':visible',
                format: {
                    body: function (data, row, column, node) {
                        // Remove currency symbol from the cell data
                        return __remove_currency_symbol(data);
                    },
                    footer: function (data, row, column, node) {
                        // Remove currency symbol from the footer data
                        return __remove_currency_symbol(data);
                    }
                }
            },
            footer: true,
        },
        {
            extend: 'print',
            text: '<i class="fa fa-print" aria-hidden="true"></i> ' + LANG.print,
            className: 'tw-dw-btn-xs  tw-dw-btn tw-dw-btn-outline tw-my-2',
            exportOptions: {
                columns: ':visible',
                stripHtml: true,
            },
            footer: true,
            customize: function (win) {
                if ($('.print_table_part').length > 0) {
                    $($('.print_table_part').html()).insertBefore(
                        $(win.document.body).find('table')
                    );
                }
                if ($(win.document.body).find('table.hide-footer').length) {
                    $(win.document.body).find('table.hide-footer tfoot').remove();
                }
                __currency_convert_recursively($(win.document.body).find('table'));
            },
        },
        {
            extend: 'colvis',
            text: '<i class="fa fa-columns" aria-hidden="true"></i> ' + LANG.col_vis,
            className: 'tw-dw-btn-xs  tw-dw-btn tw-dw-btn-outline tw-my-2',
        },
    ];

    var pdf_btn = {
        extend: 'pdf',
        text: '<i class="fa fa-file-pdf" aria-hidden="true"></i> ' + LANG.export_to_pdf,
        className: 'tw-dw-btn-xs  tw-dw-btn tw-dw-btn-outline tw-my-2',
        exportOptions: {
            columns: ':visible',
        },
        footer: true,
    };

    if (non_utf8_languages.indexOf(app_locale) == -1) {
        buttons.push(pdf_btn);
    }

    if ($('#view_export_buttons').length < 1) {
        buttons = [];
    }
    //Datables
    jQuery.extend($.fn.dataTable.defaults, {
        //Uncomment below line to enable save state of datatable.
        //stateSave: true,
        fixedHeader: true,
        dom: '<"row margin-bottom-20 text-center"<"col-sm-1"l><"col-sm-8"B><"col-sm-3"f> r>tip',
        buttons: buttons,
        aLengthMenu: [
            [25, 50, 100, 200, 500, 1000, -1],
            [25, 50, 100, 200, 500, 1000, LANG.all],
        ],
        iDisplayLength: __default_datatable_page_entries,
        language: {
            searchPlaceholder: LANG.search + ' ...',
            search: '',
            lengthMenu: LANG.show + ' _MENU_ ' + LANG.entries,
            emptyTable: LANG.table_emptyTable,
            info: LANG.table_info,
            infoEmpty: LANG.table_infoEmpty,
            loadingRecords: LANG.table_loadingRecords,
            processing: LANG.table_processing,
            zeroRecords: LANG.table_zeroRecords,
            paginate: {
                first: LANG.first,
                last: LANG.last,
                next: LANG.next,
                previous: LANG.previous,
            },
        },
    });



    if ($('input#iraqi_selling_price_adjustment').length > 0) {
        iraqi_selling_price_adjustment = true;
    } else {
        iraqi_selling_price_adjustment = false;
    }

    //Input number
    $(document).on(
        'click',
        '.input-number .quantity-up, .input-number .quantity-down',
        function () {
            var input = $(this).closest('.input-number').find('input');
            var qty = __read_number(input);
            var step = 1;
            if (input.data('step')) {
                step = input.data('step');
            }
            var min = parseFloat(input.data('min'));
            var max = parseFloat(input.data('max'));

            if ($(this).hasClass('quantity-up')) {
                //if max reached return false
                if (typeof max != 'undefined' && qty + step > max) {
                    return false;
                }

                __write_number(input, qty + step);
                input.change();
            } else if ($(this).hasClass('quantity-down')) {
                //if max reached return false
                if (typeof min != 'undefined' && qty - step < min) {
                    return false;
                }

                __write_number(input, qty - step);
                input.change();
            }
        }
    );

    $('div.pos-tab-menu>div.list-group>a').click(function (e) {
        e.preventDefault();
        $(this).siblings('a.active').removeClass('active');
        $(this).addClass('active');
        var index = $(this).index();
        $('div.pos-tab>div.pos-tab-content').removeClass('active');
        $('div.pos-tab>div.pos-tab-content').eq(index).addClass('active');
    });

    $('.scroll-top-bottom').each(function () {
        $(this).topScrollbar();
    });

    $('.datetimepicker').datetimepicker({
        format: moment_date_format + ' ' + moment_time_format,
        ignoreReadonly: true,
    });
});

//Default settings for daterangePicker
var ranges = {};
ranges[LANG.today] = [moment(), moment()];
ranges[LANG.yesterday] = [moment().subtract(1, 'days'), moment().subtract(1, 'days')];
ranges[LANG.last_7_days] = [moment().subtract(6, 'days'), moment()];
ranges[LANG.last_30_days] = [moment().subtract(29, 'days'), moment()];
ranges[LANG.this_month] = [moment().startOf('month'), moment().endOf('month')];
ranges[LANG.last_month] = [
    moment().subtract(1, 'month').startOf('month'),
    moment().subtract(1, 'month').endOf('month'),
];
ranges[LANG.this_month_last_year] = [
    moment().subtract(1, 'year').startOf('month'),
    moment().subtract(1, 'year').endOf('month'),
];
ranges[LANG.this_year] = [moment().startOf('year'), moment().endOf('year')];
ranges[LANG.last_year] = [
    moment().startOf('year').subtract(1, 'year'),
    moment().endOf('year').subtract(1, 'year'),
];
ranges[LANG.this_financial_year] = [financial_year.start, financial_year.end];
ranges[LANG.last_financial_year] = [
    moment(financial_year.start._i).subtract(1, 'year'),
    moment(financial_year.end._i).subtract(1, 'year'),
];

var dateRangeSettings = {
    showDropdowns: true,
    linkedCalendars: false,
    ranges: ranges,
    startDate: financial_year.start,
    endDate: financial_year.end,
    locale: {
        cancelLabel: LANG.clear,
        applyLabel: LANG.apply,
        customRangeLabel: LANG.custom_range,
        format: moment_date_format,
        toLabel: '~',
    },
};

//Check for number string in input field, if data-decimal is 0 then don't allow decimal symbol and if no_neg then don't allow  negative value
$(document).on('keypress', 'input.input_number', function (event) {
    var is_decimal = $(this).data('decimal');

    if (is_decimal == 0) {
        if (__currency_decimal_separator == '.') {
            var regex = new RegExp(/^[0-9,-]+$/);
        } else {
            var regex = new RegExp(/^[0-9.-]+$/);
        }
    } else {
        var regex = new RegExp(/^[0-9.,-]+$/);
    }

    // Check for no negative values
    if (is_decimal == 'no_neg') {
        var regex = new RegExp(/^[0-9.,]+$/);
    }

    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key)) {
        event.preventDefault();
        return false;
    }
});

//Select all input values on click
$(document).on('click', 'input', function (event) {
    $(this).select();
});

$(document).on('click', '.toggle-font-size', function (event) {
    localStorage.setItem('upos_font_size', $(this).data('size'));
    update_font_size();
});
$(document).on('click', '.sidebar-toggle', function () {
    var sidebar_collapse = localStorage.getItem('upos_sidebar_collapse');
    if ($('body').hasClass('sidebar-collapse')) {
        localStorage.setItem('upos_sidebar_collapse', 'false');
    } else {
        localStorage.setItem('upos_sidebar_collapse', 'true');
    }
});

//Ask for confirmation for links
$(document).on('click', 'a.link_confirmation', function (e) {
    e.preventDefault();
    swal({
        title: LANG.sure,
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    }).then((confirmed) => {
        if (confirmed) {
            window.location.href = $(this).attr('href');
        }
    });
});

//Change max quantity rule if lot number changes
$('table#stock_adjustment_product_table tbody').on('change', 'select.lot_number', function () {
    var tr = $(this).closest('tr');
    var qty_element = tr.find('input.product_quantity');
    var qty_available_el = tr.find('.qty_available_text');

    var multiplier = 1;
    var unit_name = '';
    var sub_unit_length = tr.find('select.sub_unit').length;
    if (sub_unit_length > 0) {
        var select = tr.find('select.sub_unit');
        multiplier = parseFloat(select.find(':selected').data('multiplier'));
        unit_name = select.find(':selected').data('unit_name');
    }

    if ($(this).val()) {
        var lot_qty = $('option:selected', $(this)).data('qty_available');
        var max_err_msg = $('option:selected', $(this)).data('msg-max');

        if (sub_unit_length > 0) {
            lot_qty = lot_qty / multiplier;
            var lot_qty_formated = __number_f(lot_qty, false);
            max_err_msg = __translate('lot_max_qty_error', {
                max_val: lot_qty_formated,
                unit_name: unit_name,
            });
        }

        qty_element.attr('data-rule-max-value', lot_qty);
        qty_element.attr('data-msg-max-value', max_err_msg);

        qty_element.rules('add', {
            'max-value': lot_qty,
            messages: {
                'max-value': max_err_msg,
            },
        });
        if (qty_available_el.length) {
            qty_available_el.text(__currency_trans_from_en(lot_qty, false));
        }
    } else {
        var default_qty = qty_element.data('qty_available');
        var default_err_msg = qty_element.data('msg_max_default');

        if (sub_unit_length > 0) {
            default_qty = default_qty / multiplier;
            var lot_qty_formated = __number_f(default_qty, false);
            default_err_msg = __translate('pos_max_qty_error', {
                max_val: lot_qty_formated,
                unit_name: unit_name,
            });
        }

        qty_element.attr('data-rule-max-value', default_qty);
        qty_element.attr('data-msg-max-value', default_err_msg);

        qty_element.rules('add', {
            'max-value': default_qty,
            messages: {
                'max-value': default_err_msg,
            },
        });

        if (qty_available_el.length) {
            qty_available_el.text(__currency_trans_from_en(default_qty, false));
        }
    }
    qty_element.trigger('change');
});
$('button#btnCalculator, button#return_sale').hover(function () {
    $(this).tooltip('show');
});
$('button#return_sale').click(function () {
    $(this).popover('toggle');
});
$('button#service_staff_replacement').click(function () {
    $(this).popover('toggle');
});
$(document).on('mouseleave', 'button#btnCalculator, button#return_sale', function (e) {
    $(this).tooltip('hide');
});

jQuery.validator.addMethod(
    'min-value',
    function (value, element, param) {
        return this.optional(element) || !(param > __number_uf(value));
    },
    function (params, element) {
        return $(element).data('min-value');
    }
);

$(document).on('click', '.view_uploaded_document', function (e) {
    e.preventDefault();
    var src = $(this).data('href');
    var html =
        '<div class="modal-dialog" role="document"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div><div class="modal-body"><img src="' +
        src +
        '" class="img-responsive" alt="Uploaded Document"></div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button> <a href="' +
        src +
        '" class="btn btn-success" download=""><i class="fa fa-download"></i> Download</a></div></div></div>';
    $('div.view_modal').html(html).modal('show');
});

$(document).on('click', '#accordion .box-header', function (e) {
    if (e.target.tagName == 'A' || e.target.tagName == 'I') {
        return false;
    }
    $(this).find('.box-title a').click();
});

$(document).on('shown.bs.modal', '.contains_select2, .view_modal', function () {
    $(this)
        .find('.select2')
        .each(function () {
            var $p = $(this).parent();
            $(this).select2({ dropdownParent: $p });
        });
});

//common configuration : tinyMCE editor

tinymce.overrideDefaults({
    height: 300,
    language: app_locale, // Set language dynamically
    language_url: base_path + '/js/lang/tiny/' + app_locale + '.js', // Dynamic URL
    theme: 'silver',
    plugins: [
        'advlist autolink link image lists charmap print preview hr anchor pagebreak',
        'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
        'table template paste help',
    ],
    toolbar:
        'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify |' +
        ' bullist numlist outdent indent | link image | print preview media fullpage | ' +
        'forecolor backcolor',
    menu: {
        favs: { title: 'My Favorites', items: 'code | searchreplace' },
    },
    menubar: 'favs file edit view insert format tools table help',
});

// Prevent Bootstrap dialog from blocking focusin
$(document).on('focusin', function (e) {
    if ($(e.target).closest('.tox-tinymce-aux, .moxman-window, .tam-assetmanager-root').length) {
        e.stopImmediatePropagation();
    }
});

//search parameter in url
function urlSearchParam(param) {
    var results = new RegExp('[?&]' + param + '=([^&#]*)').exec(window.location.href);
    if (results == null) {
        return null;
    } else {
        return results[1];
    }
}

// For dropdown hidden issue
// (function() {
//   var dropdownMenu;
//   $('table').on('show.bs.dropdown', function(e) {
//     dropdownMenu = $(e.target).find('.dropdown-menu');
//     $('body').append(dropdownMenu.detach());
//     var eOffset = $(e.target).offset();
//     if(dropdownMenu.hasClass('dropdown-menu-right')) {
//         dropdownMenu.css({
//             'display': 'block',
//             'top': eOffset.top + $(e.target).outerHeight(),
//             'left': 'auto',
//             'right': 0
//         });
//     } else {
//         dropdownMenu.css({
//             'display': 'block',
//             'top': eOffset.top + $(e.target).outerHeight(),
//             'left': eOffset.left
//         });
//     }
//   });
//   $('table').on('hide.bs.dropdown', function(e) {
//     $(e.target).append(dropdownMenu.detach());
//     dropdownMenu.hide();
//   });
// })();

function updateOnlineStatus() {
    if (!__is_online()) {
        $('#online_indicator').removeClass('text-success');
        $('#online_indicator').addClass('text-danger');
    } else {
        $('#online_indicator').removeClass('text-danger');
        $('#online_indicator').addClass('text-success');
    }
}

$(document).on('change', '.cash_denomination', function () {
    var total = 0;
    var table = $(this).closest('table');
    table.find('tbody tr').each(function () {
        var denomination = parseFloat($(this).find('.cash_denomination').attr('data-denomination'));
        var count = $(this).find('.cash_denomination').val()
            ? parseInt($(this).find('.cash_denomination').val())
            : 0;
        var subtotal = denomination * count;
        total = total + subtotal;
        $(this).find('span.denomination_subtotal').text(__currency_trans_from_en(subtotal, true));
    });

    table.find('span.denomination_total').text(__currency_trans_from_en(total, true));
    table.find('input.denomination_total_amount').val(total);
});

//autofocus select2 search input
let forceFocusFn = function () {
    // Gets the search input of the opened select2
    var searchInput = document.querySelector('.select2-container--open .select2-search__field');
    // If exists
    if (searchInput) searchInput.focus(); // focus
};

// Every time a select2 is opened
$(document).on('select2:open', () => {
    // We use a timeout because when a select2 is already opened and you open a new one, it has to wait to find the appropiate
    setTimeout(() => forceFocusFn(), 200);
});

function copyToClipboard(element_id) {
    var temp = $('<input>');
    $('body').append(temp);
    temp.val($('#' + element_id).text()).select();
    document.execCommand('copy');
    temp.remove();
    toastr.success(LANG.copied_to_clipboard);
}

// This function escapes HTML characters in a given string to prevent XSS attacks.
function escapeHtml(str) {
    if (typeof str !== 'string') return '';
    return str
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

//Guards against showing the "session expired" prompt more than once.
var __session_expired_notified = false;

//Timestamp of the last keep-alive ping, used to avoid pinging twice in a row.
var __last_keep_alive_at = 0;

/**
 * Catches every failed ajax request in the application.
 *
 * Individual ajax calls in this codebase mostly define only a `success` callback, so
 * without this handler a 401/419/500 response leaves the screen silently stuck - buttons
 * stay disabled and nothing happens until the user reloads the page.
 */
function __register_ajax_failure_handler() {
    $(document).ajaxError(function (event, jqXHR, settings, thrownError) {
        //Requests cancelled by the browser or by DataTables are not real failures.
        if (jqXHR.statusText === 'abort' || thrownError === 'abort') {
            return;
        }

        var status = jqXHR.status;

        //Session or CSRF token expired - the only recovery is a fresh page.
        if (status === 401 || status === 419) {
            __notify_session_expired();
            return;
        }

        if (status === 0) {
            toastr.error(LANG.not_connected_to_a_network);
            return;
        }

        if (status === 403) {
            toastr.error(LANG.unauthorized || 'Unauthorized action.');
            return;
        }

        if (status === 422) {
            var errors = jqXHR.responseJSON && jqXHR.responseJSON.errors ? jqXHR.responseJSON.errors : {};
            var shown = false;
            for (var field in errors) {
                if (errors.hasOwnProperty(field) && errors[field].length) {
                    toastr.error(errors[field][0]);
                    shown = true;
                }
            }
            if (!shown) {
                toastr.error(LANG.something_went_wrong || 'Something went wrong, please try again.');
            }
            return;
        }

        toastr.error(
            (LANG.something_went_wrong || 'Something went wrong, please try again.') + ' (' + status + ')'
        );
    });
}

/**
 * Tells the user their login has expired, instead of leaving the screen in a
 * permanently disabled state.
 *
 * Signing in happens in a separate tab on purpose: reloading this one would throw away
 * an order the operator may have spent half an hour building. Once they are signed in,
 * returning to this tab triggers a keep-alive ping that picks up a fresh CSRF token,
 * and the part-finished order can simply be saved again.
 */
function __notify_session_expired() {
    if (__session_expired_notified) {
        return;
    }
    __session_expired_notified = true;

    swal({
        title: LANG.session_expired || 'Session expired',
        text:
            LANG.session_expired_help ||
            'Your login session has expired, so this action was not saved. Sign in again in the new tab, then come back here and save again - nothing you have entered will be lost.',
        icon: 'warning',
        buttons: {
            cancel: LANG.stay_on_this_page || 'Stay on this page',
            confirm: LANG.sign_in_again || 'Sign in again',
        },
        closeOnClickOutside: false,
    }).then(function (confirmed) {
        if (confirmed) {
            window.open(base_path + '/login', '_blank');
        }
    });
}

/**
 * Pings the server periodically so a screen that stays open for a long time - a POS tab
 * parked while the customer fetches something, for example - does not have its session
 * garbage collected. The refreshed CSRF token is written back into the page so forms
 * that were rendered long ago keep submitting a valid token.
 *
 * The ping deliberately runs while the tab is hidden too: a half finished order is the
 * exact case we must not lose. Browsers throttle background timers, so we also ping
 * immediately whenever the operator switches back to the tab.
 */
function __start_session_heartbeat() {
    if (!$('meta[name="csrf-token"]').length) {
        return;
    }

    //Well below the shortest sensible SESSION_LIFETIME.
    var heartbeat_interval = 5 * 60 * 1000;

    setInterval(__send_keep_alive, heartbeat_interval);

    //Background tabs may have had their timers throttled or frozen entirely, so refresh
    //the session and the CSRF token the moment the tab is looked at again - before the
    //operator can click save with a stale token.
    $(document).on('visibilitychange', function () {
        if (!document.hidden) {
            __send_keep_alive();
        }
    });
}

/**
 * Sends a single keep-alive ping, unless one went out moments ago.
 */
function __send_keep_alive() {
    if (!__is_online()) {
        return;
    }

    var now = Date.now();
    if (now - __last_keep_alive_at < 30 * 1000) {
        return;
    }
    __last_keep_alive_at = now;

    $.ajax({
        method: 'GET',
        url: '/keep-alive',
        dataType: 'json',
        global: false,
        success: function (result) {
            if (result && result.csrf_token) {
                __refresh_csrf_token(result.csrf_token);
            }

            //The operator signed back in elsewhere, so this tab works again.
            if (__session_expired_notified) {
                __session_expired_notified = false;
                toastr.success(LANG.session_restored || 'You are signed in again.');
            }
        },
        error: function (jqXHR) {
            if (jqXHR.status === 401 || jqXHR.status === 419) {
                __notify_session_expired();
            }
        },
    });
}

/**
 * Replaces the CSRF token in the meta tag and in every rendered form.
 *
 * @param {string} token
 */
function __refresh_csrf_token(token) {
    $('meta[name="csrf-token"]').attr('content', token);
    $('input[name="_token"]').val(token);
}

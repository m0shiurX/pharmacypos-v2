@extends('layouts.app')

@section('title', __('lang_v1.language_word_management'))

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-language text-primary"></i>
                        @lang('lang_v1.language_word_management')
                    </h3>
                </div>
                <div class="box-body">
                    <!-- Module and Language Selection -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="module_select">@lang('lang_v1.select_module') <span class="text-danger">*</span>:</label>
                                <select id="module_select" class="form-control select2" required>
                                    <option value="main">@lang('lang_v1.main_application')</option>
                                    @foreach ($modules as $module)
                                        <option value="{{ $module['name'] }}">{{ $module['display_name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="file_type_select">@lang('lang_v1.select_file_type') <span class="text-danger">*</span>:</label>
                                <select id="file_type_select" class="form-control select2" required>
                                    <option value="lang_v1">lang_v1.php</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="language_select">@lang('lang_v1.select_language') <span class="text-danger">*</span>:</label>
                                <select id="language_select" class="form-control select2" required>
                                    <option value="">@lang('lang_v1.please_select')</option>
                                    @foreach ($languages as $code => $lang)
                                        <option value="{{ $code }}">{{ $lang['full_name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Add Single Word Form -->
                    <div class="row" id="single_word_form" style="display: none;">
                        <div class="col-md-12">
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title">
                                        <i class="fa fa-plus-circle text-primary"></i>
                                        @lang('lang_v1.add_single_word')
                                    </h3>
                                </div>
                                <div class="box-body">
                                    <form id="add_word_form">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="add_word_key">@lang('lang_v1.word_key') <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" id="add_word_key" name="word_key"
                                                        class="form-control" placeholder="@lang('lang_v1.enter_word_key')" required>
                                                    <small class="help-block">@lang('lang_v1.word_key_help')</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="add_word_value">@lang('lang_v1.word_value') <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" id="add_word_value" name="word_value"
                                                        class="form-control" placeholder="@lang('lang_v1.enter_word_value')" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="add_file_type">@lang('lang_v1.file_type') <span
                                                            class="text-danger">*</span></label>
                                                    <select id="add_file_type" name="file_type" class="form-control"
                                                        required>
                                                        <option value="lang_v1">lang_v1.php</option>
                                                        <option value="general">general.php</option>
                                                        <option value="core">core.php</option>
                                                        <option value="report">report.php</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-primary" id="add_word_btn">
                                                    <i class="fa fa-save"></i> @lang('lang_v1.add_word')
                                                </button>
                                                <button type="button" class="btn btn-default"
                                                    onclick="clearSingleWordForm()">
                                                    <i class="fa fa-refresh"></i> @lang('lang_v1.clear_form')
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bulk Add Words Form -->
                    <div class="row" id="bulk_words_form" style="display: none;">
                        <div class="col-md-12">
                            <div class="box box-success">
                                <div class="box-header with-border">
                                    <h3 class="box-title">
                                        <i class="fa fa-list-alt text-success"></i>
                                        @lang('lang_v1.bulk_add_words')
                                    </h3>
                                </div>
                                <div class="box-body">
                                    <form id="bulk_add_words_form">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="bulk_words_text">@lang('lang_v1.words_text') <span
                                                            class="text-danger">*</span></label>
                                                    <textarea id="bulk_words_text" name="words_text" class="form-control" rows="10" placeholder="@lang('lang_v1.words_text_placeholder')"
                                                        required></textarea>
                                                    <small class="help-block">@lang('lang_v1.words_text_help')</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="bulk_file_type">@lang('lang_v1.file_type') <span
                                                            class="text-danger">*</span></label>
                                                    <select id="bulk_file_type" name="file_type" class="form-control"
                                                        required>
                                                        <option value="lang_v1">lang_v1.php</option>
                                                        <option value="general">general.php</option>
                                                        <option value="core">core.php</option>
                                                        <option value="report">report.php</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-success" id="bulk_add_btn">
                                                    <i class="fa fa-upload"></i> @lang('lang_v1.bulk_add_words')
                                                </button>
                                                <button type="button" class="btn btn-default"
                                                    onclick="clearBulkWordsForm()">
                                                    <i class="fa fa-refresh"></i> @lang('lang_v1.clear_form')
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary" onclick="showSingleWordForm()">
                                    <i class="fa fa-plus-circle"></i> @lang('lang_v1.add_single_word')
                                </button>
                                <button type="button" class="btn btn-success" onclick="showBulkWordsForm()">
                                    <i class="fa fa-list-alt"></i> @lang('lang_v1.bulk_add_words')
                                </button>
                                <button type="button" class="btn btn-info" onclick="loadWords()" id="load_words_btn"
                                    disabled>
                                    <i class="fa fa-download"></i> @lang('lang_v1.load_words')
                                </button>
                                <button type="button" class="btn btn-warning" onclick="hideAllForms()">
                                    <i class="fa fa-eye-slash"></i> @lang('lang_v1.hide_forms')
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Words Table -->
                    <div class="row" id="words_table_container" style="display: none;">
                        <div class="col-md-12">
                            <div class="box box-info">
                                <div class="box-header with-border">
                                    <h3 class="box-title">
                                        <i class="fa fa-table text-info"></i>
                                        @lang('lang_v1.existing_words')
                                    </h3>
                                </div>
                                <div class="box-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered" id="words_table">
                                            <thead>
                                                <tr>
                                                    <th>@lang('lang_v1.word_key')</th>
                                                    <th>@lang('lang_v1.word_value')</th>
                                                    <th>@lang('lang_v1.actions')</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Words will be loaded here -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Word Modal -->
    <div class="modal fade" id="edit_word_modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">@lang('lang_v1.edit_word')</h4>
                </div>
                <div class="modal-body">
                    <form id="edit_word_form">
                        <input type="hidden" id="edit_word_key" name="word_key">
                        <input type="hidden" id="edit_language" name="language">
                        <input type="hidden" id="edit_module" name="module">
                        <input type="hidden" id="edit_file_type" name="file_type">
                        <div class="form-group">
                            <label for="edit_word_value">@lang('lang_v1.word_value') <span class="text-danger">*</span></label>
                            <input type="text" id="edit_word_value" name="word_value" class="form-control" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">@lang('lang_v1.cancel')</button>
                    <button type="button" class="btn btn-primary" onclick="updateWord()">@lang('lang_v1.update')</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        $(document).ready(function() {
            // Initialize select2
            $('.select2').select2();

            // Handle module selection change
            $('#module_select').on('change', function() {
                var module = $(this).val();
                loadFileTypes(module);
            });

            // Handle language selection change
            $('#language_select').on('change', function() {
                var language = $(this).val();
                var module = $('#module_select').val();
                var fileType = $('#file_type_select').val();

                if (language && module && fileType) {
                    $('#load_words_btn').prop('disabled', false);
                } else {
                    $('#load_words_btn').prop('disabled', true);
                }
            });

            // Handle file type selection change
            $('#file_type_select').on('change', function() {
                var language = $('#language_select').val();
                var module = $('#module_select').val();
                var fileType = $(this).val();

                if (language && module && fileType) {
                    $('#load_words_btn').prop('disabled', false);
                } else {
                    $('#load_words_btn').prop('disabled', true);
                }

                // Keep add & bulk file_type selects in sync with the top selector
                $('#add_file_type').val(fileType);
                $('#bulk_file_type').val(fileType);
            });

            // Load file types for selected module
            function loadFileTypes(module) {
                $.ajax({
                    url: "{{ route('language-words.get-module-file-types') }}",
                    method: 'GET',
                    data: {
                        module: module
                    },
                    success: function(response) {
                        var fileTypeSelect = $('#file_type_select');
                        fileTypeSelect.empty();

                        $.each(response.file_types, function(index, fileType) {
                            fileTypeSelect.append('<option value="' + fileType + '">' +
                                fileType + '.php</option>');
                        });

                        // Update add form file type options
                        var addFileTypeSelect = $('#add_file_type');
                        addFileTypeSelect.empty();
                        $.each(response.file_types, function(index, fileType) {
                            addFileTypeSelect.append('<option value="' + fileType + '">' +
                                fileType + '.php</option>');
                        });

                        // Update bulk form file type options
                        var bulkFileTypeSelect = $('#bulk_file_type');
                        bulkFileTypeSelect.empty();
                        $.each(response.file_types, function(index, fileType) {
                            bulkFileTypeSelect.append('<option value="' + fileType + '">' +
                                fileType + '.php</option>');
                        });

                        // Sync add & bulk selects to currently selected top file type
                        var currentTopFileType = $('#file_type_select').val();
                        $('#add_file_type').val(currentTopFileType);
                        $('#bulk_file_type').val(currentTopFileType);
                    },
                    error: function(xhr) {
                        console.log('Error loading file types:', xhr);
                    }
                });
            }

            // Handle add word form submission
            $('#add_word_form').on('submit', function(e) {
                e.preventDefault();

                // Validate required selections
                if (!$('#module_select').val() || !$('#language_select').val() || !$('#file_type_select')
                    .val()) {
                    toastr.error('Please select Module, Language, and File Type first');
                    return;
                }

                var formData = {
                    word_key: $('#add_word_key').val(),
                    word_value: $('#add_word_value').val(),
                    // Fallback to top selector if inner select is empty
                    file_type: $('#add_file_type').val() || $('#file_type_select').val(),
                    modules: [$('#module_select').val()],
                    languages: [$('#language_select').val()]
                };

                $.ajax({
                    url: "{{ route('language-words.add-word') }}",
                    method: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        toastr.success(response.message);
                        clearSingleWordForm();
                        loadWords(); // Reload current words
                    },
                    error: function(xhr) {
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            $.each(xhr.responseJSON.errors, function(key, value) {
                                toastr.error(value[0]);
                            });
                        } else {
                            toastr.error('@lang('lang_v1.error_adding_word')');
                        }
                    }
                });
            });

            // Handle bulk add words form submission
            $('#bulk_add_words_form').on('submit', function(e) {
                e.preventDefault();

                // Validate required selections
                if (!$('#module_select').val() || !$('#language_select').val() || !$('#file_type_select')
                    .val()) {
                    toastr.error('Please select Module, Language, and File Type first');
                    return;
                }

                var formData = {
                    words_text: $('#bulk_words_text').val(),
                    file_type: $('#bulk_file_type').val(),
                    modules: [$('#module_select').val()],
                    languages: [$('#language_select').val()]
                };

                $.ajax({
                    url: "{{ route('language-words.bulk-add-words') }}",
                    method: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        toastr.success(response.message + ' (' + response.total_words +
                            ' words)');
                        clearBulkWordsForm();
                        loadWords(); // Reload current words
                    },
                    error: function(xhr) {
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            $.each(xhr.responseJSON.errors, function(key, value) {
                                toastr.error(value[0]);
                            });
                        } else {
                            toastr.error('@lang('lang_v1.error_adding_words')');
                        }
                    }
                });
            });
        });

        function loadWords() {
            var language = $('#language_select').val();
            var module = $('#module_select').val();
            var fileType = $('#file_type_select').val();

            if (!language || !module || !fileType) {
                toastr.error('@lang('lang_v1.please_select_all_fields')');
                return;
            }

            var $btn = $('#load_words_btn');
            var originalText = $btn.html();
            $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> @lang('lang_v1.loading')...');

            $.ajax({
                url: "{{ route('language-words.get-words') }}",
                method: 'GET',
                data: {
                    language: language,
                    module: module,
                    file_type: fileType
                },
                success: function(response) {
                    displayWords(response.words);
                    $('#words_table_container').show();
                },
                error: function(xhr) {
                    toastr.error("@lang('lang_v1.error_loading_words')");
                },
                complete: function() {
                    $btn.prop('disabled', false).html(originalText);
                }
            });
        }

        function displayWords(words) {
            var tbody = $('#words_table tbody');
            tbody.empty();

            if (Object.keys(words).length === 0) {
                tbody.append('<tr><td colspan="3" class="text-center">@lang('lang_v1.no_words_found')</td></tr>');
                return;
            }

            $.each(words, function(key, value) {
                // Escape special characters for JavaScript
                var escapedKey = key.replace(/'/g, "\\'").replace(/"/g, '\\"');
                var escapedValue = value.replace(/'/g, "\\'").replace(/"/g, '\\"');

                var row = '<tr>' +
                    '<td>' + key + '</td>' +
                    '<td>' + value + '</td>' +
                    '<td>' +
                    '<button class="btn btn-xs btn-warning" onclick="editWord(\'' + escapedKey + '\', \'' +
                    escapedValue + '\')">' +
                    '@lang('lang_v1.edit')' +
                    '</button> ' +
                    '<button class="btn btn-xs btn-danger" onclick="deleteWord(\'' + escapedKey + '\')">' +
                    '@lang('lang_v1.delete')' +
                    '</button>' +
                    '</td>' +
                    '</tr>';
                tbody.append(row);
            });
        }

        function editWord(key, value) {
            $('#edit_word_key').val(key);
            $('#edit_word_value').val(value);
            $('#edit_language').val($('#language_select').val());
            $('#edit_module').val($('#module_select').val());
            $('#edit_file_type').val($('#file_type_select').val());
            $('#edit_word_modal').modal('show');
        }

        function updateWord() {
            var formData = {
                word_key: $('#edit_word_key').val(),
                word_value: $('#edit_word_value').val(),
                language: $('#edit_language').val(),
                module: $('#edit_module').val(),
                file_type: $('#edit_file_type').val()
            };

            $.ajax({
                url: "{{ route('language-words.update-word') }}",
                method: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    toastr.success(response.message);
                    $('#edit_word_modal').modal('hide');
                    loadWords();
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            toastr.error(value[0]);
                        });
                    } else {
                        toastr.error('@lang('lang_v1.error_updating_word')');
                    }
                }
            });
        }

        function deleteWord(key) {
            if (!confirm('@lang('lang_v1.confirm_delete_word')')) {
                return;
            }

            var formData = {
                word_key: key,
                language: $('#language_select').val(),
                module: $('#module_select').val(),
                file_type: $('#file_type_select').val()
            };

            // Debug: Log the form data
            console.log('Delete form data:', formData);

            // Validate required fields
            if (!formData.language || !formData.module || !formData.file_type) {
                toastr.error('Please select module, file type, and language before deleting');
                return;
            }

            $.ajax({
                url: "{{ route('language-words.delete-word') }}",
                method: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log('Delete success:', response);
                    toastr.success(response.message);
                    loadWords();
                },
                error: function(xhr) {
                    console.log('Delete error:', xhr.responseJSON);
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        toastr.error(xhr.responseJSON.error);
                    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            toastr.error(key + ': ' + value[0]);
                        });
                    } else {
                        toastr.error('@lang('lang_v1.error_deleting_word')');
                    }
                }
            });
        }

        function showSingleWordForm() {
            // Validate required selections
            if (!$('#module_select').val() || !$('#language_select').val() || !$('#file_type_select').val()) {
                toastr.error('Please select Module, Language, and File Type first');
                return;
            }

            $('#single_word_form').show();
            $('#bulk_words_form').hide();
        }

        function showBulkWordsForm() {
            // Validate required selections
            if (!$('#module_select').val() || !$('#language_select').val() || !$('#file_type_select').val()) {
                toastr.error('Please select Module, Language, and File Type first');
                return;
            }

            $('#bulk_words_form').show();
            $('#single_word_form').hide();
        }

        function hideAllForms() {
            $('#single_word_form').hide();
            $('#bulk_words_form').hide();
        }

        function clearSingleWordForm() {
            $('#add_word_form')[0].reset();
        }

        function clearBulkWordsForm() {
            $('#bulk_add_words_form')[0].reset();
        }
    </script>

    <style>
        .box {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }

        .box-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 5px 5px 0 0;
        }

        .box-primary .box-header {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        }

        .box-success .box-header {
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
        }

        .box-info .box-header {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        }

        .btn-group .btn {
            margin-right: 5px;
            border-radius: 4px;
        }

        .form-control {
            border-radius: 4px;
            border: 1px solid #ddd;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }

        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .btn-xs {
            padding: 2px 6px;
            font-size: 11px;
            border-radius: 3px;
        }
    </style>
    <script>
        $(document).ready(function() {
            // Initialize select2
            $('.select2').select2({
                placeholder: "@lang('lang_v1.select_language')",
                allowClear: true
            });

            // Language selection change
            $('#language_select').on('change', function() {
                var selectedLanguage = $(this).val();
                if (selectedLanguage) {
                    $('#load_words_btn').prop('disabled', false);
                    // Auto-hide forms when language changes
                    $('#single_word_form').hide();
                    $('#bulk_words_form').hide();
                } else {
                    $('#load_words_btn').prop('disabled', true);
                    $('#words_table_container').hide();
                }
            });

            // Add single word form submission
            $('#add_word_form').on('submit', function(e) {
                e.preventDefault();
                addWord();
            });

            // Bulk add words form submission
            $('#bulk_add_words_form').on('submit', function(e) {
                e.preventDefault();
                bulkAddWords();
            });
        });


        function hideAllForms() {
            $('#single_word_form').hide();
            $('#bulk_words_form').hide();
            // Enable form buttons
            $('#add_word_btn').prop('disabled', false);
            $('#bulk_add_btn').prop('disabled', false);
        }

        function clearSingleWordForm() {
            $('#add_word_form')[0].reset();
        }

        function clearBulkWordsForm() {
            $('#bulk_add_words_form')[0].reset();
        }


        function checkWordExists(wordKey, language) {
            var exists = false;
            $('#words_table tbody tr').each(function() {
                var key = $(this).find('td:first').text();
                if (key === wordKey) {
                    exists = true;
                    return false; // break the loop
                }
            });
            return exists;
        }

        function addWord() {
            var formData = $('#add_word_form').serialize();
            var selectedLanguage = $('#language_select').val();
            var wordKey = $('#add_word_key').val();
            var selectedModule = $('#module_select').val();
            var addFileType = $('#add_file_type').val() || $('#file_type_select').val();

            if (!selectedLanguage) {
                toastr.error("@lang('lang_v1.please_select_language')");
                return;
            }

            if (!selectedModule || !addFileType) {
                toastr.error("@lang('lang_v1.please_select_all_fields')");
                return;
            }

            if (!wordKey) {
                toastr.error("@lang('lang_v1.word_key_required')");
                return;
            }

            // Check if word already exists
            if (checkWordExists(wordKey, selectedLanguage)) {
                toastr.warning("@lang('lang_v1.word_already_exists')");
                return;
            }

            formData += '&languages[]=' + encodeURIComponent(selectedLanguage);
            formData += '&modules[]=' + encodeURIComponent(selectedModule);
            formData += '&file_type=' + encodeURIComponent(addFileType);

            // Disable button and show loading
            var $btn = $('#add_word_btn');
            var originalText = $btn.html();
            $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> @lang('lang_v1.adding')...');

            $.ajax({
                url: "{{ route('language-words.add-word') }}",
                method: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    toastr.success(response.message);
                    clearSingleWordForm();
                    if ($('#words_table_container').is(':visible')) {
                        loadWords();
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(field, messages) {
                            toastr.error(messages[0]);
                        });
                    } else if (xhr.responseJSON && xhr.responseJSON.error) {
                        toastr.error(xhr.responseJSON.error);
                    } else {
                        toastr.error("@lang('lang_v1.error_adding_word')");
                    }
                },
                complete: function() {
                    // Re-enable button and restore text
                    $btn.prop('disabled', false).html(originalText);
                }
            });
        }

        function bulkAddWords() {
            var formData = $('#bulk_add_words_form').serialize();
            var selectedLanguage = $('#language_select').val();
            var wordsText = $('#words_text').val();
            var selectedModule = $('#module_select').val();
            var bulkFileType = $('#bulk_file_type').val() || $('#file_type_select').val();

            if (!selectedLanguage) {
                toastr.error("@lang('lang_v1.please_select_language')");
                return;
            }

            if (!wordsText.trim()) {
                toastr.error("@lang('lang_v1.words_text_required')");
                return;
            }

            if (!selectedModule || !bulkFileType) {
                toastr.error("@lang('lang_v1.please_select_all_fields')");
                return;
            }

            formData += '&languages[]=' + encodeURIComponent(selectedLanguage);
            formData += '&modules[]=' + encodeURIComponent(selectedModule);
            formData += '&file_type=' + encodeURIComponent(bulkFileType);

            // Disable button and show loading
            var $btn = $('#bulk_add_btn');
            var originalText = $btn.html();
            $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> @lang('lang_v1.adding')...');

            $.ajax({
                url: "{{ route('language-words.bulk-add-words') }}",
                method: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    var message = response.message;
                    if (response.duplicates && response.duplicates.length > 0) {
                        message += ' ' + response.duplicates.length + ' @lang('lang_v1.duplicates_skipped')';
                    }
                    toastr.success(message + ' (' + response.total_words + ' @lang('lang_v1.words_added')');
                    clearBulkWordsForm();
                    if ($('#words_table_container').is(':visible')) {
                        loadWords();
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(field, messages) {
                            toastr.error(messages[0]);
                        });
                    } else {
                        toastr.error("@lang('lang_v1.error_adding_words')");
                    }
                },
                complete: function() {
                    // Re-enable button and restore text
                    $btn.prop('disabled', false).html(originalText);
                }
            });
        }

        function editWord(key, value) {
            $('#edit_word_key').val(key);
            $('#edit_word_value').val(value);
            $('#edit_language').val($('#language_select').val());
            $('#edit_module').val($('#module_select').val());
            $('#edit_file_type').val($('#file_type_select').val());
            $('#edit_word_modal').modal('show');
        }

        function updateWord() {
            var formData = $('#edit_word_form').serialize();

            // Disable button and show loading
            var $btn = $('button[onclick="updateWord()"]');
            var originalText = $btn.html();
            $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> @lang('lang_v1.updating')...');

            $.ajax({
                url: "{{ route('language-words.update-word') }}",
                method: 'POST',
                data: formData,
                success: function(response) {
                    toastr.success(response.message);
                    $('#edit_word_modal').modal('hide');
                    loadWords();
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(field, messages) {
                            toastr.error(messages[0]);
                        });
                    } else {
                        toastr.error("@lang('lang_v1.error_updating_word')");
                    }
                },
                complete: function() {
                    // Re-enable button and restore text
                    $btn.prop('disabled', false).html(originalText);
                }
            });
        }
    </script>
@endsection

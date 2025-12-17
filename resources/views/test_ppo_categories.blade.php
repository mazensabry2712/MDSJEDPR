@extends('layouts.master')

@section('title')
    Test PPO Categories
@stop

@section('css')
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <style>
        .console-output {
            background: #1e1e1e;
            color: #00ff00;
            padding: 15px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            max-height: 500px;
            overflow-y: auto;
            margin-top: 20px;
        }
        .console-output div {
            margin: 2px 0;
        }
    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">🧪 Test PPO Categories Auto-Selection</h4>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5>Test Form</h5>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Project Number:</label>
                                <select id="pr_number" class="form-control" style="width: 100%">
                                    <option value="">Choose Project</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}" data-project-name="{{ $project->name }}">
                                            {{ $project->pr_number }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Project Name:</label>
                                <input type="text" id="project_name_display" class="form-control" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Categories (Multiple):</label>
                                <select id="category" class="form-control" multiple style="height: 150px">
                                    <option value="" disabled>Select PR Number first</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <h5 class="mt-4">Console Output</h5>
                    <div class="console-output" id="console"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    
    <script>
        const consoleDiv = $('#console');
        function log(msg, color = '#00ff00') {
            const time = new Date().toLocaleTimeString();
            consoleDiv.append(`<div style="color: ${color}">[${time}] ${msg}</div>`);
            consoleDiv.scrollTop(consoleDiv[0].scrollHeight);
            console.log(msg);
        }

        $(document).ready(function() {
            log('✅ Page loaded');
            log('jQuery version: ' + $.fn.jquery, '#4488ff');
            log('Select2 available: ' + (typeof $.fn.select2 !== 'undefined'), '#4488ff');

            // Initialize Select2
            $('#pr_number').select2({
                placeholder: "Choose Project",
                allowClear: true,
                width: '100%'
            });

            $('#category').select2({
                placeholder: "Select PR Number first",
                allowClear: true,
                closeOnSelect: false,
                width: '100%'
            });

            log('✅ Select2 initialized');

            // Change event
            $('#pr_number').on('change', function() {
                log('🔔 PR Number changed!', '#ffaa00');

                const selectedOption = $(this).find('option:selected');
                const projectName = selectedOption.data('project-name');
                const prNumber = $(this).val();

                log('Selected PR Number ID: ' + prNumber, '#4488ff');
                log('Project Name: ' + projectName, '#4488ff');

                if (projectName) {
                    $('#project_name_display').val(projectName);
                    log('✅ Project name filled');
                }

                if (prNumber) {
                    loadCategories(prNumber);
                } else {
                    resetCategoryDropdown();
                }
            });

            function loadCategories(prNumber) {
                log('📡 Loading categories for Project ID: ' + prNumber, '#4488ff');

                $('#category').prop('disabled', true);

                if ($('#category').data('select2')) {
                    $('#category').select2('destroy');
                    log('🔄 Select2 destroyed', '#4488ff');
                }

                $('#category').html('<option disabled selected>Loading...</option>');

                $.ajax({
                    url: `/ppos/categories/${prNumber}`,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        log('✅ AJAX Success!');
                        log('Response: ' + JSON.stringify(response), '#4488ff');

                        if (response.success && response.categories && response.categories.length > 0) {
                            let options = '';
                            let categoryIds = [];

                            response.categories.forEach(function(category) {
                                options += `<option value="${category.id}">${category.category || 'N/A'}</option>`;
                                categoryIds.push(category.id);
                                log('  ➕ Category ID: ' + category.id + ' - ' + category.category, '#4488ff');
                            });

                            $('#category').html(options);
                            $('#category').prop('disabled', false);

                            $('#category').select2({
                                placeholder: 'Categories loaded',
                                allowClear: true,
                                closeOnSelect: false,
                                width: '100%'
                            });
                            log('✅ Select2 re-initialized');

                            log('📌 Auto-selecting IDs: [' + categoryIds.join(', ') + ']', '#ffaa00');
                            $('#category').val(categoryIds).trigger('change');

                            const selectedValues = $('#category').val();
                            log('✅ Selected after trigger: [' + (selectedValues ? selectedValues.join(', ') : 'NONE') + ']', 
                                selectedValues && selectedValues.length > 0 ? '#00ff00' : '#ff4444');

                            log('✅ Completed: ' + response.categories.length + ' categories loaded and selected');
                        } else {
                            log('⚠️ No categories found', '#ffaa00');
                            resetCategoryDropdown();
                        }
                    },
                    error: function(xhr, status, error) {
                        log('❌ AJAX Error: ' + error, '#ff4444');
                        log('❌ Status: ' + status, '#ff4444');
                        log('❌ Response: ' + xhr.responseText, '#ff4444');
                        log('❌ Status Code: ' + xhr.status, '#ff4444');
                        resetCategoryDropdown();
                    }
                });
            }

            function resetCategoryDropdown() {
                log('🔄 Resetting dropdown', '#ffaa00');

                if ($('#category').data('select2')) {
                    $('#category').select2('destroy');
                }

                $('#category').html('<option value="">No categories available</option>');
                $('#category').prop('disabled', true);

                $('#category').select2({
                    placeholder: 'No categories available',
                    allowClear: false
                });
                log('✅ Reset complete');
            }

            log('📝 Ready! Select a PR Number to test.', '#00ff00');
        });
    </script>
@endsection

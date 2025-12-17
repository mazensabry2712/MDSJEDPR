@extends('layouts.master')

@section('title')
    Edit PPO
@stop

@section('css')
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <style>
        /* تحسين مظهر textarea في الـ forms */
        .form-control textarea,
        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        textarea.form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: 500;
            color: #495057;
        }
    </style>
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Project Purchase Orders</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ Edit PPO</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Validation Errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- row -->
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="col-lg-12 margin-tb">
                        <div class="pull-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('ppos.index') }}">Back</a>
                        </div>
                    </div><br>

                    <form action="{{ route('ppos.update', $ppo->id) }}" method="POST" autocomplete="off">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mg-b-0">
                                    <label class="form-label">Project Number: <span class="tx-danger">*</span></label>
                                    <select class="form-control select2" name="pr_number" id="pr_number" required>
                                        <option value="">Choose Project</option>
                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}" data-project-name="{{ $project->name }}"
                                                {{ (old('pr_number', $ppo->pr_number) == $project->id) ? 'selected' : '' }}>
                                                {{ $project->pr_number }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mg-b-0">
                                    <label class="form-label">Project Name:</label>
                                    <input type="text" id="project_name_display" class="form-control" readonly
                                           style="background-color: #f8f9fa; cursor: not-allowed;">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category" class="form-label">Categories: <span class="tx-danger">*</span></label>
                                    <select class="form-control select2" id="category" name="category[]" multiple required>
                                        <option value="">Loading...</option>
                                    </select>
                                    <small class="text-muted">You can select multiple categories after selecting PR Number</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="dsname" class="form-label">Supplier Name: <span class="tx-danger">*</span></label>
                                    <select class="form-control select2" name="dsname" required>
                                        <option value="">Choose Supplier</option>
                                        @foreach($dses as $ds)
                                            <option value="{{ $ds->id }}"
                                                {{ (old('dsname', $ppo->dsname) == $ds->id) ? 'selected' : '' }}>
                                                {{ $ds->dsname }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="po_number" class="form-label">PO Number: <span class="tx-danger">*</span></label>
                                    <input type="text" class="form-control" id="po_number" name="po_number"
                                           value="{{ old('po_number', $ppo->po_number) }}" placeholder="Enter PO Number" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="value" class="form-label">Value:</label>
                                    <input type="number" step="0.01" min="0" class="form-control" id="value" name="value"
                                           value="{{ old('value', $ppo->value) }}" placeholder="Enter Value">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date" class="form-label">Date:</label>
                                    <input type="date" class="form-control" id="date" name="date"
                                           value="{{ old('date', $ppo->date ? $ppo->date->format('Y-m-d') : '') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="updates" class="form-label">Updates:</label>
                                    <textarea class="form-control" id="updates" name="updates" rows="4"
                                              placeholder="Enter updates...">{{ old('updates', $ppo->updates) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="status" class="form-label">Status:</label>
                                    <textarea class="form-control" id="status" name="status" rows="4"
                                              placeholder="Enter status...">{{ old('status', $ppo->status) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="notes" class="form-label">Notes:</label>
                                    <textarea class="form-control" id="notes" name="notes" rows="4"
                                              placeholder="Enter notes...">{{ old('notes', $ppo->notes) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="mg-t-30">
                            <button class="btn btn-outline-primary pd-x-20" type="submit">Update PPO</button>
                            <a href="{{ route('ppos.index') }}" class="btn btn-outline-secondary pd-x-20">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /row -->
@endsection

@section('js')
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/select2.js') }}"></script>

    <script>
        $(document).ready(function() {
            console.log('✅ PPOS Edit page loaded');

            // Wait for Select2 to initialize
            setTimeout(function() {
                console.log('🔵 Attaching PR Number change event (Edit mode)');

                // Auto-fill project name and load categories when PR Number changes
                // Use Select2's special event to ensure it fires after Select2 initialization
                $('#pr_number').on('change select2:select', function() {
                    console.log('🔔 PR Number changed!');

                    const selectedOption = $(this).find('option:selected');
                    const projectName = selectedOption.data('project-name');
                    const prNumber = $(this).val();

                    console.log('Selected PR Number:', prNumber);

                    // Fill Project Name
                    if (projectName) {
                        $('#project_name_display').val(projectName).css('color', '#495057');
                    } else {
                        $('#project_name_display').val('No project name available').css('color', '#6c757d');
                    }

                    // Load Categories from EPO (clear selection on change)
                    if (prNumber) {
                        loadCategories(prNumber);
                    } else {
                        resetCategoryDropdown();
                    }
                });

                // Initialize on page load with current value
                if ($('#pr_number').val()) {
                    const currentCategory = {{ $ppo->category ?? 'null' }};
                    console.log('Loading categories for existing PPO, current category:', currentCategory);
                    loadCategories($('#pr_number').val(), currentCategory);
                }
            }, 500); // Wait for Select2 initialization

            // Function to load categories based on PR Number
            function loadCategories(prNumber, selectedCategory = null) {
                console.log('📡 Loading categories for PR:', prNumber);

                // Show loading state
                $('#category').prop('disabled', true);
                $('#category').html('<option disabled>Loading...</option>');

                $.ajax({
                    url: `/ppos/categories/${prNumber}`,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        console.log('✅ AJAX Success:', response);

                        if (response.success && response.categories.length > 0) {
                            let options = '';

                            response.categories.forEach(function(category) {
                                const selected = selectedCategory && selectedCategory == category.id ? 'selected' : '';
                                options += `<option value="${category.id}" ${selected}>${category.category || 'N/A'}</option>`;
                                console.log('  ➕ Category:', category.category, selected ? '(SELECTED)' : '');
                            });

                            $('#category').html(options);
                            $('#category').prop('disabled', false);

                            // Re-initialize Select2 with multiple selection support
                            if (typeof $.fn.select2 !== 'undefined') {
                                $('#category').select2({
                                    placeholder: 'Choose one or more categories',
                                    allowClear: true,
                                    closeOnSelect: false,
                                    width: '100%'
                                });
                            }

                            console.log(`✅ Loaded ${response.categories.length} categories for PR ${prNumber}`);
                        } else {
                            console.log('⚠️ No categories found');
                            resetCategoryDropdown();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('❌ AJAX Error:', error);
                        console.error('Status:', status);
                        console.error('Response:', xhr.responseText);
                        resetCategoryDropdown();
                    }
                });
            }

            // Function to reset category dropdown
            function resetCategoryDropdown() {
                $('#category').html('<option value="">No categories available</option>');
                $('#category').prop('disabled', true);
            }
        });
    </script>
@endsection

@extends('layouts.master')
@section('title')
    Add Task
@stop
@section('css')
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <style>
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        textarea:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
    </style>
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Project Tasks</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ Create New Task</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <div class="pr-1 mb-3 mb-xl-0">
                <a class="btn ripple btn-outline-primary" href="{{ route('ptasks.index') }}">
                    <i class="fas fa-arrow-left"></i> Back to Tasks
                </a>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- row -->
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('ptasks.store') }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pr_number">PR#: <span class="text-danger">*</span></label>
                                    <select name="pr_number" id="pr_number" class="form-control select2" required>
                                        <option value="">Choose Project</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->id }}" data-project-name="{{ $project->name }}"
                                                {{ old('pr_number') == $project->id ? 'selected' : '' }}>
                                                {{ $project->pr_number }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Project Name:</label>
                                    <input type="text" id="project_name_display" class="form-control" readonly
                                           style="background-color: #f8f9fa; cursor: not-allowed;">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="task_date">Task Date: <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="task_date" name="task_date"
                                           value="{{ old('task_date', date('Y-m-d')) }}" required>
                                    <small class="text-muted">Auto filled with current date</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="expected_com_date">Expected Completion Date:</label>
                                    <input type="date" class="form-control" id="expected_com_date" name="expected_com_date"
                                           value="{{ old('expected_com_date') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="assigned">Assigned To:</label>
                                    <input type="text" class="form-control" id="assigned" name="assigned"
                                           value="{{ old('assigned') }}" placeholder="Enter person name">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Status: <span class="text-danger">*</span></label>
                                    <select name="status" class="form-control" required>
                                        <option value="">Choose Status</option>
                                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="progress" {{ old('status') == 'progress' ? 'selected' : '' }}>Under Progress</option>
                                        <option value="hold" {{ old('status') == 'hold' ? 'selected' : '' }}>On Hold</option>
                                    </select>
                                    <small class="text-muted">Selection: completed, pending, under progress, or on hold</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="details">Task Details:</label>
                                    <textarea class="form-control" id="details" name="details" rows="4"
                                              placeholder="Enter task details...">{{ old('details') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="mg-t-30">
                            <button class="btn btn-outline-primary pd-x-20" type="submit">Add Task</button>
                            <a href="{{ route('ptasks.index') }}" class="btn btn-outline-secondary pd-x-20">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- row closed -->
@endsection

@section('js')
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Choose Project",
                allowClear: true
            });

            // Auto-fill project name when PR Number changes
            $('#pr_number').on('change', function() {
                const selectedOption = $(this).find('option:selected');
                const projectName = selectedOption.data('project-name');

                if (projectName) {
                    $('#project_name_display').val(projectName).css('color', '#495057');
                } else {
                    $('#project_name_display').val('No project name available').css('color', '#6c757d');
                }
            });

            // Initialize on page load if old value exists
            if ($('#pr_number').val()) {
                $('#pr_number').trigger('change');
            }
        });
    </script>
@endsection

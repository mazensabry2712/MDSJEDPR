@extends('layouts.master')
@section('title')
    Edit Task
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
                <h4 class="content-title mb-0 my-auto">Project Tasks</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ Edit Task</span>
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
                    <form action="{{ route('ptasks.update', $ptasks->id) }}" method="post" enctype="multipart/form-data">
                        {{ method_field('patch') }}
                        {{ csrf_field() }}

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pr_number_display">PR#: <span class="text-danger">*</span></label>
                                    <input type="text" id="pr_number_display" class="form-control" readonly
                                           style="background-color: #f8f9fa; cursor: not-allowed;"
                                           value="{{ $ptasks->project->pr_number ?? '' }}">
                                    <input type="hidden" name="pr_number" value="{{ $ptasks->pr_number }}">
                                    <small class="text-muted">PR Number cannot be changed after creation.</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Project Name:</label>
                                    <input type="text" id="project_name_display" class="form-control" readonly
                                           style="background-color: #f8f9fa; cursor: not-allowed;"
                                           value="{{ $ptasks->project->name ?? '' }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="task_date">Task Date: <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="task_date" name="task_date"
                                           value="{{ old('task_date', $ptasks->task_date ? \Carbon\Carbon::parse($ptasks->task_date)->format('Y-m-d') : '') }}" required>
                                    <small class="text-muted">Auto filled with current date</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="expected_com_date">Expected Completion Date:</label>
                                    <input type="date" class="form-control" id="expected_com_date" name="expected_com_date"
                                           value="{{ old('expected_com_date', $ptasks->expected_com_date ? \Carbon\Carbon::parse($ptasks->expected_com_date)->format('Y-m-d') : '') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="assigned">Assigned To:</label>
                                    <input type="text" class="form-control" id="assigned" name="assigned"
                                           value="{{ old('assigned', $ptasks->assigned) }}" placeholder="Enter person name">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Status: <span class="text-danger">*</span></label>
                                    <select name="status" class="form-control" required>
                                        <option value="">Choose Status</option>
                                        <option value="completed" {{ old('status', $ptasks->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="pending" {{ old('status', $ptasks->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="progress" {{ old('status', $ptasks->status) == 'progress' ? 'selected' : '' }}>Under Progress</option>
                                        <option value="hold" {{ old('status', $ptasks->status) == 'hold' ? 'selected' : '' }}>On Hold</option>
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
                                              placeholder="Enter task details...">{{ old('details', $ptasks->details) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="mg-t-30">
                            <button class="btn btn-outline-primary pd-x-20" type="submit">Update Task</button>
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
            // No dynamic functionality needed - PR Number and Project Name are now readonly
        });
    </script>
@endsection

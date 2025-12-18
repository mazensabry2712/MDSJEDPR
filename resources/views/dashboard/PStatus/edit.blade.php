@extends('layouts.master')
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

        /* CSS لإخفاء أيقونة اختيار التاريخ والوقت في متصفحات WebKit (Chrome, Safari, Edge) */
        input[type="datetime-local"][readonly]::-webkit-calendar-picker-indicator,
        input[type="datetime-local"][readonly]::-webkit-inner-spin-button {
            display: none;
            -webkit-appearance: none;
        }
        /* CSS لإخفاء أيقونة اختيار التاريخ والوقت في Firefox */
        input[type="datetime-local"][readonly],
        #pm_name_display {
            pointer-events: none;
            background-color: #f8f9fa;
            cursor: not-allowed;
        }
    </style>
@endsection
@section('title')
    Edit Project Status
@stop

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Edit pstatus </h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/
                    pstatus</span>
            </div>
        </div>
    </div>
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


    <div class="row">

        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <form id="yourFormId" action="{{ route('pstatus.update',$pstatus->id) }}" method="post"
                        enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        @method('PUT')
                        {{-- 1 --}}


                        <div class="row mt-3">
                            <div class="col">
                                <label for="pr_number_display" class="control-label">PR Number: <span class="tx-danger">*</span></label>
                                <input type="text" id="pr_number_display" class="form-control" readonly
                                    style="background-color: #f8f9fa; cursor: not-allowed;"
                                    value="{{ $pstatus->project->pr_number ?? '' }}">
                                <input type="hidden" name="pr_number" value="{{ $pstatus->pr_number }}">
                                <small class="text-muted">PR Number cannot be changed after creation.</small>
                            </div>

                            <div class="col">
                                <label class="control-label">Project Name:</label>
                                <input type="text" id="project_name_display" class="form-control" readonly
                                    style="background-color: #f8f9fa; cursor: not-allowed;"
                                    value="{{ $pstatus->project->name ?? '' }}">
                            </div>
                        </div>

                        <div class="row mt-3">
                            {{-- Date & Time (تم تعديل النوع لـ datetime-local وحقل القراءة فقط) --}}
                            <div class="col">
                                <label for="date_time" class="control-label">Date & Time: <span class="tx-danger">*</span></label>
                                <input type="datetime-local" class="form-control" id="date_time" name="date_time" required
                                    value="{{ old('date_time', \Carbon\Carbon::parse($pstatus->date_time)->format('Y-m-d\TH:i') ?? '') }}" readonly>
                                <small class="text-muted">Auto filled on creation/update, cannot be edited.</small>
                            </div>

                            {{-- PM Name (تم تحويله إلى حقل عرض للقراءة فقط وحقل مخفي للـ ID) --}}
                            <div class="col">
                                <label for="pm_name_display" class="control-label">PM Name: <span class="tx-danger">*</span></label>
                                <input type="text" id="pm_name_display" class="form-control" readonly
                                    placeholder="Auto filled from PR Number"
                                    value="{{ $pstatus->ppm->name ?? 'N/A' }}">

                                <input type="hidden" id="pm_name_id" name="pm_name"
                                    value="{{ old('pm_name', $pstatus->pm_name) }}">

                                <small class="text-muted">Automatically linked to the selected Project</small>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label for="status" class="control-label">Status:</label>
                                <textarea class="form-control" id="status" name="status" rows="4"
                                            placeholder="Enter project status...">{{ old('status', $pstatus->status) }}</textarea>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label for="actual_completion" class="control-label">Actual Completion %:</label>
                                <input type="number" class="form-control" id="actual_completion"
                                            name="actual_completion" min="0" max="100" step="0.01"
                                            value="{{ old('actual_completion', $pstatus->actual_completion) }}"
                                            placeholder="Enter percentage (0-100)">
                            </div>

                            <div class="col-md-4">
                                <label for="expected_completion" class="control-label">Expected Completion Date:</label>
                                <input type="date" class="form-control" id="expected_completion"
                                            name="expected_completion"
                                            value="{{ old('expected_completion', $pstatus->expected_completion) }}">
                            </div>

                            <div class="col-md-4">
                                <label for="date_pending_cost_orders" class="control-label">Pending Cost/Orders:</label>
                                <input type="text" class="form-control" id="date_pending_cost_orders"
                                            name="date_pending_cost_orders"
                                            value="{{ old('date_pending_cost_orders', $pstatus->date_pending_cost_orders) }}"
                                            placeholder="Enter pending costs or orders">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label for="notes" class="control-label">Notes:</label>
                                <textarea class="form-control" id="notes" name="notes" rows="4"
                                            placeholder="Enter additional notes...">{{ old('notes', $pstatus->notes) }}</textarea>
                            </div>
                        </div>

                        <div class="mg-t-30">
                            <button class="btn btn-outline-primary pd-x-20" type="submit">Update Project Status</button>
                            <a href="{{ route('pstatus.index') }}" class="btn btn-outline-secondary pd-x-20">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    </div>

    </div>
    </div>
    @endsection
@section('js')
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/select2.js') }}"></script>

    <script>
        $(document).ready(function() {
            // PR Number is now readonly, no change events needed
            console.log('✅ PStatus Edit page loaded - PR Number is locked');
        });
    </script>
@endsection

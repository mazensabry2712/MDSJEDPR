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
        #pm_name_display { /* إضافة PM Name هنا لضمان تنسيقه كحقل للقراءة فقط */
            pointer-events: none; /* لمنع تفاعل النقر تماماً */
            background-color: #f8f9fa; /* لون خلفية مثل الحقول للقراءة فقط */
            cursor: not-allowed;
        }
    </style>
@endsection
@section('title')
    Add Project Status
@stop

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Add pstatus </h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/
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
                    <form id="yourFormId" action="{{ route('pstatus.store') }}" method="POST" enctype="multipart/form-data"
                        autocomplete="off">
                        @csrf

                        {{-- 1 --}}

                        <div class="row mt-3">
                            <div class="col">
                                <label for="pr_number" class="control-label">PR Number: <span class="tx-danger">*</span></label>
                                <select name="pr_number" id="pr_number" class="form-control SlectBox" required>
                                    <option value="">Choose PR Number</option>
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}"
                                            data-project-name="{{ $project->name }}"
                                            data-pm-id="{{ $project->ppms_id }}" {{-- ⬅️ ID مدير المشروع --}}
                                            data-pm-name="{{ $project->ppms->name ?? 'N/A' }}" {{-- ⬅️ اسم مدير المشروع --}}
                                            {{ old('pr_number') == $project->id ? 'selected' : '' }}>
                                            {{ $project->pr_number }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col">
                                <label class="control-label">Project Name:</label>
                                <input type="text" id="project_name_display" class="form-control" readonly
                                    style="background-color: #f8f9fa; cursor: not-allowed;">
                            </div>
                        </div>

                        <div class="row mt-3">
                            {{-- Date & Time (القراءة فقط والتعبئة التلقائية) --}}
                            <div class="col">
                                <label for="date_time" class="control-label">Date & Time: <span class="tx-danger">*</span></label>
                                <input type="datetime-local" class="form-control" id="date_time" name="date_time" required
                                    value="{{ old('date_time') }}" readonly> {{-- ⬅️ تم إضافة readonly --}}
                                <small class="text-muted">Auto filled upon loading</small>
                            </div>

                            {{-- PM Name (حقل عرض للقراءة فقط وحقل مخفي للـ ID) --}}
                            <div class="col">
                                <label for="pm_name_display" class="control-label">PM Name: <span class="tx-danger">*</span></label>
                                {{-- حقل لعرض اسم مدير المشروع --}}
                                <input type="text" id="pm_name_display" class="form-control" readonly
                                    placeholder="Auto filled from PR Number">

                                {{-- حقل مخفي لإرسال الـ ID الخاص بمدير المشروع إلى الـ Backend --}}
                                <input type="hidden" id="pm_name_id" name="pm_name" value="{{ old('pm_name') }}">

                                <small class="text-muted">Automatically linked to the selected Project</small>
                            </div>
                        </div>



                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label for="status" class="control-label">Status:</label>
                                <textarea class="form-control" id="status" name="status" rows="4"
                                            placeholder="Enter project status...">{{ old('status') }}</textarea>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label for="actual_completion" class="control-label">Actual Completion %:</label>
                                <input type="number" class="form-control" id="actual_completion"
                                            name="actual_completion" min="0" max="100" step="0.01"
                                            value="{{ old('actual_completion') }}"
                                            placeholder="Enter percentage (0-100)">
                            </div>

                            <div class="col-md-4">
                                <label for="expected_completion" class="control-label">Expected Completion Date:</label>
                                <input type="date" class="form-control" id="expected_completion"
                                            name="expected_completion" value="{{ old('expected_completion') }}">
                            </div>

                            <div class="col-md-4">
                                <label for="date_pending_cost_orders" class="control-label">Pending Cost/Orders:</label>
                                <input type="text" class="form-control" id="date_pending_cost_orders"
                                            name="date_pending_cost_orders"
                                            value="{{ old('date_pending_cost_orders') }}"
                                            placeholder="Enter pending costs or orders">
                            </div>
                        </div>








                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label for="notes" class="control-label">Notes:</label>
                                <textarea class="form-control" id="notes" name="notes" rows="4"
                                            placeholder="Enter additional notes...">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        <div class="mg-t-30">
                            <button class="btn btn-outline-primary pd-x-20" type="submit">Save Project Status</button>
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
        // دالة مساعدة لتعيين التاريخ والوقت الحالي بالتنسيق المطلوب (YYYY-MM-DDThh:mm)
        function setDateTimeLocal() {
            const now = new Date();
            const timezoneOffset = now.getTimezoneOffset() * 60000;
            const localISOTime = new Date(now.getTime() - timezoneOffset).toISOString().slice(0, 16);

            // تعيين القيمة فقط إذا لم تكن هناك قيمة قديمة مخزنة
            if (!$('#date_time').val()) {
                $('#date_time').val(localISOTime);
            }
        }

        // دالة لتعبئة بيانات المشروع واسم مدير المشروع
        function autoFillProjectDetails() {
            const selectedOption = $('#pr_number').find('option:selected');
            const projectName = selectedOption.data('project-name');
            const pmId = selectedOption.data('pm-id'); // ⬅️ جلب ID مدير المشروع
            const pmName = selectedOption.data('pm-name'); // ⬅️ جلب اسم مدير المشروع

            // تعبئة Project Name
            if (projectName) {
                $('#project_name_display').val(projectName).css('color', '#495057');
            } else {
                $('#project_name_display').val('No project name available').css('color', '#6c757d');
            }

            // تعبئة PM Name
            if (pmName && pmId) {
                $('#pm_name_display').val(pmName).css('color', '#495057');
                // تحديث الحقل المخفي الذي سيتم إرساله في النموذج
                $('#pm_name_id').val(pmId);
            } else {
                $('#pm_name_display').val('No PM assigned to this project').css('color', '#dc3545');
                $('#pm_name_id').val('');
            }
        }

        $(document).ready(function() {
            // 1. تعيين التاريخ والوقت فوراً عند تحميل الصفحة
            setDateTimeLocal();

            // 2. تعبئة بيانات المشروع عند تغيير PR Number
            $('#pr_number').on('change', function() {
                autoFillProjectDetails();
            });

            // 3. التهيئة عند تحميل الصفحة (في حال وجود قيمة قديمة)
            if ($('#pr_number').val()) {
                autoFillProjectDetails();
            }
        });
    </script>
@endsection

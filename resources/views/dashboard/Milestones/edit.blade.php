
@extends('layouts.master')
@section('css')
    <!--- Internal Select2 css-->
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <!---Internal Fileupload css-->
    <link href="{{ URL::asset('assets/plugins/fileuploads/css/fileupload.css') }}" rel="stylesheet" type="text/css" />
    <!---Internal Fancy uploader css-->
    <link href="{{ URL::asset('assets/plugins/fancyuploder/fancy_fileupload.css') }}" rel="stylesheet" />
    <!--Internal Sumoselect css-->
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/sumoselect/sumoselect-rtl.css') }}">
    <!--Internal  TelephoneInput css-->
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/telephoneinput/telephoneinput-rtl.css') }}">
@endsection
@section('title')
    Add Milestone
@stop

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">update milestone </h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/
                milestone</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')

    <!-- @if (session()->has('Add'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('Add') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif -->
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
                    <form id="yourFormId" action="{{ route('milestones.update',$milestones->id) }}" method="post"
                        enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        @method('PUT')
                        {{-- 1 --}}


                        <div class="row mt-3">
                            <div class="col">
                                <label for="pr_number" class="control-label">PR Number <span class="text-danger">*</span></label>
                                <select name="pr_number" id="pr_number" class="form-control SlectBox" required>
                                    <option value="" disabled>Select PR Number</option>
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}"
                                            data-project-name="{{ $project->name }}"
                                            @selected($milestones->pr_number == $project->id)>
                                            {{ $project->pr_number }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col">
                                <label for="project_name" class="control-label">Project Name</label>
                                <input type="text" class="form-control" id="project_name" readonly
                                    value="{{ $milestones->project->name ?? '' }}">
                            </div>

                            <div class="col">
                                <label for="milestone" class="control-label">Milestone <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="milestone" name="milestone"
                                    value="{{ $milestones->milestone }}" required>
                            </div>
                        </div>













                        <div class="row mt-3">
                            <div class="col">
                                <label for="planned_com" class="control-label">Planned Completion Date</label>
                                <input type="date" class="form-control" id="planned_com" name="planned_com"
                                    value="{{ $milestones->planned_com }}">
                            </div>

                            <div class="col">
                                <label for="actual_com" class="control-label">Actual Completion Date</label>
                                <input type="date" class="form-control" id="actual_com" name="actual_com"
                                    value="{{ $milestones->actual_com }}">
                            </div>

                            <div class="col">
                                <label for="status" class="control-label">Status <span class="text-danger">*</span></label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="">Select Status</option>
                                    <option value="on track" @selected($milestones->status == 'on track')>On Track</option>
                                    <option value="delayed" @selected($milestones->status == 'delayed')>Delayed</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col">
                                <label for="comments" class="control-label">Comments</label>
                                <textarea class="form-control" id="comments" name="comments" rows="3"
                                    style="min-height: 80px;">{{ $milestones->comments }}</textarea>
                            </div>
                        </div>

                        <br>



                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary"> update milestone </button>
                        </div>


                    </form>
                </div>
            </div>
        </div>
    </div>

    </div>

    <!-- row closed -->
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection
@section('js')
    <!-- Internal Select2 js-->
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <!--Internal Fileuploads js-->
    <script src="{{ URL::asset('assets/plugins/fileuploads/js/fileupload.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fileuploads/js/file-upload.js') }}"></script>
    <!--Internal Fancy uploader js-->
    <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.ui.widget.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.fileupload.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.iframe-transport.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.fancy-fileupload.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fancyuploder/fancy-uploader.js') }}"></script>
    <!--Internal  Form-elements js-->
    <script src="{{ URL::asset('assets/js/advanced-form-elements.js') }}"></script>
    <script src="{{ URL::asset('assets/js/select2.js') }}"></script>
    <!--Internal Sumoselect js-->
    <script src="{{ URL::asset('assets/plugins/sumoselect/jquery.sumoselect.js') }}"></script>
    <!--Internal  Datepicker js -->
    <script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
    <!--Internal  jquery.maskedinput js -->
    <script src="{{ URL::asset('assets/plugins/jquery.maskedinput/jquery.maskedinput.js') }}"></script>
    <!--Internal  spectrum-colorpicker js -->
    <script src="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.js') }}"></script>
    <!-- Internal form-elements js -->
    <script src="{{ URL::asset('assets/js/form-elements.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Auto-fill Project Name when PR Number is selected
            $('#pr_number').on('change', function() {
                var selectedOption = $(this).find('option:selected');
                var projectName = selectedOption.data('project-name');
                $('#project_name').val(projectName);
            });

            // Trigger auto-fill on page load if PR Number is already selected
            if ($('#pr_number').val()) {
                $('#pr_number').trigger('change');
            }
        });
    </script>
@endsection

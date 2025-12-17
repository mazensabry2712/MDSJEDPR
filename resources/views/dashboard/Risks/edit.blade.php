
@extends('layouts.master')
@section('css')
    <!--- Internal Select2 css-->
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <style>
        textarea.form-control:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
    </style>
@endsection
@section('title')
    Add Invoice
@stop

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">update risk </h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/
                    risk</span>
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
                    <form id="yourFormId" action="{{ route('risks.update',$risks->id) }}" method="post"
                        enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        @method('PUT')
                        {{-- 1 --}}


                        <div class="row mt-3">
                            <div class="col">
                                <label for="pr_number" class="control-label">PR# <span class="text-danger">*</span></label>
                                <select name="pr_number" id="pr_number" class="form-control select2" required>
                                    <option value="" disabled>Select PR Number</option>
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}" data-project-name="{{ $project->name }}"
                                            {{ old('pr_number', $risks->pr_number) == $project->id ? 'selected' : '' }}>
                                            {{ $project->pr_number }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col">
                                <label for="project_name_display" class="control-label">Project Name</label>
                                <input type="text" class="form-control" id="project_name_display" readonly
                                       style="background-color: #f8f9fa; cursor: not-allowed;">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col">
                                <label for="risk" class="control-label">Risk/Issue <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="risk" name="risk" rows="3" required>{{ old('risk', $risks->risk) }}</textarea>
                            </div>




                        </div>













                        <div class="row mt-3">
                            <div class="col">
                                <label for="impact" class="control-label">Impact <span class="text-danger">*</span></label>
                                <select class="form-control" id="impact" name="impact" required>
                                    <option value="" disabled>Select Impact</option>
                                    <option value="low" {{ old('impact', $risks->impact) == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="med" {{ old('impact', $risks->impact) == 'med' ? 'selected' : '' }}>Med</option>
                                    <option value="high" {{ old('impact', $risks->impact) == 'high' ? 'selected' : '' }}>High</option>
                                </select>
                            </div>

                            <div class="col">
                                <label for="mitigation" class="control-label">Mitigation/Action Plan</label>
                                <textarea class="form-control" id="mitigation" name="mitigation" rows="3">{{ old('mitigation', $risks->mitigation) }}</textarea>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col">
                                <label for="owner" class="control-label">Owner</label>
                                <input type="text" class="form-control" id="owner" name="owner" value="{{ old('owner', $risks->owner) }}">
                            </div>

                            <div class="col">
                                <label for="status" class="control-label">Status <span class="text-danger">*</span></label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="open" {{ old('status', $risks->status) == 'open' ? 'selected' : '' }}>Open</option>
                                    <option value="closed" {{ old('status', $risks->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                            </div>
                        </div>

                        <br>

                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-outline-primary">Update Risk</button>
                            <a href="{{ route('risks.index') }}" class="btn btn-outline-secondary ml-2">Cancel</a>
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
        var date = $('.fc-datepicker').datepicker({
            dateFormat: 'mm/dd/yy'
        }).val();
    </script>

    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2();

            // Project Name Auto-fill
            $('#pr_number').on('change', function() {
                var selectedOption = $(this).find('option:selected');
                var projectName = selectedOption.data('project-name');
                $('#project_name_display').val(projectName || '');
            });

            // Trigger on page load to show existing project name
            $('#pr_number').trigger('change');
        });
    </script>
@endsection

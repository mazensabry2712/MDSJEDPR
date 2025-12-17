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
    Add EPO
@stop

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Add pepo </h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/
                    pepo</span>
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
                    <form id="yourFormId" action="{{ route('epo.store') }}" method="post" enctype="multipart/form-data"
                        autocomplete="off">
                        {{ csrf_field() }}
                        {{-- 1 --}}



                        <div class="row mt-3">
                            <!-- PR Number -->
                            <div class="col-md-6">
                                <label for="pr_number" class="control-label">PR Number <span class="text-danger">*</span></label>
                                <select id="pr_number" name="pr_number" class="form-control select2" required>
                                    <option value="">Select PR Number</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}"
                                                data-project-name="{{ $project->name }}"
                                                {{ old('pr_number') == $project->id ? 'selected' : '' }}>
                                            {{ $project->pr_number }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('pr_number')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Project Name (Auto-filled, readonly) -->
                            <div class="col-md-6">
                                <label for="project_name" class="control-label">Project Name</label>
                                <input type="text"
                                       id="project_name"
                                       class="form-control"
                                       value="{{ old('project_name') }}"
                                       placeholder="Project Name will appear here"
                                       readonly
                                       style="background-color: #e9ecef; cursor: not-allowed;">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col">
                                <label for="category" class="control-label">Category</label>
                                <input type="text" class="form-control" id="category" name="category"
                                    value="{{ old('category') }}"
                                    title="Please enter the category">
                            </div>
                        </div>




                        <div class="row mt-3">
                            <div class="col">
                                <label for="expected_com_date" class="control-label">Planned Cost </label>
                                <input type="number" class="form-control" id="expected_com_date" name="planned_cost"
                                    title="   Please enter the planned_cost ">
                            </div>

                            <div class="col">
                                <label for="" class="control-label">Selling Price </label>
                                <input type="number" class="form-control" id="" name="selling_price"
                                    title="   Please enter the selling_price ">
                            </div>

                        </div>
                        <br>
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary"> Save pepo </button>
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
    <!--Internal  Datepicker js -->
    <script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
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
    <!-- Internal TelephoneInput js-->
    <script src="{{ URL::asset('assets/plugins/telephoneinput/telephoneinput.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/telephoneinput/inttelephoneinput.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2();

            // Auto-fill Project Name when PR Number changes
            $('#pr_number').on('change', function() {
                var selectedOption = $(this).find(':selected');
                var projectName = selectedOption.data('project-name') || '';
                $('#project_name').val(projectName);
            });

            // Trigger on page load (for old values)
            var initialProjectName = $('#pr_number').find(':selected').data('project-name') || '';
            if(initialProjectName) {
                $('#project_name').val(initialProjectName);
            }
        });

        var date = $('.fc-datepicker').datepicker({
            dateFormat: 'yy-mm-dd'
        }).val();
    </script>
@endsection

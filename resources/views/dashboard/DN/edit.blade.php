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

    <style>
        /* Drag and Drop Styles */
        .drag-drop-area {
            border: 3px dashed #dee2e6;
            border-radius: 12px;
            padding: 40px 20px;
            text-align: center;
            background-color: #f8f9fa;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            min-height: 200px;
        }

        .drag-drop-area:hover {
            border-color: #007bff;
            background-color: #e7f3ff;
        }

        .drag-drop-area.dragover {
            border-color: #28a745;
            background-color: #d4edda;
            transform: scale(1.02);
        }

        .current-file {
            background-color: #e9ecef;
            border: 1px solid #ced4da;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
            text-align: center;
        }

        .current-file img {
            max-width: 150px;
            max-height: 150px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .drag-drop-content {
            pointer-events: auto;
        }

        .drag-drop-icon {
            margin-bottom: 20px;
        }

        .drag-drop-title {
            color: #495057;
            margin-bottom: 10px;
            font-size: 1.5rem;
        }

        .drag-drop-subtitle {
            color: #6c757d;
            margin-bottom: 15px;
        }

        .browse-link {
            color: #007bff;
            text-decoration: underline;
            cursor: pointer;
            pointer-events: auto !important;
            z-index: 10;
            position: relative;
        }

        .file-preview {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #fff;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .preview-content {
            text-align: center;
            max-width: 90%;
        }

        .preview-image {
            max-width: 150px;
            max-height: 150px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-bottom: 15px;
        }

        .preview-info {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
        }

        .file-name {
            font-weight: bold;
            color: #495057;
        }

        .file-size {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .remove-file {
            margin-top: 10px;
        }

        .form-control {
            border-radius: 0.25rem;
            border: 1px solid #ced4da;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .card {
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border: none;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>
@endsection
@section('title')
    Add DN
@stop

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">update DN</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/
                    DN</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')

    @if (session()->has('delete'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('delete') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

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
                    <form id="yourFormId" action="{{ route('dn.update',$dn->id) }}" method="post" enctype="multipart/form-data"
                        autocomplete="off">
                        @csrf
                        @method('PUT')
                        {{-- 1 --}}

                        <div class="row">




                            <div class="col">
                                <label for="dn_number" class="control-label">DN number </label>
                                <input type="text" class="form-control @error('dn_number') is-invalid @enderror" id="dn_number" name="dn_number"
                                    title="   Please enter the dn number "
                                    value="{{ old('dn_number', $dn->dn_number) }}">
                                @error('dn_number')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>





                            <div class="col">
                                <label for="pr_numberr" class="control-label"> PR number </label>
                                <select name="pr_number" class="form-control SlectBox" id="pr_number_select_edit"
                                    onchange="updateProjectNameEdit()">
                                    <!--placeholder-->
                                    <option value="" selected disabled> PR number </option>
                                    @foreach ($projects as $pr_numberr)
                                        <option value="{{ $pr_numberr->id }}"
                                            data-project-name="{{ $pr_numberr->name }}"
                                            @if(old('pr_number', $dn->pr_number) == $pr_numberr->id) selected @endif>
                                            {{ $pr_numberr->pr_number }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col">
                                <label for="project_name_display_edit" class="control-label">Project Name</label>
                                <input type="text" class="form-control" id="project_name_display_edit"
                                    placeholder="Project name will appear here..." readonly
                                    style="background-color: #f8f9fa; cursor: not-allowed;">
                            </div>

                            <div class="col">
                                <label for="status" class="control-label">Status </label>
                                <input type="text" class="form-control" id="status" name="status"
                                    title="   Please enter the  status  "
                                    value="{{ old('status', $dn->status) }}">
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col">
                                <div style="text-align:justify;">
                                    <div style="display: inline-flex;">
                                        <h5 class="card-title mr-3">DN Copy attachment</h5>
                                        <p class="text-danger">* Attachment format pdf, jpeg, .jpg, png </p>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-12">
                                    <input type="file" name="dn_copy" class="dropify"
                                        accept=".pdf,.jpg, .png, image/jpeg, image/png" data-height="70" />
                                </div><br>
                            </div>
                        </div> <br>


                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary"> Update DN </button>
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
    {{-- ds  --}}
    <script>
        $(document).ready(function() {
            $('select[name="ds_id"]').on('change', function() {
                var SectionId = $(this).val();
                if (SectionId) {
                    $.ajax({
                        url: "{{ URL::to('ds_id') }}/" + SectionId,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('select[name="product"]').empty();
                            $.each(data, function(key, value) {
                                $('select[name="product"]').append('<option value="' +
                                    value + '">' + value + '</option>');
                            });
                        },
                    });

                } else {
                    console.log('AJAX load did not work');
                }
            });

        });
    </script>
    {{-- /ds  --}}





    <script>
        var date = $('.fc-datepicker').datepicker({
            dateFormat: 'yy-mm-dd'
        }).val();

        // Function to update project name when PR number is selected (Edit page)
        function updateProjectNameEdit() {
            const selectElement = document.getElementById('pr_number_select_edit');
            const projectNameDisplay = document.getElementById('project_name_display_edit');

            if (selectElement.value) {
                const selectedOption = selectElement.options[selectElement.selectedIndex];
                const projectName = selectedOption.getAttribute('data-project-name');

                if (projectName && projectName.trim() !== '') {
                    projectNameDisplay.value = projectName;
                    projectNameDisplay.style.color = '#495057';
                } else {
                    projectNameDisplay.value = 'No project name available';
                    projectNameDisplay.style.color = '#6c757d';
                }
            } else {
                projectNameDisplay.value = '';
                projectNameDisplay.placeholder = 'Project name will appear here...';
            }
        }

        // Initialize project name on page load for edit page
        document.addEventListener('DOMContentLoaded', function() {
            updateProjectNameEdit();
        });
    </script>
@endsection

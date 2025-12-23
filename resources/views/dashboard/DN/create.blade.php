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
                <h4 class="content-title mb-0 my-auto">Add Delivery Note</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/
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
                    <form id="yourFormId" action="{{ route('dn.store') }}" method="post" enctype="multipart/form-data"
                        autocomplete="off">
                        @csrf
                        {{-- 1 --}}

                        <div class="row">




                            <div class="col">
                                <label for="dn_number" class="control-label">DN number </label>
                                <input type="text" class="form-control @error('dn_number') is-invalid @enderror" id="dn_number" name="dn_number"
                                    value="{{ old('dn_number') }}"
                                    title="   Please enter the dn number ">
                                @error('dn_number')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>





                            <div class="col">
                                <label for="pr_numberr" class="control-label"> PR number </label>
                                <select name="pr_number" class="form-control SlectBox" id="pr_number_select"
                                    onchange="updateProjectName()">
                                    <!--placeholder-->
                                    <option value="" selected disabled> PR number </option>
                                    @foreach ($projects as $pr_numberr)
                                        <option value="{{ $pr_numberr->id }}"
                                            data-project-name="{{ $pr_numberr->name }}"
                                            {{ old('pr_number') == $pr_numberr->id ? 'selected' : '' }}>
                                            {{ $pr_numberr->pr_number }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col">
                                <label for="project_name_display" class="control-label">Project Name</label>
                                <input type="text" class="form-control" id="project_name_display"
                                    placeholder="Project name will appear here..." readonly
                                    style="background-color: #f8f9fa; cursor: not-allowed;">
                            </div>

                            <div class="col">
                                <label for="date" class="control-label">Date </label>
                                <input type="date" class="form-control" id="date" name="date"
                                    value="{{ old('date') }}"
                                    title="Please enter the date">
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col">
                                <div style="text-align:justify;">
                                    <div style="display: inline-flex;">
                                        <h5 class="card-title mr-3">DN Copy Attachment</h5>
                                        <p class="text-danger">* Supported formats: PDF, JPEG, JPG, PNG (Max: 2MB)</p>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-12">
                                    <div class="drag-drop-area" id="dragDropArea">
                                        <div class="drag-drop-content">
                                            <div class="drag-drop-icon">
                                                <i class="fas fa-cloud-upload-alt fa-3x text-muted"></i>
                                            </div>
                                            <h4 class="drag-drop-title">Drop files here</h4>
                                            <p class="drag-drop-subtitle">or <span class="browse-link">browse files</span></p>
                                            <small class="text-muted">Supported formats: PDF, JPEG, JPG, PNG</small>
                                        </div>
                                    </div>
                                    <input type="file" name="dn_copy" id="dnCopyInput"
                                           accept=".pdf,.jpg,.png,.jpeg"
                                           style="display: none;" />
                                </div><br>
                            </div>
                        </div> <br>


                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary"> Save DN </button>
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
    </script>

    <script>
        // Drag and Drop functionality
        $(document).ready(function() {
            const dragDropArea = $('#dragDropArea');
            const fileInput = $('#dnCopyInput');

            // Click to browse functionality
            dragDropArea.on('click', function(e) {
                if (!$(e.target).hasClass('remove-file') && !$(e.target).closest('.file-preview').length) {
                    fileInput.click();
                }
            });

            // Browse link click
            $(document).on('click', '.browse-link', function(e) {
                e.preventDefault();
                e.stopPropagation();
                fileInput.click();
            });

            // Drag and drop events
            dragDropArea.on('dragover dragenter', function(e) {
                e.preventDefault();
                e.stopPropagation();
                dragDropArea.addClass('dragover');
            });

            dragDropArea.on('dragleave', function(e) {
                e.preventDefault();
                e.stopPropagation();
                dragDropArea.removeClass('dragover');
            });

            dragDropArea.on('drop', function(e) {
                e.preventDefault();
                e.stopPropagation();
                dragDropArea.removeClass('dragover');

                const files = e.originalEvent.dataTransfer.files;
                if (files.length > 0) {
                    handleFileSelection(files[0]);
                }
            });

            // File input change
            fileInput.on('change', function(e) {
                if (this.files && this.files.length > 0) {
                    handleFileSelection(this.files[0]);
                }
            });

            function handleFileSelection(file) {
                // Validate file type
                const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Please select a valid file type (PDF, JPEG, JPG, PNG)');
                    return;
                }

                // Validate file size (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('File size should not exceed 2MB');
                    return;
                }

                showFilePreview(file);
            }

            function showFilePreview(file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    let previewHTML = '';

                    if (file.type.startsWith('image/')) {
                        previewHTML = `
                            <div class="preview-content">
                                <img src="${e.target.result}" class="preview-image" alt="Preview">
                                <div class="preview-info">
                                    <div class="file-name">${file.name}</div>
                                    <div class="file-size">${formatFileSize(file.size)}</div>
                                    <button type="button" class="btn btn-sm btn-danger remove-file">
                                        <i class="fas fa-trash"></i> Remove
                                    </button>
                                </div>
                            </div>
                        `;
                    } else {
                        previewHTML = `
                            <div class="preview-content">
                                <i class="fas fa-file-pdf fa-5x text-danger mb-3"></i>
                                <div class="preview-info">
                                    <div class="file-name">${file.name}</div>
                                    <div class="file-size">${formatFileSize(file.size)}</div>
                                    <button type="button" class="btn btn-sm btn-danger remove-file">
                                        <i class="fas fa-trash"></i> Remove
                                    </button>
                                </div>
                            </div>
                        `;
                    }

                    dragDropArea.html(`<div class="file-preview">${previewHTML}</div>`);
                };

                reader.readAsDataURL(file);
            }

            // Remove file
            $(document).on('click', '.remove-file', function(e) {
                e.preventDefault();
                e.stopPropagation();
                fileInput.val('');
                dragDropArea.html(`
                    <div class="drag-drop-content">
                        <div class="drag-drop-icon">
                            <i class="fas fa-cloud-upload-alt fa-3x text-muted"></i>
                        </div>
                        <h4 class="drag-drop-title">Drop files here</h4>
                        <p class="drag-drop-subtitle">or <span class="browse-link">browse files</span></p>
                        <small class="text-muted">Supported formats: PDF, JPEG, JPG, PNG</small>
                    </div>
                `);
            });

            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }
        });

        // Function to update project name when PR number is selected
        function updateProjectName() {
            const selectElement = document.getElementById('pr_number_select');
            const projectNameDisplay = document.getElementById('project_name_display');

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

        // Initialize project name on page load if old value exists
        document.addEventListener('DOMContentLoaded', function() {
            updateProjectName();
        });
    </script>
@endsection

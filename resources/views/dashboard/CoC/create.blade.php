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

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Dashboard</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ Certificate of Compliance</span>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ Add New</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <a class="btn btn-secondary" href="{{ route('coc.index') }}">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    <!-- row -->
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Add New Certificate of Compliance</div>
                </div>
                <div class="card-body">
                    <form action="{{ route('coc.store') }}" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pr_number">
                                        <i class="fas fa-hashtag mr-2"></i>PR Number
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control select2 @error('pr_number') is-invalid @enderror"
                                            name="pr_number"
                                            id="pr_number"
                                            required>
                                        <option value="" selected disabled>Select PR Number</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->id }}"
                                                    data-project-name="{{ $project->name }}"
                                                    {{ old('pr_number') == $project->id ? 'selected' : '' }}>
                                                {{ $project->pr_number }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('pr_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="project_name_display">
                                        <i class="fas fa-project-diagram mr-2"></i>Project Name
                                    </label>
                                    <input type="text"
                                           class="form-control"
                                           id="project_name_display"
                                           placeholder="Project name will appear here..."
                                           readonly
                                           style="background-color: #f8f9fa; cursor: not-allowed;">
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col">
                                <div style="text-align:justify;">
                                    <div style="display: inline-flex;">
                                        <h5 class="card-title mr-3">CoC File Attachment <span class="text-danger">*</span></h5>
                                        <p class="text-danger">Supported formats: PDF, Word, JPG, JPEG, PNG, GIF (Max: 10MB)</p>
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
                                            <small class="text-muted">Supported formats: PDF, Word, JPG, JPEG, PNG, GIF</small>
                                        </div>
                                    </div>
                                    <input type="file" name="coc_copy" id="cocCopyInput"
                                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif"
                                           style="display: none;" required />
                                    @error('coc_copy')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div><br>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="mg-t-30">
                                    <button class="btn btn-main-primary pd-x-20" type="submit">
                                        <i class="fas fa-save"></i> Save Certificate
                                    </button>
                                    <a href="{{ route('coc.index') }}" class="btn btn-secondary pd-x-20">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- row closed -->
@endsection

@section('js')
<!--Internal  Select2 js -->
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
<!--Internal  Telephone Input js -->
<script src="{{ URL::asset('assets/plugins/telephoneinput/telephoneinput.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/telephoneinput/inttelephoneinput.js') }}"></script>

<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            placeholder: "Select PR Number",
            allowClear: true
        });

        // Auto-fill Project Name when PR Number is selected
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

        // Drag and Drop functionality
        const dragDropArea = $('#dragDropArea');
        const fileInput = $('#cocCopyInput');

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
            const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!allowedTypes.includes(file.type)) {
                alert('Please select a valid file type (PDF, Word, JPG, JPEG, PNG, GIF)');
                return;
            }

            // Validate file size (10MB)
            if (file.size > 10 * 1024 * 1024) {
                alert('File size should not exceed 10MB');
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
                } else if (file.type === 'application/pdf') {
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
                } else {
                    previewHTML = `
                        <div class="preview-content">
                            <i class="fas fa-file-word fa-5x text-primary mb-3"></i>
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
                    <small class="text-muted">Supported formats: PDF, Word, JPG, JPEG, PNG, GIF</small>
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
</script>
@endsection

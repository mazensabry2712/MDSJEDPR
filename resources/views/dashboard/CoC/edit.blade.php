@extends('layouts.master')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .info-card { background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); border: none; border-radius: 15px; box-shadow: 0 10px 20px rgba(0,123,255,0.2); }
    .card { border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); border: none; margin-bottom: 20px; }
    .card-header { background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); color: white; border-radius: 15px 15px 0 0 !important; padding: 20px; border: none; }
    .card-header h4 { margin: 0; font-weight: 600; }
    .form-group label { font-weight: 600; color: #333; margin-bottom: 8px; }
    .form-control { border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 15px; transition: all 0.3s ease; }
    .form-control:focus { border-color: #007bff; box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25); }
    .btn-primary { background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); border: none; border-radius: 8px; padding: 12px 30px; font-weight: 600; transition: all 0.3s ease; }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0, 123, 255, 0.4); }
    .btn-secondary { border-radius: 8px; padding: 12px 30px; font-weight: 600; }
    .select2-container--default .select2-selection--single { border-radius: 8px; border: 1px solid #dee2e6; height: calc(2.25rem + 2px); padding: 10px 15px; }
    .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 23px; }
    .select2-container--default .select2-selection--single .select2-selection__arrow { height: calc(2.25rem + 2px); }
    .alert { border-radius: 10px; border: none; }
    .alert-info { background-color: #e7f3ff; color: #004085; }
    .fas { color: #007bff; }
    .text-danger { color: #dc3545 !important; }

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
</style>
@endsection

@section('page-header')
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">CoC</h4>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ Edit Certificate of Compliance</span>
        </div>
    </div>
</div>
@endsection

@section('content')

@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0"><i class="fas fa-file-signature mr-2"></i>CoC Information</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('coc.update', $coc->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pr_number"><i class="fas fa-briefcase mr-2"></i>PR Number <span class="text-danger">*</span></label>
                                <select class="form-control select2 @error('pr_number') is-invalid @enderror" name="pr_number" id="pr_number" required>
                                    <option value="">Select PR Number</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}"
                                                data-project-name="{{ $project->name }}"
                                                data-pr-number="{{ $project->pr_number }}"
                                                {{ old('pr_number', $coc->pr_number) == $project->id ? 'selected' : '' }}>
                                            {{ $project->pr_number }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('pr_number')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="project_name"><i class="fas fa-project-diagram mr-2"></i>Project Name</label>
                                <input type="text" class="form-control" id="project_name" value="{{ old('project_name', $coc->project->name ?? '') }}" readonly placeholder="Auto-fill from PR Number">
                            </div>
                        </div>
                    </div>

                    @if($coc->coc_copy)
                        <div class="alert alert-info">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle fa-2x mr-3"></i>
                                <div>
                                    <strong>Current File:</strong>
                                    <a href="{{ asset('../storge/' . $coc->coc_copy) }}" target="_blank" class="text-primary">{{ basename($coc->coc_copy) }}</a>
                                    <br><small class="text-muted"><i class="fas fa-exclamation-triangle mr-1"></i>Upload a new file only if you want to replace the current one</small>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="row mt-3">
                        <div class="col">
                            <div style="text-align:justify;">
                                <div style="display: inline-flex;">
                                    <h5 class="card-title mr-3">CoC File Attachment (Optional)</h5>
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
                                       style="display: none;" />
                                @error('coc_copy')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div><br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mb-0 mt-3">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-2"></i>Update CoC</button>
                                <a href="{{ route('coc.index') }}" class="btn btn-secondary"><i class="fas fa-times mr-2"></i>Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({ placeholder: 'Select PR Number', allowClear: true });

    // Auto-fill Project Name
    $('#pr_number').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var projectName = selectedOption.data('project-name');
        $('#project_name').val(projectName || '');
    });

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

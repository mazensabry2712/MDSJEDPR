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

        /* Animated Alert Styles - Same as Index */
        .alert {
            border-radius: 8px;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 15px 20px;
            position: relative;
            animation: slideInDown 0.5s ease-out;
        }

        .alert-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }

        .alert-danger {
            background: linear-gradient(135deg, #dc3545 0%, #e74c3c 100%);
            color: white;
        }

        .alert .close {
            color: white;
            opacity: 0.8;
            font-size: 20px;
        }

        .alert .close:hover {
            opacity: 1;
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endsection
@section('title')
    Edit Invoice
@stop

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Invoices</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/
                    Edit Invoice</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')

    @if (session()->has('Add'))
        <div class="alert alert-success alert-dismissible fade show" role="alert"
            style="position: relative; z-index: 1050;">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle mr-3" style="font-size: 20px;"></i>
                <div>
                    <strong>Success!</strong>
                    <div>{{ session()->get('Add') }}</div>
                </div>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"
                style="position: absolute; top: 15px; right: 20px;">
                <span aria-hidden="true" style="color: white; font-size: 24px;">&times;</span>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert"
            style="position: relative; z-index: 1050;">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle mr-3" style="font-size: 20px;"></i>
                <div>
                    <strong>Error!</strong>
                    <ul class="mb-0 mt-2" style="padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"
                style="position: absolute; top: 15px; right: 20px;">
                <span aria-hidden="true" style="color: white; font-size: 24px;">&times;</span>
            </button>
        </div>
    @endif


    <!-- row -->
    <div class="row">

        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <form id="yourFormId" action="{{ route('invoices.update', $invoices->id) }}" method="post"
                        enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        @method('PUT')
                        {{-- 1 --}}



                        <div class="row">
                            <div class="col-md-4">
                                <label for="invoice_number" class="control-label">
                                    <i class="fas fa-file-invoice mr-2"></i>Invoice Number
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="number"
                                       class="form-control @error('invoice_number') is-invalid @enderror"
                                       id="invoice_number"
                                       name="invoice_number"
                                       value="{{ old('invoice_number', $invoices->invoice_number) }}"
                                       placeholder="Enter invoice number"
                                       required>
                                @error('invoice_number')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="value" class="control-label">
                                    <i class="fas fa-dollar-sign mr-2"></i>Value (SAR)
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="number"
                                       step="0.01"
                                       class="form-control @error('value') is-invalid @enderror"
                                       id="value"
                                       name="value"
                                       value="{{ old('value', $invoices->value) }}"
                                       placeholder="Enter invoice value"
                                       required>
                                @error('value')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="pr_number" class="control-label">
                                    <i class="fas fa-project-diagram mr-2"></i>PR Number
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="pr_number"
                                        id="pr_number"
                                        class="form-control select2 @error('pr_number') is-invalid @enderror"
                                        required>
                                    <option value="" selected disabled>Select PR Number</option>
                                    @foreach ($pr_number_idd as $pr_number_id)
                                        <option value="{{ $pr_number_id->id }}"
                                                data-project-name="{{ $pr_number_id->name }}"
                                                {{ (old('pr_number', $invoices->pr_number) == $pr_number_id->id) ? 'selected' : '' }}>
                                            {{ $pr_number_id->pr_number }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('pr_number')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label for="project_name_display" class="control-label">
                                    <i class="fas fa-tag mr-2"></i>Project Name
                                </label>
                                <input type="text"
                                       class="form-control"
                                       id="project_name_display"
                                       placeholder="Project name will appear here..."
                                       readonly
                                       style="background-color: #f8f9fa; cursor: not-allowed;">
                            </div>
                        </div>












                        <div class="row mt-4">
                            <div class="col">
                                <label for="invoice_copy" class="control-label mb-3">
                                    <i class="fas fa-file-upload mr-2"></i>Invoice Copy (PDF or Image)
                                </label>

                                @if($invoices->invoice_copy_path)
                                    <div class="alert alert-info animated fadeIn" style="animation-duration: 0.5s;">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-info-circle fa-2x mr-3"></i>
                                            <div>
                                                <strong>Current File:</strong>
                                                <a href="{{ asset($invoices->invoice_copy_path) }}" target="_blank" class="text-primary">
                                                    {{ basename($invoices->invoice_copy_path) }}
                                                </a>
                                                <br>
                                                <small class="text-muted">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                                    Upload a new file to replace the current one
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="col-sm-12 col-md-12">
                                    <div class="drag-drop-area" id="dragDropArea">
                                        <div class="drag-drop-content">
                                            <div class="drag-drop-icon">
                                                <i class="fas fa-cloud-upload-alt fa-3x text-muted"></i>
                                            </div>
                                            <h4 class="drag-drop-title">Drop files here</h4>
                                            <p class="drag-drop-subtitle">or <span class="browse-link">browse files</span></p>
                                            <small class="text-muted">Supported formats: PDF, JPEG, JPG, PNG, GIF (Max 10MB)</small>
                                        </div>
                                    </div>
                                    <input type="file" name="invoice_copy_path" id="invoiceCopyInput"
                                           accept=".pdf,.jpg,.png,.jpeg,.gif"
                                           style="display: none;" />
                                </div><br>
                            </div>
                        </div>







                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label for="status" class="control-label">
                                    <i class="fas fa-info-circle mr-2"></i>Status
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       class="form-control @error('status') is-invalid @enderror"
                                       id="status"
                                       name="status"
                                       value="{{ old('status', $invoices->status) }}"
                                       placeholder="Enter invoice status (e.g., Paid, Pending, Overdue)"
                                       required>
                                @error('status')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="form-text text-muted">
                                    <i class="fas fa-lightbulb mr-1"></i>
                                    Examples: Paid, Pending, Overdue, Cancelled, Processing, etc.
                                </small>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('invoices.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left mr-2"></i>Back to List
                                    </a>
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-save mr-2"></i>Save Invoice
                                    </button>
                                </div>
                            </div>
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

    {{-- Drag and Drop & Auto-fill Functionality --}}
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                placeholder: 'Select PR Number',
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
            const fileInput = $('#invoiceCopyInput');

            // Click to browse
            dragDropArea.on('click', function(e) {
                if (!$(e.target).hasClass('remove-file') && !$(e.target).parent().hasClass('remove-file')) {
                    fileInput.click();
                }
            });

            $('.browse-link').on('click', function(e) {
                e.stopPropagation();
                fileInput.click();
            });

            // Drag and drop events
            dragDropArea.on('dragover', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).addClass('dragover');
            });

            dragDropArea.on('dragleave', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).removeClass('dragover');
            });

            dragDropArea.on('drop', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).removeClass('dragover');

                const files = e.originalEvent.dataTransfer.files;
                if (files.length > 0) {
                    fileInput[0].files = files;
                    handleFile(files[0]);
                }
            });

            // File input change
            fileInput.on('change', function() {
                if (this.files.length > 0) {
                    handleFile(this.files[0]);
                }
            });

            function handleFile(file) {
                // Validate file type
                const validTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!validTypes.includes(file.type)) {
                    alert('Please select a valid file (PDF, JPEG, JPG, PNG, or GIF)');
                    fileInput.val('');
                    return;
                }

                // Validate file size (10MB)
                if (file.size > 10 * 1024 * 1024) {
                    alert('File size must be less than 10MB');
                    fileInput.val('');
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
                        <small class="text-muted">Supported formats: PDF, JPEG, JPG, PNG, GIF (Max 10MB)</small>
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

            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);
        });
    </script>
@endsection

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
        .current-logo {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }

        .current-logo img {
            border: 2px solid #dee2e6;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .new-file-preview {
            border-left: 4px solid #17a2b8;
        }

        .form-control.is-invalid {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }

        .btn {
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: none;
        }

        .card-body {
            padding: 2rem;
        }

        .control-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
        }

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
@section('title')
    Edit Customer
@stop
@section('page-header')
    <!-- breadcrumb -->
    @foreach (['Error', 'Add', 'delete', 'edit'] as $msg)
        @if (session()->has($msg))
            <div class="alert alert-{{ $msg == 'Error' || $msg == 'delete' ? 'danger' : 'success' }} alert-dismissible fade show"
                role="alert">
                <strong>{{ session()->get($msg) }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
    @endforeach

    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Edit Customer</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/
                    Customer</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    @if (session()->has('Add'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('Add') }}</strong>
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

    <!-- row -->

    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <form id="yourFormId" action="{{ route('customer.update', $customer->id) }}" method="POST"
                        enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        @method('PUT')
                        {{-- 1 --}}
                        <div class="row">
                            <div class="col">
                                <label for="name" class="control-label">Name </label>
                                <input type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" id="name" name="name"
                                    title="   Please enter the Customer name  " value="{{ old('name', $customer->name) }}">
                                @if($errors->has('name'))
                                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                @endif
                            </div>
                            <div class="col">
                                <label for="abb" class="control-label">Abb </label>
                                <input type="text" class="form-control {{ $errors->has('abb') ? 'is-invalid' : '' }}" id="abb" name="abb"
                                    title="   Please enter the customer Abb  " value="{{ old('abb', $customer->abb) }}">
                                @if($errors->has('abb'))
                                    <div class="invalid-feedback">{{ $errors->first('abb') }}</div>
                                @endif
                            </div>

                            @php
                                $all = [
                                    'GOV' => 'GOV',
                                    'PRIVATE' => 'PRIVATE',
                                ];
                            @endphp

                            <div class="col">
                                <label for="tybe" class="control-label">Type</label>
                                <select class="form-control SlectBox {{ $errors->has('tybe') ? 'is-invalid' : '' }}" onclick="console.log($(this).val())"
                                    onchange="console.log('change is firing')" id="tybe" name="tybe">
                                    <option value="">Select Value</option>
                                    <option value="GOV" {{ old('tybe', $customer->tybe) == 'GOV' ? 'selected' : '' }}>GOV</option>
                                    <option value="PRIVATE" {{ old('tybe', $customer->tybe) == 'PRIVATE' ? 'selected' : '' }}>PRIVATE</option>
                                </select>
                                @if($errors->has('tybe'))
                                    <div class="invalid-feedback">{{ $errors->first('tybe') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col">
                                <div style="text-align:justify;">
                                    <div style="display: inline-flex;">
                                        <h5 class="card-title mr-3">Customer Logo</h5>
                                        <p class="text-primary">* Attachment format pdf, jpeg, .jpg, png </p>
                                    </div>
                                </div>

                                @if($customer->logo && file_exists(public_path($customer->logo)))
                                    <div class="current-logo mb-3">
                                        <label class="control-label">Current Logo:</label>
                                        <div class="mt-2">
                                            <img src="{{ asset($customer->logo) }}" alt="Current Logo"
                                                 class="img-thumbnail" style="max-width: 150px; max-height: 150px;">
                                            <br><small class="text-muted">{{ basename($customer->logo) }}</small>
                                        </div>
                                    </div>
                                @endif

                                <div class="col-sm-12 col-md-12">
                                    <div class="drag-drop-area" id="dragDropArea">
                                        <input type="file" name="logo" id="logoInput" class="d-none {{ $errors->has('logo') ? 'is-invalid' : '' }}"
                                            accept=".jpg,.jpeg,.png,.gif,.webp" />

                                        <div class="drag-drop-content">
                                            <div class="drag-drop-icon">
                                                <i class="fas fa-cloud-upload-alt fa-3x text-primary"></i>
                                            </div>
                                            <h4 class="drag-drop-title">Drag & Drop Logo Here</h4>
                                            <p class="drag-drop-subtitle">or <span class="browse-link">click to browse</span></p>
                                            <small class="text-muted">Supported formats: JPG, JPEG, PNG, GIF, WEBP (Max: 2MB)</small>
                                        </div>

                                        <div class="file-preview d-none">
                                            <div class="preview-content">
                                                <img class="preview-image" src="" alt="Preview">
                                                <div class="preview-info">
                                                    <span class="file-name"></span>
                                                    <span class="file-size"></span>
                                                    <button type="button" class="btn btn-sm btn-danger remove-file">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if($errors->has('logo'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('logo') }}</div>
                                    @endif
                                    @if(!$customer->logo)
                                        <small class="text-muted">No logo uploaded yet</small>
                                    @endif
                                </div><br>

                            </div>
                        </div>


                        <div class="row mt-1">
                            <div class="col">
                                <label for="customercontactname" class="control-label">Customer Contact Name </label>
                                <input type="text" class="form-control {{ $errors->has('customercontactname') ? 'is-invalid' : '' }}" id="customercontactname"
                                    name="customercontactname" title="   Please enter the Customer Contact Name  " value="{{ old('customercontactname', $customer->customercontactname) }}">
                                @if($errors->has('customercontactname'))
                                    <div class="invalid-feedback">{{ $errors->first('customercontactname') }}</div>
                                @endif
                            </div>

                            <div class="col">
                                <label for="customercontactposition" class="control-label">Customer Contact Position</label>
                                <input type="text" class="form-control {{ $errors->has('customercontactposition') ? 'is-invalid' : '' }}" id="customercontactposition"
                                    name="customercontactposition" title="   Please enter the Customer Contact Position  " value="{{ old('customercontactposition', $customer->customercontactposition) }}">
                                @if($errors->has('customercontactposition'))
                                    <div class="invalid-feedback">{{ $errors->first('customercontactposition') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col">
                                <label for="email" class="control-label">Email </label>
                                <input type="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" id="email" name="email"
                                    title="   Please enter the Customer Email  " value="{{ old('email', $customer->email) }}">
                                @if($errors->has('email'))
                                    <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                                @endif
                            </div>
                            <div class="col">
                                <label for="phone" class="control-label">Phone </label>
                                <input type="text" class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" id="phone" name="phone"
                                    title="   Please enter the Customer Phone  " value="{{ old('phone', $customer->phone) }}">
                                @if($errors->has('phone'))
                                    <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
                                @endif
                            </div>
                        </div>


                        <div class="d-flex justify-content-center mt-4">
                            <button type="submit" class="btn btn-primary mr-3">
                                <i class="fas fa-save"></i> Update Customer
                            </button>
                            <a href="{{ route('customer.show', $customer->id) }}" class="btn btn-info mr-3">
                                <i class="fas fa-eye"></i> View Details
                            </a>
                            <a href="{{ route('customer.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
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

    <script>
        $(document).ready(function() {
            // Drag and Drop functionality
            const dragDropArea = $('#dragDropArea');
            const fileInput = $('#logoInput');
            const filePreview = dragDropArea.find('.file-preview');
            const dragDropContent = dragDropArea.find('.drag-drop-content');


            console.log('Drag drop initialized');
            console.log('DragDropArea:', dragDropArea.length);
            console.log('FileInput:', fileInput.length);

            // Click to browse functionality - works for entire area including browse link
            dragDropArea.on('click', function(e) {
                console.log('Area clicked:', e.target);
                // Don't open file dialog only if clicking on remove button or preview image
                if ($(e.target).hasClass('remove-file') ||
                    $(e.target).closest('.remove-file').length > 0 ||
                    $(e.target).closest('.file-preview').length > 0) {
                    return;
                }
                console.log('Opening file dialog');
                fileInput[0].click();
            });

            // Drag and drop events
            dragDropArea.on('dragover dragenter', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).addClass('dragover');
            });

            dragDropArea.on('dragleave dragend', function(e) {
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
                    handleFiles(files);
                }
            });

            // File input change event
            fileInput.on('change', function() {
                if (this.files.length > 0) {
                    handleFiles(this.files);
                }
            });

            // Remove file button
            $(document).on('click', '.remove-file', function(e) {
                e.preventDefault();
                e.stopPropagation();
                removeFile();
            });

            function handleFiles(files) {
                const file = files[0];

                // Validate file type
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Please select a valid image file (JPG, JPEG, PNG, GIF, WEBP)');
                    return;
                }

                // Validate file size (2MB max)
                if (file.size > 2 * 1024 * 1024) {
                    alert('File size must be less than 2MB');
                    return;
                }

                // Set file to input
                const dt = new DataTransfer();
                dt.items.add(file);
                fileInput[0].files = dt.files;

                // Show preview
                showPreview(file);
            }

            function showPreview(file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    filePreview.find('.preview-image').attr('src', e.target.result);
                    filePreview.find('.file-name').text(file.name);
                    filePreview.find('.file-size').text(formatFileSize(file.size));

                    dragDropContent.addClass('d-none');
                    filePreview.removeClass('d-none');
                };
                reader.readAsDataURL(file);
            }

            function removeFile() {
                fileInput.val('');
                filePreview.addClass('d-none');
                dragDropContent.removeClass('d-none');
            }

            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }

            // تأكيد عند محاولة المغادرة مع وجود تغييرات غير محفوظة
            var formChanged = false;
            $('#yourFormId input, #yourFormId select').change(function() {
                formChanged = true;
            });

            $('.btn-secondary').click(function(e) {
                if (formChanged) {
                    if (!confirm('You have unsaved changes. Are you sure you want to leave?')) {
                        e.preventDefault();
                    }
                }
            });

            // تحسين validation visual feedback
            $('input, select').on('input change', function() {
                if ($(this).hasClass('is-invalid')) {
                    $(this).removeClass('is-invalid');
                    $(this).siblings('.invalid-feedback').hide();
                }
            });
        });
    </script>

    <!--Internal  Datepicker js -->
    <script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
    <!--Internal  jquery.maskedinput js -->
    <script src="{{ URL::asset('assets/plugins/jquery.maskedinput/jquery.maskedinput.js') }}"></script>
    <!--Internal  spectrum-colorpicker js -->
    <script src="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.js') }}"></script>
    <!-- Internal form-elements js -->
    <script src="{{ URL::asset('assets/js/form-elements.js') }}"></script>
@endsection

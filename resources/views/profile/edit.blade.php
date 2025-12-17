@extends('layouts.master')

@section('title')
    My Profile | MDSJEDPR
@stop

@section('css')
<!-- Cropper.js CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">

<style>
    .profile-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        margin-bottom: 20px;
        overflow: hidden;
    }

    .profile-card .card-header {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        color: white;
        padding: 20px;
        border: none;
    }

    .profile-card .card-header h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .profile-card .card-body {
        padding: 30px;
    }

    .form-group label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .form-control {
        border: 2px solid #e9ecef;
        border-radius: 6px;
        padding: 10px 15px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
    }

    .btn-save {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        border: none;
        padding: 12px 30px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
    }

    .btn-danger-custom {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        border: none;
        padding: 12px 30px;
        font-weight: 600;
        transition: all 0.3s ease;
        color: white;
    }

    .btn-danger-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
    }

    .alert-success {
        background: #d4edda;
        border: 1px solid #c3e6cb;
        color: #155724;
        padding: 12px 20px;
        border-radius: 6px;
        margin-bottom: 20px;
    }

    .text-danger {
        color: #dc3545;
        font-size: 13px;
        margin-top: 5px;
    }

    .user-info-display {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        border-left: 4px solid #007bff;
        margin-bottom: 20px;
    }

    .user-info-display .info-label {
        font-weight: 600;
        color: #6c757d;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .user-info-display .info-value {
        font-size: 16px;
        color: #212529;
        margin-top: 5px;
    }

    /* Profile Picture Styles */
    .profile-picture-section {
        text-align: center;
        margin-bottom: 30px;
    }

    .profile-picture-container {
        position: relative;
        display: inline-block;
        margin-bottom: 20px;
    }

    .profile-picture-preview {
        width: 180px;
        height: 180px;
        border-radius: 50%;
        object-fit: cover;
        border: 6px solid #007bff;
        box-shadow: 0 8px 25px rgba(0, 123, 255, 0.3);
        transition: all 0.3s ease;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .profile-picture-preview:hover {
        transform: scale(1.05);
        box-shadow: 0 10px 30px rgba(0, 123, 255, 0.5);
    }

    .profile-placeholder {
        width: 180px;
        height: 180px;
        border-radius: 50%;
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        border: 6px solid #007bff;
        box-shadow: 0 8px 25px rgba(0, 123, 255, 0.3);
    }

    .profile-placeholder i {
        font-size: 80px;
        color: white;
        opacity: 0.9;
    }

    .profile-placeholder-text {
        position: absolute;
        bottom: -30px;
        left: 50%;
        transform: translateX(-50%);
        color: #6c757d;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
    }

    .upload-overlay {
        position: absolute;
        bottom: 5px;
        right: 5px;
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }

    .upload-overlay:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 15px rgba(0, 123, 255, 0.5);
    }

    .upload-overlay i {
        color: white;
        font-size: 18px;
    }

    #profile_picture {
        display: none;
    }

    .remove-picture-btn {
        margin-top: 10px;
        padding: 8px 20px;
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .remove-picture-btn:hover {
        background: #c82333;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
    }

    .profile-picture-info {
        margin-top: 15px;
        font-size: 12px;
        color: #6c757d;
    }

    /* Cropper Modal Styles */
    .cropper-modal {
        background: rgba(0, 0, 0, 0.8) !important;
    }

    #cropperModal .modal-dialog {
        max-width: 800px;
    }

    #cropperModal .modal-body {
        padding: 30px;
    }

    .cropper-container {
        max-height: 500px;
        margin-bottom: 20px;
    }

    .cropper-controls {
        display: flex;
        gap: 10px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .cropper-controls .btn {
        padding: 8px 20px;
        font-size: 14px;
    }

    #imageToCrop {
        max-width: 100%;
        display: block;
    }
</style>
@endsection

@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">Profile</h4>
            <span class="text-muted mt-1 tx-13 ml-2 mb-0">/ My Profile</span>
        </div>
    </div>
</div>
<!-- breadcrumb -->
@endsection

@section('content')

{{-- Success Messages --}}
@if(session('status') === 'profile-updated')
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> Profile updated successfully!
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if(session('status') === 'password-updated')
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> Password updated successfully!
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<div class="row">
    <div class="col-lg-12">
        {{-- Profile Picture Card --}}
        <div class="profile-card">
            <div class="card-header">
                <h3>
                    <i class="fas fa-camera"></i>
                    Profile Picture
                </h3>
                <p class="mb-0 mt-2" style="font-size: 13px; opacity: 0.9;">
                    Upload your profile picture to personalize your account.
                </p>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" id="profilePictureForm">
                    @csrf
                    @method('PATCH')

                    <div class="profile-picture-section">
                        <div class="profile-picture-container">
                            @if($user->profile_picture)
                                <img src="{{ url('storge/' . $user->profile_picture) }}"
                                     alt="Profile Picture"
                                     class="profile-picture-preview"
                                     id="picturePreview"
                                     style="object-fit: cover;">
                            @else
                                <div class="profile-placeholder" id="picturePlaceholder">
                                    <i class="fas fa-user"></i>
                                </div>
                                <img src=""
                                     alt="Profile Picture"
                                     class="profile-picture-preview"
                                     id="picturePreview"
                                     style="display: none; object-fit: cover;">
                            @endif                            <label for="profile_picture" class="upload-overlay" title="Upload Photo">
                                <i class="fas fa-camera"></i>
                            </label>
                            <input type="file"
                                   id="profile_picture"
                                   name="profile_picture"
                                   accept="image/jpeg,image/jpg,image/png,image/gif">
                        </div>

                        <div class="profile-picture-info">
                            <i class="fas fa-info-circle"></i>
                            Allowed: JPG, PNG, GIF | Max size: 2MB | Recommended: 300x300px
                        </div>

                        @error('profile_picture')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror

                        <div class="mt-3" id="pictureActions" style="display: none;">
                            <button type="submit" class="btn btn-primary btn-save">
                                <i class="fas fa-upload"></i> Upload Picture
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="cancelUpload()">
                                <i class="fas fa-times"></i> Cancel
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Profile Information Card --}}
        <div class="profile-card">
            <div class="card-header">
                <h3>
                    <i class="fas fa-user-circle"></i>
                    Profile Information
                </h3>
                <p class="mb-0 mt-2" style="font-size: 13px; opacity: 0.9;">
                    Update your account's profile information and email address.
                </p>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PATCH')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">
                                    <i class="fas fa-user"></i> Full Name
                                </label>
                                <input type="text"
                                       class="form-control @error('name') is-invalid @enderror"
                                       id="name"
                                       name="name"
                                       value="{{ old('name', $user->name) }}"
                                       required
                                       autofocus>
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">
                                    <i class="fas fa-envelope"></i> Email Address
                                </label>
                                <input type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       id="email"
                                       name="email"
                                       value="{{ old('email', $user->email) }}"
                                       required>
                                @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            Your email address is unverified.
                            <form method="POST" action="{{ route('verification.send') }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-link p-0" style="text-decoration: underline;">
                                    Click here to re-send the verification email.
                                </button>
                            </form>
                        </div>
                    @endif

                    <div class="text-right mt-4">
                        <button type="submit" class="btn btn-primary btn-save">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Update Password Card --}}
        <div class="profile-card">
            <div class="card-header">
                <h3>
                    <i class="fas fa-lock"></i>
                    Update Password
                </h3>
                <p class="mb-0 mt-2" style="font-size: 13px; opacity: 0.9;">
                    Ensure your account is using a long, random password to stay secure.
                </p>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="current_password">
                                    <i class="fas fa-key"></i> Current Password
                                </label>
                                <input type="password"
                                       class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                                       id="current_password"
                                       name="current_password"
                                       autocomplete="current-password">
                                @error('current_password', 'updatePassword')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">
                                    <i class="fas fa-lock"></i> New Password
                                </label>
                                <input type="password"
                                       class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                                       id="password"
                                       name="password"
                                       autocomplete="new-password">
                                @error('password', 'updatePassword')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password_confirmation">
                                    <i class="fas fa-lock"></i> Confirm Password
                                </label>
                                <input type="password"
                                       class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror"
                                       id="password_confirmation"
                                       name="password_confirmation"
                                       autocomplete="new-password">
                                @error('password_confirmation', 'updatePassword')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="text-right mt-4">
                        <button type="submit" class="btn btn-primary btn-save">
                            <i class="fas fa-save"></i> Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Delete Account Card --}}
        <div class="profile-card">
            <div class="card-header" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);">
                <h3>
                    <i class="fas fa-trash-alt"></i>
                    Delete Account
                </h3>
                <p class="mb-0 mt-2" style="font-size: 13px; opacity: 0.9;">
                    Once your account is deleted, all of its resources and data will be permanently deleted.
                </p>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">
                    Before deleting your account, please download any data or information that you wish to retain.
                </p>

                <button type="button" class="btn btn-danger-custom" data-toggle="modal" data-target="#deleteAccountModal">
                    <i class="fas fa-trash-alt"></i> Delete Account
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Image Cropper Modal --}}
<div class="modal fade" id="cropperModal" tabindex="-1" role="dialog" aria-labelledby="cropperModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); color: white;">
                <h5 class="modal-title" id="cropperModalLabel">
                    <i class="fas fa-crop"></i> Crop & Edit Your Profile Picture
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="cropper-container">
                    <img id="imageToCrop" src="" alt="Image to crop">
                </div>

                <div class="cropper-controls">
                    <button type="button" class="btn btn-secondary" onclick="cropper.rotate(-45)">
                        <i class="fas fa-undo"></i> Rotate Left
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="cropper.rotate(45)">
                        <i class="fas fa-redo"></i> Rotate Right
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="cropper.scaleX(-cropper.getData().scaleX || -1)">
                        <i class="fas fa-arrows-alt-h"></i> Flip H
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="cropper.scaleY(-cropper.getData().scaleY || -1)">
                        <i class="fas fa-arrows-alt-v"></i> Flip V
                    </button>
                    <button type="button" class="btn btn-info" onclick="cropper.reset()">
                        <i class="fas fa-sync"></i> Reset
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button type="button" class="btn btn-primary" onclick="cropAndUpload()">
                    <i class="fas fa-check"></i> Crop & Upload
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Delete Account Modal --}}
<div class="modal fade" id="deleteAccountModal" tabindex="-1" role="dialog" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteAccountModalLabel">
                    <i class="fas fa-exclamation-triangle"></i> Confirm Account Deletion
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf
                @method('DELETE')

                <div class="modal-body">
                    <p class="font-weight-bold">Are you sure you want to delete your account?</p>
                    <p class="text-muted">
                        Once your account is deleted, all of its resources and data will be permanently deleted.
                        Please enter your password to confirm you would like to permanently delete your account.
                    </p>

                    <div class="form-group">
                        <label for="delete_password">
                            <i class="fas fa-key"></i> Password
                        </label>
                        <input type="password"
                               class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                               id="delete_password"
                               name="password"
                               placeholder="Enter your password"
                               required>
                        @error('password', 'userDeletion')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash-alt"></i> Delete Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('js')
<!-- Cropper.js JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<script>
    let cropper;
    let selectedFile;

    $(document).ready(function() {
        // Auto-hide success messages after 5 seconds
        setTimeout(function() {
            $('.alert-success').fadeOut('slow');
        }, 5000);

        // Show modal if there are validation errors
        @if($errors->userDeletion->isNotEmpty())
            $('#deleteAccountModal').modal('show');
        @endif

        // Profile Picture Preview with Cropper
        $('#profile_picture').on('change', function(e) {
            const file = e.target.files[0];

            if (file) {
                // Validate file size (2MB max)
                if (file.size > 2 * 1024 * 1024) {
                    alert('File size must be less than 2MB');
                    $(this).val('');
                    return;
                }

                // Validate file type
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!validTypes.includes(file.type)) {
                    alert('Please select a valid image file (JPG, PNG, or GIF)');
                    $(this).val('');
                    return;
                }

                selectedFile = file;

                // Show cropper modal
                const reader = new FileReader();
                reader.onload = function(event) {
                    $('#imageToCrop').attr('src', event.target.result);
                    $('#cropperModal').modal('show');
                };
                reader.readAsDataURL(file);
            }
        });

        // Initialize Cropper when modal is shown
        $('#cropperModal').on('shown.bs.modal', function () {
            const image = document.getElementById('imageToCrop');

            if (cropper) {
                cropper.destroy();
            }

            cropper = new Cropper(image, {
                aspectRatio: 1, // Square crop
                viewMode: 2,
                dragMode: 'move',
                autoCropArea: 1,
                restore: false,
                guides: true,
                center: true,
                highlight: false,
                cropBoxMovable: true,
                cropBoxResizable: true,
                toggleDragModeOnDblclick: false,
            });
        });

        // Destroy cropper when modal is hidden
        $('#cropperModal').on('hidden.bs.modal', function () {
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
            $('#profile_picture').val('');
        });
    });

    // Crop and upload function
    function cropAndUpload() {
        if (!cropper) {
            return;
        }

        // Get cropped canvas
        const canvas = cropper.getCroppedCanvas({
            width: 300,
            height: 300,
            imageSmoothingQuality: 'high'
        });

        // Convert to blob
        canvas.toBlob(function(blob) {
            // Create form data
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('_method', 'PATCH');
            formData.append('profile_picture', blob, 'profile.jpg');
            formData.append('name', '{{ $user->name }}');
            formData.append('email', '{{ $user->email }}');

            // Show loading
            $('#cropperModal').modal('hide');

            // Upload via AJAX
            $.ajax({
                url: '{{ route("profile.update") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    // Update preview
                    canvas.toBlob(function(previewBlob) {
                        const previewUrl = URL.createObjectURL(previewBlob);
                        $('#picturePlaceholder').hide();
                        $('#picturePreview').attr('src', previewUrl).show();

                        // Show success message
                        alert('✅ Profile picture updated successfully! Refreshing page...');

                        // Reload page to update header
                        location.reload();
                    });
                },
                error: function(xhr) {
                    alert('❌ Error uploading profile picture. Please try again.');
                    console.error(xhr.responseText);
                }
            });
        }, 'image/jpeg', 0.9);
    }

    // Cancel upload function
    function cancelUpload() {
        $('#profile_picture').val('');

        @if($user->profile_picture)
            // If user has profile picture, restore it
            $('#picturePreview').attr('src', '{{ url("storge/" . $user->profile_picture) }}').show();
            $('#picturePlaceholder').hide();
        @else
            // If no profile picture, show placeholder
            $('#picturePreview').hide();
            $('#picturePlaceholder').show();
        @endif

        $('#pictureActions').fadeOut();
    }
</script>
@endsection

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

    <!-- Lightbox CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">

    <style>
        .customer-header {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e9ecef;
            margin-bottom: 20px;
        }

        .customer-logo {
            width: 120px;
            height: 120px;
            border-radius: 8px;
            border: 2px solid #dee2e6;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .no-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 120px;
            height: 120px;
            border-radius: 8px;
            border: 2px dashed #dee2e6;
            background-color: #f8f9fa;
            color: #6c757d;
            font-size: 14px;
            text-align: center;
        }

        .info-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border: 1px solid #e9ecef;
        }

        .info-item {
            display: flex;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #f5f5f5;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-icon {
            width: 40px;
            height: 40px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 16px;
            color: white;
            background-color: #007bff;
        }

        .info-content {
            flex: 1;
        }

        .info-label {
            font-size: 12px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .info-value {
            font-size: 16px;
            color: #495057;
            font-weight: 500;
        }

        .empty-value {
            color: #ccc;
            font-style: italic;
        }

        .section-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 15px;
        }

        .type-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .type-gov {
            background-color: #007bff;
            color: white;
        }

        .type-private {
            background-color: #ffc107;
            color: #212529;
        }

        .type-default {
            background-color: #6c757d;
            color: white;
        }

        .customer-name {
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0;
            color: #495057;
        }

        .customer-type {
            margin-top: 10px;
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

        @media (max-width: 768px) {
            .customer-header {
                text-align: center;
                padding: 15px;
            }

            .customer-name {
                font-size: 1.5rem;
            }

            .info-card {
                padding: 15px;
            }
        }
    </style>
@endsection

@section('title')
    Customer Details - {{ $customer->name }}
@stop

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Customers</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ Details</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <div class="pr-1 mb-3 mb-xl-0">
                <a href="{{ route('customer.edit', $customer->id) }}" class="btn btn-info btn-icon ml-2">
                    <i class="mdi mdi-pencil"></i>
                </a>
            </div>
            <div class="pr-1 mb-3 mb-xl-0">
                <a href="{{ route('customer.index') }}" class="btn btn-warning btn-icon ml-2">
                    <i class="mdi mdi-arrow-left"></i>
                </a>
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

    <!-- row -->
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <!-- Customer Header -->
                    <div class="customer-header">
                        <div class="row align-items-center">
                            <div class="col-md-2 text-center mb-3 mb-md-0">
                                @if($customer->logo && file_exists(public_path($customer->logo)))
                                    <a href="{{ asset($customer->logo) }}" data-lightbox="customer-logo" data-title="{{ $customer->name }} Logo">
                                        <img src="{{ asset($customer->logo) }}" alt="{{ $customer->name }} Logo" class="customer-logo">
                                    </a>
                                @else
                                    <div class="no-logo">
                                        <div>
                                            <i class="fas fa-image fa-2x mb-2"></i>
                                            <div>No Logo</div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-10">
                                <h1 class="customer-name">{{ $customer->name }}</h1>
                                <div class="customer-type">
                                    @if($customer->tybe)
                                        <span class="type-badge {{ $customer->tybe == 'GOV' ? 'type-gov' : 'type-private' }}">
                                            {{ $customer->tybe == 'GOV' ? 'Government' : 'Private' }}
                                        </span>
                                    @else
                                        <span class="type-badge type-default">Not Specified</span>
                                    @endif
                                </div>
                                @if($customer->abb)
                                    <div class="mt-2">
                                        <span class="badge badge-light" style="font-size: 1rem; padding: 8px 12px;">{{ $customer->abb }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Customer Information -->
                        <div class="col-lg-8">
                            <!-- Basic Information -->
                            <div class="info-card">
                                <h3 class="section-title">
                                    <i class="fas fa-info-circle mr-2"></i>Basic Information
                                </h3>

                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="fas fa-sort-numeric-up"></i>
                                    </div>
                                    <div class="info-content">
                                        <div class="info-label">Order Number</div>
                                        <div class="info-value">#{{ $loop_index ?? 1 }}</div>
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="fas fa-building"></i>
                                    </div>
                                    <div class="info-content">
                                        <div class="info-label">Customer Name</div>
                                        <div class="info-value">{{ $customer->name }}</div>
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="fas fa-tag"></i>
                                    </div>
                                    <div class="info-content">
                                        <div class="info-label">Abbreviation</div>
                                        <div class="info-value {{ !$customer->abb ? 'empty-value' : '' }}">
                                            {{ $customer->abb ?? 'Not provided' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="fas fa-sitemap"></i>
                                    </div>
                                    <div class="info-content">
                                        <div class="info-label">Organization Type</div>
                                        <div class="info-value {{ !$customer->tybe ? 'empty-value' : '' }}">
                                            @if($customer->tybe == 'GOV')
                                                Government
                                            @elseif($customer->tybe == 'PRIVATE')
                                                Private
                                            @else
                                                Not specified
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div class="info-card">
                                <h3 class="section-title">
                                    <i class="fas fa-address-book mr-2"></i>Contact Information
                                </h3>

                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="info-content">
                                        <div class="info-label">Contact Person</div>
                                        <div class="info-value {{ !$customer->customercontactname ? 'empty-value' : '' }}">
                                            {{ $customer->customercontactname ?? 'Not provided' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="fas fa-briefcase"></i>
                                    </div>
                                    <div class="info-content">
                                        <div class="info-label">Contact Position</div>
                                        <div class="info-value {{ !$customer->customercontactposition ? 'empty-value' : '' }}">
                                            {{ $customer->customercontactposition ?? 'Not provided' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div class="info-content">
                                        <div class="info-label">Email Address</div>
                                        <div class="info-value {{ !$customer->email ? 'empty-value' : '' }}">
                                            @if($customer->email)
                                                <a href="mailto:{{ $customer->email }}" class="text-decoration-none">{{ $customer->email }}</a>
                                            @else
                                                Not provided
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                    <div class="info-content">
                                        <div class="info-label">Phone Number</div>
                                        <div class="info-value {{ !$customer->phone ? 'empty-value' : '' }}">
                                            @if($customer->phone)
                                                <a href="tel:{{ $customer->phone }}" class="text-decoration-none">{{ $customer->phone }}</a>
                                            @else
                                                Not provided
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons & System Info -->
                        <div class="col-lg-4">
                            <!-- Action Buttons -->
                            <div class="info-card">
                                <h3 class="section-title">
                                    <i class="fas fa-cogs mr-2"></i>Actions
                                </h3>

                                <div class="d-flex flex-column">
                                    <a href="{{ route('customer.edit', $customer->id) }}" class="btn btn-primary mb-2">
                                        <i class="fas fa-edit mr-2"></i>Edit Customer
                                    </a>

                                    <a href="{{ route('customer.index') }}" class="btn btn-secondary mb-2">
                                        <i class="fas fa-arrow-left mr-2"></i>Back to List
                                    </a>

                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal">
                                        <i class="fas fa-trash mr-2"></i>Delete Customer
                                    </button>
                                </div>
                            </div>

                            <!-- System Information -->
                            <div class="info-card">
                                <h3 class="section-title">
                                    <i class="fas fa-info mr-2"></i>System Info
                                </h3>

                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="fas fa-calendar-plus"></i>
                                    </div>
                                    <div class="info-content">
                                        <div class="info-label">Created Date</div>
                                        <div class="info-value">{{ $customer->created_at ? $customer->created_at->format('M d, Y') : 'N/A' }}</div>
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="fas fa-calendar-edit"></i>
                                    </div>
                                    <div class="info-content">
                                        <div class="info-label">Last Updated</div>
                                        <div class="info-value">{{ $customer->updated_at ? $customer->updated_at->format('M d, Y') : 'N/A' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel">
                        <i class="fas fa-exclamation-triangle mr-2"></i>Confirm Deletion
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                        <h5>Are you sure you want to delete this customer?</h5>
                        <p class="text-muted">
                            This action cannot be undone. Customer "<strong>{{ $customer->name }}</strong>" and all associated data will be permanently removed.
                        </p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </button>
                    <form action="{{ route('customer.destroy', $customer->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash mr-2"></i>Delete Customer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
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

    <!-- Lightbox JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>

    <script>
        $(document).ready(function() {
            // Configure Lightbox
            lightbox.option({
                'resizeDuration': 200,
                'wrapAround': true,
                'albumLabel': "Image %1 of %2"
            });

            // Add loading state to buttons
            $('.btn').on('click', function() {
                if (!$(this).hasClass('btn-danger') && !$(this).hasClass('close')) {
                    $(this).prop('disabled', true);
                    const originalText = $(this).html();
                    $(this).html('<i class="fas fa-spinner fa-spin mr-2"></i>Loading...');

                    setTimeout(() => {
                        $(this).prop('disabled', false);
                        $(this).html(originalText);
                    }, 2000);
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

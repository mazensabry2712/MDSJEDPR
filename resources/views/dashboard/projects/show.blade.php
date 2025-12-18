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
        .project-header {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e9ecef;
            margin-bottom: 20px;
        }

        .img-thumbnail {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px;
            transition: 0.3s;
        }
        .img-thumbnail:hover {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
        }
        .project-attachment {
            width: 120px;
            height: 120px;
            object-fit: cover;
        }

        .no-attachment {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 120px;
            height: 120px;
            border: 1px dashed #ccc;
            border-radius: 4px;
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
        }

        .info-icon.icon-primary { background-color: #007bff; }
        .info-icon.icon-success { background-color: #28a745; }
        .info-icon.icon-info { background-color: #17a2b8; }
        .info-icon.icon-warning { background-color: #ffc107; color: #212529; }
        .info-icon.icon-danger { background-color: #dc3545; }
        .info-icon.icon-secondary { background-color: #6c757d; }

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

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-active {
            background-color: #28a745;
            color: white;
        }

        .status-pending {
            background-color: #ffc107;
            color: #212529;
        }

        .status-completed {
            background-color: #007bff;
            color: white;
        }

        .status-default {
            background-color: #6c757d;
            color: white;
        }

        .project-name {
            font-size: 1.8rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .value-badge {
            background-color: #28a745;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: 600;
        }

        .export-buttons {
            margin-bottom: 20px;
        }

        .export-buttons .btn {
            margin: 0 2px;
            transition: all 0.3s ease;
        }

        .export-buttons .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .sidebar-section {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border: 1px solid #e9ecef;
        }

        .attachment-section img {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        .timeline-item {
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .timeline-item:last-child {
            border-bottom: none;
        }

        .timeline-date {
            font-weight: 600;
            color: #007bff;
        }

        .timeline-content {
            color: #6c757d;
            margin-top: 5px;
        }
    </style>
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Projects</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ Project Details</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <div class="pr-1 mb-3 mb-xl-0">
                <button type="button" class="btn btn-warning btn-icon ml-2" onclick="window.print();">
                    <i class="mdi mdi-printer"></i>
                </button>
            </div>
            <div class="pr-1 mb-3 mb-xl-0">
                <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-primary btn-icon ml-2">
                    <i class="mdi mdi-pencil"></i>
                </a>
            </div>
            <div class="pr-1 mb-3 mb-xl-0">
                <a href="{{ route('projects.index') }}" class="btn btn-secondary btn-icon ml-2">
                    <i class="mdi mdi-arrow-left"></i>
                </a>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    <!-- Export Buttons -->
    <div class="export-buttons text-right mb-3">
        <div class="btn-group">
            <button type="button" class="btn btn-success btn-sm" id="export-pdf">
                <i class="fas fa-file-pdf mr-1"></i> PDF
            </button>
            <button type="button" class="btn btn-primary btn-sm" id="export-excel">
                <i class="fas fa-file-excel mr-1"></i> Excel
            </button>
            {{-- <button type="button" class="btn btn-info btn-sm" id="export-csv">
                <i class="fas fa-file-csv mr-1"></i> CSV
            </button> --}}
            <button type="button" class="btn btn-warning btn-sm" id="export-print">
                <i class="fas fa-print mr-1"></i> Print
            </button>
            <button type="button" class="btn btn-secondary btn-sm" id="export-share">
                <i class="fas fa-share mr-1"></i> Share
            </button>
        </div>
    </div>

    @if(session()->has('Add'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('Add') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session()->has('edit'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('edit') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session()->has('delete'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('delete') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Main Content Row -->
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <!-- Project Header -->
                    <div class="project-header">
                        <div class="row align-items-center">
                            <div class="col-md-2 text-center mb-3 mb-md-0">
                                <!-- PO Attachment -->
                                @if($project->po_attachment && file_exists(public_path($project->po_attachment)))
                                    @php
                                        $fileExtension = strtolower(pathinfo($project->po_attachment, PATHINFO_EXTENSION));
                                        $isImage = in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                    @endphp
                                    @if($isImage)
                                        <a href="{{ asset($project->po_attachment) }}" data-lightbox="gallery-{{ $project->id }}"
                                           data-title="PO Attachment - {{ $project->name }}" title="Click to view full size">
                                            <img src="{{ asset($project->po_attachment) }}"
                                                 class="img-thumbnail project-attachment"
                                                 alt="PO Attachment"
                                                 title="PO Attachment - Click to enlarge">
                                        </a>
                                        <small class="d-block text-primary mt-1">PO File</small>
                                    @else
                                        <div class="no-attachment">
                                            <a href="{{ asset($project->po_attachment) }}" target="_blank" title="Download PO File">
                                                <i class="fas fa-file-alt text-primary" style="font-size: 40px;"></i>
                                                <small class="text-primary d-block mt-2">PO File</small>
                                            </a>
                                        </div>
                                    @endif
                                @else
                                    <div class="no-attachment">
                                        <i class="fas fa-file-alt text-muted" style="font-size: 40px;"></i>
                                        <small class="text-muted d-block mt-2">No PO</small>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-2 text-center mb-3 mb-md-0">
                                <!-- EPO Attachment -->
                                @if($project->epo_attachment && file_exists(public_path($project->epo_attachment)))
                                    @php
                                        $fileExtension = strtolower(pathinfo($project->epo_attachment, PATHINFO_EXTENSION));
                                        $isImage = in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                    @endphp
                                    @if($isImage)
                                        <a href="{{ asset($project->epo_attachment) }}" data-lightbox="gallery-{{ $project->id }}"
                                           data-title="EPO Attachment - {{ $project->name }}" title="Click to view full size">
                                            <img src="{{ asset($project->epo_attachment) }}"
                                                 class="img-thumbnail project-attachment"
                                                 alt="EPO Attachment"
                                                 title="EPO Attachment - Click to enlarge">
                                        </a>
                                        <small class="d-block text-success mt-1">EPO File</small>
                                    @else
                                        <div class="no-attachment">
                                            <a href="{{ asset($project->epo_attachment) }}" target="_blank" title="Download EPO File">
                                                <i class="fas fa-file-alt text-success" style="font-size: 40px;"></i>
                                                <small class="text-success d-block mt-2">EPO File</small>
                                            </a>
                                        </div>
                                    @endif
                                @else
                                    <div class="no-attachment">
                                        <i class="fas fa-file-alt text-muted" style="font-size: 40px;"></i>
                                        <small class="text-muted d-block mt-2">No EPO</small>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-8">
                                <h1 class="project-name">{{ $project->name }}</h1>
                                <div class="project-number">
                                    @if($project->pr_number)
                                        <span class="badge badge-light" style="font-size: 1rem; padding: 8px 12px;">{{ $project->pr_number }}</span>
                                    @else
                                        <span class="badge badge-secondary" style="font-size: 1rem; padding: 8px 12px;">No Project Number</span>
                                    @endif
                                </div>
                                @if($project->technologies)
                                    <div class="mt-2">
                                        <span class="badge badge-info" style="font-size: 0.9rem; padding: 6px 10px;">{{ $project->technologies }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Project Information -->
                        <div class="col-lg-8">
                            <!-- Basic Information -->
                            <div class="info-card">
                                <h3 class="section-title">
                                    <i class="fas fa-info-circle mr-2"></i>Basic Information
                                </h3>

                                <div class="info-item">
                                    <div class="info-icon icon-primary">
                                        <i class="fas fa-hashtag"></i>
                                    </div>
                                    <div class="info-content">
                                        <div class="info-label">Project Number</div>
                                        <div class="info-value">{{ $project->pr_number ?? 'Not assigned' }}</div>
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="info-icon icon-info">
                                        <i class="fas fa-project-diagram"></i>
                                    </div>
                                    <div class="info-content">
                                        <div class="info-label">Project Name</div>
                                        <div class="info-value">{{ $project->name }}</div>
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="info-icon icon-success">
                                        <i class="fas fa-code"></i>
                                    </div>
                                    <div class="info-content">
                                        <div class="info-label">Technologies</div>
                                        <div class="info-value {{ !$project->technologies ? 'empty-value' : '' }}">
                                            {{ $project->technologies ?? 'Not specified' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="info-icon icon-warning">
                                        <i class="fas fa-file-contract"></i>
                                    </div>
                                    <div class="info-content">
                                        <div class="info-label">Customer PO</div>
                                        <div class="info-value {{ !$project->customer_po ? 'empty-value' : '' }}">
                                            {{ $project->customer_po ?? 'Not provided' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="info-icon icon-success">
                                        <i class="fas fa-dollar-sign"></i>
                                    </div>
                                    <div class="info-content">
                                        <div class="info-label">Project Value</div>
                                        <div class="info-value">
                                            @if($project->value)
                                                <span class="value-badge">${{ number_format($project->value, 2) }}</span>
                                            @else
                                                <span class="empty-value">Not specified</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                @if($project->description)
                                <div class="info-item">
                                    <div class="info-icon icon-secondary">
                                        <i class="fas fa-align-left"></i>
                                    </div>
                                    <div class="info-content">
                                        <div class="info-label">Description</div>
                                        <div class="info-value">{{ $project->description }}</div>
                                    </div>
                                </div>
                                @endif
                            </div>

                            <!-- Team & Relationships -->
                            <div class="info-card">
                                <h3 class="section-title">
                                    <i class="fas fa-users mr-2"></i>Team & Relationships
                                </h3>

                                <!-- All Customers -->
                                @if($project->customers && $project->customers->count() > 0)
                                <div class="info-item">
                                    <div class="info-icon icon-info">
                                        <i class="fas fa-building-user"></i>
                                    </div>
                                    <div class="info-content">
                                        <div class="info-label">All Customers</div>
                                        <div class="info-value">
                                            @foreach($project->customers as $customer)
                                                <span class="badge badge-info mr-1">
                                                    {{ $customer->name }}
                                                    @if($customer->pivot->project_role)
                                                        <small class="d-block">{{ $customer->pivot->project_role }}</small>
                                                    @endif
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- All Vendors -->
                                @if($project->vendors && $project->vendors->count() > 0)
                                <div class="info-item">
                                    <div class="info-icon icon-secondary">
                                        <i class="fas fa-truck-loading"></i>
                                    </div>
                                    <div class="info-content">
                                        <div class="info-label">All Vendors</div>
                                        <div class="info-value">
                                            @foreach($project->vendors as $vendor)
                                                <span class="badge badge-info mr-1">
                                                    {{ $vendor->vendors }}
                                                    @if($vendor->pivot->service_type)
                                                        <small class="d-block">{{ $vendor->pivot->service_type }}</small>
                                                    @endif
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <div class="info-item">
                                    <div class="info-icon icon-info">
                                        <i class="fas fa-user-tie"></i>
                                    </div>
                                    <div class="info-content">
                                        <div class="info-label">Account Manager</div>
                                        <div class="info-value {{ !$project->aams ? 'empty-value' : '' }}">
                                            {{ $project->aams->name ?? 'Not assigned' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="info-icon icon-primary">
                                        <i class="fas fa-user-cog"></i>
                                    </div>
                                    <div class="info-content">
                                        <div class="info-label">Project Manager</div>
                                        <div class="info-value {{ !$project->ppms ? 'empty-value' : '' }}">
                                            {{ $project->ppms->name ?? 'Not assigned' }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Lead Distributor/Supplier -->
                                <div class="info-item">
                                    <div class="info-icon icon-danger">
                                        <i class="fas fa-chart-line"></i>
                                    </div>
                                    <div class="info-content">
                                        <div class="info-label">Lead Distributor/Supplier</div>
                                        <div class="info-value {{ !$project->ds ? 'empty-value' : '' }}">
                                            {{ $project->ds->name ?? 'Not assigned' }}
                                        </div>
                                    </div>
                                </div>

                                <!-- All Disti/Supplier-->
                                @if($project->deliverySpecialists && $project->deliverySpecialists->count() > 0)
                                <div class="info-item">
                                    <div class="info-icon icon-success">
                                        <i class="fas fa-users-cog"></i>
                                    </div>
                                    <div class="info-content">
                                        <div class="info-label">All Disti/Supplier</div>
                                        <div class="info-value">
                                            @foreach($project->deliverySpecialists as $ds)
                                                <span class="badge badge-info mr-1">
                                                    {{ $ds->name }}
                                                    @if($ds->pivot->specialization)
                                                        <small class="d-block">{{ $ds->pivot->specialization }}</small>
                                                    @endif
                                                    @if($ds->pivot->allocation_percentage)
                                                        <small class="d-block">{{ $ds->pivot->allocation_percentage }}% allocated</small>
                                                    @endif
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Sidebar -->
                        <div class="col-lg-4">
                            <!-- Timeline -->
                            <div class="sidebar-section">
                                <h4 class="section-title">
                                    <i class="fas fa-calendar-alt mr-2"></i>Timeline
                                </h4>

                                @if($project->customer_po_date)
                                <div class="timeline-item">
                                    <div class="timeline-date">{{ \Carbon\Carbon::parse($project->customer_po_date)->format('M d, Y') }}</div>
                                    <div class="timeline-content">Customer PO Date</div>
                                </div>
                                @endif

                                @if($project->customer_po_deadline)
                                <div class="timeline-item">
                                    <div class="timeline-date">{{ \Carbon\Carbon::parse($project->customer_po_deadline)->format('M d, Y') }}</div>
                                    <div class="timeline-content">Project Deadline</div>
                                </div>
                                @endif

                                @if($project->customer_po_duration)
                                <div class="timeline-item">
                                    <div class="timeline-date">{{ $project->customer_po_duration }} days</div>
                                    <div class="timeline-content">Project Duration</div>
                                </div>
                                @endif

                                <div class="timeline-item">
                                    <div class="timeline-date">{{ \Carbon\Carbon::parse($project->created_at)->format('M d, Y') }}</div>
                                    <div class="timeline-content">Project Created</div>
                                </div>
                            </div>

                            <!-- Contact Information -->
                            @if($project->customer_contact_details)
                            <div class="sidebar-section">
                                <h4 class="section-title">
                                    <i class="fas fa-address-book mr-2"></i>Contact Details
                                </h4>
                                <p class="mb-0">{{ $project->customer_contact_details }}</p>
                            </div>
                            @endif

                            <!-- Attachments -->
                            <div class="sidebar-section">
                                <h4 class="section-title">
                                    <i class="fas fa-paperclip mr-2"></i>Attachments
                                </h4>

                                @if($project->po_attachment)
                                <div class="attachment-section mb-3">
                                    <strong>PO Attachment:</strong>
                                    @if(file_exists(public_path($project->po_attachment)))
                                        @php
                                            $fileExtension = strtolower(pathinfo($project->po_attachment, PATHINFO_EXTENSION));
                                            $isImage = in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                        @endphp
                                        @if($isImage)
                                            <div class="mt-2">
                                                <a href="{{ asset($project->po_attachment) }}" data-lightbox="gallery-{{ $project->id }}" data-title="PO Attachment">
                                                    <img src="{{ asset($project->po_attachment) }}" class="img-thumbnail" style="max-width: 200px;">
                                                </a>
                                            </div>
                                        @else
                                            <div class="mt-2">
                                                <a href="{{ asset($project->po_attachment) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-download mr-1"></i>Download PO File
                                                </a>
                                            </div>
                                        @endif
                                    @else
                                        <div class="text-muted mt-2">File not found</div>
                                    @endif
                                </div>
                                @endif

                                @if($project->epo_attachment)
                                <div class="attachment-section">
                                    <strong>EPO Attachment:</strong>
                                    @if(file_exists(public_path($project->epo_attachment)))
                                        @php
                                            $fileExtension = strtolower(pathinfo($project->epo_attachment, PATHINFO_EXTENSION));
                                            $isImage = in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                        @endphp
                                        @if($isImage)
                                            <div class="mt-2">
                                                <a href="{{ asset($project->epo_attachment) }}" data-lightbox="gallery-{{ $project->id }}" data-title="EPO Attachment">
                                                    <img src="{{ asset($project->epo_attachment) }}" class="img-thumbnail" style="max-width: 200px;">
                                                </a>
                                            </div>
                                        @else
                                            <div class="mt-2">
                                                <a href="{{ asset($project->epo_attachment) }}" target="_blank" class="btn btn-sm btn-outline-success">
                                                    <i class="fas fa-download mr-1"></i>Download EPO File
                                                </a>
                                            </div>
                                        @endif
                                    @else
                                        <div class="text-muted mt-2">File not found</div>
                                    @endif
                                </div>
                                @endif

                                @if(!$project->po_attachment && !$project->epo_attachment)
                                <p class="text-muted">No attachments available</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <!--Internal  Select2 js -->
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <!--Internal  Parsley.min js -->
    <script src="{{ URL::asset('assets/plugins/parsleyjs/parsley.min.js') }}"></script>
    <!-- Internal Form-validation js -->
    <script src="{{ URL::asset('assets/js/form-validation.js') }}"></script>
    <!--Internal  Datepicker js -->
    <script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
    <!--Internal  jquery.maskedinput js -->
    <script src="{{ URL::asset('assets/plugins/jquery.maskedinput/jquery.maskedinput.js') }}"></script>
    <!--Internal  spectrum-colorpicker js -->
    <script src="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.js') }}"></script>
    <!-- Internal form-elements js -->
    <script src="{{ URL::asset('assets/js/form-elements.js') }}"></script>

    <!-- Lightbox JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>

    <script>
        $(document).ready(function() {
            // Lightbox configuration
            lightbox.option({
                'resizeDuration': 200,
                'wrapAround': true,
                'albumLabel': 'Image %1 of %2'
            });

            // Export functionality
            $('#export-pdf').click(function() {
                window.print();
            });

            $('#export-excel').click(function() {
                // Excel export logic would go here
                alert('Excel export functionality to be implemented');
            });

            // $('#export-csv').click(function() {
            //     // CSV export logic would go here
            //     alert('CSV export functionality to be implemented');
            // });

            $('#export-print').click(function() {
                window.print();
            });

            $('#export-share').click(function() {
                // Share functionality would go here
                if (navigator.share) {
                    navigator.share({
                        title: 'Project: {{ $project->name }}',
                        url: window.location.href
                    });
                } else {
                    // Fallback: copy to clipboard
                    navigator.clipboard.writeText(window.location.href).then(function() {
                        alert('Link copied to clipboard!');
                    });
                }
            });
        });
    </script>
@endsection

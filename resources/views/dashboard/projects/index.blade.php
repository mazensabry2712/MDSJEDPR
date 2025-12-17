@extends('layouts.master')
@section('css')
    <!-- Internal Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

    <!-- Lightbox CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">

    <style>
        .img-thumbnail {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px;
            transition: 0.3s;
        }

        .img-thumbnail:hover {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
        }

        .no-image {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 50px;
            width: 50px;
            border: 1px dashed #ccc;
            border-radius: 4px;
        }

        .attachment-preview {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
        }

        .attachment-icon {
            font-size: 24px;
            color: #007bff;
        }

        .attachment-name {
            font-size: 10px;
            text-align: center;
            word-break: break-all;
            max-width: 80px;
        }

        /* Hide default DataTables buttons */
        .dt-buttons {
            display: none !important;
        }

        /* Export buttons styling */
        .export-buttons .btn {
            transition: all 0.3s ease;
            margin: 0 2px;
        }

        .export-buttons .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-group .btn {
            border-radius: 0;
        }

        .btn-group .btn:first-child {
            border-top-left-radius: 0.25rem;
            border-bottom-left-radius: 0.25rem;
        }

        .btn-group .btn:last-child {
            border-top-right-radius: 0.25rem;
            border-bottom-right-radius: 0.25rem;
        }

        @media (max-width: 768px) {
            .card-header .d-flex {
                flex-direction: column;
                align-items: flex-start !important;
            }

            .btn-group {
                margin-bottom: 10px;
                margin-right: 0 !important;
            }
        }

        /* Loading animation */
        .btn-loading {
            pointer-events: none;
            opacity: 0.6;
        }

        .btn-loading .fas {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Force table to stay in single row */
        #example1 {
            white-space: nowrap;
        }

        /* تحسين شكل عرض Description نفس استايل vendor AM details */
        .project-description {
            max-width: 300px !important;
            min-width: 200px;
            white-space: normal !important;
        }

        .project-description .text-wrap {
            background-color: #f8f9fa;
            padding: 8px 12px;
            border-radius: 6px;
            border-left: 3px solid #28a745;
            font-size: 13px;
            line-height: 1.6;
            word-wrap: break-word;
            overflow-wrap: break-word;
            max-height: 120px;
            overflow-y: auto;
        }

        /* تحسين العمود */
        #example1 td.project-description {
            padding: 10px 8px;
            vertical-align: top;
        }

        /* للشاشات الصغيرة */
        @media (max-width: 768px) {
            .project-description {
                max-width: 250px !important;
            }

            .project-description .text-wrap {
                font-size: 12px;
                padding: 6px 8px;
            }
        }

        #example1 td {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 150px;
        }

        #example1 th {
            white-space: nowrap;
        }

        /* Responsive table settings */
        .table-responsive {
            overflow-x: auto;
        }

        /* Prevent responsive breaking */
        .dt-responsive {
            width: 100% !important;
        }

        /* Make sure DataTable doesn't break columns */
        .dataTable {
            width: 100% !important;
        }
    </style>
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Projects</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ All Projects</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <!-- Additional header content can go here -->
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    <!-- Display success/error messages -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session('success') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ session('error') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Main content row -->
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <h5 class="card-title mb-0">Project Details</h5>
                            <p class="text-muted mb-0 small">Manage and view all project information</p>
                        </div>
                        <div class="d-flex align-items-center">
                            <!-- Export Buttons -->
                            <a href="{{ route('projects.export.pdf') }}" class="btn btn-sm btn-danger btn-export-pdf mr-1" target="_blank">
                                <i class="fas fa-file-pdf"></i> PDF
                            </a>

                            <a href="{{ route('projects.export.excel') }}" class="btn btn-sm btn-success btn-export-excel mr-1">
                                <i class="fas fa-file-excel"></i> Excel
                            </a>

                            <a href="{{ route('projects.print') }}" class="btn btn-sm btn-secondary btn-export-print mr-2" target="_blank">
                                <i class="fas fa-print"></i> Print
                            </a>

                            <!-- Add New Project Button -->
                            <a class="btn btn-primary" href="{{ route('projects.create') }}">
                                <i class="fas fa-plus"></i> Add New Project
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table text-md-nowrap" id="example1">
                            <thead class="thead-light">
                                <tr>
                                    <th class="border-bottom-0 text-center">#</th>
                                    <th class="border-bottom-0 text-center">Actions</th>
                                    <th class="border-bottom-0">PR Number</th>
                                    <th class="border-bottom-0">Project Name</th>
                                    <th class="border-bottom-0">Technologies</th>
                                    <th class="border-bottom-0">All Vendors</th>
                                    <th class="border-bottom-0">All DS</th>
                                    <th class="border-bottom-0">Customer</th>
                                    <th class="border-bottom-0">Customer PO</th>
                                    <th class="border-bottom-0 text-center">Value</th>
                                    <th class="border-bottom-0">AC Manager</th>
                                    <th class="border-bottom-0">Project Manager</th>
                                    <th class="border-bottom-0">Customer Contact</th>
                                    <th class="border-bottom-0 text-center">PO Attachment</th>
                                    <th class="border-bottom-0 text-center">EPO Attachment</th>
                                    <th class="border-bottom-0 text-center">PO Date</th>
                                    <th class="border-bottom-0 text-center">Duration (Days)</th>
                                    <th class="border-bottom-0 text-center">Deadline</th>
                                    <th class="border-bottom-0">Description</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($projects as $index => $project)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td class="text-center action-buttons">
                                            <!-- View Project -->
                                            <a href="{{ route('projects.show', $project->id) }}"
                                                class="btn btn-sm btn-primary" title="View Details">
                                                <i class="las la-eye"></i>
                                            </a>

                                            @can('Edit')
                                                <a href="{{ route('projects.edit', $project->id) }}"
                                                    class="btn btn-sm btn-info" title="Edit">
                                                    <i class="las la-pen"></i>
                                                </a>
                                            @endcan

                                            @can('Delete')
                                                <a class="btn btn-sm btn-danger" data-effect="effect-scale"
                                                    data-id="{{ $project->id }}" data-name="{{ $project->name }}"
                                                    data-toggle="modal" href="#modaldemo9" title="Delete">
                                                    <i class="las la-trash"></i>
                                                </a>
                                            @endcan
                                        </td>
                                        <td>
                                            @if ($project->pr_number)
                                                <span
                                                    class="badge badge-primary badge-custom">{{ $project->pr_number }}</span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td class="font-weight-bold">{{ $project->name }}</td>
                                        <td>
                                            @if ($project->technologies)
                                                <span
                                                    class="badge badge-info badge-custom">{{ $project->technologies }}</span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($project->vendors && $project->vendors->count() > 0)
                                                @foreach ($project->vendors as $vendor)
                                                    <span
                                                        class="badge badge-{{ $vendor->pivot->is_primary ? 'success' : 'secondary' }} badge-custom mr-1">
                                                        {{ $vendor->vendors }}
                                                        @if ($vendor->pivot->is_primary)
                                                            <i class="fas fa-star ml-1" title="Primary"></i>
                                                        @endif
                                                    </span>
                                                @endforeach
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($project->deliverySpecialists && $project->deliverySpecialists->count() > 0)
                                                @foreach ($project->deliverySpecialists as $ds)
                                                    <span
                                                        class="badge badge-{{ $ds->pivot->is_lead ? 'success' : 'info' }} badge-custom mr-1">
                                                        {{ $ds->dsname }}
                                                        @if ($ds->pivot->is_lead)
                                                            <i class="fas fa-star ml-1" title="Lead DS"></i>
                                                        @endif
                                                    </span>
                                                @endforeach
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>{{ $project->cust->name ?? 'N/A' }}</td>
                                        <td>{{ $project->customer_po ?? 'N/A' }}</td>
                                        <td class="text-center">
                                            @if ($project->value)
                                                <span
                                                    class="badge badge-success badge-custom">${{ number_format($project->value, 2) }}</span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>{{ $project->aams->name ?? 'N/A' }}</td>
                                        <td>{{ $project->ppms->name ?? 'N/A' }}</td>
                                        <td>
                                            @if ($project->customer_contact_details)
                                                <span title="{{ $project->customer_contact_details }}">
                                                    {{ Str::limit($project->customer_contact_details, 30) }}
                                                </span>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($project->po_attachment && file_exists(public_path($project->po_attachment)))
                                                @php
                                                    $fileExtension = strtolower(
                                                        pathinfo($project->po_attachment, PATHINFO_EXTENSION),
                                                    );
                                                    $isImage = in_array($fileExtension, [
                                                        'jpg',
                                                        'jpeg',
                                                        'png',
                                                        'gif',
                                                        'webp',
                                                    ]);
                                                @endphp
                                                @if ($isImage)
                                                    <a href="{{ asset($project->po_attachment) }}"
                                                        data-lightbox="gallery-{{ $project->id }}"
                                                        data-title="PO Attachment - {{ $project->pr_number }}"
                                                        title="Click to view full size">
                                                        <img src="{{ asset($project->po_attachment) }}"
                                                            alt="PO Attachment" height="50" width="50"
                                                            class="img-thumbnail"
                                                            title="PO Attachment - Click to enlarge">
                                                    </a>
                                                @else
                                                    <div class="no-image" title="PO File Available">
                                                        <a href="{{ asset($project->po_attachment) }}" target="_blank"
                                                            title="Download PO File">
                                                            <i class="fas fa-file-alt text-primary"
                                                                style="font-size: 20px;"></i>
                                                            <small class="text-primary" style="font-size: 10px;">PO
                                                                File</small>
                                                        </a>
                                                    </div>
                                                @endif
                                            @else
                                                <div class="no-image" title="No PO attachment uploaded">
                                                    <i class="fas fa-file-alt text-muted" style="font-size: 20px;"></i>
                                                    <small class="text-muted" style="font-size: 10px;">No PO</small>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($project->epo_attachment && file_exists(public_path($project->epo_attachment)))
                                                @php
                                                    $fileExtension = strtolower(
                                                        pathinfo($project->epo_attachment, PATHINFO_EXTENSION),
                                                    );
                                                    $isImage = in_array($fileExtension, [
                                                        'jpg',
                                                        'jpeg',
                                                        'png',
                                                        'gif',
                                                        'webp',
                                                    ]);
                                                @endphp
                                                @if ($isImage)
                                                    <a href="{{ asset($project->epo_attachment) }}"
                                                        data-lightbox="gallery-{{ $project->id }}"
                                                        data-title="EPO Attachment - {{ $project->pr_number }}"
                                                        title="Click to view full size">
                                                        <img src="{{ asset($project->epo_attachment) }}"
                                                            alt="EPO Attachment" height="50" width="50"
                                                            class="img-thumbnail"
                                                            title="EPO Attachment - Click to enlarge">
                                                    </a>
                                                @else
                                                    <div class="no-image" title="EPO File Available">
                                                        <a href="{{ asset($project->epo_attachment) }}" target="_blank"
                                                            title="Download EPO File">
                                                            <i class="fas fa-file-alt text-success"
                                                                style="font-size: 20px;"></i>
                                                            <small class="text-success" style="font-size: 10px;">EPO
                                                                File</small>
                                                        </a>
                                                    </div>
                                                @endif
                                            @else
                                                <div class="no-image" title="No EPO attachment uploaded">
                                                    <i class="fas fa-file-alt text-muted" style="font-size: 20px;"></i>
                                                    <small class="text-muted" style="font-size: 10px;">No EPO</small>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($project->customer_po_date)
                                                <span
                                                    class="badge badge-light badge-custom">{{ \Carbon\Carbon::parse($project->customer_po_date)->format('M d, Y') }}</span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($project->customer_po_duration)
                                                <span
                                                    class="badge badge-secondary badge-custom">{{ $project->customer_po_duration }}
                                                    days</span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($project->customer_po_deadline)
                                                @php
                                                    $deadline = \Carbon\Carbon::parse($project->customer_po_deadline);
                                                    $isOverdue = $deadline->isPast();
                                                @endphp
                                                <span class="{{ $isOverdue ? 'text-danger' : 'text-success' }}">
                                                    {{ $deadline->format('M d, Y') }}
                                                    @if ($isOverdue)
                                                        <i class="fas fa-exclamation-triangle" title="Overdue"></i>
                                                    @endif
                                                </span>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td class="project-description">
                                            @if ($project->description)
                                                <div class="text-wrap">
                                                    {{ $project->description }}
                                                </div>
                                            @else
                                                <div class="text-wrap">
                                                    N/A
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- delete -->
    <div class="modal" id="modaldemo9">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">Delete</h6><button aria-label="Close" class="close" data-dismiss="modal"
                        type="button"><span aria-hidden="true">&times;</span></button>
                </div>

                <form id="deleteForm" action="" method="post">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <p> Are you sure about the deletion process?</p><br>
                        <input class="form-control" name="name" id="name" type="text" readonly>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Confirm</button>
                    </div>
            </div>
            </form>

        </div>
    </div>
@endsection

@section('js')
    <!-- Internal Data tables -->
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
    <!--Internal Datatable js -->
    <!-- <script src="{{ URL::asset('assets/js/table-data.js') }}"></script> --> <!-- Removed to avoid DataTable reinitialize conflict -->
    <script src="{{ URL::asset('assets/js/modal.js') }}"></script>

    <!-- Lightbox JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>

    <!-- jsPDF and autoTable for PDF generation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>

    <script>
        $('#modaldemo9').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var id = button.data('id')
            var name = button.data('name')
            var modal = $(this)

            // Update form action URL with the project ID for delete route
            modal.find('form').attr('action', '{{ route('projects.destroy', ':id') }}'.replace(':id', id));
            modal.find('.modal-body #name').val(name);
        })

        // Initialize lightbox and DataTable
        $(document).ready(function() {
            // إعداد Lightbox
            lightbox.option({
                'resizeDuration': 200,
                'wrapAround': true,
                'albumLabel': 'Image %1 of %2',
                'fadeDuration': 600,
                'imageFadeDuration': 600
            });

            // Initialize DataTable with export buttons
            if ($.fn.DataTable.isDataTable('#example1')) {
                $('#example1').DataTable().destroy();
            }



        // Export Functions with loading feedback
        function exportToPDF() {
            showLoadingButton('PDF');
            try {
                // Generate custom PDF with proper data
                generateProjectsPDF();
                showSuccessMessage('PDF generated successfully!');
            } catch (error) {
                console.error('PDF export error:', error);
                // Fallback to DataTables PDF
                $('#example1').DataTable().button('.buttons-pdf').trigger();
                showSuccessMessage('PDF export started!');
            }
            resetButton();
        }


        // Helper function to escape XML special characters
        function escapeXML(str) {
            if (str === null || str === undefined) return '';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&apos;');
        }

        // Print function removed - now using TCPDF PDF export with auto-print

        // Generate Custom PDF with proper data
        function generateProjectsPDF() {
            const {
                jsPDF
            } = window.jspdf;
            const doc = new jsPDF('portrait', 'mm', 'a4'); // تغيير إلى portrait بدلاً من landscape

            // Header
            doc.setFontSize(16);
            doc.setTextColor(40, 40, 40);
            doc.text('Projects Management Report', 105, 15, {
                align: 'center'
            });

            // Date
            doc.setFontSize(9);
            doc.setTextColor(100, 100, 100);
            doc.text(`Generated: ${new Date().toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            })}`, 105, 22, {
                align: 'center'
            });

            // Get table data
            const table = $('#example1').DataTable();
            const data = [];

            // Get filtered/searched data from DataTable
            table.rows({
                search: 'applied'
            }).every(function(rowIdx, tableLoop, rowLoop) {
                const rowData = this.data();
                const row = this.node();
                const cells = $(row).find('td');

                if (cells.length > 0) {
                    data.push([
                        (rowLoop + 1).toString(), // #
                        $(cells[2]).text().trim() || 'N/A', // PR Number
                        $(cells[3]).text().trim() || 'N/A', // Project Name
                        $(cells[5]).text().trim() || 'N/A', // Vendor
                        $(cells[7]).text().trim() || 'N/A', // DS
                        $(cells[9]).text().trim() || 'N/A', // Customer
                        $(cells[12]).text().trim() || 'N/A', // Value
                        $(cells[13]).text().trim() || 'N/A', // AC Manager
                        $(cells[14]).text().trim() || 'N/A' // PM
                    ]);
                }
            });

            // Check if data exists
            if (data.length === 0) {
                alert('No data available to export!');
                console.error('No data extracted from table');
                return false;
            }

            console.log('Extracted rows:', data.length);

            // Table configuration for A4 portrait
            doc.autoTable({
                head: [
                    ['#', 'PR No', 'Project', 'Vendor', 'DS', 'Customer', 'Value', 'AC Mgr', 'PM']
                ],
                body: data,
                startY: 28,
                theme: 'striped',
                styles: {
                    fontSize: 7,
                    cellPadding: 2,
                    overflow: 'linebreak',
                    halign: 'left',
                    valign: 'middle',
                    lineWidth: 0.1,
                    lineColor: [200, 200, 200]
                },
                headStyles: {
                    fillColor: [103, 126, 234], // لون أزرق جميل
                    textColor: 255,
                    fontStyle: 'bold',
                    halign: 'center',
                    fontSize: 8
                },
                alternateRowStyles: {
                    fillColor: [248, 249, 250]
                },
                columnStyles: {
                    0: {
                        cellWidth: 8,
                        halign: 'center'
                    }, // #
                    1: {
                        cellWidth: 20
                    }, // PR Number
                    2: {
                        cellWidth: 35
                    }, // Project Name
                    3: {
                        cellWidth: 25
                    }, // Vendor
                    4: {
                        cellWidth: 20
                    }, // DS
                    5: {
                        cellWidth: 25
                    }, // Customer
                    6: {
                        cellWidth: 18,
                        halign: 'right'
                    }, // Value
                    7: {
                        cellWidth: 20
                    }, // AC Manager
                    8: {
                        cellWidth: 20
                    } // PM
                },
                margin: {
                    top: 28,
                    right: 10,
                    bottom: 20,
                    left: 10
                },
                showHead: 'everyPage', // إظهار العناوين في كل صفحة
                rowPageBreak: 'auto', // السماح بتقسيم الصفوف تلقائياً
                tableWidth: 'auto',
                didDrawPage: function(data) {
                    // Header في كل صفحة
                    if (data.pageNumber > 1) {
                        doc.setFontSize(12);
                        doc.setTextColor(100, 100, 100);
                        doc.text('Projects Report (Continued)', 105, 15, {
                            align: 'center'
                        });
                    }

                    // Footer
                    const pageCount = doc.internal.getNumberOfPages();
                    doc.setFontSize(8);
                    doc.setTextColor(150);

                    // خط فاصل قبل الفوتر
                    doc.setDrawColor(200, 200, 200);
                    doc.line(10, doc.internal.pageSize.height - 15, 200, doc.internal.pageSize.height - 15);

                    // رقم الصفحة
                    doc.text(
                        `Page ${data.pageNumber} of ${pageCount}`,
                        105,
                        doc.internal.pageSize.height - 10,
                        {
                            align: 'center'
                        }
                    );

                    // معلومات إضافية
                    doc.setFontSize(7);
                    doc.text('Corporate Sites Management System', 10, doc.internal.pageSize.height - 10);
                    doc.text(new Date().toLocaleDateString(), 200, doc.internal.pageSize.height - 10, {
                        align: 'right'
                    });
                }
            });

            // Save PDF
            doc.save(`Projects_Report_${new Date().toISOString().slice(0, 10)}.pdf`);
        }

        // printProjectsTable() function removed - now using TCPDF server-side generation

        // Helper functions for user feedback
        function showLoadingButton(type) {
            const buttons = document.querySelectorAll('.btn-group .btn');
            buttons.forEach(btn => {
                if (btn.textContent.includes(type)) {
                    btn.classList.add('btn-loading');
                    const icon = btn.querySelector('i');
                    if (icon) {
                        icon.classList.add('fa-spin');
                    }
                }
            });
        }

        function resetButton() {
            setTimeout(() => {
                const buttons = document.querySelectorAll('.btn-group .btn');
                buttons.forEach(btn => {
                    btn.classList.remove('btn-loading');
                    const icon = btn.querySelector('i');
                    if (icon) {
                        icon.classList.remove('fa-spin');
                    }
                });
            }, 2000);
        }

        function showSuccessMessage(message) {
            const toast = document.createElement('div');
            toast.className = 'alert alert-success position-fixed';
            toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            toast.innerHTML = `
            <i class="fas fa-check-circle mr-2"></i>
            ${message}
            <button type="button" class="close ml-2" onclick="this.parentElement.remove()">
                <span>&times;</span>
            </button>
        `;
            document.body.appendChild(toast);

            // Auto remove after 4 seconds
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.remove();
                }
            }, 4000);
        }

        // Alternative manual export functions as backup
        function downloadTableAsCSV() {
            const table = document.getElementById('example1');
            let csv = [];
            const rows = table.querySelectorAll('tr');

            for (let i = 0; i < rows.length; i++) {
                const row = [],
                    cols = rows[i].querySelectorAll('td, th');

                for (let j = 2; j < cols.length; j++) { // Skip first two columns (# and Actions)
                    let data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, '').replace(/(\s\s)/gm, ' ');
                    data = data.replace(/"/g, '""');
                    row.push('"' + data + '"');
                }
                csv.push(row.join(','));
            }

            const csvFile = new Blob([csv.join('\n')], {
                type: 'text/csv'
            });
            const downloadLink = document.createElement('a');
            downloadLink.download = 'projects_' + new Date().toISOString().slice(0, 10) + '.csv';
            downloadLink.href = window.URL.createObjectURL(csvFile);
            downloadLink.style.display = 'none';
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
        }

    </script>

    <!-- Export Functions -->
    <script src="{{ URL::asset('assets/js/export-functions.js') }}"></script>
@endsection

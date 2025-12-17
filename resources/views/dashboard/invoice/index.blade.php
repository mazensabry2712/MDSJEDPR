@extends('layouts.master')
@section('title')
    Invoices | MDSJEDPR
@stop
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

        .no-file {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 50px;
            width: 50px;
            border: 1px dashed #ccc;
            border-radius: 4px;
        }

        /* تحسين شكل عرض Project name */
        .project-name {
            max-width: 200px !important;
            min-width: 150px;
            white-space: normal !important;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .project-name .badge {
            display: inline-block;
            max-width: 100%;
            word-wrap: break-word;
            white-space: normal;
            line-height: 1.4;
        }

        /* تحسين شكل الجدول */
        #example1 {
            width: 100% !important;
            table-layout: auto;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        /* تحسين أزرار التصدير */
        .export-buttons .btn {
            transition: all 0.3s ease;
            margin: 0 1px;
            border-radius: 4px;
        }

        .export-buttons .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-group .btn {
            border-radius: 0;
        }

        .btn-group .btn:first-child {
            border-top-left-radius: 4px;
            border-bottom-left-radius: 4px;
        }

        .btn-group .btn:last-child {
            border-top-right-radius: 4px;
            border-bottom-right-radius: 4px;
        }

        /* إخفاء أزرار DataTables الافتراضية */
        .dt-buttons {
            display: none !important;
        }

        .image-thumbnail {
            cursor: pointer;
            border: 2px solid #ddd;
            border-radius: 8px;
            transition: all 0.3s ease;
            object-fit: cover;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .image-thumbnail:hover {
            border-color: #007bff;
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
        }

        /* للشاشات الصغيرة */
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

        /* تحسين شكل الـ Alerts */
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

        /* تأكيد عدم الاختفاء السريع */
        .alert.fade.show {
            opacity: 1 !important;
        }
    </style>
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Invoices</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ All Invoices</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
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

    @if (session()->has('edit'))
        <div class="alert alert-success alert-dismissible fade show" role="alert"
            style="position: relative; z-index: 1050;">
            <div class="d-flex align-items-center">
                <i class="fas fa-edit mr-3" style="font-size: 20px;"></i>
                <div>
                    <strong>Updated!</strong>
                    <div>{{ session()->get('edit') }}</div>
                </div>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"
                style="position: absolute; top: 15px; right: 20px;">
                <span aria-hidden="true" style="color: white; font-size: 24px;">&times;</span>
            </button>
        </div>
    @endif

    @if (session()->has('delete'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert"
            style="position: relative; z-index: 1050;">
            <div class="d-flex align-items-center">
                <i class="fas fa-trash mr-3" style="font-size: 20px;"></i>
                <div>
                    <strong>Deleted!</strong>
                    <div>{{ session()->get('delete') }}</div>
                </div>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"
                style="position: absolute; top: 15px; right: 20px;">
                <span aria-hidden="true" style="color: white; font-size: 24px;">&times;</span>
            </button>
        </div>
    @endif

    @if (session()->has('Error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert"
            style="position: relative; z-index: 1050;">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle mr-3" style="font-size: 20px;"></i>
                <div>
                    <strong>Error!</strong>
                    <div>{{ session()->get('Error') }}</div>
                </div>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"
                style="position: absolute; top: 15px; right: 20px;">
                <span aria-hidden="true" style="color: white; font-size: 24px;">&times;</span>
            </button>
        </div>
    @endif

    <!-- row opened -->
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Invoices Management</h6>
                        </div>
                        <div>
                            <div class="d-flex align-items-center">
                                <!-- Export buttons -->
                                <a href="{{ route('invoices.export.pdf') }}" target="_blank" class="btn btn-sm btn-danger btn-export-pdf mr-1">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </a>
                                <a href="{{ route('invoices.export.excel') }}" class="btn btn-sm btn-success btn-export-excel mr-1">
                                    <i class="fas fa-file-excel"></i> Excel
                                </a>
                                <a href="{{ route('invoices.print') }}" target="_blank" class="btn btn-sm btn-secondary btn-export-print mr-1">
                                    <i class="fas fa-print"></i> Print
                                </a>

                                @can('Add')
                                    <a class="btn btn-primary" data-effect="effect-scale"
                                        href="{{ route('invoices.create') }}">
                                        <i class="fas fa-plus"></i> Add Invoice
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table text-md-nowrap" id="example1">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Operations</th>
                                    <th>PR Number</th>
                                    <th>Project Name</th>
                                    <th>Invoice Number</th>
                                    <th>Invoice Copy</th>
                                    <th>Value</th>
                                    <th>PR Invoices Total Value</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; ?>
                                @foreach ($invoices as $invoice)
                                    <?php $i++; ?>
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>
                                            {{-- @can('Show') --}}
                                                <a class="btn btn-sm btn-primary"
                                                    href="{{ route('invoices.show', $invoice->id) }}" title="View">
                                                    <i class="las la-eye"></i>
                                                </a>
                                            {{-- @endcan --}}

                                            @can('Edit')
                                                <a class="btn btn-sm btn-info"
                                                    href="{{ route('invoices.edit', $invoice->id) }}" title="Update">
                                                    <i class="las la-pen"></i>
                                                </a>
                                            @endcan

                                            @can('Delete')
                                                <a class="modal-effect btn btn-sm btn-danger" data-effect="effect-scale"
                                                    data-id="{{ $invoice->id }}"
                                                    data-invoice_number="{{ $invoice->invoice_number }}" data-toggle="modal"
                                                    href="#modaldemo9" title="Delete">
                                                    <i class="las la-trash"></i>
                                                </a>
                                            @endcan
                                        </td>
                                        <td>{{ $invoice->project->pr_number ?? 'N/A' }}</td>
                                        <td class="project-name">
                                            @if ($invoice->project && $invoice->project->name)
                                                <span class="badge badge-info"
                                                    style="font-size: 12px; padding: 6px 10px;">
                                                    {{ $invoice->project->name }}
                                                </span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>{{ $invoice->invoice_number }}</td>

                                        <td>
                                            @if ($invoice->invoice_copy_path)
                                                @php
                                                    $filePath = '../storge/' . $invoice->invoice_copy_path;
                                                    $extension = strtolower(
                                                        pathinfo($invoice->invoice_copy_path, PATHINFO_EXTENSION),
                                                    );
                                                    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                                                @endphp

                                                @if (in_array($extension, $imageExtensions))
                                                    <a href="{{ asset($filePath) }}"
                                                        data-lightbox="invoice-{{ $invoice->id }}"
                                                        data-title="Invoice {{ $invoice->invoice_number }}">
                                                        <img src="{{ asset($filePath) }}" alt="Invoice Copy"
                                                            class="image-thumbnail" width="50" height="50">
                                                    </a>
                                                @else
                                                    <a href="{{ asset($filePath) }}" target="_blank"
                                                        class="btn btn-sm btn-outline-danger">
                                                        <i class="fas fa-file-pdf"></i>
                                                    </a>
                                                @endif
                                            @else
                                                <div class="no-file">
                                                    <i class="fas fa-file-slash text-muted"></i>
                                                    <small class="text-muted">No file</small>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-primary">
                                                {{ number_format($invoice->value, 2) }} SAR
                                            </span>
                                        </td>
                                        <td>
                                            @if($invoice->project && $invoice->project->value)
                                                <span class="badge badge-success" style="font-size: 13px; padding: 8px 12px;">
                                                    <i class="fas fa-calculator"></i> {{ number_format($invoice->pr_invoices_total_value, 2) }}
                                                    <strong>of</strong>
                                                    <i class="fas fa-project-diagram"></i> {{ number_format($invoice->project->value, 2) }} SAR
                                                </span>
                                            @else
                                                <span class="badge badge-success" style="font-size: 13px; padding: 8px 12px;">
                                                    <i class="fas fa-calculator"></i> {{ number_format($invoice->pr_invoices_total_value, 2) }} SAR
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $statusLower = strtolower($invoice->status);
                                                $badgeClass = 'badge-primary';
                                                $icon = 'fas fa-info-circle';

                                                if (str_contains($statusLower, 'paid') || str_contains($statusLower, 'complete')) {
                                                    $badgeClass = 'badge-success';
                                                    $icon = 'fas fa-check-circle';
                                                } elseif (str_contains($statusLower, 'pending') || str_contains($statusLower, 'waiting') || str_contains($statusLower, 'processing')) {
                                                    $badgeClass = 'badge-warning';
                                                    $icon = 'fas fa-clock';
                                                } elseif (str_contains($statusLower, 'overdue') || str_contains($statusLower, 'late')) {
                                                    $badgeClass = 'badge-danger';
                                                    $icon = 'fas fa-exclamation-triangle';
                                                } elseif (str_contains($statusLower, 'cancel') || str_contains($statusLower, 'reject')) {
                                                    $badgeClass = 'badge-secondary';
                                                    $icon = 'fas fa-ban';
                                                }
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">
                                                <i class="{{ $icon }}"></i> {{ $invoice->status }}
                                            </span>
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

    <!-- delete modal -->
    <div class="modal" id="modaldemo9">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">Delete Invoice</h6>
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="invoices/destroy" method="post">
                    {{ method_field('delete') }}
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <p>Are you sure about the deletion process?</p><br>
                        <input type="hidden" name="id" id="id" value="">
                        <input class="form-control" name="invoice_number" id="invoice_number" type="text" readonly>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
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
    <!--Internal  Datatable js -->
    {{-- <script src="{{ URL::asset('assets/js/table-data.js') }}"></script> --}}
    <script src="{{ URL::asset('assets/js/modal.js') }}"></script>

    <!-- Lightbox JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>

    <script>
        // Initialize DataTable with export buttons (Only once!)
        var table = $('#example1').DataTable({
            dom: 'Bfrtip',
            buttons: [{
                    extend: 'pdfHtml5',
                    text: '<i class="fas fa-file-pdf"></i> PDF',
                    className: 'btn btn-danger btn-sm',
                    title: 'Invoices List',
                    exportOptions: {
                        columns: [0, 2, 3, 4, 6, 7, 8]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    className: 'btn btn-success btn-sm',
                    title: 'Invoices List',
                    exportOptions: {
                        columns: [0, 2, 3, 4, 6, 7, 8]
                    }
                },
                // {
                //     extend: 'csvHtml5',
                //     text: '<i class="fas fa-file-csv"></i> CSV',
                //     className: 'btn btn-info btn-sm',
                //     title: 'Invoices List',
                //     exportOptions: {
                //         columns: [0, 2, 3, 4, 6, 7, 8]
                //     }
                // },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Print',
                    className: 'btn btn-secondary btn-sm',
                    title: 'Invoices List',
                    exportOptions: {
                        columns: [0, 2, 3, 4, 6, 7, 8]
                    }
                }
            ],
            responsive: true,
            language: {
                searchPlaceholder: 'Search invoices...',
                sSearch: '',
                lengthMenu: '_MENU_ invoices per page',
            }
        });

        // Export functions for custom buttons
        function exportToPDF() {
            table.button('.buttons-pdf').trigger();
        }

        function exportToExcel() {
            const button = event.target.closest('button');
            showLoadingButton(button);
            try {
                const dataTable = $('#example1').DataTable();

                // Create workbook data in Excel XML format
                let excelData = [];
                excelData.push(['#', 'PR Number', 'Project Name', 'Invoice Number', 'Value', 'PR Total Value', 'Project Value', 'Status']); // Headers

                // Get all data from DataTable
                dataTable.rows({ search: 'applied' }).every(function(rowIdx) {
                    const rowNode = this.node();
                    const cells = $(rowNode).find('td');

                    excelData.push([
                        cells.eq(0).text().trim(),
                        cells.eq(2).text().trim(),
                        cells.eq(3).text().trim(),
                        cells.eq(4).text().trim(),
                        cells.eq(6).text().trim(),
                        cells.eq(7).text().trim().split('of')[0].trim(),
                        cells.eq(7).text().trim().includes('of') ? cells.eq(7).text().trim().split('of')[1].trim() : '',
                        cells.eq(8).text().trim()
                    ]);
                });

                // Convert to Excel worksheet
                let worksheet = '<ss:Worksheet ss:Name="Invoices"><ss:Table>';

                excelData.forEach((row, rowIndex) => {
                    worksheet += '<ss:Row>';
                    row.forEach((cell) => {
                        // Escape XML special characters
                        const escapedCell = String(cell)
                            .replace(/&/g, '&amp;')
                            .replace(/</g, '&lt;')
                            .replace(/>/g, '&gt;')
                            .replace(/"/g, '&quot;')
                            .replace(/'/g, '&apos;');

                        if (rowIndex === 0) {
                            // Header row
                            worksheet += '<ss:Cell ss:StyleID="header"><ss:Data ss:Type="String">' + escapedCell + '</ss:Data></ss:Cell>';
                        } else {
                            worksheet += '<ss:Cell><ss:Data ss:Type="String">' + escapedCell + '</ss:Data></ss:Cell>';
                        }
                    });
                    worksheet += '</ss:Row>';
                });

                worksheet += '</ss:Table></ss:Worksheet>';

                // Complete Excel XML
                const excelXML = '<?xml version="1.0"?>' +
                    '<?mso-application progid="Excel.Sheet"?>' +
                    '<ss:Workbook xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">' +
                    '<ss:Styles>' +
                    '<ss:Style ss:ID="header">' +
                    '<ss:Font ss:Bold="1" ss:Color="#FFFFFF"/>' +
                    '<ss:Interior ss:Color="#677EEA" ss:Pattern="Solid"/>' +
                    '</ss:Style>' +
                    '</ss:Styles>' +
                    worksheet +
                    '</ss:Workbook>';

                // Create blob and download
                const blob = new Blob([excelXML], {
                    type: 'application/vnd.ms-excel'
                });
                const link = document.createElement("a");
                const url = URL.createObjectURL(blob);

                link.setAttribute("href", url);
                link.setAttribute("download", 'Invoices_' + new Date().toISOString().slice(0,10) + '.xls');
                link.style.display = 'none';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                URL.revokeObjectURL(url);

                showSuccessToast('Excel file exported successfully!');
                hideLoadingButton(button);
            } catch (error) {
                console.error('Export error:', error);
                showSuccessToast('Export failed');
                hideLoadingButton(button);
            }
        }

        function showLoadingButton(button) {
            if (button) {
                button.classList.add('btn-loading');
                const icon = button.querySelector('i');
                if (icon) icon.classList.add('fa-spin');
            }
        }

        function hideLoadingButton(button) {
            if (button) {
                button.classList.remove('btn-loading');
                const icon = button.querySelector('i');
                if (icon) icon.classList.remove('fa-spin');
            }
        }

        function showSuccessToast(message) {
            // You can implement toast notification here
            console.log(message);
        }

        // function exportToCSV() {
        //     table.button('.buttons-csv').trigger();
        // }

        function printTable() {
            table.button('.buttons-print').trigger();
        }

        // Delete modal
        $('#modaldemo9').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var id = button.data('id')
            var invoice_number = button.data('invoice_number')
            var modal = $(this)
            modal.find('.modal-body #id').val(id);
            modal.find('.modal-body #invoice_number').val(invoice_number);
        });

        // Lightbox configuration
        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': true,
            'albumLabel': 'Invoice %1 of %2'
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
    </script>
@endsection

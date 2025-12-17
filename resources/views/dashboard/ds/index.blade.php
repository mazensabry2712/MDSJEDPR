@extends('layouts.master')
@section('title')
    Disti/ Supplier | MDSJEDPR
@stop
@section('css')
    <!-- Internal Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

    <style>
        /* تحسين شكل عرض DS details */
        .ds-details {
            max-width: 300px !important;
            min-width: 200px;
            white-space: normal !important;
        }

        .ds-details .text-wrap {
            background-color: #f8f9fa;
            padding: 8px 12px;
            border-radius: 6px;
            border-left: 3px solid #dc3545;
            font-size: 13px;
            line-height: 1.6;
            word-wrap: break-word;
            overflow-wrap: break-word;
            max-height: 120px;
            overflow-y: auto;
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

        /* تحسين العمود */
        #example1 td.ds-details {
            padding: 10px 8px;
            vertical-align: top;
        }

        /* للشاشات الصغيرة */
        @media (max-width: 768px) {
            .ds-details {
                max-width: 250px !important;
            }

            .ds-details .text-wrap {
                font-size: 12px;
                padding: 6px 8px;
            }
        }

        /* تحسين مظهر textarea في الـ modals */
        .modal-body textarea {
            resize: vertical;
            min-height: 100px;
        }

        .modal-body textarea:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }

        /* تحسين أزرار التصدير */
        .export-buttons .btn {
            transition: all 0.3s ease;
            margin: 0 1px;
            border-radius: 4px;
        }

        .export-buttons .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
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

        /* View Modal Styles */
        .bg-primary-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        #viewModal .modal-lg {
            max-width: 900px;
        }

        #viewModal .form-control[readonly] {
            background-color: #f8f9fa;
            cursor: default;
            border: 1px solid #e0e0e0;
        }

        #viewModal .card {
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        #viewModal .form-group label {
            color: #495057;
            margin-bottom: 8px;
        }

        #viewModal .btn {
            transition: all 0.3s ease;
        }

        #viewModal .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .btn-loading {
            position: relative;
            pointer-events: none;
            opacity: 0.7;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        @media print {
            body * {
                visibility: hidden;
            }
            .print-content, .print-content * {
                visibility: visible;
            }
            .print-content {
                position: absolute;
                left: 0;
                top: 0;
            }
        }
    </style>
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Distributors / Suppliers</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ All DS</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif



    @php
    $alerts = [
        'Add' => 'success',
        'Error' => 'danger',
        'delete' => 'danger',
        'edit' => 'success',
    ];
@endphp

@foreach ($alerts as $key => $type)
    @if (session()->has($key))
        <div class="alert alert-{{ $type }} alert-dismissible fade show" role="alert">
            <strong>{{ session()->get($key) }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
@endforeach



    <!-- row opened -->
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Disti/Supplier</h6>
                        </div>
                        <div>
                            <div class="d-flex align-items-center">
                                <!-- Export buttons -->
                                <a href="{{ route('ds.export.pdf') }}" target="_blank" class="btn btn-sm btn-danger btn-export-pdf mr-1">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </a>
                                <button onclick="exportToExcel()" class="btn btn-sm btn-success btn-export-excel mr-1">
                                    <i class="fas fa-file-excel"></i> Excel
                                </button>
                                <a href="{{ route('ds.print') }}" target="_blank" class="btn btn-sm btn-secondary btn-export-print mr-1">
                                    <i class="fas fa-print"></i> Print
                                </a>

                                @can('Add')
                                    <a class="modal-effect btn btn-primary" data-effect="effect-scale" data-toggle="modal"
                                        href="#modaldemo8">
                                        <i class="fas fa-plus"></i> Add Delivery Specialist
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
                                    <th> Operations </th>
                                    <th>D/S Name </th>
                                    <th>D/S Contact Details </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($ds as $index => $x)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <a class="modal-effect btn btn-sm btn-primary" data-effect="effect-scale"
                                                data-dsname="{{ $x->dsname }}"
                                                data-ds_contact="{{ $x->ds_contact }}"
                                                data-toggle="modal" href="#viewModal" title="View">
                                                <i class="las la-eye"></i>
                                            </a>

                                            @can('Edit')
                                            <a class="modal-effect btn btn-sm btn-info" data-effect="effect-scale"
                                                data-id="{{ $x->id }}" data-dsname="{{ $x->dsname }}"
                                                data-ds_contact="{{ $x->ds_contact }}"
                                                data-toggle="modal" href="#exampleModal2" title="Update"><i
                                                    class="las la-pen"></i></a>
                                            @endcan

                                            @can('Delete')
                                            <a class="modal-effect btn btn-sm btn-danger" data-effect="effect-scale"
                                                data-id="{{ $x->id }}" data-dsname="{{ $x->dsname }}"
                                                data-toggle="modal" href="#modaldemo9" title="Delete"><i
                                                    class="las la-trash"></i></a>
                                            @endcan
                                        </td>

                                        <td>{{ $x->dsname }}</td>
                                        <td class="ds-details">
                                            <div class="text-wrap">
                                                {{ $x->ds_contact }}
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">
                                            <i class="las la-inbox" style="font-size: 48px; color: #ccc;"></i>
                                            <p class="text-muted">No Disti/Supplier found</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="modaldemo8">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title"> Add D/S </h6><button aria-label="Close" class="close" data-dismiss="modal"
                        type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('ds.store') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="dsname">D/S Name </label>
                            <input type="text" class="form-control" id="dsname" name="dsname" >
                        </div>
                        <div class="form-group">
                            <label for="ds_contact">D/S Contact Details </label>
                            <textarea class="form-control" id="ds_contact" name="ds_contact" rows="4" placeholder="Enter delivery specialist contact details..."></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-outline-primary">Add</button>
                            <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- End Basic modal -->
        <!-- /row -->
    </div>

    <!-- View Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary-gradient">
                    <h5 class="modal-title text-white" id="viewModalLabel">
                        <i class="fas fa-eye"></i> View Disti/Supplier Details
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-right mb-3">
                        <button type="button" class="btn btn-sm btn-info" onclick="printDS()">
                            <i class="fas fa-print"></i> Print
                        </button>
                        <button type="button" class="btn btn-sm btn-success" onclick="exportDSToExcel()">
                            <i class="fas fa-file-excel"></i> Excel
                        </button>
                        {{-- <button type="button" class="btn btn-sm btn-secondary" onclick="exportDSToCSV()">
                            <i class="fas fa-file-csv"></i> CSV
                        </button> --}}
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="font-weight-bold">
                                            <i class="fas fa-user text-primary"></i> D/S Name
                                        </label>
                                        <input type="text" class="form-control" id="view-dsname" readonly>
                                    </div>

                                    <div class="form-group">
                                        <label class="font-weight-bold">
                                            <i class="fas fa-address-book text-info"></i> D/S Contact Details
                                        </label>
                                        <textarea class="form-control" id="view-ds_contact" rows="6" readonly></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- edit -->
    <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit D/S</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="ds/update" method="post" autocomplete="off">
                        {{ method_field('put') }}
                        {{ csrf_field() }}



                        <div class="form-group">
                            <input type="hidden" name="id" id="id" value="">
                            <label for="dsname" class="col-form-label">D/S Name</label>
                            <input class="form-control" name="dsname" id="dsname" type="text">
                        </div>
                        <div class="form-group">
                            <label for="ds_contact" class="col-form-label">D/S Contact Details</label>
                            <textarea class="form-control" name="ds_contact" id="ds_contact" rows="4" placeholder="Enter delivery specialist contact details..."></textarea>
                        </div>







                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-outline-primary">Confirm</button>
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                </div>
                </form>
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
                <form action="ds/destroy" method="POST">
                    {{ method_field('DELETE') }}
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <p> Are you sure about the deletion process?</p><br>
                        <input type="hidden" name="id" id="id" value="">
                        <input class="form-control" name="dsname" id="dsname" type="text" readonly>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Confirm</button>
                    </div>
            </div>
            </form>
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
    <script src="{{ URL::asset('assets/js/table-data.js') }}"></script>
    <script src="{{ URL::asset('assets/js/modal.js') }}"></script>

    <script>
        // View Modal
        $('#viewModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const modal = $(this);

            modal.find('#view-dsname').val(button.data('dsname'));
            modal.find('#view-ds_contact').val(button.data('ds_contact'));
        });

        // Edit Modal
        $('#exampleModal2').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var id = button.data('id')
            var dsname = button.data('dsname')
            var ds_contact = button.data('ds_contact')
            var modal = $(this)
            modal.find('.modal-body #id').val(id);
            modal.find('.modal-body #dsname').val(dsname);
            modal.find('.modal-body #ds_contact').val(ds_contact);
        })
    </script>

    <script>
        // Delete Modal
        $('#modaldemo9').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var id = button.data('id')
            var dsname = button.data('dsname')
            var ds_contact = button.data('ds_contact')
            var modal = $(this)
            modal.find('.modal-body #id').val(id);
            modal.find('.modal-body #dsname').val(dsname);
            modal.find('.modal-body #ds_contact').val(ds_contact);
        })

        // Auto-hide alerts
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
    </script>

    <script>
        // Export functions for table
        function exportToPDF() {
            const table = $('#example1').DataTable();
            table.button('.buttons-pdf').trigger();
        }

        function exportToExcel() {
            const button = event.target.closest('button');
            showLoadingButton(button);
            try {
                const dataTable = $('#example1').DataTable();

                // Create workbook data in Excel XML format
                let excelData = [];
                excelData.push(['#', 'D/S Name', 'D/S Contact Details']); // Headers

                // Get all data from DataTable (not just visible rows)
                dataTable.rows({ search: 'applied' }).every(function(rowIdx) {
                    const data = this.data();
                    const rowNode = this.node();
                    const cells = $(rowNode).find('td');

                    excelData.push([
                        cells.eq(0).text().trim(),
                        cells.eq(2).text().trim(),
                        cells.eq(3).text().trim()
                    ]);
                });

                // Convert to Excel worksheet
                let worksheet = '<ss:Worksheet ss:Name="Delivery Specialists"><ss:Table>';

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
                link.setAttribute("download", 'DS_' + new Date().toISOString().slice(0,10) + '.xls');
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

        function printTable() {
            const table = $('#example1').DataTable();
            table.button('.buttons-print').trigger();
        }

        // Print DS Function
        function printDS() {
            const button = event.target.closest('button');
            showLoadingButton(button);

            try {
                const dsName = document.getElementById('view-dsname').value;
                const dsContact = document.getElementById('view-ds_contact').value;

                const printWindow = window.open('', '_blank');
                const printContent = `
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <title>Delivery Specialist Details - ${dsName}</title>
                        <style>
                            body { font-family: Arial, sans-serif; margin: 40px; line-height: 1.6; }
                            .header { text-align: center; margin-bottom: 40px; border-bottom: 3px solid #667eea; padding-bottom: 20px; }
                            .header h1 { color: #667eea; margin: 0; font-size: 28px; }
                            .header p { color: #666; margin: 10px 0 0 0; }
                            .ds-details { margin: 30px 0; background: #f8f9fa; padding: 30px; border-radius: 10px; }
                            .detail-row { display: flex; margin: 20px 0; padding: 15px; background: white; border-radius: 5px; border-left: 4px solid #667eea; }
                            .detail-label { font-weight: bold; width: 200px; color: #495057; }
                            .detail-value { flex: 1; color: #212529; white-space: pre-wrap; }
                            .footer { margin-top: 50px; text-align: center; color: #999; font-size: 12px; border-top: 1px solid #ddd; padding-top: 20px; }
                            @media print {
                                body { margin: 20px; }
                                .no-print { display: none; }
                            }
                        </style>
                    </head>
                    <body>
                        <div class="header">
                            <h1>Delivery Specialist Details</h1>
                            <p>Generated on: ${new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</p>
                        </div>
                        <div class="ds-details">
                            <div class="detail-row">
                                <div class="detail-label">👤 D/S Name:</div>
                                <div class="detail-value">${dsName}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">📋 D/S Contact Details:</div>
                                <div class="detail-value">${dsContact}</div>
                            </div>
                        </div>
                        <div class="footer">
                            <p>Corporate Sites Management System - Delivery Specialist Report</p>
                            <p>This is an automatically generated document</p>
                        </div>
                    </body>
                    </html>
                `;

                printWindow.document.write(printContent);
                printWindow.document.close();

                setTimeout(() => {
                    printWindow.print();
                    hideLoadingButton(button);
                }, 500);

                showSuccessToast('Print dialog opened!');
            } catch (error) {
                console.error('Print error:', error);
                window.print();
                hideLoadingButton(button);
                showSuccessToast('Browser print opened as alternative!');
            }
        }

        // Export DS to Excel Function
        function exportDSToExcel() {
            const button = event.target.closest('button');
            showLoadingButton(button);

            try {
                const dsName = document.getElementById('view-dsname').value;
                const dsContact = document.getElementById('view-ds_contact').value;

                const data = [
                    ['Field', 'Value'],
                    ['D/S Name', dsName],
                    ['D/S Contact Details', dsContact],
                    ['', ''],
                    ['Generated On', new Date().toLocaleString()]
                ];

                const csv = data.map(row => row.map(cell => `"${cell}"`).join(',')).join('\n');
                const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
                const link = document.createElement('a');
                const url = URL.createObjectURL(blob);

                link.setAttribute('href', url);
                link.setAttribute('download', `DS_${dsName.replace(/\s+/g, '_')}_${new Date().toISOString().slice(0, 10)}.csv`);
                link.style.visibility = 'hidden';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                hideLoadingButton(button);
                showSuccessToast('Excel file exported successfully!');
            } catch (error) {
                console.error('Excel export error:', error);
                hideLoadingButton(button);
                showSuccessToast('Export failed. Please try again.');
            }
        }

        // Helper Functions
        function showLoadingButton(button) {
            button.classList.add('btn-loading');
            const icon = button.querySelector('i');
            if (icon) icon.classList.add('fa-spin');
        }

        function hideLoadingButton(button) {
            button.classList.remove('btn-loading');
            const icon = button.querySelector('i');
            if (icon) icon.classList.remove('fa-spin');
        }

        function showSuccessToast(message) {
            const toast = document.createElement('div');
            toast.className = 'alert alert-success position-fixed';
            toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 250px; animation: slideIn 0.3s ease-out;';
            toast.innerHTML = `
                <strong><i class="fas fa-check-circle"></i> Success!</strong>
                <p class="mb-0">${message}</p>
            `;
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.style.animation = 'slideOut 0.3s ease-in';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        // Enhanced DataTable initialization
        $(document).ready(function() {
            if ($.fn.DataTable.isDataTable('#example1')) {
                $('#example1').DataTable().destroy();
            }

            $('#example1').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'pdfHtml5',
                        className: 'buttons-pdf d-none',
                        title: 'Delivery Specialists List'
                    },
                    {
                        extend: 'excelHtml5',
                        className: 'buttons-excel d-none',
                        title: 'Delivery Specialists List'
                    },
                    {
                        extend: 'csvHtml5',
                        className: 'buttons-csv d-none',
                        title: 'Delivery Specialists List'
                    },
                    {
                        extend: 'print',
                        className: 'buttons-print d-none',
                        title: 'Delivery Specialists List'
                    }
                ],
                responsive: true,
                language: {
                    searchPlaceholder: 'Search...',
                    sSearch: '',
                    lengthMenu: '_MENU_ items/page',
                }
            });
        });
    </script>
@endsection

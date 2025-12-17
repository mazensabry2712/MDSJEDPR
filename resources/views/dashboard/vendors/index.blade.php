@extends('layouts.master')
@section('title')
    Vendors | MDSJEDPR
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
        /* تحسين شكل عرض vendor AM details */
        .vendor-am-details {
            max-width: 300px !important;
            min-width: 200px;
            white-space: normal !important;
        }

        .vendor-am-details .text-wrap {
            background-color: #f8f9fa;
            padding: 8px 12px;
            border-radius: 6px;
            border-left: 3px solid #007bff;
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
        #example1 td.vendor-am-details {
            padding: 10px 8px;
            vertical-align: top;
        }

        /* للشاشات الصغيرة */
        @media (max-width: 768px) {
            .vendor-am-details {
                max-width: 250px !important;
            }

            .vendor-am-details .text-wrap {
                font-size: 12px;
                padding: 6px 8px;
            }
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
                <h4 class="content-title mb-0 my-auto">Vendors</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ All Vendors</span>
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
    @if (session()->has('Error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('Error') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if (session()->has('Add'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('Add') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session()->has('delete'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('delete') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session()->has('edit'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('edit') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
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
                            <h6 class="card-title mb-0">Vendors Management</h6>
                        </div>
                        <div>
                            <div class="d-flex align-items-center">
                                <!-- Export buttons -->
                                <a href="{{ route('vendors.export.pdf') }}" target="_blank" class="btn btn-sm btn-danger btn-export-pdf mr-1">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </a>
                                <button onclick="exportToExcel()" class="btn btn-sm btn-success btn-export-excel mr-1">
                                    <i class="fas fa-file-excel"></i> Excel
                                </button>
                                <a href="{{ route('vendors.print') }}" target="_blank" class="btn btn-sm btn-secondary btn-export-print mr-1">
                                    <i class="fas fa-print"></i> Print
                                </a>
                                {{-- <button onclick="exportToCSV()" class="btn btn-sm btn-info btn-export-csv mr-1">
                                    <i class="fas fa-file-csv"></i> CSV
                                </button> --}}

                                @can('Add')
                                    <a class="modal-effect btn btn-primary" data-effect="effect-scale" data-toggle="modal"
                                        href="#modaldemo8">
                                        <i class="fas fa-plus"></i> Add Vendor
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
                                    <th>Vendors </th>
                                    <th>Vendor AM details </th>
                                </tr>

                            </thead>

                            <tbody>
                                @forelse ($vendors as $index => $vendor)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <a class="modal-effect btn btn-sm btn-primary" data-effect="effect-scale"
                                                data-vendors="{{ $vendor->vendors }}"
                                                data-vendor_am_details="{{ $vendor->vendor_am_details }}"
                                                data-toggle="modal" href="#viewModal" title="View">
                                                <i class="las la-eye"></i>
                                            </a>

                                            @can('Edit')
                                                <a class="modal-effect btn btn-sm btn-info" data-effect="effect-scale"
                                                    data-id="{{ $vendor->id }}" data-vendors="{{ $vendor->vendors }}"
                                                    data-vendor_am_details="{{ $vendor->vendor_am_details }}" data-toggle="modal"
                                                    href="#exampleModal2" title="Update"><i class="las la-pen"></i></a>
                                            @endcan

                                            @can('Delete')
                                                <a class="modal-effect btn btn-sm btn-danger" data-effect="effect-scale"
                                                    data-id="{{ $vendor->id }}" data-vendors="{{ $vendor->vendors }}"
                                                    data-toggle="modal" href="#modaldemo9" title="Delete"><i
                                                        class="las la-trash"></i></a>
                                            @endcan
                                        </td>

                                        <td>{{ $vendor->vendors }}</td>
                                        <td class="vendor-am-details">
                                            <div class="text-wrap">
                                                {{ $vendor->vendor_am_details }}
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">
                                            <i class="las la-inbox" style="font-size: 48px; color: #ccc;"></i>
                                            <p class="text-muted">No vendors found</p>
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
                    <h6 class="modal-title"> Add Vendor </h6><button aria-label="Close" class="close" data-dismiss="modal"
                        type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('vendors.store') }}" method="post">
                        {{-- <form action="vendors/store" method="post"> --}}
                        @csrf


                        {{-- <div class="form-group">
                            <label for="exampleInputEmail1"> AM name </label>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">AM email </label>
                            <input type="text" class="form-control" id="email" name="email">
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1"> AM phone </label>
                            <input type="number" class="form-control" id="phone" name="phone">
                        </div> --}}
                        <div class="form-group">
                            <label for="vendors">Vendors</label>
                            <input type="text" class="form-control" id="vendors" name="vendors" required>
                        </div>
                        <div class="form-group">
                            <label for="vendor_am_details">Vendor AM Details</label>
                            <textarea class="form-control" id="vendor_am_details" name="vendor_am_details"
                                rows="4" placeholder="Enter vendor account manager details..." required></textarea>
                        </div>





                        {{-- <div class="form-group">
                            <label for="exampleFormControlTextarea1">Project Name</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div> --}}

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
                        <i class="fas fa-eye"></i> View Vendor Details
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-right mb-3">
                        <button type="button" class="btn btn-sm btn-info" onclick="printVendor()">
                            <i class="fas fa-print"></i> Print
                        </button>
                        <button type="button" class="btn btn-sm btn-success" onclick="exportVendorToExcel()">
                            <i class="fas fa-file-excel"></i> Excel
                        </button>
                        {{-- <button type="button" class="btn btn-sm btn-secondary" onclick="exportVendorToCSV()">
                            <i class="fas fa-file-csv"></i> CSV
                        </button> --}}
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="font-weight-bold">
                                            <i class="fas fa-building text-primary"></i> Vendor Name
                                        </label>
                                        <input type="text" class="form-control" id="view-vendors" readonly>
                                    </div>

                                    <div class="form-group">
                                        <label class="font-weight-bold">
                                            <i class="fas fa-info-circle text-info"></i> Vendor AM Details
                                        </label>
                                        <textarea class="form-control" id="view-vendor_am_details" rows="6" readonly></textarea>
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
                    <h5 class="modal-title" id="exampleModalLabel">Edit Vendor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- @foreach ($vendors as $vendor)
                        <form action="{{ route('vendors.update', $vendor->id) }}" method="POST" autocomplete="off">
                            @endforeach --}}
                    <form action="vendors/update" method="POST" autocomplete="off">
                        @csrf
                        {{ method_field('PUT') }}

                        <div class="form-group">
                            <input type="hidden" name="id" id="id" value="">
                            <label for="vendors" class="col-form-label">Vendor</label>
                            <input class="form-control" name="vendors" id="vendors" type="text">
                        </div>

                        <div class="form-group">
                            <label for="vendor_am_details" class="col-form-label">Vendor AM Details</label>
                            <textarea class="form-control" name="vendor_am_details" id="vendor_am_details"
                                rows="4" placeholder="Enter vendor account manager details..."></textarea>
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
                {{-- @foreach ($vendors as $vendor)
                    <form action="{{ route('vendors.destroy', $vendor->id) }}" method="post">
                        @csrf
                        @endforeach --}}
                <form action="vendors/destroy" method="post">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <p> Are you sure about the deletion process?</p><br>
                        <input type="hidden" name="id" id="id" value="">
                        <input class="form-control" name="vendors" id="vendors" type="text" readonly>
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

            modal.find('#view-vendors').val(button.data('vendors'));
            modal.find('#view-vendor_am_details').val(button.data('vendor_am_details'));
        });

        // Edit Modal
        $('#exampleModal2').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var id = button.data('id')
            var vendors = button.data('vendors')
            var vendor_am_details = button.data('vendor_am_details')

            var modal = $(this)
            modal.find('.modal-body #id').val(id);
            modal.find('.modal-body #vendors').val(vendors);
            modal.find('.modal-body #vendor_am_details').val(vendor_am_details);

        })
    </script>

    <script>
        // Delete Modal
        $('#modaldemo9').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var id = button.data('id')
            var vendors = button.data('vendors')
            var vendor_am_details = button.data('vendor_am_details')
            var modal = $(this)
            modal.find('.modal-body #id').val(id);
            modal.find('.modal-body #vendors').val(vendors);
            modal.find('.modal-body #vendor_am_details').val(vendor_am_details);

        })

        // Auto-hide alerts
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
    </script>

    <!-- Export Functions -->
    <script src="{{ URL::asset('assets/js/export-functions.js') }}"></script>

    <script>
        // Print Vendor Function
        function printVendor() {
            const button = event.target.closest('button');
            showLoadingButton(button);

            try {
                const vendorName = document.getElementById('view-vendors').value;
                const vendorDetails = document.getElementById('view-vendor_am_details').value;

                const printWindow = window.open('', '_blank');
                const printContent = `
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <title>Vendor Details - ${vendorName}</title>
                        <style>
                            body { font-family: Arial, sans-serif; margin: 40px; line-height: 1.6; }
                            .header { text-align: center; margin-bottom: 40px; border-bottom: 3px solid #667eea; padding-bottom: 20px; }
                            .header h1 { color: #667eea; margin: 0; font-size: 28px; }
                            .header p { color: #666; margin: 10px 0 0 0; }
                            .vendor-details { margin: 30px 0; background: #f8f9fa; padding: 30px; border-radius: 10px; }
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
                            <h1>Vendor Details</h1>
                            <p>Generated on: ${new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</p>
                        </div>
                        <div class="vendor-details">
                            <div class="detail-row">
                                <div class="detail-label">🏢 Vendor Name:</div>
                                <div class="detail-value">${vendorName}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">📋 Vendor AM Details:</div>
                                <div class="detail-value">${vendorDetails}</div>
                            </div>
                        </div>
                        <div class="footer">
                            <p>Corporate Sites Management System - Vendor Report</p>
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

        // Export Vendor to Excel Function
        function exportVendorToExcel() {
            const button = event.target.closest('button');
            showLoadingButton(button);

            try {
                const vendorName = document.getElementById('view-vendors').value;
                const vendorDetails = document.getElementById('view-vendor_am_details').value;

                const data = [
                    ['Field', 'Value'],
                    ['Vendor Name', vendorName],
                    ['Vendor AM Details', vendorDetails],
                    ['', ''],
                    ['Generated On', new Date().toLocaleString()]
                ];

                const csv = data.map(row => row.map(cell => `"${cell}"`).join(',')).join('\n');
                const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
                const link = document.createElement('a');
                const url = URL.createObjectURL(blob);

                link.setAttribute('href', url);
                link.setAttribute('download', `Vendor_${vendorName.replace(/\s+/g, '_')}_${new Date().toISOString().slice(0, 10)}.csv`);
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
                        title: 'Vendors List'
                    },
                    {
                        extend: 'excelHtml5',
                        className: 'buttons-excel d-none',
                        title: 'Vendors List'
                    },
                    {
                        extend: 'csvHtml5',
                        className: 'buttons-csv d-none',
                        title: 'Vendors List'
                    },
                    {
                        extend: 'print',
                        className: 'buttons-print d-none',
                        title: 'Vendors List'
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

        // Export to Excel Function
        function exportToExcel() {
            const button = event.target.closest('button');
            showLoadingButton(button);
            try {
                const table = document.getElementById('example1');
                const rows = table.querySelectorAll('tbody tr');

                // Create workbook data in Excel XML format
                let excelData = [];
                excelData.push(['#', 'Vendor Name', 'Vendor AM Details']); // Headers

                rows.forEach((row) => {
                    const cells = row.querySelectorAll('td');
                    if (cells.length >= 4) {
                        excelData.push([
                            cells[0]?.textContent.trim() || '',
                            cells[2]?.textContent.trim() || '',
                            cells[3]?.textContent.trim() || ''
                        ]);
                    }
                });

                // Convert to Excel worksheet
                let worksheet = '<ss:Worksheet ss:Name="Vendors"><ss:Table>';

                excelData.forEach((row, rowIndex) => {
                    worksheet += '<ss:Row>';
                    row.forEach((cell) => {
                        if (rowIndex === 0) {
                            // Header row
                            worksheet += '<ss:Cell ss:StyleID="header"><ss:Data ss:Type="String">' + cell + '</ss:Data></ss:Cell>';
                        } else {
                            worksheet += '<ss:Cell><ss:Data ss:Type="String">' + cell + '</ss:Data></ss:Cell>';
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
                    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                });
                const link = document.createElement("a");
                const url = URL.createObjectURL(blob);

                link.setAttribute("href", url);
                link.setAttribute("download", 'Vendors_' + new Date().toISOString().slice(0,10) + '.xlsx');
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
    </script>
@endsection

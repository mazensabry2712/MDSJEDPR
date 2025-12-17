@extends('layouts.master')
@section('title')
    AMs | MDSJEDPR
@stop
@section('css')
    <!-- Internal Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

    <!-- Unified Export Buttons CSS -->
    <link href="{{ URL::asset('assets/css/export-buttons.css') }}" rel="stylesheet">

    <style>
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

        /* تأكيد عدم الاختفاء السريع */
        .alert.fade.show {
            opacity: 1 !important;
        }

        /* View Modal Styles */
        .bg-primary-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .modal-lg {
            max-width: 800px;
        }

        #viewModal .card {
            border-radius: 10px;
        }

        #viewModal .form-control[readonly] {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            cursor: default;
        }

        #viewModal label {
            color: #495057;
            margin-bottom: 0.5rem;
        }

        /* Export buttons animation */
        .btn-group .btn {
            transition: all 0.3s ease;
        }

        .btn-group .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
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
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Print styles */
        @media print {
            body * { visibility: hidden; }
            #am-details-content, #am-details-content * { visibility: visible; }
            #am-details-content { position: absolute; left: 0; top: 0; width: 100%; }
            .btn-group { display: none !important; }
        }
    </style>

@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Account Managers</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ All AMs</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">

        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')

    @if (session()->has('Add'))
        <div class="alert alert-success alert-dismissible fade show"
            role="alert" style="position: relative; z-index: 1050;">
            <div class="d-flex align-items-center">
                <i class="fas fa-plus mr-3" style="font-size: 20px;"></i>
                <div>
                    <strong>Added!</strong>
                    <div>{{ session()->get('Add') }}</div>
                </div>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="position: absolute; top: 15px; right: 20px;">
                <span aria-hidden="true" style="color: white; font-size: 24px;">&times;</span>
            </button>
        </div>
    @endif

    @if (session()->has('Error'))
        <div class="alert alert-danger alert-dismissible fade show"
            role="alert" style="position: relative; z-index: 1050;">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle mr-3" style="font-size: 20px;"></i>
                <div>
                    <strong>Error!</strong>
                    <div>{{ session()->get('Error') }}</div>
                </div>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="position: absolute; top: 15px; right: 20px;">
                <span aria-hidden="true" style="color: white; font-size: 24px;">&times;</span>
            </button>
        </div>
    @endif

    @if (session()->has('delete'))
        <div class="alert alert-danger alert-dismissible fade show"
            role="alert" style="position: relative; z-index: 1050;">
            <div class="d-flex align-items-center">
                <i class="fas fa-trash mr-3" style="font-size: 20px;"></i>
                <div>
                    <strong>Deleted!</strong>
                    <div>{{ session()->get('delete') }}</div>
                </div>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="position: absolute; top: 15px; right: 20px;">
                <span aria-hidden="true" style="color: white; font-size: 24px;">&times;</span>
            </button>
        </div>
    @endif

    @if (session()->has('edit'))
        <div class="alert alert-success alert-dismissible fade show"
            role="alert" style="position: relative; z-index: 1050;">
            <div class="d-flex align-items-center">
                <i class="fas fa-edit mr-3" style="font-size: 20px;"></i>
                <div>
                    <strong>Updated!</strong>
                    <div>{{ session()->get('edit') }}</div>
                </div>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="position: absolute; top: 15px; right: 20px;">
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
                            <h6 class="card-title mb-0">Account Managers Management</h6>
                        </div>
                        <div>
                            <div class="d-flex align-items-center">
                                <!-- Export Buttons - Matching PStatus Style -->
                                <a href="{{ route('am.export.pdf') }}" target="_blank" class="btn btn-sm btn-danger btn-export-pdf mr-1">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </a>
                                <button onclick="exportToExcel()" class="btn btn-sm btn-success btn-export-excel mr-1">
                                    <i class="fas fa-file-excel"></i> Excel
                                </button>
                                <a href="{{ route('am.print') }}" target="_blank" class="btn btn-sm btn-secondary btn-export-print mr-1">
                                    <i class="fas fa-print"></i> Print
                                </a>
                                {{-- <button onclick="exportToCSV()" class="btn btn-sm btn-info btn-export-csv mr-1">
                                    <i class="fas fa-file-csv"></i> CSV
                                </button> --}}


                                @can('Add')
                                    <a class="btn btn-primary" data-effect="effect-scale" data-toggle="modal"
                                        href="#modaldemo8">
                                        <i class="fas fa-plus"></i> Add Account Manager
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
                                    <th>AM Name</th>
                                    <th>AM Email</th>
                                    <th>AM Phone</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($aams as $index => $x)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <a class="modal-effect btn btn-sm btn-primary" data-effect="effect-scale"
                                                data-id="{{ $x->id }}" data-name="{{ $x->name }}"
                                                data-email="{{ $x->email }}" data-phone="{{ $x->phone }}"
                                                data-toggle="modal" href="#viewModal" title="View">
                                                <i class="las la-eye"></i>
                                            </a>

                                            @can('Edit')
                                            <a class="modal-effect btn btn-sm btn-info" data-effect="effect-scale"
                                                data-id="{{ $x->id }}" data-name="{{ $x->name }}"
                                                data-email="{{ $x->email }}" data-phone="{{ $x->phone }}"
                                                data-toggle="modal" href="#exampleModal2" title="Update">
                                                <i class="las la-pen"></i>
                                            </a>
                                            @endcan

                                            @can('Delete')
                                            <a class="modal-effect btn btn-sm btn-danger" data-effect="effect-scale"
                                                data-id="{{ $x->id }}" data-name="{{ $x->name }}"
                                                data-toggle="modal" href="#modaldemo9" title="Delete">
                                                <i class="las la-trash"></i>
                                            </a>
                                            @endcan
                                        </td>
                                        <td>{{ $x->name }}</td>
                                        <td>{{ $x->email }}</td>
                                        <td>{{ $x->phone }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            <i class="las la-inbox" style="font-size: 48px;"></i>
                                            <p>No AMs found</p>
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
                    <h6 class="modal-title"> Add AM </h6><button aria-label="Close" class="close" data-dismiss="modal"
                        type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('am.store') }}" method="post">
                        @csrf

                        <div class="form-group">
                            <label for="name">AM Name</label>
                            <input type="text" class="form-control" id="name" name="name" {{--  placeholder="Enter AM name" --}}
                                required>
                        </div>

                        <div class="form-group">
                            <label for="email">AM Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                {{--  placeholder="Enter AM email" --}}required>
                        </div>

                        <div class="form-group">
                            <label for="phone">AM Phone</label>
                            <input type="tel" class="form-control" id="phone" name="phone"
                                {{--  placeholder="Enter AM phone number"   pattern="[0-9]{10}" --}}required>
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
    <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary-gradient">
                    <h5 class="modal-title text-white" id="viewModalLabel">
                        <i class="las la-user-circle"></i> AM Details
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="am-details-content">
                    <!-- Export Buttons -->
                    <div class="d-flex justify-content-end mb-3">
                        <button type="button" class="btn btn-sm btn-secondary mr-1" onclick="printAM()" title="Print AM Details">
                            <i class="fas fa-print"></i> Print
                        </button>
                        <button type="button" class="btn btn-sm btn-success mr-1" onclick="exportAMToExcel()" title="Export to Excel">
                            <i class="fas fa-file-excel"></i> Excel
                        </button>
                        {{-- <button type="button" class="btn btn-sm btn-info mr-2" onclick="exportAMToCSV()" title="Export to CSV">
                            <i class="fas fa-file-csv"></i> CSV
                        </button> --}}
                    </div>

                    <!-- AM Information Card -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="view-name" class="col-form-label font-weight-bold">
                                            <i class="las la-user text-primary"></i> AM Name
                                        </label>
                                        <input class="form-control" id="view-name" type="text" readonly>
                                    </div>

                                    <div class="form-group">
                                        <label for="view-email" class="col-form-label font-weight-bold">
                                            <i class="las la-envelope text-primary"></i> AM Email
                                        </label>
                                        <input type="email" class="form-control" id="view-email" readonly>
                                    </div>

                                    <div class="form-group mb-0">
                                        <label for="view-phone" class="col-form-label font-weight-bold">
                                            <i class="las la-phone text-primary"></i> AM Phone
                                        </label>
                                        <input type="tel" class="form-control" id="view-phone" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="las la-times"></i> Close
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
                    <h5 class="modal-title" id="exampleModalLabel">Edit AM</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="am/update" method="post" autocomplete="off">
                        {{ method_field('PUT') }}
                        {{ csrf_field() }}



                        <div class="form-group">
                            <input type="hidden" name="id" id="id" value="">
                            <label for="recipient-name" class="col-form-label">AM Name</label>
                            <input class="form-control" name="name" id="name" type="text">
                        </div>





                        <div class="form-group">
                            <label for="message-text" class="col-form-label">AM Email</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>

                        <div class="form-group">
                            <label for="message-text" class="col-form-label">AM Phone</label>
                            <input type="tel" class="form-control" id="phone" name="phone">
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
                <form action="am/destroy" method="post">
                    {{ method_field('delete') }}
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <p> Are you sure about the deletion process?</p><br>
                        <input type="hidden" name="id" id="id" value="">
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
    <script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/table-data.js') }}"></script>
    <script src="{{ URL::asset('assets/js/modal.js') }}"></script>

    <!-- Unified Export Functions (New!) -->
    <script src="{{ URL::asset('assets/js/export-functions.js') }}"></script>

    <script>
        // View modal
        $('#viewModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const modal = $(this);

            modal.find('#view-name').val(button.data('name'));
            modal.find('#view-email').val(button.data('email'));
            modal.find('#view-phone').val(button.data('phone'));
        });

        // Edit modal
        $('#exampleModal2').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var id = button.data('id')
            var name = button.data('name')
            var email = button.data('email')
            var phone = button.data('phone')
            var modal = $(this)
            modal.find('.modal-body #id').val(id);
            modal.find('.modal-body #name').val(name);
            modal.find('.modal-body #email').val(email);
            modal.find('.modal-body #phone').val(phone);
        })

        // Delete modal
        $('#modaldemo9').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var id = button.data('id')
            var name = button.data('name')
            var email = button.data('email')
            var phone = button.data('phone')
            var modal = $(this)
            modal.find('.modal-body #id').val(id);
            modal.find('.modal-body #name').val(name);
            modal.find('.modal-body #email').val(email);
            modal.find('.modal-body #phone').val(phone);
        })

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);

        // Print AM Function - Fixed Version
function printAM() {
    const button = event.target.closest('button');
    showLoadingButton(button);

    try {
        const amName = document.getElementById('view-name').value;
        const amEmail = document.getElementById('view-email').value;
        const amPhone = document.getElementById('view-phone').value;

        // Create print container
        const printContainer = document.createElement('div');
        printContainer.id = 'print-container-' + Date.now();
        printContainer.style.display = 'none';

        const currentDate = new Date().toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        const htmlContent = `
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Account Manager Details - ${amName}</title>
                <style>
                    * {
                        margin: 0;
                        padding: 0;
                        box-sizing: border-box;
                    }
                    body {
                        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                        background: #fff;
                        color: #333;
                        line-height: 1.6;
                    }
                    .print-wrapper {
                        padding: 40px 30px;
                        max-width: 900px;
                        margin: 0 auto;
                    }
                    .print-header {
                        text-align: center;
                        margin-bottom: 50px;
                        border-bottom: 3px solid #667eea;
                        padding-bottom: 30px;
                    }
                    .print-header h1 {
                        color: #667eea;
                        font-size: 32px;
                        margin-bottom: 15px;
                        font-weight: 700;
                    }
                    .print-header p {
                        color: #666;
                        font-size: 14px;
                    }
                    .am-details {
                        margin: 40px 0;
                    }
                    .detail-row {
                        display: flex;
                        margin: 25px 0;
                        padding: 20px;
                        background: #f8f9fa;
                        border-radius: 8px;
                        border-left: 5px solid #667eea;
                    }
                    .detail-label {
                        font-weight: 700;
                        width: 140px;
                        color: #667eea;
                        flex-shrink: 0;
                    }
                    .detail-value {
                        flex: 1;
                        color: #212529;
                        word-break: break-word;
                    }
                    .print-footer {
                        margin-top: 60px;
                        text-align: center;
                        border-top: 2px solid #eee;
                        padding-top: 30px;
                        color: #999;
                        font-size: 12px;
                    }
                    .print-footer p {
                        margin: 8px 0;
                    }
                    @media print {
                        body {
                            margin: 0;
                            padding: 0;
                        }
                        .print-wrapper {
                            padding: 20mm 15mm;
                        }
                        .detail-row {
                            page-break-inside: avoid;
                        }
                    }
                </style>
            </head>
            <body>
                <div class="print-wrapper">
                    <div class="print-header">
                        <h1>Account Manager Details</h1>
                        <p>Generated on: ${currentDate}</p>
                    </div>

                    <div class="am-details">
                        <div class="detail-row">
                            <div class="detail-label">Name:</div>
                            <div class="detail-value">${amName || 'N/A'}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Email:</div>
                            <div class="detail-value">${amEmail || 'N/A'}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Phone:</div>
                            <div class="detail-value">${amPhone || 'N/A'}</div>
                        </div>
                    </div>

                    <div class="print-footer">
                        <p>Corporate Sites Management System</p>
                        <p>Account Manager Report - Automatically Generated Document</p>
                    </div>
                </div>
            </body>
            </html>
        `;

        // Create and append iframe
        const iframe = document.createElement('iframe');
        iframe.id = 'print-iframe-' + Date.now();
        iframe.style.display = 'none';
        iframe.style.position = 'absolute';
        iframe.style.width = '0';
        iframe.style.height = '0';
        iframe.style.border = 'none';
        document.body.appendChild(iframe);

        // Write content to iframe
        const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
        iframeDoc.open();
        iframeDoc.write(htmlContent);
        iframeDoc.close();

        // Wait for content to load then print
        iframe.onload = function() {
            try {
                iframe.contentWindow.print();
                hideLoadingButton(button);
                showSuccessToast('Print dialog opened successfully!');
            } catch (e) {
                console.error('Error:', e);
                hideLoadingButton(button);
                showErrorToast('Printing failed. Please try again.');
            }

            // Clean up after print
            setTimeout(function() {
                document.body.removeChild(iframe);
            }, 1000);
        };

        // Fallback if onload doesn't trigger
        setTimeout(function() {
            if (document.body.contains(iframe)) {
                try {
                    iframe.contentWindow.print();
                    hideLoadingButton(button);
                } catch (e) {
                    console.error('Error:', e);
                    hideLoadingButton(button);
                }
            }
        }, 500);

    } catch (error) {
        console.error('Print error:', error);
        hideLoadingButton(button);
        showErrorToast('An error occurred. Please try again.');
    }
}

// Error toast function
function showErrorToast(message) {
    const toast = document.createElement('div');
    toast.className = 'alert alert-danger position-fixed';
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; border-radius: 8px;';
    toast.innerHTML = `
        <strong><i class="fas fa-times-circle"></i> Error!</strong>
        <p class="mb-0">${message}</p>
    `;
    document.body.appendChild(toast);

    setTimeout(() => {
        if (toast && toast.parentNode) {
            toast.parentNode.removeChild(toast);
        }
    }, 4000);
}

// Export AM to Excel Function
        function exportAMToExcel() {
            const button = event.target.closest('button');
            showLoadingButton(button);

            try {
                const amName = document.getElementById('view-name').value;
                const amEmail = document.getElementById('view-email').value;
                const amPhone = document.getElementById('view-phone').value;

                const data = [
                    ['Field', 'Value'],
                    ['AM Name', amName],
                    ['AM Email', amEmail],
                    ['AM Phone', amPhone],
                    ['', ''],
                    ['Generated On', new Date().toLocaleString()]
                ];

                const csv = data.map(row => row.join(',')).join('\n');
                const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
                const link = document.createElement('a');
                const url = URL.createObjectURL(blob);

                link.setAttribute('href', url);
                link.setAttribute('download', `AM_${amName.replace(/\s+/g, '_')}_${new Date().toISOString().slice(0, 10)}.csv`);
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

        // Export AM to Excel Function
        function exportToExcel() {
            const button = event.target.closest('button');
            showLoadingButton(button);
            try {
                const table = document.getElementById('example1');
                const rows = table.querySelectorAll('tbody tr');

                // Create workbook data in Excel XML format
                let excelData = [];
                excelData.push(['#', 'Name', 'Email', 'Phone']); // Headers

                rows.forEach((row) => {
                    const cells = row.querySelectorAll('td');
                    if (cells.length >= 5) {
                        excelData.push([
                            cells[0]?.textContent.trim() || '',
                            cells[2]?.textContent.trim() || '',
                            cells[3]?.textContent.trim() || '',
                            cells[4]?.textContent.trim() || ''
                        ]);
                    }
                });

                // Convert to Excel worksheet
                let worksheet = '<ss:Worksheet ss:Name="Account Managers"><ss:Table>';

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
                    type: 'application/vnd.ms-excel'
                });
                const link = document.createElement("a");
                const url = URL.createObjectURL(blob);

                link.setAttribute("href", url);
                link.setAttribute("download", 'Account_Managers_' + new Date().toISOString().slice(0,10) + '.xls');
                link.style.display = 'none';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                URL.revokeObjectURL(url);

                showSuccessToast('Excel file exported successfully!');
            } catch (error) {
                console.error('Export error:', error);
                showErrorToast('Export failed');
            } finally {
                hideLoadingButton(button);
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
    </script>
@endsection

@extends('layouts.master')
@section('title')
    Customers | MDSJEDPR
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
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
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
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Force table to stay in single row */
        #example1 {
            white-space: nowrap;
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
                <h4 class="content-title mb-0 my-auto">Customers</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ All Customers</span>
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











    <!-- row opened -->
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">


                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h5 class="card-title mb-0">Customers Management</h5>
                        <div class="d-flex align-items-center">
                            <!-- Export Buttons -->
                            <a href="{{ route('customer.export.pdf') }}" target="_blank" class="btn btn-sm btn-danger btn-export-pdf mr-1">
                                <i class="fas fa-file-pdf"></i> PDF
                            </a>

                            <a href="{{ route('customer.export.excel') }}" class="btn btn-sm btn-success btn-export-excel mr-1">
                                <i class="fas fa-file-excel"></i> Excel
                            </a>

                            <a href="{{ route('customer.print') }}" target="_blank" class="btn btn-sm btn-secondary btn-export-print mr-1">
                                <i class="fas fa-print"></i> Print
                            </a>

                            @can('Add')
                            <a class="btn btn-primary" href="{{ route('customer.create') }}">
                                <i class="fas fa-plus"></i> Add Customer
                            </a>
                            @endcan
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
                                    <th>Customer Name </th>
                                    <th>Customer Abb </th>
                                    <th>Customer type </th>
                                    <th> Logo </th>
                                    <th> Customer Contact name </th>
                                    <th> Customer contact position </th>
                                    <th>Email </th>
                                    <th>Phone</th>

                                </tr>

                            </thead>

                            <tbody>
                                <?php $i = 0; ?>
                                @foreach ($custs as $cust)
                                    <?php $i++; ?>

                                    <td>{{ $i }}</td>
                                    <td>
                                        {{-- @can('View') --}}
                                        <a href="{{ route('customer.show', $cust->id) }}" class="btn btn-sm btn-primary" title="View Details">
                                            <i class="las la-eye"></i>
                                        </a>
                                        {{-- @endcan --}}
                                        @can('Edit')
                                        <a href="{{ route('customer.edit', $cust->id) }}" class="btn btn-sm btn-info" title="Edit">
                                            <i class="las la-pen"></i>
                                        </a>
                                        @endcan
                                        @can('Delete')
                                        <a class="btn btn-sm btn-danger" data-effect="effect-scale"
                                            data-id="{{ $cust->id }}" data-name="{{ $cust->name }}"
                                            data-toggle="modal" href="#modaldemo9" title="Delete">
                                            <i class="las la-trash"></i>
                                        </a>
                                        @endcan
                                    </td>
                                    <td>{{ $cust->name }}</td>
                                    <td>{{ $cust->abb }}</td>
                                    <td>{{ $cust->tybe }}</td>
                                    {{-- <td>{{ $cust->custs_attachments->logo }}</td> --}}
                                    {{-- <td>{{ $cust->logo }}</td> --}}
                                    <td>
                                        @if($cust->logo && file_exists(public_path($cust->logo)))
                                            <a href="{{ asset($cust->logo) }}" data-lightbox="gallery-{{$cust->id}}"
                                                data-title="{{$cust->name}} Logo" title="Click to view full size">
                                                <img src="{{ asset($cust->logo) }}" alt="{{$cust->name}} Logo"
                                                     height="50" width="50" class="img-thumbnail"
                                                     title="{{$cust->name}} Logo - Click to enlarge">
                                            </a>
                                        @else
                                            <div class="no-image" title="No logo uploaded">
                                                <i class="fas fa-building text-muted" style="font-size: 20px;"></i>
                                                <small class="text-muted" style="font-size: 10px;">No Logo</small>
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $cust->customercontactname }}</td>
                                    <td>{{ $cust->customercontactposition }}</td>
                                    <td>{{ $cust->email }}</td>
                                    <td>{{ $cust->phone }}</td>
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

    <!-- Lightbox JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>

    <script>
        // إعداد Lightbox
        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': true,
            'albumLabel': "صورة %1 من %2"
        });

        // Initialize DataTable with export buttons
        $(document).ready(function() {
            if ($.fn.DataTable.isDataTable('#example1')) {
                $('#example1').DataTable().destroy();
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
        // function downloadTableAsCSV() {
        //     const table = document.getElementById('example1');
        //     let csv = [];
        //     const rows = table.querySelectorAll('tr');

        //     for (let i = 0; i < rows.length; i++) {
        //         const row = [], cols = rows[i].querySelectorAll('td, th');

        //         for (let j = 2; j < cols.length; j++) { // Skip first two columns (# and Actions)
        //             let data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, '').replace(/(\s\s)/gm, ' ');
        //             data = data.replace(/"/g, '""');
        //             row.push('"' + data + '"');
        //         }
        //         csv.push(row.join(','));
        //     }

        //     const csvFile = new Blob([csv.join('\n')], { type: 'text/csv' });
        //     const downloadLink = document.createElement('a');
        //     downloadLink.download = 'customers_' + new Date().toISOString().slice(0, 10) + '.csv';
        //     downloadLink.href = window.URL.createObjectURL(csvFile);
        //     downloadLink.style.display = 'none';
        //     document.body.appendChild(downloadLink);
        //     downloadLink.click();
        //     document.body.removeChild(downloadLink);
        // }
 </script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
    <!--Internal  Datatable js -->
    <!-- <script src="{{ URL::asset('assets/js/table-data.js') }}"></script> --> <!-- Removed to avoid DataTable reinitialize conflict -->
    <script src="{{ URL::asset('assets/js/modal.js') }}"></script>



    <script>
        $('#exampleModal2').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var name = button.data('name');
            var abb = button.data('abb');
            var tybe = button.data('tybe');
            var logo = button.data('logo');
            var customercontactname = button.data('customercontactname');
            var customercontactposition = button.data('customercontactposition');
            var email = button.data('email');
            var phone = button.data('phone');
            var modal = $(this)
            modal.find('.modal-body #id').val(id);
            modal.find('.modal-body #name').val(name);
            modal.find('.modal-body #abb').val(abb);
            modal.find('.modal-body #tybe').val(tybe);
            modal.find('.modal-body #logo').val(logo);
            modal.find('.modal-body #customercontactname').val(customercontactname);
            modal.find('.modal-body #customercontactposition').val(customercontactposition);
            modal.find('.modal-body #email').val(email);
            modal.find('.modal-body #phone').val(phone);
            modal.find('.modal-body #abb').val(abb);

        })
    </script>

    <script>
        $('#modaldemo9').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var id = button.data('id');
            var name = button.data('name');
            var modal = $(this);

            // Update form action URL with the customer ID for delete route
            modal.find('form').attr('action', '{{ route("customer.destroy", ":id") }}'.replace(':id', id));
            modal.find('.modal-body #name').val(name);
        });
    </script>

    <!-- Export Functions -->
    <script>
        function exportToExcel() {
            const button = event.target.closest('button');
            const originalHTML = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Exporting...';
            button.disabled = true;

            try {
                const dataTable = $('#example1').DataTable();
                let excelData = [];

                // Headers
                excelData.push([
                    '#',
                    'Customer Name',
                    'Customer Abb',
                    'Customer Type',
                    'Contact Name',
                    'Contact Position',
                    'Email',
                    'Phone'
                ]);

                // Extract data from ALL rows (including paginated)
                dataTable.rows({ search: 'applied' }).every(function(rowIdx) {
                    const rowNode = this.node();
                    const cells = $(rowNode).find('td');

                    excelData.push([
                        cells.eq(0).text().trim(),
                        cells.eq(2).text().trim(),
                        cells.eq(3).text().trim(),
                        cells.eq(4).text().trim(),
                        cells.eq(6).text().trim(),
                        cells.eq(7).text().trim(),
                        cells.eq(8).text().trim(),
                        cells.eq(9).text().trim()
                    ]);
                });

                // Build SpreadsheetML XML
                let excelXML = '<?xml version="1.0" encoding="UTF-8"?>' +
                    '<?mso-application progid="Excel.Sheet"?>' +
                    '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" ' +
                    'xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">' +
                    '<Worksheet ss:Name="Customers">' +
                    '<Table>';

                // Add header row with styling
                excelXML += '<Row>';
                excelData[0].forEach(header => {
                    excelXML += '<Cell><Data ss:Type="String">' + escapeXML(header) + '</Data></Cell>';
                });
                excelXML += '</Row>';

                // Add data rows
                for (let i = 1; i < excelData.length; i++) {
                    excelXML += '<Row>';
                    excelData[i].forEach((cell, index) => {
                        const cellValue = cell || '';
                        excelXML += '<Cell><Data ss:Type="String">' + escapeXML(cellValue) + '</Data></Cell>';
                    });
                    excelXML += '</Row>';
                }

                excelXML += '</Table></Worksheet></Workbook>';

                // Download
                const blob = new Blob([excelXML], { type: 'application/vnd.ms-excel' });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'Customers_' + new Date().toISOString().split('T')[0] + '.xls';
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);

                showSuccessMessage('Excel file downloaded successfully!');
            } catch (error) {
                console.error('Export error:', error);
            } finally {
                button.innerHTML = originalHTML;
                button.disabled = false;
            }
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
    </script>
@endsection

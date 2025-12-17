@extends('layouts.master')

@section('title')
    PPOs Management | MDSJEDPR
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
        /* تحسين شكل عرض التفاصيل */
        .ppo-details {
            max-width: 300px !important;
            min-width: 200px;
            white-space: normal !important;
        }

        .ppo-details .text-wrap {
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
        #example1 td.ppo-details {
            padding: 10px 8px;
            vertical-align: top;
        }

        /* للشاشات الصغيرة */
        @media (max-width: 768px) {
            .ppo-details {
                max-width: 250px !important;
            }

            .ppo-details .text-wrap {
                font-size: 12px;
                padding: 6px 8px;
            }

            .card-header .d-flex {
                flex-direction: column;
                align-items: flex-start !important;
            }

            .btn-group {
                margin-bottom: 10px;
                margin-right: 0 !important;
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

        /* تحسين المودال */
        .modal-body textarea {
            resize: vertical;
            min-height: 100px;
        }

        .modal-body textarea:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .btn-loading {
            position: relative;
            pointer-events: none;
            opacity: 0.7;
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
                <h4 class="content-title mb-0 my-auto">Project Purchase Orders</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ All PPOs</span>
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
            'Edit' => 'success',
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
                            <h6 class="card-title mb-0">PPOs Management</h6>
                        </div>
                        <div>
                            <div class="d-flex align-items-center">

                                <!-- Export Buttons -->
                                <a href="{{ route('ppos.export.pdf') }}" target="_blank" class="btn btn-sm btn-danger btn-export-pdf mr-1" title="Export to PDF">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </a>
                                <button onclick="exportToExcel()" class="btn btn-sm btn-success btn-export-excel mr-1" title="Export to Excel">
                                    <i class="fas fa-file-excel"></i> Excel
                                </button>
                                <a href="{{ route('ppos.print') }}" target="_blank" class="btn btn-sm btn-secondary btn-export-print mr-1" title="Print">
                                    <i class="fas fa-print"></i> Print
                                </a>

                                @can('Add')
                                    <a class="btn btn-primary" href="{{ route('ppos.create') }}">
                                        <i class="fas fa-plus"></i> Add PPO
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
                                    <th>Category</th>
                                    <th>Supplier Name</th>
                                    <th>PO Number</th>
                                    <th>Value</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Updates</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($ppos as $index => $x)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <a class="btn btn-sm btn-primary" href="{{ route('ppos.show', $x->id) }}"
                                                title="Show"><i class="las la-eye"></i></a>

                                            @can('Edit')
                                                <a class="btn btn-sm btn-info" href="{{ route('ppos.edit', $x->id) }}"
                                                    title="Update"><i class="las la-pen"></i></a>
                                            @endcan

                                            @can('Delete')
                                                <a class="modal-effect btn btn-sm btn-danger" data-effect="effect-scale"
                                                    data-id="{{ $x->id }}" data-name="{{ $x->po_number }}"
                                                    data-toggle="modal" href="#modaldemo9" title="Delete"><i
                                                        class="las la-trash"></i></a>
                                            @endcan
                                        </td>
                                        <td>{{ $x->project->pr_number ?? 'N/A' }}</td>
                                        <td>{{ $x->project->name ?? 'N/A' }}</td>
                                        <td>
                                            @php
                                                // Get all categories for this PO Number
                                                $allCategories = \App\Models\Ppos::where('po_number', $x->po_number)
                                                    ->with('pepo:id,category')
                                                    ->get()
                                                    ->pluck('pepo.category')
                                                    ->filter()
                                                    ->unique()
                                                    ->implode(', ');
                                            @endphp
                                            <span class="badge badge-primary-light" title="{{ $allCategories }}">
                                                {{ $allCategories ?: 'N/A' }}
                                            </span>
                                        </td>
                                        <td>{{ $x->ds->dsname ?? 'N/A' }}</td>
                                        <td>{{ $x->po_number }}</td>
                                        <td>
                                            @if($x->value)
                                               {{ number_format($x->value, 2) }} SAR
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>{{ $x->date ? $x->date->format('Y-m-d') : 'N/A' }}</td>
                                        <td class="ppo-details">
                                            <div class="text-wrap">
                                                {{ $x->status ?? 'N/A' }}
                                            </div>
                                        </td>
                                        <td class="ppo-details">
                                            <div class="text-wrap">
                                                {{ $x->updates ?? 'No updates' }}
                                            </div>
                                        </td>
                                        <td class="ppo-details">
                                            <div class="text-wrap">
                                                {{ $x->notes ?? 'No notes' }}
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="text-center">
                                            <i class="las la-inbox" style="font-size: 48px; color: #ccc;"></i>
                                            <p class="text-muted">No PPOs found</p>
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

    <!-- delete modal -->
    <div class="modal" id="modaldemo9">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">Delete PPO</h6><button aria-label="Close" class="close" data-dismiss="modal"
                        type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <form action="{{ url('ppos/destroy') }}" method="post">
                    {{ method_field('delete') }}
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <p>Are you sure about the deletion process?</p><br>
                        <input type="hidden" name="id" id="id" value="">
                        <input class="form-control" name="name" id="name" type="text" readonly>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Confirm</button>
                    </div>
                </form>
            </div>
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
    <script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
    <!--Internal  Datatable js -->
    <script src="{{ URL::asset('assets/js/table-data.js') }}"></script>
    <script src="{{ URL::asset('assets/js/modal.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    <script>
        $('#modaldemo9').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var id = button.data('id')
            var name = button.data('name')
            var modal = $(this)
            modal.find('.modal-body #id').val(id);
            modal.find('.modal-body #name').val(name);
        })

        // Export to Excel Function
        function exportToExcel() {
            const button = event.target.closest('button');
            const originalHTML = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Exporting...';
            button.disabled = true;

            try {
                const dataTable = $('#example1').DataTable();
                let excelData = [];

                // Add headers
                excelData.push(['#', 'PR Number', 'Project Name', 'Category', 'Supplier Name', 'PO Number', 'Value', 'Date', 'Status', 'Updates']);

                // Get ALL data from DataTable (including paginated rows)
                dataTable.rows({ search: 'applied' }).every(function(rowIdx) {
                    const rowNode = this.node();
                    const cells = $(rowNode).find('td');

                    excelData.push([
                        cells.eq(0).text().trim(), // #
                        cells.eq(2).text().trim(), // PR Number
                        cells.eq(3).text().trim(), // Project Name
                        cells.eq(4).text().trim(), // Category
                        cells.eq(5).text().trim(), // Supplier Name
                        cells.eq(6).text().trim(), // PO Number
                        cells.eq(7).text().trim(), // Value
                        cells.eq(8).text().trim(), // Date
                        cells.eq(9).text().trim(), // Status
                        cells.eq(10).text().trim() // Updates
                    ]);
                });

                // Build Excel XML content with escaped characters
                let worksheet = '<ss:Worksheet ss:Name="Project Purchase Orders"><ss:Table>';

                excelData.forEach((row, rowIndex) => {
                    worksheet += '<ss:Row>';
                    row.forEach((cell) => {
                        // Escape special XML characters
                        const escapedCell = String(cell)
                            .replace(/&/g, '&amp;')
                            .replace(/</g, '&lt;')
                            .replace(/>/g, '&gt;')
                            .replace(/"/g, '&quot;')
                            .replace(/'/g, '&apos;');

                        if (rowIndex === 0) {
                            // Header row with style
                            worksheet += `<ss:Cell ss:StyleID="header"><ss:Data ss:Type="String">${escapedCell}</ss:Data></ss:Cell>`;
                        } else {
                            // Data rows
                            worksheet += `<ss:Cell><ss:Data ss:Type="String">${escapedCell}</ss:Data></ss:Cell>`;
                        }
                    });
                    worksheet += '</ss:Row>';
                });

                worksheet += '</ss:Table></ss:Worksheet>';

                // Build complete Excel XML with styles
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

                // Create blob and trigger download
                const blob = new Blob([excelXML], { type: 'application/vnd.ms-excel' });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'PPOs_' + new Date().toISOString().split('T')[0] + '.xls';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                window.URL.revokeObjectURL(url);

            } catch (error) {
                console.error('Export error:', error);
                alert('Error exporting to Excel. Please try again.');
            } finally {
                button.innerHTML = originalHTML;
                button.disabled = false;
            }
        }
  </script>
@endsection

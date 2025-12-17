@extends('layouts.master')
@section('title')
    Project Status | MDSJEDPR
@stop
@section('css')
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <style>
        .pstatus-details {
            padding: 10px;
            margin: 5px 0;
            border-left: 4px solid #007bff;
            background-color: #f8f9fa;
            border-radius: 4px;
            max-height: 150px;
            overflow-y: auto;
        }
        .pstatus-details .text-wrap {
            white-space: pre-wrap;
            word-wrap: break-word;
        }
    </style>
@endsection
@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Project Status</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ All Status</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">

        </div>
    </div>
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

    @if (session()->has('Add'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('Add') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
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


    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Project Status List</h4>
                        <div>

                            <!-- Export Buttons -->
                            <a href="{{ route('pstatus.export.pdf') }}" target="_blank" class="btn btn-sm btn-danger btn-export-pdf mr-1" title="Export to PDF">
                                <i class="fas fa-file-pdf"></i> PDF
                            </a>
                            <a href="{{ route('pstatus.export.excel') }}" class="btn btn-sm btn-success btn-export-excel mr-1" title="Export to Excel">
                                <i class="fas fa-file-excel"></i> Excel
                            </a>
                            <a href="{{ route('pstatus.print') }}" target="_blank" class="btn btn-sm btn-secondary btn-export-print mr-1" title="Print">
                                <i class="fas fa-print"></i> Print
                            </a>

                            @can('Add')
                            <a class="btn btn-primary" href="{{ route('pstatus.create') }}">
                                <i class="fas fa-plus"></i> Add New Status
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table text-md-nowrap table-hover" id="pstatusTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Operations</th>
                                    <th>PR Number</th>
                                    <th>Project Name</th>
                                    <th>Date & Time</th>
                                    <th>PM Name</th>
                                    <th>Status</th>
                                    <th>Actual %</th>
                                    <th>Expected Date</th>
                                    <th>Pending Cost</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pstatus as $i => $item)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>
                                            <a class="btn btn-sm btn-primary" href="{{ route('pstatus.show', $item->id) }}" title="Show">
                                                <i class="las la-eye"></i>
                                            </a>
                                            {{-- @can('Edit') --}}
                                            <a class="btn btn-sm btn-info" href="{{ route('pstatus.edit', $item->id) }}" title="Edit">
                                                <i class="las la-pen"></i>
                                            </a>
                                            {{-- @endcan --}}
                                            {{-- @can('Delete') --}}
                                            <a class="modal-effect btn btn-sm btn-danger" data-effect="effect-scale"
                                                data-id="{{ $item->id }}" data-name="{{ $item->project->pr_number ?? 'N/A' }}"
                                                data-toggle="modal" href="#modaldemo9" title="Delete">
                                                <i class="las la-trash"></i>
                                            </a>
                                            {{-- @endcan --}}
                                        </td>
                                        <td>{{ $item->project->pr_number ?? 'N/A' }}</td>
                                        <td>{{ $item->project->name ?? 'N/A' }}</td>
                                        <td>{{ $item->date_time ? \Carbon\Carbon::parse($item->date_time)->format('d/m/Y H:i') : 'N/A' }}</td>
                                        <td>{{ $item->ppm->name ?? 'N/A' }}</td>
                                        <td>
                                            <div class="pstatus-details">
                                                <div class="text-wrap">{{ $item->status ?: 'No status' }}</div>
                                            </div>
                                        </td>
                                        <td>{{ $item->actual_completion ? number_format($item->actual_completion, 2) . '%' : 'N/A' }}</td>
                                        <td>{{ $item->expected_completion ? \Carbon\Carbon::parse($item->expected_completion)->format('d/m/Y') : 'N/A' }}</td>
                                        <td>
                                            <div class="pstatus-details">
                                                <div class="text-wrap">{{ $item->date_pending_cost_orders ?: 'N/A' }}</div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="pstatus-details">
                                                <div class="text-wrap">{{ $item->notes ?: 'No notes' }}</div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center">No project status records found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal" id="modaldemo9">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">Delete</h6><button aria-label="Close" class="close" data-dismiss="modal"
                        type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <form action="pstatus/destroy" method="post">
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


    </div>
    @endsection

@section('js')
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#pstatusTable').DataTable({
                "order": [[0, "desc"]],
                "pageLength": 25,
                "language": {
                    "search": "Search:",
                    "lengthMenu": "Show _MENU_ entries",
                    "info": "Showing _START_ to _END_ of _TOTAL_ entries"
                }
            });
        });




        // Delete Modal
        $('#modaldemo9').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const id = button.data('id');
            const name = button.data('name');
            const modal = $(this);
            modal.find('.modal-body #id').val(id);
            modal.find('.modal-body #name').val(name);
        });

        // Export to Excel Function
        function exportToExcel() {
            const button = event.target.closest('button');
            const originalHTML = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Exporting...';
            button.disabled = true;

            try {
                const dataTable = $('#pstatusTable').DataTable();
                let excelData = [];

                // Add headers
                excelData.push(['#', 'PR Number', 'Project Name', 'Date & Time', 'PM Name', 'Status', 'Actual %', 'Expected Date', 'Pending Cost', 'Notes']);

                // Get ALL data from DataTable (including paginated rows)
                dataTable.rows({ search: 'applied' }).every(function(rowIdx) {
                    const rowNode = this.node();
                    const cells = $(rowNode).find('td');

                    // Extract Status (from div inside td)
                    const statusText = cells.eq(6).find('.text-wrap').text().trim() || cells.eq(6).text().trim();

                    // Extract Pending Cost (from div inside td)
                    const pendingCostText = cells.eq(9).find('.text-wrap').text().trim() || cells.eq(9).text().trim();

                    // Extract Notes (from div inside td)
                    const notesText = cells.eq(10).find('.text-wrap').text().trim() || cells.eq(10).text().trim();

                    excelData.push([
                        cells.eq(0).text().trim(), // #
                        cells.eq(2).text().trim(), // PR Number
                        cells.eq(3).text().trim(), // Project Name
                        cells.eq(4).text().trim(), // Date & Time
                        cells.eq(5).text().trim(), // PM Name
                        statusText,                // Status
                        cells.eq(7).text().trim(), // Actual %
                        cells.eq(8).text().trim(), // Expected Date
                        pendingCostText,           // Pending Cost
                        notesText                  // Notes
                    ]);
                });

                // Build Excel XML content with escaped characters
                let worksheet = '<ss:Worksheet ss:Name="Project Status"><ss:Table>';

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
                a.download = 'ProjectStatus_' + new Date().toISOString().split('T')[0] + '.xls';
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

@extends('layouts.master')
@section('title')
    Project Tasks | MDSJEDPR
@stop
@section('css')
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <style>
        .task-details {
            padding: 10px;
            margin: 5px 0;
            border-left: 4px solid #007bff;
            background-color: #f8f9fa;
            border-radius: 4px;
            max-height: 150px;
            overflow-y: auto;
        }
        .task-details .text-wrap {
            white-space: pre-wrap;
            word-wrap: break-word;
        }
    </style>
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
<h4 class="content-title mb-0 my-auto">Project Tasks</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ All Tasks</span>
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

    <!-- row opened -->
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Project Tasks List</h4>
                        <div>

                            <!-- Export Buttons -->
                            <a href="{{ route('ptasks.export.pdf') }}" target="_blank" class="btn btn-sm btn-danger btn-export-pdf mr-1" title="Export to PDF">
                                <i class="fas fa-file-pdf"></i> PDF
                            </a>
                            <a href="{{ route('ptasks.export.excel') }}" class="btn btn-sm btn-success btn-export-excel mr-1" title="Export to Excel">
                                <i class="fas fa-file-excel"></i> Excel
                            </a>
                            <a href="{{ route('ptasks.print') }}" target="_blank" class="btn btn-sm btn-secondary btn-export-print mr-1" title="Print">
                                <i class="fas fa-print"></i> Print
                            </a>

                            @can('Add')
                            <a class="btn btn-primary" href="{{ route('ptasks.create') }}">
                                <i class="fas fa-plus"></i> Add New Task
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table text-md-nowrap table-hover" id="ptasksTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Operations</th>
                                    <th>PR Number</th>
                                    <th>Project Name</th>
                                    <th>Task Date</th>
                                    <th>Task Details</th>
                                    <th>Assigned To</th>
                                    <th>Expected Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($ptasks as $i => $ptask)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>
                                            <a class="btn btn-sm btn-primary" href="{{ route('ptasks.show', $ptask->id) }}" title="Show">
                                                <i class="las la-eye"></i>
                                            </a>
                                            {{-- @can('Edit') --}}
                                            <a class="btn btn-sm btn-info" href="{{ route('ptasks.edit', $ptask->id) }}" title="Edit">
                                                <i class="las la-pen"></i>
                                            </a>
                                            {{-- @endcan --}}
                                            {{-- @can('Delete') --}}
                                            <a class="modal-effect btn btn-sm btn-danger" data-effect="effect-scale"
                                                data-id="{{ $ptask->id }}" data-task_details="{{ $ptask->details }}"
                                                data-toggle="modal" href="#modaldemo9" title="Delete">
                                                <i class="las la-trash"></i>
                                            </a>
                                            {{-- @endcan --}}
                                        </td>
                                        <td>{{ $ptask->project->pr_number ?? 'N/A' }}</td>
                                        <td>{{ $ptask->project->name ?? 'N/A' }}</td>
                                        <td>{{ $ptask->task_date ? \Carbon\Carbon::parse($ptask->task_date)->format('d/m/Y') : 'N/A' }}</td>
                                        <td>
                                            <div class="task-details">
                                                <div class="text-wrap">{{ $ptask->details ?: 'No details' }}</div>
                                            </div>
                                        </td>
                                        <td>{{ $ptask->assigned ?? 'Not assigned' }}</td>
                                        <td>{{ $ptask->expected_com_date ? \Carbon\Carbon::parse($ptask->expected_com_date)->format('d/m/Y') : 'N/A' }}</td>
                                        <td>
                                            @if($ptask->status == 'completed')
                                                <span class="badge badge-success">Completed</span>
                                            @elseif($ptask->status == 'progress')
                                                <span class="badge badge-warning">Under Progress</span>
                                            @elseif($ptask->status == 'pending')
                                                <span class="badge badge-info">Pending</span>
                                            @elseif($ptask->status == 'hold')
                                                <span class="badge badge-secondary">On Hold</span>
                                            @else
                                                <span class="badge badge-dark">{{ ucfirst($ptask->status) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No project task records found</td>
                                    </tr>
                                @endforelse
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
                <form action="ptasks/destroy" method="post">
                    {{ method_field('delete') }}
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <p> Are you sure about the deletion process?</p><br>
                        <input type="hidden" name="id" id="id" value="">
                        <input class="form-control" name="task_details" id="task_details" type="text" readonly>
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
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#ptasksTable').DataTable({
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
            var button = $(event.relatedTarget)
            var id = button.data('id')
            var task_details = button.data('task_details')
            var modal = $(this)
            modal.find('.modal-body #id').val(id);
            modal.find('.modal-body #task_details').val(task_details);
        })

        // Export to Excel Function
        function exportToExcel() {
            const button = event.target.closest('button');
            const originalHTML = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Exporting...';
            button.disabled = true;

            try {
                const dataTable = $('#ptasksTable').DataTable();
                let excelData = [];

                // Add headers
                excelData.push(['#', 'PR Number', 'Project Name', 'Task Date', 'Task Details', 'Assigned To', 'Expected Date', 'Status']);

                // Get ALL data from DataTable (including paginated rows)
                dataTable.rows({ search: 'applied' }).every(function(rowIdx) {
                    const rowNode = this.node();
                    const cells = $(rowNode).find('td');

                    // Extract Task Details (from div inside td)
                    const taskDetailsText = cells.eq(5).find('.text-wrap').text().trim() || cells.eq(5).text().trim();

                    // Extract Status (from badge)
                    const statusText = cells.eq(8).find('.badge').text().trim() || cells.eq(8).text().trim();

                    excelData.push([
                        cells.eq(0).text().trim(), // #
                        cells.eq(2).text().trim(), // PR Number
                        cells.eq(3).text().trim(), // Project Name
                        cells.eq(4).text().trim(), // Task Date
                        taskDetailsText,           // Task Details
                        cells.eq(6).text().trim(), // Assigned To
                        cells.eq(7).text().trim(), // Expected Date
                        statusText                 // Status
                    ]);
                });

                // Build Excel XML content with escaped characters
                let worksheet = '<ss:Worksheet ss:Name="Project Tasks"><ss:Table>';

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
                a.download = 'ProjectTasks_' + new Date().toISOString().split('T')[0] + '.xls';
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

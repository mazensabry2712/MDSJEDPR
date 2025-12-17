@extends('layouts.master')
@section('title')
    Risks | MDSJEDPR
@stop
@section('css')
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <style>
        .risk-details {
            padding: 10px;
            margin: 5px 0;
            border-left: 4px solid #007bff;
            background-color: #f8f9fa;
            border-radius: 4px;
            max-height: 150px;
            overflow-y: auto;
        }
        .risk-details .text-wrap {
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .mitigation-details {
            padding: 10px;
            margin: 5px 0;
            border-left: 4px solid #28a745;
            background-color: #f8f9fa;
            border-radius: 4px;
            max-height: 150px;
            overflow-y: auto;
        }
        .mitigation-details .text-wrap {
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
                <h4 class="content-title mb-0 my-auto">Project Risks</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ All Risks</span>
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
                        <h4 class="card-title">Risks List</h4>
                        <div>
                            <!-- Export Buttons -->
                            <a href="{{ route('risks.export.pdf') }}" target="_blank" class="btn btn-sm btn-danger btn-export-pdf mr-1">
                                <i class="fas fa-file-pdf"></i> PDF
                            </a>
                            <button onclick="exportToExcel()" class="btn btn-sm btn-success btn-export-excel mr-1">
                                <i class="fas fa-file-excel"></i> Excel
                            </button>
                            <a href="{{ route('risks.print') }}" target="_blank" class="btn btn-sm btn-secondary btn-export-print mr-2">
                                <i class="fas fa-print"></i> Print
                            </a>

                            @can('Add')
                            <a class="btn btn-primary" href="{{ route('risks.create') }}">
                                <i class="fas fa-plus"></i> Add New Risk
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table text-md-nowrap table-hover" id="risksTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Operations</th>
                                    <th>PR Number</th>
                                    <th>Project Name</th>
                                    <th>Risk/Issue</th>
                                    <th>Impact</th>
                                    <th>Mitigation</th>
                                    <th>Owner</th>
                                    <th>Status</th>



                                </tr>




                            </thead>

                            <tbody>
                                @forelse ($risks as $index => $risk)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <a class="btn btn-sm btn-primary" href="{{ route('risks.show', $risk->id) }}" title="Show">
                                                <i class="las la-eye"></i>
                                            </a>
                                            {{-- @can('edit') --}}
                                            <a class="btn btn-sm btn-info" href="{{ route('risks.edit', $risk->id) }}" title="Edit">
                                                <i class="las la-pen"></i>
                                            </a>
                                            {{-- @endcan --}}
                                            {{-- @can('delete') --}}
                                            <a class="btn btn-sm btn-danger" data-effect="effect-scale"
                                                data-id="{{ $risk->id }}" data-name="{{ $risk->risk }}"
                                                data-toggle="modal" href="#modaldemo9" title="Delete">
                                                <i class="las la-trash"></i>
                                            </a>
                                            {{-- @endcan --}}
                                        </td>
                                        <td>{{ $risk->project->pr_number ?? 'N/A' }}</td>
                                        <td>{{ $risk->project->name ?? 'N/A' }}</td>
                                        <td>
                                            <div class="risk-details">
                                                <div class="text-wrap">{{ $risk->risk ?: 'No risk description' }}</div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($risk->impact == 'low')
                                                <span class="badge badge-success">Low</span>
                                            @elseif($risk->impact == 'med')
                                                <span class="badge badge-warning">Medium</span>
                                            @else
                                                <span class="badge badge-danger">High</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="mitigation-details">
                                                <div class="text-wrap">{{ $risk->mitigation ?: 'N/A' }}</div>
                                            </div>
                                        </td>
                                        <td>{{ $risk->owner ?? 'N/A' }}</td>
                                        <td>
                                            @if($risk->status == 'open')
                                                <span class="badge badge-warning">Open</span>
                                            @else
                                                <span class="badge badge-success">Closed</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No risk records found</td>
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
                <form action="risks/destroy" method="post">
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
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#risksTable').DataTable({
                "order": [[0, "desc"]],
                "pageLength": 25,
                "language": {
                    "search": "Search:",
                    "lengthMenu": "Show _MENU_ entries",
                    "info": "Showing _START_ to _END_ of _TOTAL_ entries"
                }
            });
        });

        // Export to PDF
        function exportToPDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('l', 'mm', 'a4');

            doc.setFontSize(18);
            doc.text('Risks Report', 14, 15);
            doc.setFontSize(10);
            doc.text('Generated: ' + new Date().toLocaleString(), 14, 22);

            const headers = [['#', 'PR#', 'Project Name', 'Risk', 'Impact', 'Mitigation', 'Owner', 'Status']];
            const data = [];

            $('#risksTable tbody tr').each(function(index) {
                if ($(this).find('td').length > 1) {
                    const row = [];
                    $(this).find('td').each(function(i) {
                        if (i === 0 || i > 1) {
                            row.push($(this).text().trim());
                        }
                    });
                    data.push(row);
                }
            });

            doc.autoTable({
                head: headers,
                body: data,
                startY: 28,
                theme: 'grid',
                headStyles: { fillColor: [0, 123, 255], textColor: 255 },
                styles: { fontSize: 8, cellPadding: 2 },
                columnStyles: {
                    0: { cellWidth: 10 },
                    3: { cellWidth: 40 },
                    5: { cellWidth: 40 }
                }
            });

            doc.save('risks_report_' + new Date().getTime() + '.pdf');
        }

        // Export to Excel Function
        function exportToExcel() {
            const button = event.target.closest('button');
            const originalHTML = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Exporting...';
            button.disabled = true;

            try {
                const dataTable = $('#risksTable').DataTable();
                let excelData = [];

                // Headers
                excelData.push([
                    '#',
                    'PR Number',
                    'Project Name',
                    'Risk/Issue',
                    'Impact',
                    'Mitigation',
                    'Owner',
                    'Status'
                ]);

                // Extract data from ALL rows (including paginated)
                dataTable.rows({ search: 'applied' }).every(function(rowIdx) {
                    const rowNode = this.node();
                    const cells = $(rowNode).find('td');

                    // Extract risk and mitigation from text-wrap divs
                    const riskText = cells.eq(4).find('.text-wrap').text().trim() || cells.eq(4).text().trim();
                    const impactText = cells.eq(5).find('.badge').text().trim() || cells.eq(5).text().trim();
                    const mitigationText = cells.eq(6).find('.text-wrap').text().trim() || cells.eq(6).text().trim();
                    const statusText = cells.eq(8).find('.badge').text().trim() || cells.eq(8).text().trim();

                    excelData.push([
                        cells.eq(0).text().trim(),
                        cells.eq(2).text().trim(),
                        cells.eq(3).text().trim(),
                        riskText,
                        impactText,
                        mitigationText,
                        cells.eq(7).text().trim(),
                        statusText
                    ]);
                });

                // Build SpreadsheetML XML
                let excelXML = '<?xml version="1.0" encoding="UTF-8"?>' +
                    '<?mso-application progid="Excel.Sheet"?>' +
                    '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" ' +
                    'xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">' +
                    '<Worksheet ss:Name="Project Risks">' +
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
                a.download = 'Risks_' + new Date().toISOString().split('T')[0] + '.xls';
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);

            } catch (error) {
                console.error('Export error:', error);
                alert('Error exporting to Excel. Please try again.');
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

        // Delete Modal
        $('#modaldemo9').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var id = button.data('id')
            var name = button.data('name')
            var modal = $(this)
            modal.find('.modal-body #id').val(id);
            modal.find('.modal-body #name').val(name);
        })
    </script>
@endsection

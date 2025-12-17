@extends('layouts.master')
@section('title')
    Milestones | MDSJEDPR
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
        /* جدول متناسق */
        .table-responsive { width: 100%; overflow-x: auto; }

        /* Comments column style - same as DN status */
        .comments-details {
            max-width: 300px !important;
            min-width: 200px;
            white-space: normal !important;
        }

        .comments-details .text-wrap {
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

        /* Scrollbar styling for comments */
        .comments-details .text-wrap::-webkit-scrollbar { width: 6px; }
        .comments-details .text-wrap::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        .comments-details .text-wrap::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }
        .comments-details .text-wrap::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* تحسين العمود */
        #milestonesTable td.comments-details {
            padding: 10px 8px;
            vertical-align: top;
        }

        /* أزرار التصدير */
        .export-buttons .btn { transition: all 0.3s ease; margin: 0 1px; border-radius: 4px; }
        .export-buttons .btn:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.2); }
        .btn-group .btn { border-radius: 0; }
        .btn-group .btn:first-child { border-top-left-radius: 4px; border-bottom-left-radius: 4px; }
        .btn-group .btn:last-child { border-top-right-radius: 4px; border-bottom-right-radius: 4px; }
        .dt-buttons { display: none !important; }

        /* Alerts */
        .alert { border-radius: 8px; border: none; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); margin-bottom: 20px; padding: 15px 20px; position: relative; animation: slideInDown 0.5s ease-out; }
        .alert-success { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; }
        .alert-danger { background: linear-gradient(135deg, #dc3545 0%, #e74c3c 100%); color: white; }
        .alert-info { background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); color: white; }
        .alert .close { color: white; opacity: 0.8; font-size: 20px; }
        .alert .close:hover { opacity: 1; }
        @keyframes slideInDown { from { opacity: 0; transform: translateY(-30px); } to { opacity: 1; transform: translateY(0); } }
        .alert.fade.show { opacity: 1 !important; }

        /* للشاشات الصغيرة */
        @media (max-width: 768px) {
            .comments-details {
                max-width: 250px !important;
            }

            .comments-details .text-wrap {
                font-size: 12px;
                padding: 6px 8px;
            }
        }
    </style>
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Project Milestones</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ All Milestones</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">

        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')

    {{-- @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif --}}

    @if (session()->has('Add'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="position: relative; z-index: 1050;">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle mr-3" style="font-size: 20px;"></i>
                <div>
                    <strong>Success!</strong>
                    <div>{{ session()->get('Add') }}</div>
                </div>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="position: absolute; top: 15px; right: 20px;">
                <span aria-hidden="true" style="color: white; font-size: 24px;">&times;</span>
            </button>
        </div>
    @endif
    @if (session()->has('Error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="position: relative; z-index: 1050;">
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
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="position: relative; z-index: 1050;">
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
        <div class="alert alert-info alert-dismissible fade show" role="alert" style="position: relative; z-index: 1050;">
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



                {{-- <div class="card-header pb-0">
                        <a class="modal-effect btn btn-outline-primary btn-block" data-effect="effect-scale" data-toggle="modal"
                            href="{{ route('milestones.create') }}"> Add Milestone </a>
                    </div> --}}





                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Milestones Management</h6>
                        </div>
                        <div>
                            <div class="d-flex align-items-center">
                                <!-- Export buttons -->
                                <a href="{{ route('milestones.export.pdf') }}" target="_blank" class="btn btn-sm btn-danger btn-export-pdf mr-1">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </a>
                                <button onclick="exportToExcel()" class="btn btn-sm btn-success btn-export-excel mr-1">
                                    <i class="fas fa-file-excel"></i> Excel
                                </button>
                                <a href="{{ route('milestones.print') }}" target="_blank" class="btn btn-sm btn-secondary btn-export-print mr-2">
                                    <i class="fas fa-print"></i> Print
                                </a>
                                <a class="btn btn-primary" data-effect="effect-scale" href="{{ route('milestones.create') }}">
                                    Add Milestone
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="milestonesTable" style="white-space: nowrap; width: 100%;">
                            <thead>
                                <tr style="white-space: nowrap;">
                                    <th style="white-space: nowrap;">#</th>
                                    <th style="white-space: nowrap;">Operations</th>
                                    <th style="white-space: nowrap;">PR Number</th>
                                    <th style="white-space: nowrap;">Project Name</th>
                                    <th style="white-space: nowrap;">Milestone</th>
                                    <th style="white-space: nowrap;">Planned Completion</th>
                                    <th style="white-space: nowrap;">Actual Completion</th>
                                    <th style="white-space: nowrap;">Status</th>
                                    <th style="white-space: nowrap;">Comments</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; ?>
                                @foreach ($Milestones as $x)
                                    <?php $i++; ?>
                                    <tr>
                                        <td style="white-space: nowrap;">{{ $i }}</td>
                                        <td style="white-space: nowrap;">
                                            <a href="{{ route('milestones.show', $x->id) }}" class="btn btn-sm btn-primary" title="View"><i class="las la-eye"></i></a>
                                            {{-- @can('Edit') --}}
                                            <a class="btn btn-sm btn-info" href="{{ route('milestones.edit', $x->id) }}" title="Edit"><i class="las la-pen"></i></a>
                                            {{-- @endcan --}}
                                            {{-- @can('Delete') --}}
                                            <a class="modal-effect btn btn-sm btn-danger" data-effect="effect-scale" data-id="{{ $x->id }}" data-name="{{ $x->milestone }}" data-toggle="modal" href="#modaldemo9" title="Delete"><i class="las la-trash"></i></a>
                                            {{-- @endcan --}}
                                        </td>
                                        <td style="white-space: nowrap;">{{ $x->project->pr_number ?? 'N/A' }}</td>
                                        <td style="white-space: nowrap;">{{ $x->project->name ?? 'N/A' }}</td>
                                        <td style="white-space: nowrap;">{{ $x->milestone }}</td>
                                        <td style="white-space: nowrap;">{{ $x->planned_com ? date('Y-m-d', strtotime($x->planned_com)) : 'N/A' }}</td>
                                        <td style="white-space: nowrap;">{{ $x->actual_com ? date('Y-m-d', strtotime($x->actual_com)) : 'N/A' }}</td>
                                        <td style="white-space: nowrap;">
                                            @if($x->status == 'on track')
                                                <span class="badge badge-success">On Track</span>
                                            @else
                                                <span class="badge badge-warning">Delayed</span>
                                            @endif
                                        </td>
                                        <td class="comments-details">
                                            <div class="text-wrap">
                                                {{ $x->comments ?? '-' }}
                                            </div>
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
                <form action="milestones/destroy" method="post">
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
    <script src="{{ URL::asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
    <!--Internal  Datatable js -->
    <script src="{{ URL::asset('assets/js/modal.js') }}"></script>

    <!-- jsPDF for PDF export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>
    <!-- SheetJS for Excel export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#milestonesTable').DataTable({
                "pageLength": 25,
                "ordering": true,
                "searching": true,
                "responsive": true,
                "language": {
                    "searchPlaceholder": "Search...",
                    "sSearch": "",
                }
            });
        });

        $('#modaldemo9').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var name = button.data('name');
            var modal = $(this);
            modal.find('.modal-body #id').val(id);
            modal.find('.modal-body #name').val(name);
        });

        // Export to PDF
        function exportToPDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('landscape');

            // Add title
            doc.setFontSize(18);
            doc.setTextColor(102, 126, 234);
            doc.text('Milestones Report', 14, 20);

            // Add date
            doc.setFontSize(10);
            doc.setTextColor(100);
            doc.text('Generated: ' + new Date().toLocaleDateString(), 14, 28);

            // Prepare table data
            const tableData = [];
            $('#milestonesTable tbody tr').each(function() {
                const row = [];
                $(this).find('td').each(function(index) {
                    if (index !== 1) { // Skip Operations column
                        row.push($(this).text().trim());
                    }
                });
                tableData.push(row);
            });

            // Add table
            doc.autoTable({
                head: [['#', 'PR Number', 'Project Name', 'Milestone', 'Planned Completion', 'Actual Completion', 'Status', 'Comments']],
                body: tableData,
                startY: 35,
                theme: 'grid',
                headStyles: {
                    fillColor: [102, 126, 234],
                    textColor: 255,
                    fontSize: 10,
                    fontStyle: 'bold'
                },
                bodyStyles: {
                    fontSize: 9
                },
                alternateRowStyles: {
                    fillColor: [245, 245, 250]
                }
            });

            doc.save('milestones-report.pdf');
        }

        // Export to Excel Function
        function exportToExcel() {
            const button = event.target.closest('button');
            const originalHTML = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Exporting...';
            button.disabled = true;

            try {
                const dataTable = $('#milestonesTable').DataTable();
                let excelData = [];

                // Headers
                excelData.push([
                    '#',
                    'PR Number',
                    'Project Name',
                    'Milestone',
                    'Planned Completion',
                    'Actual Completion',
                    'Status',
                    'Comments'
                ]);

                // Extract data from ALL rows (including paginated)
                dataTable.rows({ search: 'applied' }).every(function(rowIdx) {
                    const rowNode = this.node();
                    const cells = $(rowNode).find('td');

                    // Extract status from badge and comments from text-wrap
                    const statusText = cells.eq(7).find('.badge').text().trim() || cells.eq(7).text().trim();
                    const commentsText = cells.eq(8).find('.text-wrap').text().trim() || cells.eq(8).text().trim();

                    excelData.push([
                        cells.eq(0).text().trim(),
                        cells.eq(2).text().trim(),
                        cells.eq(3).text().trim(),
                        cells.eq(4).text().trim(),
                        cells.eq(5).text().trim(),
                        cells.eq(6).text().trim(),
                        statusText,
                        commentsText
                    ]);
                });

                // Build SpreadsheetML XML
                let excelXML = '<?xml version="1.0" encoding="UTF-8"?>' +
                    '<?mso-application progid="Excel.Sheet"?>' +
                    '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" ' +
                    'xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">' +
                    '<Worksheet ss:Name="Project Milestones">' +
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
                a.download = 'Milestones_' + new Date().toISOString().split('T')[0] + '.xls';
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
    </script>
@endsection

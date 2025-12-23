@extends('layouts.master')
@section('title')
    Certificate of Compilation | MDSJEDPR
@stop
@section('css')
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

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

        /* تحسين شكل عرض اسم المشروع */
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

        .image-thumbnail {
            cursor: pointer;
            border: 2px solid #ddd;
            border-radius: 8px;
            transition: all 0.3s ease;
            object-fit: cover;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .image-thumbnail:hover {
            border-color: #007bff;
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(0,123,255,0.3);
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

        .alert-info {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
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

        /* Date styling */
        .date-info {
            font-size: 12px;
            color: #6c757d;
        }

        .date-info i {
            margin-right: 4px;
        }

        /* ==================== PRINT STYLES ==================== */
        @media print {
            /* إخفاء عناصر الواجهة التي لا نحتاجها */
            body > *:not(.main-content) { /* يفترض أن الجدول داخل .main-content */
                display: none !important;
            }

            /* إظهار المحتوى الرئيسي والجدول بوضوح */
            .main-content, .card, .card-body, .table-responsive, #example1 {
                visibility: visible !important;
                position: static !important;
                display: block !important;
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
                border: none !important;
            }

            /* إخفاء الأعمدة غير الضرورية (مثل العمليات والملفات) */
            /* العواميد المراد إخفاؤها هي: العمليات (index 1) و CoC File (index 4) */
            #example1 th:nth-child(2),
            #example1 td:nth-child(2),
            #example1 th:nth-child(5),
            #example1 td:nth-child(5),
            .export-buttons, /* إخفاء أزرار التصدير */
            .breadcrumb-header, /* إخفاء شريط العنوان */
            .card-header .d-flex > div:last-child a /* إخفاء زر إضافة جديد */
            {
                display: none !important;
            }

            /* إضافة عنوان التقرير وتاريخ الطباعة */
            @page {
                size: landscape; /* استخدام وضع أفقي إذا كان الجدول عريضًا */
                margin: 1cm;
            }

            body:before {
                content: "Certificate of Compilation Report";
                display: block;
                text-align: center;
                font-size: 20pt;
                font-weight: bold;
                margin-bottom: 15px;
            }

            body:after {
                content: "Printed on: " attr(data-print-date);
                display: block;
                text-align: right;
                font-size: 10pt;
                margin-top: 15px;
            }

            /* إزالة الـ badge background لتوفير الحبر */
            .badge {
                background: none !important;
                color: #000 !important;
                border: 1px solid #ccc;
                padding: 4px 8px;
            }

            /* لضمان رؤية محتوى الخلايا بشكل جيد */
            #example1 td, #example1 th {
                border: 1px solid #000 !important;
                padding: 5px !important;
            }
        }
    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Certificate of Compilation</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ All Certificates</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">

        </div>
    </div>
    @endsection

@section('content')

    @if (session()->has('Add'))
        <div class="alert alert-success alert-dismissible fade show"
            role="alert" style="position: relative; z-index: 1050;">
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
        <div class="alert alert-info alert-dismissible fade show"
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


    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Certificate of Compilation Management</h6>
                        </div>
                        <div>
                            <div class="d-flex align-items-center">

                                <!-- Export Buttons -->
                                <a href="{{ route('coc.export.pdf') }}" target="_blank" class="btn btn-sm btn-danger btn-export-pdf mr-1" title="Export to PDF">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </a>
                                <a href="{{ route('coc.export.excel') }}" class="btn btn-sm btn-success btn-export-excel mr-1" title="Export to Excel">
                                    <i class="fas fa-file-excel"></i> Excel
                                </a>
                                <a href="{{ route('coc.print') }}" target="_blank" class="btn btn-sm btn-secondary btn-export-print mr-1" title="Print">
                                    <i class="fas fa-print"></i> Print
                                </a>

                                @can('Add')
                                    <a class="btn btn-primary" data-effect="effect-scale" href="{{ route('coc.create') }}">
                                        <i class="fas fa-plus"></i> Add CoC
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
                                    <th>CoC File</th>
                                    <th>Upload Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; ?>
                                @foreach ($coc as $item)
                                    <?php $i++; ?>
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>
                                            {{-- @can('Show') --}}
                                                <a class="btn btn-sm btn-primary"
                                                    href="{{ route('coc.show', $item->id) }}" title="View">
                                                    <i class="las la-eye"></i>
                                                </a>
                                            {{-- @endcan --}}

                                            @can('Edit')
                                                <a class="btn btn-sm btn-info"
                                                    href="{{ route('coc.edit', $item->id) }}" title="Update">
                                                    <i class="las la-pen"></i>
                                                </a>
                                            @endcan

                                            @can('Delete')
                                                <a class="modal-effect btn btn-sm btn-danger" data-effect="effect-scale"
                                                    data-id="{{ $item->id }}"
                                                    data-project_name="{{ $item->project->name ?? 'N/A' }}"
                                                    data-toggle="modal" href="#modaldemo9" title="Delete">
                                                    <i class="las la-trash"></i>
                                                </a>
                                            @endcan
                                        </td>
                                        <td>
                                            @if($item->project && $item->project->pr_number)
                                                <span class="badge badge-primary" style="font-size: 11px; padding: 5px 10px;">
                                                    <i class="fas fa-hashtag mr-1"></i>
                                                    {{ $item->project->pr_number }}
                                                </span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td class="project-name">
                                            @if($item->project && $item->project->name)
                                                <span class="badge badge-info" style="font-size: 12px; padding: 6px 10px;">
                                                    <i class="fas fa-project-diagram mr-1"></i>
                                                    {{ $item->project->name }}
                                                </span>
                                            @else
                                                <span class="badge badge-secondary" style="font-size: 11px; padding: 5px 8px;">
                                                    No project assigned
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->coc_copy && file_exists(public_path('../storge/' . $item->coc_copy)))
                                                @php
                                                    $fileExtension = pathinfo($item->coc_copy, PATHINFO_EXTENSION);
                                                    $isImage = in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                                    $filePath = '../storge/' . $item->coc_copy;
                                                @endphp

                                                @if($isImage)
                                                    <a href="{{ asset($filePath) }}" data-lightbox="gallery-{{$item->id}}"
                                                         data-title="CoC Copy - {{ $item->project->name ?? 'N/A' }}" title="Click to view full size">
                                                        <img src="{{ asset($filePath) }}" alt="CoC Copy"
                                                             height="50" width="50" class="image-thumbnail"
                                                             title="CoC Copy - Click to enlarge">
                                                    </a>
                                                @else
                                                    <a href="{{ asset($filePath) }}" target="_blank"
                                                       title="Click to view/download file" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-file-pdf"></i> View File
                                                    </a>
                                                @endif
                                            @else
                                                <div class="no-file" title="No file uploaded">
                                                    <i class="fas fa-file text-muted" style="font-size: 20px;"></i>
                                                    <small class="text-muted" style="font-size: 10px;">No File</small>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="date-info">
                                                <i class="fas fa-calendar-alt"></i>
                                                {{ $item->created_at->format('Y-m-d') }}
                                            </div>
                                            <div class="date-info">
                                                <i class="fas fa-clock"></i>
                                                {{ $item->created_at->format('h:i A') }}
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

    <div class="modal" id="modaldemo9">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">Delete Certificate of Compilation</h6>
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('coc.destroy', 'test') }}" method="post">
                    {{ method_field('delete') }}
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <p>Are you sure you want to delete this Certificate of Compilation?</p><br>
                        <input type="hidden" name="id" id="id" value="">
                        <div class="form-group">
                            <label for="project_name">Project:</label>
                            <input class="form-control" name="project_name" id="project_name" type="text" readonly>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Confirm Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/table-data.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    <script>
        // Delete Modal
        $('#modaldemo9').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var project_name = button.data('project_name');
            var modal = $(this);
            modal.find('.modal-body #id').val(id);
            modal.find('.modal-body #project_name').val(project_name);
        });

          // Export to Excel (بقي كما هو)

        // Print Table (تم تعديلها)

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);

        // Configure Lightbox
        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': true,
            'fadeDuration': 300,
            'albumLabel': 'Image %1 of %2'
        });

        // DataTables initialization (تم تعديلها لإزالة تهيئة الأزرار المدمجة)
        $(document).ready(function() {
            if ($.fn.DataTable.isDataTable('#example1')) {
                $('#example1').DataTable().destroy();
            }

            $('#example1').DataTable({
                // لا نستخدم زرار DataTables Buttons هنا لأن التصدير يتم يدويًا
                dom: 'lfrtip', // لضمان ظهور شريط البحث وخيارات العرض
                responsive: true,
                columnDefs: [
                    // الأعمدة التي لا يمكن فرزها أو البحث فيها
                    { targets: [1, 4], orderable: false, searchable: false },
                    // العمود رقم 5 (Upload Date) هو الأخير، يمكن الفرز فيه
                ],
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
            const originalHTML = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Exporting...';
            button.disabled = true;

            try {
                const dataTable = $('#example1').DataTable();
                let excelData = [];

                // Add headers
                excelData.push(['#', 'PR Number', 'Project Name', 'Upload Date', 'Upload Time']);

                // Get ALL data from DataTable (including paginated rows)
                dataTable.rows({ search: 'applied' }).every(function(rowIdx) {
                    const rowNode = this.node();
                    const cells = $(rowNode).find('td');

                    excelData.push([
                        cells.eq(0).text().trim(), // #
                        cells.eq(2).text().trim(), // PR Number
                        cells.eq(3).text().trim(), // Project Name
                        cells.eq(5).text().trim().split(' ')[0], // Upload Date (first part before space)
                        cells.eq(5).text().trim().split(' ').slice(1).join(' ') // Upload Time (after date)
                    ]);
                });

                // Build Excel XML content with escaped characters
                let worksheet = '<ss:Worksheet ss:Name="Certificate of Compilation"><ss:Table>';

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
                a.download = 'CoC_' + new Date().toISOString().split('T')[0] + '.xls';
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

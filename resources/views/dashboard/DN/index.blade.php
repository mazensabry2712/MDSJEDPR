@extends('layouts.master')
@section('title')
    DN | MDSJEDPR
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

        /* تحسين شكل عرض DN details */
        .dn-details {
            max-width: 300px !important;
            min-width: 200px;
            white-space: normal !important;
        }

        .dn-details .text-wrap {
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

        /* تحسين العمود */
        #example1 td.dn-details {
            padding: 10px 8px;
            vertical-align: top;
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
            .dn-details {
                max-width: 250px !important;
            }

            .dn-details .text-wrap {
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

        /* تأكيد عدم الاختفاء السريع */
        .alert.fade.show {
            opacity: 1 !important;
        }

        /* ============== PRINT STYLES (تحسينات الطباعة) ============== */
        @media print {
            /* إخفاء كل العناصر غير المرغوب فيها */
            body * {
                visibility: hidden;
            }

            /* إظهار حاوية الجدول وكل ما بداخلها فقط */
            .table-responsive, .table-responsive *,
            .modal-content-demo, .modal-content-demo * { /* لطباعة المودال إذا لزم الأمر */
                visibility: visible;
            }

            /* تحديد موقع الجدول في أعلى الصفحة المطبوعة */
            .table-responsive {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                padding: 0;
                border: none !important;
            }

            /* إزالة الظلال والحدود غير المرغوبة */
            .card, .card-header, .card-body {
                border: none !important;
                box-shadow: none !important;
            }

            /* ضمان أن الجدول يأخذ عرض الصفحة بالكامل */
            #example1 {
                width: 100% !important;
            }

            /* منع تكسر الصفوف بين الصفحات */
            tr {
                page-break-inside: avoid;
            }
        }
    </style>
@endsection
@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Delivery Notes</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ All DNs</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">

        </div>
    </div>
    @endsection
@section('content')

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


    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Delivery Notes Management</h6>
                        </div>
                        <div>
                            <div class="d-flex align-items-center">
                                <!-- Export buttons -->
                                <a href="{{ route('dn.export.pdf') }}" target="_blank" class="btn btn-sm btn-danger btn-export-pdf mr-1">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </a>
                                <a href="{{ route('dn.export.excel') }}" class="btn btn-sm btn-success btn-export-excel mr-1">
                                    <i class="fas fa-file-excel"></i> Excel
                                </a>
                                <a href="{{ route('dn.print') }}" target="_blank" class="btn btn-sm btn-secondary btn-export-print mr-1">
                                    <i class="fas fa-print"></i> Print
                                </a>

                                @can('Add')
                                    <a class="btn btn-primary" data-effect="effect-scale" href="{{ route('dn.create') }}">
                                        <i class="fas fa-plus"></i> Add Delivery Note
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
                                    <th>DN number </th>
                                    <th>PR number</th>
                                    <th>Project Name</th>
                                    <th>DN Copy</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; ?>
                                @foreach ($dn as $dnnn)
                                    <?php $i++; ?>

                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>
                                            {{-- @can('Show') --}}
                                            <a class="btn btn-sm btn-primary"
                                                href="{{route('dn.show',$dnnn->id)}}" title="View"><i class="las la-eye"></i></a>
                                            {{-- @endcan --}}

                                             @can('Edit')
                                            <a class=" btn btn-sm btn-info"
                                                href="{{route('dn.edit',$dnnn->id)}}" title="Upadte"><i class="las la-pen"></i></a>
                                             @endcan

                                             @can('Delete')
                                            <a class="modal-effect btn btn-sm btn-danger" data-effect="effect-scale"
                                                data-id="{{ $dnnn->id }}" data-dn_number="{{ $dnnn->dn_number }}"
                                                data-toggle="modal" href="#modaldemo9" title="Delete"><i
                                                    class="las la-trash"></i></a>
                                             @endcan
                                        </td>
                                        <td>{{ $dnnn->dn_number }}</td>
                                        <td>{{ $dnnn->project->pr_number}}</td>
                                        <td class="project-name">
                                            @if($dnnn->project && $dnnn->project->name)
                                                <span class="badge badge-info" style="font-size: 12px; padding: 6px 10px;">
                                                    {{ $dnnn->project->name }}
                                                </span>
                                            @else
                                                <span class="badge badge-secondary" style="font-size: 11px; padding: 5px 8px;">
                                                    No name available
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($dnnn->dn_copy && file_exists(public_path($dnnn->dn_copy)))
                                                @php
                                                    $fileExtension = pathinfo($dnnn->dn_copy, PATHINFO_EXTENSION);
                                                    $isImage = in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                                @endphp

                                                @if($isImage)
                                                    <a href="{{ asset($dnnn->dn_copy) }}" data-lightbox="gallery-{{$dnnn->id}}"
                                                        data-title="DN Copy - {{$dnnn->dn_number}}" title="Click to view full size">
                                                        <img src="{{ asset($dnnn->dn_copy) }}" alt="DN Copy"
                                                             height="50" width="50" class="img-thumbnail"
                                                             title="DN Copy - Click to enlarge">
                                                    </a>
                                                @else
                                                    <a href="{{ asset($dnnn->dn_copy) }}" target="_blank"
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
                                        <td class="dn-details">
                                            @if($dnnn->status && trim($dnnn->status) !== '')
                                                <div class="text-wrap">
                                                    {{ $dnnn->status }}
                                                </div>
                                            @else
                                                <span class="badge badge-secondary">No Status</span>
                                            @endif
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



    </div>



    <div class="modal" id="modaldemo9">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">Delete</h6><button aria-label="Close" class="close" data-dismiss="modal"
                        type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <form action="dn/destroy" method="post">
                    {{ method_field('delete') }}
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <p> Are you sure about the deletion process?</p><br>
                        <input type="hidden" name="id" id="id" value="">
                        <input class="form-control" name="dn_number" id="dn_number" type="text" readonly>
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



    <script>
        // Export to Excel function
        function exportToExcel() {
            const button = event.target.closest('button');
            showLoadingButton(button);
            try {
                const dataTable = $('#example1').DataTable();

                // Create workbook data in Excel XML format
                let excelData = [];
                excelData.push(['#', 'DN Number', 'PR Number', 'Project Name', 'Status']); // Headers

                // Get all data from DataTable
                dataTable.rows({ search: 'applied' }).every(function(rowIdx) {
                    const rowNode = this.node();
                    const cells = $(rowNode).find('td');

                    excelData.push([
                        cells.eq(0).text().trim(),
                        cells.eq(2).text().trim(),
                        cells.eq(3).text().trim(),
                        cells.eq(4).text().trim(),
                        cells.eq(6).text().trim()
                    ]);
                });

                // Convert to Excel worksheet
                let worksheet = '<ss:Worksheet ss:Name="Delivery Notes"><ss:Table>';

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
                link.setAttribute("download", 'DN_' + new Date().toISOString().slice(0,10) + '.xls');
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

        function showSuccessToast(message) {
            console.log(message);
        }

        $('#modaldemo9').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var id = button.data('id')
            var dn_number = button.data('dn_number')

            var modal = $(this)
            modal.find('.modal-body #id').val(id);
            modal.find('.modal-body #dn_number').val(dn_number);

        })
    </script>

    <script>
        // Export functions




        // Enhanced DataTable initialization
        $(document).ready(function() {
            // تحسين الـ Alerts
            $('.alert').each(function() {
                var $alert = $(this);

                // منع الاختفاء التلقائي
                $alert.on('close.bs.alert', function(e) {
                    e.preventDefault();
                    $alert.fadeOut(500, function() {
                        $alert.remove();
                    });
                });

                // إضافة تأثير hover
                $alert.hover(
                    function() {
                        $(this).css('transform', 'translateY(-2px)');
                    },
                    function() {
                        $(this).css('transform', 'translateY(0)');
                    }
                );

                // اختفاء تلقائي بعد 8 ثواني (بدلاً من السريع)
                setTimeout(function() {
                    if ($alert.is(':visible')) {
                        $alert.fadeOut(1000, function() {
                            $alert.remove();
                        });
                    }
                }, 8000);
            });

            if ($.fn.DataTable.isDataTable('#example1')) {
                $('#example1').DataTable().destroy();
            }

            // تهيئة الجدول مع إعدادات طباعة محسّنة
            $('#example1').DataTable({
                dom: 'Bfrtip',
                buttons: [

                    {
                        extend: 'excelHtml5',
                        className: 'buttons-excel d-none',
                        exportOptions: { columns: [0, 2, 3, 4, 6] },
                        title: 'Delivery Notes List'
                    },
                    {
                        extend: 'csvHtml5',
                        className: 'buttons-csv d-none',
                        exportOptions: { columns: [0, 2, 3, 4, 6] },
                        title: 'Delivery Notes List'
                    },
                    {
                        extend: 'print',
                        className: 'buttons-print d-none',
                        autoPrint: true, // فتح نافذة الطباعة تلقائياً
                        title: '', // إزالة العنوان الافتراضي لكي نضع عنواننا الخاص
                        exportOptions: {
                            // تحديد الأعمدة التي ستتم طباعتها (استبعاد العمليات ونسخة الـ DN)
                            columns: [0, 2, 3, 4, 6]
                        },
                        customize: function (win) {
                            $(win.document.body)
                                .css('font-size', '10pt')
                                .prepend(
                                    '<h1 style="text-align: center; margin-bottom: 20px;">Delivery Notes Report</h1>'
                                );

                            $(win.document.body).find('table')
                                .addClass('compact')
                                .css('font-size', 'inherit')
                                .css('border', '1px solid #000')
                                .css('width', '100%');

                            $(win.document.body).find('thead th')
                                .css('text-align', 'center')
                                .css('border-bottom', '1px solid #000')
                                .css('padding', '8px');

                            $(win.document.body).find('tbody td')
                                .css('border', '1px solid #ccc')
                                .css('padding', '5px');
                        },
                        messageTop: function () {
                            // إضافة تاريخ ووقت الطباعة
                            return '<div style="text-align:center; margin-bottom: 15px;">Printed on: ' + new Date().toLocaleString() + '</div>';
                        }
                    }
                ],
                responsive: true,
                columnDefs: [
                    { width: "5%", targets: 0 },   // # column
                    { width: "15%", targets: 1, orderable: false, searchable: false }, // Operations column (تم تعطيل الفرز والبحث)
                    { width: "15%", targets: 2 },  // DN number column
                    { width: "15%", targets: 3 },  // PR number column
                    { width: "20%", targets: 4 },  // Project Name column
                    { width: "10%", targets: 5, orderable: false, searchable: false }, // DN Copy column (تم تعطيل الفرز والبحث)
                    { width: "20%", targets: 6 }   // Status column
                ],
                language: {
                    searchPlaceholder: 'Search...',
                    sSearch: '',
                    lengthMenu: '_MENU_ items/page',
                }
            });
        });

        // Initialize Lightbox
        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': true,
            'fadeDuration': 300,
            'imageFadeDuration': 300
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
@endsection

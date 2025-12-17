@extends('layouts.master')

@section('css')
    <style>
        .info-card {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        .info-card h2 {
            font-size: 2rem;
            margin-bottom: 10px;
            font-weight: 700;
        }
        .info-card p {
            font-size: 1.1rem;
            margin: 0;
            opacity: 0.95;
        }
        .detail-box {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-left: 4px solid #007bff;
        }
        .detail-box h5 {
            color: #007bff;
            font-weight: 600;
            margin-bottom: 15px;
            font-size: 1.2rem;
        }
        .detail-item {
            margin-bottom: 15px;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .detail-item:hover {
            background: #e9ecef;
            transform: translateX(5px);
        }
        .detail-label {
            font-weight: 600;
            color: #495057;
            display: inline-block;
            min-width: 150px;
        }
        .detail-value {
            color: #212529;
        }
        .status-badge {
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
            display: inline-block;
        }
        .status-open {
            background: #6ea8fe;
            color: #fff;
        }
        .status-closed {
            background: #0d6efd;
            color: #fff;
        }
        .impact-badge {
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
            display: inline-block;
        }
        .impact-low {
            background: #0d6efd;
            color: #fff;
        }
        .impact-med {
            background: #0dcaf0;
            color: #fff;
        }
        .impact-high {
            background: #6ea8fe;
            color: #fff;
        }
        .action-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .action-card h5 {
            color: #495057;
            font-weight: 600;
            margin-bottom: 15px;
        }
        .btn-action {
            width: 100%;
            margin-bottom: 10px;
            padding: 12px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        .risk-content {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
            white-space: pre-wrap;
            word-wrap: break-word;
            line-height: 1.8;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            .info-card {
                background: #667eea !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
@endsection

@section('title')
    Risk Details
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Risks</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ Risk Details</span>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <!-- Main Content -->
        <div class="col-xl-9 col-lg-8">
            <!-- Header Card -->
            <div class="info-card">
                <h2><i class="fas fa-exclamation-triangle"></i> {{ $risks->project->name }}</h2>
                <p><strong>PR#:</strong> {{ $risks->project->pr_number }}</p>
            </div>

            <!-- Risk Details -->
            <div class="detail-box">
                <h5><i class="fas fa-info-circle"></i> Risk Information</h5>

                <div class="detail-item">
                    <span class="detail-label"><i class="fas fa-hashtag"></i> PR Number:</span>
                    <span class="detail-value">{{ $risks->project->pr_number }}</span>
                </div>

                <div class="detail-item">
                    <span class="detail-label"><i class="fas fa-project-diagram"></i> Project Name:</span>
                    <span class="detail-value">{{ $risks->project->name }}</span>
                </div>

                <div class="detail-item">
                    <span class="detail-label"><i class="fas fa-chart-line"></i> Impact:</span>
                    <span class="detail-value">
                        @if($risks->impact == 'low')
                            <span class="impact-badge impact-low"><i class="fas fa-arrow-down"></i> Low</span>
                        @elseif($risks->impact == 'med')
                            <span class="impact-badge impact-med"><i class="fas fa-minus"></i> Medium</span>
                        @else
                            <span class="impact-badge impact-high"><i class="fas fa-arrow-up"></i> High</span>
                        @endif
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label"><i class="fas fa-user"></i> Owner:</span>
                    <span class="detail-value">{{ $risks->owner ?? 'N/A' }}</span>
                </div>

                <div class="detail-item">
                    <span class="detail-label"><i class="fas fa-flag"></i> Status:</span>
                    <span class="detail-value">
                        @if($risks->status == 'open')
                            <span class="status-badge status-open"><i class="fas fa-folder-open"></i> Open</span>
                        @else
                            <span class="status-badge status-closed"><i class="fas fa-check-circle"></i> Closed</span>
                        @endif
                    </span>
                </div>
            </div>

            <!-- Risk Description -->
            <div class="detail-box">
                <h5><i class="fas fa-exclamation-circle"></i> Risk/Issue Description</h5>
                <div class="risk-content">{{ $risks->risk }}</div>
            </div>

            <!-- Mitigation Plan -->
            @if($risks->mitigation)
            <div class="detail-box">
                <h5><i class="fas fa-shield-alt"></i> Mitigation/Action Plan</h5>
                <div class="risk-content">{{ $risks->mitigation }}</div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-xl-3 col-lg-4">
            <!-- Quick Actions -->
            <div class="action-card no-print">
                <h5><i class="fas fa-bolt"></i> Quick Actions</h5>
                {{-- @can('edit') --}}
                <a href="{{ route('risks.edit', $risks->id) }}" class="btn btn-primary btn-action">
                    <i class="fas fa-edit"></i> Edit Risk
                </a>
                {{-- @endcan --}}
                <a href="{{ route('risks.index') }}" class="btn btn-outline-primary btn-action">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
                {{-- @can('delete') --}}
                <button type="button" class="btn btn-danger btn-action" data-toggle="modal" data-target="#deleteModal">
                    <i class="fas fa-trash"></i> Delete Risk
                </button>
                {{-- @endcan --}}
            </div>

            <!-- Export Options -->
            <div class="action-card no-print">
                <h5><i class="fas fa-download"></i> Export</h5>
                <button onclick="exportToPDF()" class="btn btn-primary btn-action">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </button>
                <button onclick="exportToExcel()" class="btn btn-info btn-action">
                    <i class="fas fa-file-excel"></i> Export Excel
                </button>
                <button onclick="window.print()" class="btn btn-outline-primary btn-action">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>

            <!-- Additional Info -->
            <div class="action-card">
                <h5><i class="fas fa-clock"></i> Risk Info</h5>
                <div class="detail-item">
                    <small class="text-muted">Created:</small><br>
                    <strong>{{ $risks->created_at->format('d/m/Y H:i') }}</strong>
                </div>
                <div class="detail-item">
                    <small class="text-muted">Last Updated:</small><br>
                    <strong>{{ $risks->updated_at->format('d/m/Y H:i') }}</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle"></i> Confirm Delete</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="{{ url('risks/destroy') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="id" value="{{ $risks->id }}">
                    <div class="modal-body">
                        <p class="mb-3">Are you sure you want to delete this risk?</p>
                        <div class="alert alert-warning">
                            <strong>Risk:</strong> {{ Str::limit($risks->risk, 100) }}
                        </div>
                        <p class="text-danger"><strong>This action cannot be undone!</strong></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    <script>
        function exportToPDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            // Header
            doc.setFillColor(102, 126, 234);
            doc.rect(0, 0, 210, 40, 'F');
            doc.setTextColor(255, 255, 255);
            doc.setFontSize(24);
            doc.text('Risk Details', 105, 20, { align: 'center' });
            doc.setFontSize(12);
            doc.text('{{ $risks->project->name }}', 105, 30, { align: 'center' });

            // Reset text color
            doc.setTextColor(0, 0, 0);

            let yPos = 50;

            // Risk Information
            doc.setFontSize(16);
            doc.setTextColor(102, 126, 234);
            doc.text('Risk Information', 14, yPos);
            yPos += 10;

            doc.setFontSize(11);
            doc.setTextColor(0, 0, 0);

            const infoData = [
                ['PR Number', '{{ $risks->project->pr_number }}'],
                ['Project Name', '{{ $risks->project->name }}'],
                ['Impact', '{{ ucfirst($risks->impact) }}'],
                ['Owner', '{{ $risks->owner ?? "N/A" }}'],
                ['Status', '{{ ucfirst($risks->status) }}']
            ];

            doc.autoTable({
                body: infoData,
                startY: yPos,
                theme: 'striped',
                headStyles: { fillColor: [102, 126, 234] },
                margin: { left: 14, right: 14 }
            });

            yPos = doc.lastAutoTable.finalY + 15;

            // Risk Description
            doc.setFontSize(16);
            doc.setTextColor(102, 126, 234);
            doc.text('Risk/Issue Description', 14, yPos);
            yPos += 10;

            doc.setFontSize(11);
            doc.setTextColor(0, 0, 0);
            const riskText = `{{ $risks->risk }}`;
            const splitRisk = doc.splitTextToSize(riskText, 180);
            doc.text(splitRisk, 14, yPos);
            yPos += (splitRisk.length * 7) + 15;

            @if($risks->mitigation)
            // Check if we need a new page
            if (yPos > 250) {
                doc.addPage();
                yPos = 20;
            }

            // Mitigation Plan
            doc.setFontSize(16);
            doc.setTextColor(102, 126, 234);
            doc.text('Mitigation/Action Plan', 14, yPos);
            yPos += 10;

            doc.setFontSize(11);
            doc.setTextColor(0, 0, 0);
            const mitigationText = `{{ $risks->mitigation }}`;
            const splitMitigation = doc.splitTextToSize(mitigationText, 180);
            doc.text(splitMitigation, 14, yPos);
            @endif

            // Footer
            const pageCount = doc.internal.getNumberOfPages();
            for (let i = 1; i <= pageCount; i++) {
                doc.setPage(i);
                doc.setFontSize(10);
                doc.setTextColor(128, 128, 128);
                doc.text(`Page ${i} of ${pageCount}`, 105, 290, { align: 'center' });
                doc.text('Generated on {{ date("d/m/Y H:i") }}', 14, 290);
            }

            doc.save('risk_{{ $risks->id }}_details.pdf');
        }

        function exportToExcel() {
            const data = [
                ['Risk Details Report'],
                [''],
                ['PR Number', '{{ $risks->project->pr_number }}'],
                ['Project Name', '{{ $risks->project->name }}'],
                ['Impact', '{{ ucfirst($risks->impact) }}'],
                ['Owner', '{{ $risks->owner ?? "N/A" }}'],
                ['Status', '{{ ucfirst($risks->status) }}'],
                [''],
                ['Risk/Issue Description'],
                ['{{ $risks->risk }}'],
                [''],
                @if($risks->mitigation)
                ['Mitigation/Action Plan'],
                ['{{ $risks->mitigation }}'],
                [''],
                @endif
                ['Created', '{{ $risks->created_at->format("d/m/Y H:i") }}'],
                ['Updated', '{{ $risks->updated_at->format("d/m/Y H:i") }}']
            ];

            const ws = XLSX.utils.aoa_to_sheet(data);
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, 'Risk Details');
            XLSX.writeFile(wb, 'risk_{{ $risks->id }}_details.xlsx');
        }
    </script>
@endsection

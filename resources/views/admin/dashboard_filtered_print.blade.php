<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filtered Dashboard Print</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            size: A4 portrait;
            margin: 8mm;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 8px;
            line-height: 1.4;
            color: #2c3e50;
            background: #fff;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .page-header {
            text-align: center;
            margin-bottom: 8px;
            padding-bottom: 8px;
            border-bottom: 3px solid #007bff;
        }

        .page-header h1 {
            color: #007bff;
            font-size: 18px;
            margin-bottom: 3px;
        }

        .page-header .subtitle {
            color: #6c757d;
            font-size: 10px;
            font-weight: 600;
        }

        .page-header .print-date {
            color: #6c757d;
            font-size: 8px;
            margin-top: 3px;
        }

        /* Project Card */
        .project-card {
            border: 2px solid #007bff;
            border-radius: 8px;
            margin-bottom: 10px;
            page-break-inside: avoid;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            padding: 8px 12px;
        }

        .card-header h3 {
            font-size: 12px;
            margin: 0;
        }

        .card-header .pr-badge {
            background: white;
            color: #007bff;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
            margin-left: 6px;
        }

        .card-body {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            padding: 10px;
        }

        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 6px;
            margin-bottom: 10px;
        }

        .info-box {
            background: white;
            padding: 8px;
            border-radius: 4px;
            border-top: 2px solid #667eea;
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
        }

        .info-box.pm { border-top-color: #28a745; }
        .info-box.value { border-top-color: #ffc107; }
        .info-box.date { border-top-color: #17a2b8; }
        .info-box.tech { border-top-color: #6f42c1; }

        .info-box .label {
            color: #6c757d;
            font-weight: 600;
            font-size: 6px;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .info-box .value {
            color: #2c3e50;
            font-weight: 600;
            font-size: 8px;
        }

        .info-box i {
            font-size: 10px;
            margin-right: 3px;
        }

        /* Progress Section */
        .progress-section {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border: 1px solid #e9ecef;
            margin-bottom: 8px;
        }

        .progress-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .progress-title {
            font-size: 10px;
            font-weight: 700;
            color: #2c3e50;
        }

        .progress-title i {
            color: #28a745;
            margin-right: 4px;
        }

        .progress-percentage {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            font-size: 14px;
            font-weight: 700;
            padding: 6px 12px;
            border-radius: 6px;
            box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
            min-width: 50px;
            text-align: center;
        }

        /* Progress Bar */
        .progress-bar-container {
            background: #e9ecef;
            height: 16px;
            border-radius: 8px;
            overflow: hidden;
            position: relative;
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 8px;
        }

        .progress-bar-fill {
            background: linear-gradient(90deg, #28a745 0%, #34ce57 100%);
            height: 100%;
            border-radius: 8px;
            transition: width 0.6s ease;
            box-shadow: 0 1px 4px rgba(40, 167, 69, 0.4);
        }

        /* Stats Boxes */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 6px;
            margin-bottom: 8px;
        }

        .stat-box {
            padding: 8px;
            border-radius: 6px;
            border-left: 3px solid;
        }

        .stat-box.pending {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeeba 100%);
            border-left-color: #ffc107;
        }

        .stat-box.total {
            background: linear-gradient(135deg, #e2e3e5 0%, #d6d8db 100%);
            border-left-color: #6c757d;
        }

        .stat-box .stat-label {
            font-size: 7px;
            font-weight: 600;
            margin-bottom: 3px;
        }

        .stat-box .stat-value {
            font-size: 18px;
            font-weight: 700;
        }

        .stat-box.pending .stat-value { color: #ffc107; }
        .stat-box.total .stat-value { color: #495057; }

        /* Expected Date Box */
        .expected-date {
            background: white;
            padding: 8px 10px;
            border-radius: 6px;
            border-left: 3px solid #17a2b8;
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
            margin-bottom: 8px;
        }

        .expected-date .date-label {
            color: #6c757d;
            font-weight: 600;
            font-size: 7px;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .expected-date .date-value {
            color: #2c3e50;
            font-weight: 600;
            font-size: 9px;
        }

        .expected-date i {
            color: #17a2b8;
            font-size: 10px;
            margin-right: 4px;
        }

        /* Details Sections */
        .details-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 5px;
        }

        .detail-card {
            padding: 8px;
            border-radius: 6px;
            color: white;
        }

        .detail-card.tasks {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }

        .detail-card.risks {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        }

        .detail-card.milestones {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        }

        .detail-card.invoices {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
        }

        .detail-card .card-title {
            font-size: 7px;
            font-weight: 600;
            opacity: 0.9;
            margin-bottom: 5px;
        }

        .detail-card .card-content {
            font-size: 7px;
            line-height: 1.4;
            max-height: 60px;
            overflow: hidden;
        }

        .detail-item {
            padding: 3px 0;
            border-bottom: 1px solid rgba(255,255,255,0.15);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-name {
            font-weight: 600;
            flex: 1;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .detail-arrow {
            font-size: 6px;
            opacity: 0.7;
            margin: 0 3px;
        }

        .detail-assigned {
            background: rgba(255,255,255,0.3);
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 6px;
            font-weight: 600;
            white-space: nowrap;
        }

        .detail-summary {
            opacity: 0.85;
            font-weight: 600;
            margin-top: 5px;
            font-size: 7px;
        }

        .no-data {
            opacity: 0.7;
            padding: 5px 0;
            font-size: 7px;
        }

        /* Page Footer */
        .page-footer {
            position: fixed;
            bottom: 5mm;
            left: 0;
            right: 0;
            text-align: center;
            color: #007bff;
            font-weight: bold;
            font-size: 8px;
            padding: 3px 0;
            border-top: 1px solid #e9ecef;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .project-card {
                page-break-inside: avoid;
            }

            @page {
                size: A4 portrait;
                margin: 8mm;
            }
        }

        @media screen {
            body {
                background: #f5f5f5;
                padding: 15px;
                max-width: 210mm;
                margin: 0 auto;
            }
        }
    </style>
</head>
<body>

<!-- Page Header -->
<div class="page-header">
    <h1><i class="fas fa-project-diagram"></i> Filtered Dashboard Report</h1>
    <div class="subtitle">Project Progress Overview</div>
    <div class="print-date">
        <i class="fas fa-calendar"></i> Printed on: {{ now()->format('Y-m-d H:i:s') }}
    </div>
</div>

<!-- Projects List -->
@forelse ($filteredProjects as $project)
    @php
        // Calculate task statistics
        $totalTasks = $project->tasks->count();
        $completedTasks = $project->tasks->whereIn('status', ['Completed', 'completed', 'Done', 'done'])->count();
        $pendingTasks = $project->tasks->whereIn('status', ['Pending', 'pending', 'In Progress', 'in progress'])->count();
        $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 1) : 0;
    @endphp

    <div class="project-card">
        <!-- Card Header -->
        <div class="card-header">
            <h3>
                <i class="fas fa-project-diagram"></i> {{ $project->name }}
                <span class="pr-badge">PR# {{ $project->pr_number }}</span>
            </h3>
        </div>

        <!-- Card Body -->
        <div class="card-body">
            <!-- Project Info Grid -->
            <div class="info-grid">
                <div class="info-box">
                    <div class="label">
                        <i class="fas fa-building" style="color: #667eea;"></i> Customer
                    </div>
                    <div class="value">{{ $project->cust->name ?? 'N/A' }}</div>
                </div>
                <div class="info-box pm">
                    <div class="label">
                        <i class="fas fa-user-tie" style="color: #28a745;"></i> PM
                    </div>
                    <div class="value">{{ $project->ppms->name ?? 'N/A' }}</div>
                </div>
                <div class="info-box value">
                    <div class="label">
                        <i class="fas fa-dollar-sign" style="color: #ffc107;"></i> Value
                    </div>
                    <div class="value">{{ number_format($project->value ?? 0, 0) }} SAR</div>
                </div>
                <div class="info-box date">
                    <div class="label">
                        <i class="fas fa-calendar-alt" style="color: #17a2b8;"></i> PO Date
                    </div>
                    <div class="value">{{ $project->customer_po_date ?? 'N/A' }}</div>
                </div>
                <div class="info-box tech">
                    <div class="label">
                        <i class="fas fa-code" style="color: #6f42c1;"></i> Technologies
                    </div>
                    <div class="value">{{ $project->technologies ?? 'N/A' }}</div>
                </div>
            </div>

            <!-- Progress Section -->
            <div class="progress-section">
                <div class="progress-header">
                    <div class="progress-title">
                        <i class="fas fa-chart-line"></i> Project Progress
                    </div>
                    <div class="progress-percentage">
                        {{ $progress }}%
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="progress-bar-container">
                    <div class="progress-bar-fill" style="width: {{ $progress }}%;"></div>
                </div>

                <!-- Expected Completion Date -->
                <div class="expected-date">
                    <div class="date-label">
                        <i class="fas fa-calendar-check"></i> Expected Completion Date
                    </div>
                    <div class="date-value">{{ $project->customer_po_deadline ?? 'Not Set' }}</div>
                </div>

                <!-- Stats Grid -->
                <div class="stats-grid">
                    <div class="stat-box pending">
                        <div class="stat-label">
                            <i class="fas fa-clock"></i> PENDING
                        </div>
                        <div class="stat-value">{{ $pendingTasks }}</div>
                    </div>
                    <div class="stat-box total">
                        <div class="stat-label">
                            <i class="fas fa-list"></i> TOTAL TASKS
                        </div>
                        <div class="stat-value">{{ $totalTasks }}</div>
                    </div>
                </div>
            </div>

            <!-- Details Grid -->
            <div class="details-grid">
                {{-- Tasks --}}
                <div class="detail-card tasks">
                    <div class="card-title">Tasks</div>
                    <div class="card-content">
                        @php
                            $pendingTasksList = $project->tasks->whereIn('status', ['Pending', 'pending', 'In Progress', 'in progress'])->take(4);
                        @endphp
                        @if($pendingTasksList->count() > 0)
                            @foreach($pendingTasksList as $task)
                                <div class="detail-item">
                                    <span class="detail-name">{{ $task->details ?? 'Task' }}</span>
                                    <i class="fas fa-long-arrow-alt-right detail-arrow"></i>
                                    <span class="detail-assigned">{{ $task->assigned ?? 'N/A' }}</span>
                                </div>
                            @endforeach
                        @else
                            <div class="no-data">No pending tasks</div>
                        @endif
                        <div class="detail-summary">{{ $pendingTasks }}/{{ $totalTasks }} Pending</div>
                    </div>
                </div>

                {{-- Risks --}}
                <div class="detail-card risks">
                    <div class="card-title">Risks</div>
                    <div class="card-content">
                        @php
                            $risksList = $project->risks->take(4);
                        @endphp
                        @if($risksList->count() > 0)
                            @foreach($risksList as $risk)
                                <div class="detail-item">
                                    <span class="detail-name">{{ $risk->risk ?? 'Risk' }}</span>
                                    <i class="fas fa-long-arrow-alt-right detail-arrow"></i>
                                    <span class="detail-assigned">{{ $risk->impact ?? 'N/A' }}</span>
                                </div>
                            @endforeach
                        @else
                            <div class="no-data">No risks recorded</div>
                        @endif
                        <div class="detail-summary">{{ $project->risks->count() }} Total</div>
                    </div>
                </div>

                {{-- Milestones --}}
                <div class="detail-card milestones">
                    <div class="card-title">Milestones</div>
                    <div class="card-content">
                        @php
                            $milestonesList = $project->milestones->take(4);
                        @endphp
                        @if($milestonesList->count() > 0)
                            @foreach($milestonesList as $milestone)
                                <div class="detail-item">
                                    <span class="detail-name">{{ $milestone->milestone ?? 'Milestone' }}</span>
                                    <i class="fas fa-long-arrow-alt-right detail-arrow"></i>
                                    <span class="detail-assigned">{{ $milestone->status ?? 'N/A' }}</span>
                                </div>
                            @endforeach
                        @else
                            <div class="no-data">No milestones</div>
                        @endif
                        <div class="detail-summary">{{ $project->milestones->count() }} Total</div>
                    </div>
                </div>

                {{-- Invoices --}}
                <div class="detail-card invoices">
                    <div class="card-title">Invoices</div>
                    <div class="card-content">
                        @php
                            $invoicesList = $project->invoices->take(4);
                        @endphp
                        @if($invoicesList->count() > 0)
                            @foreach($invoicesList as $invoice)
                                <div class="detail-item">
                                    <span class="detail-name">{{ $invoice->invoice_number ?? 'Invoice' }}</span>
                                    <i class="fas fa-long-arrow-alt-right detail-arrow"></i>
                                    <span class="detail-assigned">{{ $invoice->status ?? 'N/A' }}</span>
                                </div>
                            @endforeach
                        @else
                            <div class="no-data">No invoices</div>
                        @endif
                        <div class="detail-summary">{{ $project->invoices->count() }} Total</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@empty
    <div style="text-align: center; padding: 40px; color: #6c757d;">
        <i class="fas fa-info-circle" style="font-size: 32px; margin-bottom: 10px;"></i>
        <p style="font-size: 12px;">No projects found with the applied filters.</p>
    </div>
@endforelse

<!-- Page Footer -->
<div class="page-footer">
    <div>MDS - Project Management Dashboard</div>
</div>

<script>
    // Auto-trigger print when page loads
    window.addEventListener('load', function() {
        setTimeout(function() {
            window.print();
        }, 500);
    });

    // Close window after printing or canceling
    window.addEventListener('afterprint', function() {
        window.close();
    });
</script>

</body>
</html>

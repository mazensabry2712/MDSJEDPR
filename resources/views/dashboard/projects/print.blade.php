<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects Print View</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            size: A4 portrait;
            margin: 10mm;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 9px;
            line-height: 1.3;
            color: #000;
            background: #fff;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            background: white;
            page-break-after: always;
            position: relative;
            padding: 10mm;
        }

        .page-header {
            text-align: center;
            margin-bottom: 5mm;
        }

        .page-header h1 {
            color: #677eea;
            font-size: 14px;
            margin-bottom: 3px;
        }

        .page-header .date {
            color: #787878;
            font-size: 8px;
        }

        .page-footer {
            position: absolute;
            bottom: 5mm;
            left: 0;
            right: 0;
            text-align: center;
            color: #677eea;
            font-weight: bold;
            font-size: 9px;
        }

        .card {
            width: 190mm;
            height: 52mm;
            border: 1.5px solid #677eea;
            border-radius: 3px;
            margin-bottom: 1.5mm;
            page-break-inside: avoid;
            background: white;
            position: relative;
        }

        .card-header {
            background: #677eea;
            color: white;
            padding: 1.5mm 3mm;
            border-radius: 3px 3px 0 0;
            height: 8mm;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-header .pr-number {
            font-weight: bold;
            font-size: 9px;
        }

        .card-header .project-index {
            font-size: 7px;
        }

        .card-body {
            padding: 2mm 5mm;
        }

        .project-name {
            font-size: 9px;
            font-weight: bold;
            color: #323232;
            margin-bottom: 2mm;
        }

        .columns {
            display: flex;
            gap: 2mm;
        }

        .column {
            flex: 1;
        }

        .field {
            margin-bottom: 1mm;
            font-size: 6px;
        }

        .field-label {
            font-weight: bold;
            color: #505050;
            display: inline-block;
            min-width: 32mm;
        }

        .field-value {
            color: #000;
            display: inline-block;
        }

        .field-value.value-amount {
            color: #008000;
        }

        .description {
            margin-top: 1.5mm;
            padding-top: 1mm;
            border-top: 1px solid #e0e0e0;
            font-size: 6px;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .page {
                margin: 0;
                width: 210mm;
                height: 297mm;
                padding: 10mm;
            }

            .card {
                page-break-inside: avoid;
            }

            .page:last-child {
                page-break-after: avoid;
            }

            @page {
                size: A4 portrait;
                margin: 0;
            }

            /* Hide scrollbars */
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }

        @media screen {
            body {
                background: #f5f5f5;
                padding: 20px;
            }

            .page {
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                margin-bottom: 20px;
            }
        }
    </style>
</head>
<body>

<script>
    // Auto-trigger print when page loads
    window.addEventListener('load', function() {
        // Small delay to ensure page is fully rendered
        setTimeout(function() {
            window.print();
        }, 500);
    });

    // Close window after printing or canceling
    window.addEventListener('afterprint', function() {
        window.close();
    });

    // Fallback: if window can't close (opened directly), show message
    setTimeout(function() {
        if (!window.opener && document.visibilityState === 'visible') {
            // Window is still open after 5 seconds, probably can't auto-close
            console.log('Print dialog closed. You can close this tab manually.');
        }
    }, 5000);
</script>

@php
    $chunked = $projects->chunk(5);
@endphp

@foreach($chunked as $pageIndex => $pageProjects)
<div class="page">
    <!-- Page Header -->
    <div class="page-header">
        <h1>Projects Report</h1>
        <div class="date">Generated: {{ date('d/m/Y g:i A') }}</div>
    </div>

    <!-- Cards -->
    @foreach($pageProjects as $index => $project)
    @php
        $globalIndex = ($pageIndex * 5) + $loop->iteration;
        // Use loaded relationships, not queries
        $allVendors = $project->vendors->pluck('vendors')->implode(', ') ?: 'N/A';
        $allCustomers = $project->customers->pluck('name')->implode(', ') ?: 'N/A';
        $allDS = $project->deliverySpecialists->pluck('dsname')->implode(', ') ?: 'N/A';
    @endphp

    <div class="card">
        <!-- Card Header -->
        <div class="card-header">
            <span class="pr-number">PR: {{ $project->pr_number ?? 'N/A' }}</span>
            <span class="project-index">Project #{{ $globalIndex }}</span>
        </div>

        <!-- Card Body -->
        <div class="card-body">
            <div class="project-name">{{ $project->name ?? 'N/A' }}</div>

            <div class="columns">
                <!-- Left Column -->
                <div class="column">
                    <div class="field">
                        <span class="field-label">Technologies:</span>
                        <span class="field-value">{{ $project->technologies ?? 'N/A' }}</span>
                    </div>
                    <div class="field">
                        <span class="field-label">Primary Vendor:</span>
                        <span class="field-value">{{ optional($project->vendor)->vendors ?? 'N/A' }}</span>
                    </div>
                    <div class="field">
                        <span class="field-label">All Vendors:</span>
                        <span class="field-value">{{ $allVendors }}</span>
                    </div>
                    <div class="field">
                        <span class="field-label">Primary Customer:</span>
                        <span class="field-value">{{ optional($project->cust)->name ?? 'N/A' }}</span>
                    </div>
                    <div class="field">
                        <span class="field-label">All Customers:</span>
                        <span class="field-value">{{ $allCustomers }}</span>
                    </div>
                </div>

                <!-- Middle Column -->
                <div class="column">
                    <div class="field">
                        <span class="field-label">Value:</span>
                        <span class="field-value value-amount">{{ $project->value ? number_format($project->value, 2) . ' SAR' : 'N/A' }}</span>
                    </div>
                    <div class="field">
                        <span class="field-label">AC Manager:</span>
                        <span class="field-value">{{ optional($project->aams)->name ?? 'N/A' }}</span>
                    </div>
                    <div class="field">
                        <span class="field-label">Project Manager:</span>
                        <span class="field-value">{{ optional($project->ppms)->name ?? 'N/A' }}</span>
                    </div>
                    <div class="field">
                        <span class="field-label">Primary DS:</span>
                        <span class="field-value">{{ optional($project->ds)->dsname ?? 'N/A' }}</span>
                    </div>
                    <div class="field">
                        <span class="field-label">All DS:</span>
                        <span class="field-value">{{ $allDS }}</span>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="column">
                    <div class="field">
                        <span class="field-label">PO Date:</span>
                        <span class="field-value">{{ $project->customer_po_date ? date('d/m/Y', strtotime($project->customer_po_date)) : 'N/A' }}</span>
                    </div>
                    <div class="field">
                        <span class="field-label">Duration:</span>
                        <span class="field-value">{{ $project->customer_po_duration ? $project->customer_po_duration . ' days' : 'N/A' }}</span>
                    </div>
                    <div class="field">
                        <span class="field-label">Contact:</span>
                        <span class="field-value">{{ $project->customer_contact_details ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="description">
                <span class="field-label">Description:</span>
                <span class="field-value">{{ Str::limit($project->description ?? 'N/A', 100) }}</span>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Page Footer -->
    <div class="page-footer">
        MDSJEDPR
    </div>
</div>
@endforeach

</body>
</html>

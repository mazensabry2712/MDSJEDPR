<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoices - Print</title>
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

        .card-header .invoice-number {
            font-weight: bold;
            font-size: 9px;
        }

        .card-header .invoice-index {
            font-size: 7px;
        }

        .card-body {
            padding: 2mm 5mm;
        }

        .columns {
            display: flex;
            gap: 2mm;
        }

        .column {
            flex: 1;
        }

        .field {
            margin-bottom: 1.5mm;
            font-size: 8px;
        }

        .field-label {
            font-weight: bold;
            color: #505050;
            display: inline-block;
            min-width: 40mm;
        }

        .field-value {
            color: #000;
            display: inline-block;
        }

        .field-value.value-amount {
            color: #008000;
            font-weight: bold;
        }

        .field-value.value-total {
            color: #000080;
        }

        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background-color: #677EEA;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            z-index: 1000;
        }

        .print-btn:hover {
            background-color: #5668d3;
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

            .print-btn {
                display: none;
            }

            .no-print {
                display: none;
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

<button onclick="window.print()" class="print-btn no-print">🖨️ Print</button>

<script>
    // Auto-trigger print when page loads
    window.addEventListener('load', function() {
        // Small delay to ensure page is fully rendered
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
            console.log('Print dialog closed. You can close this tab manually.');
        }
    }, 5000);
</script>

@php
    $chunked = $invoices->chunk(5);
@endphp

@foreach($chunked as $pageIndex => $pageInvoices)
<div class="page">
    <!-- Page Header -->
    <div class="page-header">
        <h1>Invoices Report</h1>
        <div class="date">Generated: {{ date('d/m/Y g:i A') }}</div>
    </div>

    <!-- Cards -->
    @foreach($pageInvoices as $index => $invoice)
    @php
        $globalIndex = ($pageIndex * 5) + $loop->iteration;
    @endphp

    <div class="card">
        <!-- Card Header -->
        <div class="card-header">
            <span class="invoice-number">Invoice: {{ $invoice->invoice_number ?? 'N/A' }}</span>
            <span class="invoice-index">Invoice #{{ $globalIndex }}</span>
        </div>

        <!-- Card Body -->
        <div class="card-body">
            <div class="columns">
                <!-- Left Column -->
                <div class="column">
                    <div class="field">
                        <span class="field-label">Invoice Number:</span>
                        <span class="field-value">{{ $invoice->invoice_number ?? 'N/A' }}</span>
                    </div>
                    <div class="field">
                        <span class="field-label">PR Number:</span>
                        <span class="field-value">{{ $invoice->project->pr_number ?? 'N/A' }}</span>
                    </div>
                    <div class="field">
                        <span class="field-label">Project Name:</span>
                        <span class="field-value">{{ Str::limit($invoice->project->name ?? 'N/A', 35) }}</span>
                    </div>
                    <div class="field">
                        <span class="field-label">Invoice Value:</span>
                        <span class="field-value value-amount">{{ $invoice->value ? number_format($invoice->value, 2) . ' SAR' : 'N/A' }}</span>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="column">
                    <div class="field">
                        <span class="field-label">PR Invoices Total:</span>
                        <span class="field-value value-total">
                            {{ number_format($invoice->pr_invoices_total_value, 2) }}
                            @if($invoice->project && $invoice->project->value)
                                of {{ number_format($invoice->project->value, 2) }}
                            @endif
                            SAR
                        </span>
                    </div>
                    <div class="field">
                        <span class="field-label">Status:</span>
                        <span class="field-value">{{ $invoice->status ?? 'N/A' }}</span>
                    </div>
                    <div class="field">
                        <span class="field-label">Created Date:</span>
                        <span class="field-value">{{ $invoice->created_at ? $invoice->created_at->format('d/m/Y') : 'N/A' }}</span>
                    </div>
                </div>
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

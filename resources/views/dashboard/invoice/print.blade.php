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

        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #677EEA;
            padding-bottom: 15px;
        }

        .system-name {
            font-size: 24px;
            font-weight: bold;
            color: #677EEA;
            margin-bottom: 5px;
        }

        .title {
            font-size: 18px;
            color: #333;
            margin-bottom: 5px;
        }

        .date {
            font-size: 12px;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 11px;
        }

        th {
            background-color: #677EEA;
            color: white;
            padding: 10px 8px;
            text-align: left;
            border: 1px solid #ddd;
            font-weight: bold;
        }

        td {
            padding: 8px;
            border: 1px solid #ddd;
        }

        tr:nth-child(even) {
            background-color: #f5f5f5;
        }

        tr:hover {
            background-color: #e8f4f8;
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
        }

        .print-btn:hover {
            background-color: #5668d3;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
        }

        .badge-success {
            background-color: #28a745;
            color: white;
        }

        .badge-warning {
            background-color: #ffc107;
            color: #333;
        }

        .badge-danger {
            background-color: #dc3545;
            color: white;
        }

        .badge-info {
            background-color: #17a2b8;
            color: white;
        }

        .badge-secondary {
            background-color: #6c757d;
            color: white;
        }

        .badge-primary {
            background-color: #007bff;
            color: white;
        }

        @media print {
            body {
                margin: 0;
                padding: 10px;
            }

            .print-btn {
                display: none;
            }

            .no-print {
                display: none;
            }

            table {
                page-break-inside: auto;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
        }
    </style>
</head>
<body>
    <button onclick="window.print()" class="print-btn no-print">🖨️ Print</button>

    <div class="header">
        <div class="system-name">MDSJEDPR</div>
        <div class="title">Invoices Management</div>
        <div class="date">Generated: {{ date('m/d/Y, g:i:s A') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>PR Number</th>
                <th>Project Name</th>
                <th>Invoice Number</th>
                <th>Value</th>
                <th>PR Total Value</th>
                <th>Project Value</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoices as $index => $invoice)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $invoice->project->pr_number ?? 'N/A' }}</td>
                    <td>{{ $invoice->project->name ?? 'N/A' }}</td>
                    <td>{{ $invoice->invoice_number }}</td>
                    <td>{{ number_format($invoice->value, 2) }} SAR</td>
                    <td>{{ number_format($invoice->pr_invoices_total_value, 2) }} SAR</td>
                    <td>{{ number_format($invoice->project->value ?? 0, 2) }} SAR</td>
                    <td>
                        @php
                            $statusLower = strtolower($invoice->status);
                            $badgeClass = 'badge-primary';

                            if (str_contains($statusLower, 'paid') || str_contains($statusLower, 'complete')) {
                                $badgeClass = 'badge-success';
                            } elseif (str_contains($statusLower, 'pending') || str_contains($statusLower, 'waiting') || str_contains($statusLower, 'processing')) {
                                $badgeClass = 'badge-warning';
                            } elseif (str_contains($statusLower, 'overdue') || str_contains($statusLower, 'late')) {
                                $badgeClass = 'badge-danger';
                            } elseif (str_contains($statusLower, 'cancel') || str_contains($statusLower, 'reject')) {
                                $badgeClass = 'badge-secondary';
                            }
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ $invoice->status }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 20px; color: #999;">
                        No invoices available
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <script>
        // Auto-print when page loads
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        }
    </script>
</body>
</html>

<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Project EPO</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: white;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #677EEA;
        }

        .header h1 {
            color: #677EEA;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .header h2 {
            color: #333;
            font-size: 20px;
            margin-bottom: 5px;
        }

        .header .date {
            color: #666;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 12px;
        }

        th {
            background-color: #677EEA;
            color: white;
            padding: 12px 8px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #ddd;
        }

        td {
            padding: 10px 8px;
            border: 1px solid #ddd;
            text-align: left;
        }

        tbody tr:nth-child(even) {
            background-color: #f5f5f5;
        }

        tbody tr:hover {
            background-color: #e9ecef;
        }

        .print-button {
            position: fixed;
            top: 20px;
            left: 20px;
            background-color: #677EEA;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            z-index: 1000;
        }

        .print-button:hover {
            background-color: #5568d3;
        }

        @media print {
            .print-button {
                display: none;
            }

            body {
                padding: 0;
            }

            @page {
                size: landscape;
                margin: 10mm;
            }
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
            color: white;
        }

        .badge-success {
            background-color: #28a745;
        }

        .badge-warning {
            background-color: #ffc107;
            color: #000;
        }

        .badge-danger {
            background-color: #dc3545;
        }

        .badge-secondary {
            background-color: #6c757d;
        }
    </style>
</head>
<body>
    <button class="print-button" onclick="window.print()">
        <i class="fas fa-print"></i> Print
    </button>

    <div class="header">
        <h1>MDSJEDPR</h1>
        <h2>Project EPO Management</h2>
        <div class="date">
            Generated: {{ \Carbon\Carbon::now()->format('m/d/Y, g:i:s A') }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th>PR Number</th>
                <th>Project Name</th>
                <th>Category</th>
                <th>Planned Cost</th>
                <th>Selling Price</th>
                <th>Margin (%)</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pepo as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->project->pr_number ?? 'N/A' }}</td>
                    <td>{{ $item->project->name ?? 'N/A' }}</td>
                    <td>{{ $item->category ?? 'N/A' }}</td>
                    <td class="text-right">{{ $item->planned_cost ? number_format($item->planned_cost, 2) : 'N/A' }}</td>
                    <td class="text-right">{{ $item->selling_price ? number_format($item->selling_price, 2) : 'N/A' }}</td>
                    <td class="text-center">
                        @if($item->margin !== null)
                            <span class="badge badge-{{ $item->margin >= 0.2 ? 'success' : ($item->margin >= 0.1 ? 'warning' : 'danger') }}">
                                {{ number_format($item->margin * 100, 2) }}%
                            </span>
                        @else
                            <span class="badge badge-secondary">N/A</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No data available</td>
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
        };
    </script>
</body>
</html>

<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Project Milestones</title>
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
            font-size: 10px;
            table-layout: fixed;
        }

        th {
            background-color: #677EEA;
            color: white;
            padding: 10px 6px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #ddd;
            font-size: 11px;
        }

        td {
            padding: 8px 6px;
            border: 1px solid #ddd;
            text-align: left;
            vertical-align: top;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        /* Column widths */
        th:nth-child(1), td:nth-child(1) { width: 3%; }
        th:nth-child(2), td:nth-child(2) { width: 10%; }
        th:nth-child(3), td:nth-child(3) { width: 18%; }
        th:nth-child(4), td:nth-child(4) { width: 18%; }
        th:nth-child(5), td:nth-child(5) { width: 12%; }
        th:nth-child(6), td:nth-child(6) { width: 12%; }
        th:nth-child(7), td:nth-child(7) { width: 10%; }
        th:nth-child(8), td:nth-child(8) { width: 22%; }

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

        .text-center {
            text-align: center;
        }

        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
            color: white;
            font-size: 10px;
        }

        .badge-success {
            background-color: #28a745;
        }

        .badge-warning {
            background-color: #ffc107;
            color: #000;
        }

        .text-wrap {
            word-wrap: break-word;
            white-space: normal;
            line-height: 1.4;
            max-height: none;
        }
    </style>
</head>
<body>
    <button class="print-button" onclick="window.print()">
        <i class="fas fa-print"></i> Print
    </button>

    <div class="header">
        <h1>MDSJEDPR</h1>
        <h2>Project Milestones Management</h2>
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
                <th>Milestone</th>
                <th class="text-center">Planned Date</th>
                <th class="text-center">Actual Date</th>
                <th class="text-center">Status</th>
                <th>Comments</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($milestones as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->project->pr_number ?? 'N/A' }}</td>
                    <td>{{ $item->project->name ?? 'N/A' }}</td>
                    <td>{{ $item->milestone }}</td>
                    <td class="text-center">{{ $item->planned_com ? date('Y-m-d', strtotime($item->planned_com)) : 'N/A' }}</td>
                    <td class="text-center">{{ $item->actual_com ? date('Y-m-d', strtotime($item->actual_com)) : 'N/A' }}</td>
                    <td class="text-center">
                        @if($item->status == 'on track')
                            <span class="badge badge-success">On Track</span>
                        @else
                            <span class="badge badge-warning">Delayed</span>
                        @endif
                    </td>
                    <td>
                        <div class="text-wrap">{{ $item->comments ?? '-' }}</div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">No data available</td>
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

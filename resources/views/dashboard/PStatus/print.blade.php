<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Status - Print</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
            background: white;
        }

        .print-btn {
            background-color: #677EEA;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin-bottom: 20px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .print-btn:hover {
            background-color: #5568d3;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #677EEA;
        }

        .system-name {
            color: #677EEA;
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .title {
            font-size: 20px;
            color: #333;
            margin-bottom: 8px;
        }

        .date {
            color: #666;
            font-size: 14px;
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
            padding: 10px 6px;
            text-align: left;
            font-weight: 600;
            border: 1px solid #5568d3;
        }

        th:first-child {
            text-align: center;
            width: 40px;
        }

        td {
            padding: 8px 6px;
            border: 1px solid #ddd;
        }

        td:first-child {
            text-align: center;
            font-weight: 600;
            color: #677EEA;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f0f0f0;
        }

        .percentage-cell {
            text-align: center;
            font-weight: 600;
            color: #28a745;
        }

        .cost-cell {
            text-align: right;
            font-weight: 600;
            color: #dc3545;
        }

        .date-cell {
            text-align: center;
            color: #666;
        }

        .text-truncate {
            max-width: 120px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .notes-cell {
            white-space: pre-wrap;
            word-wrap: break-word;
            max-width: 200px;
            line-height: 1.4;
        }

        .status-badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: 600;
        }

        @media print {
            .print-btn {
                display: none;
            }

            body {
                padding: 10px;
            }

            table {
                page-break-inside: auto;
                font-size: 9px;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            th, td {
                padding: 5px 3px;
            }
        }

        @page {
            size: landscape;
            margin: 15mm;
        }
    </style>
</head>
<body>
    <button onclick="window.print()" class="print-btn no-print">
        🖨️ Print
    </button>

    <div class="header">
        <div class="system-name">MDSJEDPR</div>
        <div class="title">Project Status Management</div>
        <div class="date">Generated: {{ date('m/d/Y, g:i:s A') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>PR Number</th>
                <th>Project Name</th>
                <th>Date & Time</th>
                <th>PM Name</th>
                <th>Status</th>
                <th>Actual %</th>
                <th>Expected Date</th>
                <th>Pending Cost</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pstatus as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->project->pr_number ?? 'N/A' }}</td>
                    <td class="text-truncate" title="{{ $item->project->name ?? 'N/A' }}">
                        {{ $item->project->name ?? 'N/A' }}
                    </td>
                    <td class="date-cell">
                        @if($item->date_time)
                            {{ \Carbon\Carbon::parse($item->date_time)->format('d/m/Y H:i') }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td>{{ $item->ppm->name ?? 'N/A' }}</td>
                    <td class="text-truncate" title="{{ $item->status ?? 'N/A' }}">
                        {{ $item->status ?? 'N/A' }}
                    </td>
                    <td class="percentage-cell">
                        @if($item->actual_completion)
                            {{ number_format($item->actual_completion, 2) }}%
                        @else
                            N/A
                        @endif
                    </td>
                    <td class="date-cell">
                        @if($item->expected_completion)
                            {{ \Carbon\Carbon::parse($item->expected_completion)->format('d/m/Y') }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td class="text-truncate" title="{{ $item->date_pending_cost_orders ?? 'N/A' }}">
                        {{ $item->date_pending_cost_orders ?? 'N/A' }}
                    </td>
                    <td class="notes-cell">
                        {{ $item->notes ?? 'No notes' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        }
    </script>
</body>
</html>

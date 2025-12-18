<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Tasks - Print</title>
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

        .date-cell {
            text-align: center;
            color: #666;
        }

        .text-truncate {
            max-width: 150px;
        }

        .task-details-cell {
            white-space: pre-wrap;
            word-wrap: break-word;
            max-width: 250px;
            line-height: 1.4;
        }

        .status-badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: 600;
            display: inline-block;
        }

        .status-completed {
            background-color: #28a745;
            color: white;
        }

        .status-progress {
            background-color: #ffc107;
            color: #333;
        }

        .status-pending {
            background-color: #17a2b8;
            color: white;
        }

        .status-hold {
            background-color: #6c757d;
            color: white;
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
        <div class="title">Project Tasks Management</div>
        <div class="date">Generated: {{ date('m/d/Y, g:i:s A') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>PR Number</th>
                <th>Project Name</th>
                <th>Task Date</th>
                <th>Task Details</th>
                <th>Assigned To</th>
                <th>Expected Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ptasks as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->project->pr_number ?? 'N/A' }}</td>
                    <td class="text-truncate" title="{{ $item->project->name ?? 'N/A' }}">
                        {{ $item->project->name ?? 'N/A' }}
                    </td>
                    <td class="date-cell">
                        @if($item->task_date)
                            {{ \Carbon\Carbon::parse($item->task_date)->format('d/m/Y') }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td class="task-details-cell">
                        {{ $item->details ?? 'No details' }}
                    </td>
                    <td>{{ $item->assigned ?? 'Not assigned' }}</td>
                    <td class="date-cell">
                        @if($item->expected_com_date)
                            {{ \Carbon\Carbon::parse($item->expected_com_date)->format('d/m/Y') }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        @if($item->status == 'completed')
                            <span class="status-badge status-completed">Completed</span>
                        @elseif($item->status == 'progress')
                            <span class="status-badge status-progress">Under Progress</span>
                        @elseif($item->status == 'pending')
                            <span class="status-badge status-pending">Pending</span>
                        @elseif($item->status == 'hold')
                            <span class="status-badge status-hold">On Hold</span>
                        @else
                            <span class="status-badge">{{ ucfirst($item->status) }}</span>
                        @endif
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

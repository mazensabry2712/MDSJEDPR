<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Notes - Print</title>
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
        }

        th {
            background-color: #677EEA;
            color: white;
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
            font-weight: bold;
        }

        td {
            padding: 10px 12px;
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
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
        }

        .badge-info {
            background-color: #17a2b8;
            color: white;
        }

        .badge-secondary {
            background-color: #6c757d;
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
        <div class="title">Delivery Notes Management</div>
        <div class="date">Generated: {{ date('m/d/Y, g:i:s A') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>DN Number</th>
                <th>PR Number</th>
                <th>Project Name</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dn as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->dn_number }}</td>
                    <td>{{ $item->project->pr_number ?? 'N/A' }}</td>
                    <td>
                        @if($item->project && $item->project->name)
                            <span class="badge badge-info">{{ $item->project->name }}</span>
                        @else
                            <span class="badge badge-secondary">No name available</span>
                        @endif
                    </td>
                    <td>{{ $item->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px; color: #999;">
                        No delivery notes available
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

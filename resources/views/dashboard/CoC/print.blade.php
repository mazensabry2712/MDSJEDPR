<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Compilation - Print</title>
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
        }

        th {
            background-color: #677EEA;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            border: 1px solid #5568d3;
        }

        th:first-child {
            text-align: center;
            width: 50px;
        }

        td {
            padding: 10px 12px;
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

        .project-name {
            color: #333;
            font-weight: 500;
        }

        .date-cell {
            text-align: center;
            color: #666;
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
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
        }
    </style>
</head>
<body>
    <button onclick="window.print()" class="print-btn no-print">
        🖨️ Print
    </button>

    <div class="header">
        <div class="system-name">MDSJEDPR</div>
        <div class="title">Certificate of Compilation Management</div>
        <div class="date">Generated: {{ date('m/d/Y, g:i:s A') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>PR Number</th>
                <th>Project Name</th>
                <th>Upload Date</th>
                <th>Upload Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($coc as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->project->pr_number ?? 'N/A' }}</td>
                    <td class="project-name">{{ $item->project->name ?? 'N/A' }}</td>
                    <td class="date-cell">{{ $item->created_at->format('Y-m-d') }}</td>
                    <td class="date-cell">{{ $item->created_at->format('h:i A') }}</td>
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

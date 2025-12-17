<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Purchase Orders - Print</title>
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
            font-size: 12px;
        }

        th {
            background-color: #677EEA;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-weight: 600;
            border: 1px solid #5568d3;
        }

        th:first-child {
            text-align: center;
            width: 40px;
        }

        td {
            padding: 8px;
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

        .value-cell {
            text-align: right;
            font-weight: 600;
            color: #28a745;
        }

        .date-cell {
            text-align: center;
            color: #666;
        }

        .text-truncate {
            max-width: 150px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
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
                font-size: 10px;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            th, td {
                padding: 6px 4px;
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
        <div class="title">Project Purchase Orders Management</div>
        <div class="date">Generated: {{ date('m/d/Y, g:i:s A') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>PR Number</th>
                <th>Project Name</th>
                <th>Category</th>
                <th>Supplier</th>
                <th>PO Number</th>
                <th>Value</th>
                <th>Date</th>
                <th>Status</th>
                <th>Updates</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ppos as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->project->pr_number ?? 'N/A' }}</td>
                    <td class="text-truncate" title="{{ $item->project->name ?? 'N/A' }}">
                        {{ $item->project->name ?? 'N/A' }}
                    </td>
                    <td>
                        @php
                            $allCategories = \App\Models\Ppos::where('po_number', $item->po_number)
                                ->with('pepo:id,category')
                                ->get()
                                ->pluck('pepo.category')
                                ->filter()
                                ->unique()
                                ->implode(', ');
                        @endphp
                        {{ $allCategories ?: 'N/A' }}
                    </td>
                    <td>{{ $item->ds->dsname ?? 'N/A' }}</td>
                    <td>{{ $item->po_number ?? 'N/A' }}</td>
                    <td class="value-cell">
                        @if($item->value)
                            ${{ number_format($item->value, 2) }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td class="date-cell">{{ $item->date ? $item->date->format('Y-m-d') : 'N/A' }}</td>
                    <td class="text-truncate" title="{{ $item->status ?? 'N/A' }}">
                        {{ $item->status ?? 'N/A' }}
                    </td>
                    <td class="text-truncate" title="{{ $item->updates ?? 'No updates' }}">
                        {{ $item->updates ?? 'No updates' }}
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

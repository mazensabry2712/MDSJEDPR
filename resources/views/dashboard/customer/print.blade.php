<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers - Print</title>
    <style>
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .system-name {
            font-size: 24px;
            font-weight: bold;
            color: #677EEA;
            margin-bottom: 10px;
        }
        .title {
            font-size: 20px;
            font-weight: bold;
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
            font-size: 9px;
        }
        th {
            background-color: #677EEA;
            color: white;
            padding: 8px;
            text-align: center;
            border: 1px solid #ddd;
        }
        td {
            padding: 6px;
            border: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background-color: #f5f5f5;
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
        }
    </style>
</head>
<body>
    <button onclick="window.print()" class="print-btn no-print">🖨️ Print</button>

    <div class="header">
        <div class="system-name">MDSJEDPR</div>
        <div class="title">Customers Management</div>
        <div class="date">Generated: {{ date('m/d/Y, g:i:s A') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 3%;">#</th>
                <th style="width: 15%;">Customer Name</th>
                <th style="width: 10%;">Customer Abb</th>
                <th style="width: 10%;">Customer type</th>
                <th style="width: 6%;">Logo</th>
                <th style="width: 16%;">Customer Contact name</th>
                <th style="width: 16%;">Customer contact position</th>
                <th style="width: 16%;">Email</th>
                <th style="width: 8%;">Phone</th>
            </tr>
        </thead>
        <tbody>
            @foreach($customers as $index => $customer)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $customer->name ?? 'N/A' }}</td>
                <td>{{ $customer->abb ?? 'N/A' }}</td>
                <td>{{ $customer->tybe ?? 'N/A' }}</td>
                <td style="text-align: center;">
                    @if(!empty($customer->logo) && file_exists(public_path($customer->logo)))
                        Yes
                    @else
                        No
                    @endif
                </td>
                <td>{{ $customer->customercontactname ?? 'N/A' }}</td>
                <td>{{ $customer->customercontactposition ?? 'N/A' }}</td>
                <td>{{ $customer->email ?? 'N/A' }}</td>
                <td style="text-align: center;">{{ $customer->phone ?? 'N/A' }}</td>
            </tr>
            @endforeach
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Managers - Print</title>
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
        }
        th {
            background-color: #677EEA;
            color: white;
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        td {
            padding: 8px;
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
        <div class="title">Account Managers Management</div>
        <div class="date">Generated: {{ date('m/d/Y, g:i:s A') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 30%;">Name</th>
                <th style="width: 40%;">Email</th>
                <th style="width: 25%;">Phone</th>
            </tr>
        </thead>
        <tbody>
            @foreach($aams as $index => $am)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $am->name }}</td>
                <td>{{ $am->email }}</td>
                <td style="text-align: center;">{{ $am->phone ?? 'N/A' }}</td>
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

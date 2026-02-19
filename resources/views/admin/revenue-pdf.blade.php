<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Revenue PDF</title>
     <style>
        body { font-family: Arial, sans-serif; font-size: 14px; color: #111; }
        h1 { margin-bottom: 6px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f3f4f6; }
    </style>
</head>
<body>
    <h1>Monthly Revenue Report</h1>
    <p>Month {{$report->month}}</p>
    <table>
        <thead>
           <tr>
                <th>Item</th>
                <th>Value</th>
           </tr>
        </thead>
        <tbody>
            <tr>
                <td>Gross</td> 
                <td>€{{ number_format($report->gross_cents / 100, 2) }}</td>
            </tr>
            
            <tr>
                <td>Rent</td>
                <td>€{{ number_format($report->rent_eur, 2) }}</td>
            </tr>
            <tr>
                <td>Net</td>
                <td>€{{ number_format($report->net_cents / 100, 2) }}</td>
            </tr>
            <tr>
                <td>Done count</td>
                <td>{{ $report->done_count }}</td>
            </tr>
            <tr>
                <td>Avg ticket</td>
                <td>€{{ number_format($report->avg_ticket_cents / 100, 2) }}</td>
            </tr>
        </tbody>    
    </table>
</body>
</html>
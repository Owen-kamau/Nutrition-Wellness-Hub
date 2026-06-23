<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Weekly Nutrition Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #123d29; }
        h1 { margin-bottom: 8px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #d5e6d9; padding: 6px; text-align: left; }
        th { background: #e9f6ed; }
    </style>
</head>
<body>
    <h1>Weekly Nutrition Report</h1>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Meal</th>
                <th>Food</th>
                <th>Quantity</th>
                <th>Calories</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
                <tr>
                    <td>{{ $log->consumed_on }}</td>
                    <td>{{ ucfirst($log->meal_type) }}</td>
                    <td>{{ $log->food->name }}</td>
                    <td>{{ $log->quantity }}</td>
                    <td>{{ $log->calories_consumed }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

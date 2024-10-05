<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Watering History</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Sarabun', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .container {
            margin-top: 50px;
            max-width: 900px;
            margin-left: auto;
            margin-right: auto;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        .no-data {
            text-align: center;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Watering History</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Watering Time</th>
                </tr>
            </thead>
            <tbody>
                @if(count($wateringHistory) > 0)
                    @foreach ($wateringHistory as $history)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $history->watering_time }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="2" class="no-data">No watering history available.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</body>
</html>

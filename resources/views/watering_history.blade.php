<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Watering History</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;600&display=swap');

        body {
            font-family: 'Sarabun', sans-serif;
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
        .back-button {
            margin-top: 20px;
            text-align: left;
        }
        .back-button a {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }
        .back-button a:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="back-button">
            <a href="javascript:history.back()">กลับ</a>
        </div>
        <h1>ประวัติการรดน้ำ</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>เวลาที่รดน้ำ</th>
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
                        <td colspan="2" class="no-data">ไม่มีประวัติการรดน้ำ</td>
                    </tr>
                @endif
            </tbody>
        </table>

    </div>
</body>
</html>

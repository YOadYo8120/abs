<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Attendance List - {{ $className }}</title>
    <style>
        @page {
            margin: 15mm;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11pt;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 15px;
        }

        .header h1 {
            margin: 0;
            font-size: 20pt;
            color: #1e40af;
        }

        .header h2 {
            margin: 5px 0 0 0;
            font-size: 14pt;
            color: #64748b;
            font-weight: normal;
        }

        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
        }

        .info-row {
            display: table-row;
        }

        .info-cell {
            display: table-cell;
            padding: 8px 12px;
            border-bottom: 1px solid #e2e8f0;
        }

        .info-cell:first-child {
            font-weight: bold;
            background-color: #f8fafc;
            width: 25%;
            color: #475569;
        }

        .students-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .students-table th {
            background-color: #2563eb;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #1e40af;
        }

        .students-table td {
            padding: 8px;
            border: 1px solid #cbd5e1;
        }

        .students-table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .students-table tbody tr:hover {
            background-color: #e0e7ff;
        }

        .checkbox {
            width: 15px;
            height: 15px;
            border: 2px solid #64748b;
            display: inline-block;
            margin: 0 auto;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e2e8f0;
            font-size: 9pt;
            color: #64748b;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Attendance List</h1>
        <h2>{{ $className }}</h2>
    </div>

    <div class="info-grid">
        <div class="info-row">
            <div class="info-cell">Week:</div>
            <div class="info-cell">Week {{ $week_number }}</div>
            <div class="info-cell">Day:</div>
            <div class="info-cell">{{ $day }}</div>
        </div>
        <div class="info-row">
            <div class="info-cell">Date:</div>
            <div class="info-cell">{{ $sessionDate->format('d/m/Y') }}</div>
            <div class="info-cell">Time:</div>
            <div class="info-cell">{{ $start_time }} - {{ $end_time }} ({{ $duration }})</div>
        </div>
        <div class="info-row">
            <div class="info-cell">Semester:</div>
            <div class="info-cell">Semester {{ $semester }}</div>
            <div class="info-cell">Total Students:</div>
            <div class="info-cell">{{ $students->count() }}</div>
        </div>
    </div>

    <table class="students-table">
        <thead>
            <tr>
                <th style="width: 5%;">No.</th>
                <th style="width: 12%;">Code</th>
                <th style="width: 20%;">Last Name</th>
                <th style="width: 20%;">First Name</th>
                <th style="width: 8%;">Present</th>
                <th style="width: 8%;">Absent</th>
                <th style="width: 8%;">Late</th>
                <th style="width: 19%;">Signature</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $index => $student)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $student->student_code }}</td>
                <td><strong>{{ strtoupper($student->last_name) }}</strong></td>
                <td>{{ $student->first_name }}</td>
                <td style="text-align: center;"><span class="checkbox"></span></td>
                <td style="text-align: center;"><span class="checkbox"></span></td>
                <td style="text-align: center;"><span class="checkbox"></span></td>
                <td style="border-left: 2px solid #cbd5e1;"></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Generated on {{ $generatedAt->format('d/m/Y H:i') }}
    </div>
</body>
</html>

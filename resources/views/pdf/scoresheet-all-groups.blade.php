<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Scoresheet - All Groups - {{ $event->title }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.3;
            padding: 15mm;
        }

        .page-break {
            page-break-after: always;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 3px solid #333;
        }

        .header h1 {
            font-size: 22px;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .header h2 {
            font-size: 18px;
            color: #666;
            margin-bottom: 3px;
        }

        .event-info {
            margin-bottom: 15px;
            background-color: #f5f5f5;
            padding: 10px;
            border: 1px solid #ddd;
        }

        .event-info table {
            width: 100%;
        }

        .event-info td {
            padding: 3px 10px;
        }

        .event-info-label {
            font-weight: bold;
            width: 120px;
        }

        .group-info {
            background-color: #e8e8e8;
            padding: 8px 10px;
            margin-bottom: 15px;
            border: 2px solid #333;
            text-align: center;
        }

        .group-name {
            font-size: 16px;
            font-weight: bold;
            display: inline-block;
            margin-right: 20px;
        }

        .group-category {
            font-size: 14px;
            color: #555;
            display: inline-block;
        }

        .score-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .score-table th,
        .score-table td {
            border: 2px solid #333;
            padding: 8px;
            text-align: center;
        }

        .score-table th {
            background-color: #d0d0d0;
            font-weight: bold;
            font-size: 12px;
        }

        .score-table .participant-name {
            text-align: left;
            font-weight: bold;
        }

        .score-table .score-cell {
            background-color: #fff;
            min-height: 35px;
            width: 80px;
        }

        .score-table .average-cell {
            background-color: #ffe8a1;
            font-weight: bold;
            width: 80px;
        }

        .score-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .remarks-section {
            margin-top: 20px;
            border: 2px solid #333;
            padding: 10px;
            min-height: 60px;
        }

        .remarks-label {
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 12px;
        }

        .signature-section {
            margin-top: 20px;
            display: table;
            width: 100%;
        }

        .signature-box {
            display: table-cell;
            width: 33.33%;
            padding: 10px;
            text-align: center;
        }

        .signature-line {
            border-top: 2px solid #333;
            margin-top: 50px;
            padding-top: 8px;
            font-weight: bold;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }

        .no-participants {
            text-align: center;
            padding: 30px;
            color: #888;
            font-style: italic;
            border: 2px dashed #ccc;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    @foreach($groups as $index => $group)
        <div class="header">
            <h1>{{ $event->title }}</h1>
            <h2>SCORESHEET</h2>
        </div>

        <div class="event-info">
            <table>
                <tr>
                    <td class="event-info-label">Event Date:</td>
                    <td>{{ $event->start_date->format('d M Y') }} - {{ $event->end_date->format('d M Y') }}</td>
                    <td class="event-info-label">Venue:</td>
                    <td>{{ $event->venue ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>

        <div class="group-info">
            <span class="group-name">{{ $group->name }}</span>
            <span class="group-category">
                Category: {{ $group->filtered_pesertas->first()->category ?? ($category ?? 'N/A') }}
            </span>
        </div>

        @if($group->filtered_pesertas->count() > 0)
            <table class="score-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">No.</th>
                        <th style="width: 35%;">Participant Name</th>
                        <th style="width: 12%;">Round 1</th>
                        <th style="width: 12%;">Round 2</th>
                        <th style="width: 12%;">Round 3</th>
                        <th style="width: 12%;">Average</th>
                        <th style="width: 12%;">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($group->filtered_pesertas as $participant)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="participant-name">{{ $participant->nama_penuh }}</td>
                            <td class="score-cell"></td>
                            <td class="score-cell"></td>
                            <td class="score-cell"></td>
                            <td class="average-cell"></td>
                            <td class="score-cell"></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="remarks-section">
                <div class="remarks-label">General Comments / Notes:</div>
                <div style="min-height: 40px;"></div>
            </div>

            <div class="signature-section">
                <div class="signature-box">
                    <div class="signature-line">
                        Judge 1 - Name & Signature
                    </div>
                </div>
                <div class="signature-box">
                    <div class="signature-line">
                        Judge 2 - Name & Signature
                    </div>
                </div>
                <div class="signature-box">
                    <div class="signature-line">
                        Date
                    </div>
                </div>
            </div>
        @else
            <div class="no-participants">
                No participants assigned to this group
                @if($category)
                    for category "{{ $category }}"
                @endif
            </div>
        @endif

        <div class="footer">
            Printed on: {{ now()->format('d M Y, h:i A') }}
        </div>

        @if(!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach
</body>
</html>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Scoresheet - {{ $group->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            /* Reduced from 11px */
            line-height: 1.2;
            /* Reduced from 1.3 */
            padding: 10mm;
            /* Reduced from 15mm */
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
            /* Reduced from 15px */
            padding-bottom: 8px;
            /* Reduced from 10px */
            border-bottom: 3px solid #333;
        }

        .header h1 {
            font-size: 20px;
            /* Reduced from 22px */
            margin-bottom: 3px;
            /* Reduced from 5px */
            text-transform: uppercase;
        }

        .header h2 {
            font-size: 16px;
            /* Reduced from 18px */
            color: #666;
            margin-bottom: 2px;
            /* Reduced from 3px */
        }

        .event-info {
            margin-bottom: 10px;
            /* Reduced from 15px */
            background-color: #f5f5f5;
            padding: 8px;
            /* Reduced from 10px */
            border: 1px solid #ddd;
        }

        .event-info table {
            width: 100%;
        }

        .event-info td {
            padding: 2px 8px;
            /* Reduced from 3px 10px */
        }

        .event-info-label {
            font-weight: bold;
            width: 120px;
        }

        .group-info {
            background-color: #e8e8e8;
            padding: 6px 8px;
            /* Reduced from 8px 10px */
            margin-bottom: 10px;
            /* Reduced from 15px */
            border: 2px solid #333;
            text-align: center;
        }

        .group-name {
            font-size: 14px;
            /* Reduced from 16px */
            font-weight: bold;
            display: inline-block;
            margin-right: 15px;
            /* Reduced from 20px */
        }

        .group-category {
            font-size: 12px;
            /* Reduced from 14px */
            color: #555;
            display: inline-block;
        }

        .score-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            /* Reduced from 20px */
        }

        .score-table th,
        .score-table td {
            border: 2px solid #333;
            padding: 6px;
            /* Reduced from 8px */
            text-align: center;
        }

        .score-table th {
            background-color: #d0d0d0;
            font-weight: bold;
            font-size: 11px;
            /* Reduced from 12px */
        }

        .score-table .participant-name {
            text-align: left;
            font-weight: bold;
        }

        .score-table .score-cell {
            background-color: #fff;
            min-height: 28px;
            /* Reduced from 35px */
            width: 70px;
            /* Reduced from 80px */
        }

        .score-table .average-cell {
            background-color: #ffe8a1;
            font-weight: bold;
            width: 70px;
            /* Reduced from 80px */
        }

        .score-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .remarks-section {
            margin-top: 10px;
            /* Reduced from 20px */
            border: 2px solid #333;
            padding: 8px;
            /* Reduced from 10px */
            min-height: 40px;
            /* Keep same */
        }

        .remarks-label {
            font-weight: bold;
            margin-bottom: 3px;
            /* Reduced from 5px */
            font-size: 11px;
            /* Reduced from 12px */
        }

        .signature-section {
            margin-top: 10px;
            /* Reduced from 20px */
            display: table;
            width: 100%;
        }

        .signature-box {
            display: table-cell;
            width: 33.33%;
            padding: 5px;
            /* Reduced from 10px */
            text-align: center;
            vertical-align: top;
        }

        .signature-line {
            border-top: 2px solid #333;
            margin-top: 30px;
            /* Reduced from 50px */
            padding-top: 5px;
            /* Reduced from 8px */
            font-weight: bold;
            font-size: 10px;
        }

        /* QR Code styling - COMPACT */
        .qr-code-container {
            margin-top: 8px;
            /* Reduced from 15px */
            text-align: center;
        }

        .qr-code-container img {
            width: 60px;
            /* Reduced from 100px */
            height: 60px;
            /* Reduced from 100px */
            display: block;
            margin: 0 auto;
            border: 1px solid #333;
            /* Reduced from 2px */
            padding: 3px;
            /* Reduced from 5px */
            background-color: #fff;
        }

        .qr-code-container p {
            font-size: 7px;
            /* Reduced from 8px */
            margin-top: 3px;
            /* Reduced from 5px */
            font-weight: bold;
            color: #333;
        }

        .footer {
            margin-top: 10px;
            /* Reduced from 20px */
            text-align: center;
            font-size: 9px;
            /* Reduced from 10px */
            color: #666;
        }
    </style>
</head>

<body>
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
        <span class="group-category">Category: {{ $participants->first()->category ?? 'N/A' }}</span>
    </div>

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
            @foreach($participants as $index => $participant)
                <tr>
                    <td>{{ $index + 1 }}</td>
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
            <!-- QR Code with Clickable Link -->
            <div class="qr-code-container">
                @if(!empty($group->qr_code_path) && file_exists($group->qr_code_path))
                    <img src="{{ $group->qr_code_path }}" alt="QR Code">
                    <p>SCAN TO SUBMIT</p>
                    <!-- Clickable Link for Testing -->
                    <a href="{{ url('/markah/' . $group->token) }}"
                        style="display: block; margin-top: 5px; font-size: 7px; color: #0066cc; text-decoration: none; word-break: break-all;">
                        {{ url('/markah/' . $group->token) }}
                    </a>
                @else
                    <p style="color: #999; font-size: 7px;">QR Code unavailable</p>
                @endif
            </div>
        </div>
    </div>

    <div class="footer">
        Printed on: {{ now()->format('d M Y, h:i A') }}
    </div>
</body>

</html>
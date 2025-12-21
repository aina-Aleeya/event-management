<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rankings - {{ $event->title }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            color: #000;
            padding: 20px;
            line-height: 1.4;
        }

        .header {
            border-bottom: 3px solid #000;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .header h1 {
            font-size: 20px;
            margin-bottom: 5px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .header p {
            font-size: 11px;
            color: #333;
            margin-top: 3px;
        }

        .category-section {
            margin-bottom: 35px;
            page-break-inside: avoid;
        }

        .category-title {
            background-color: #000;
            color: #fff;
            padding: 8px 12px;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
        }

        th, td {
            border: 1px solid #000;
            padding: 8px;
            font-size: 10px;
        }

        th {
            background-color: #e8e8e8;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
        }

        td {
            text-align: center;
        }

        td:nth-child(2) {
            text-align: left;
        }

        tbody tr:nth-child(even) {
            background-color: #f5f5f5;
        }

        /* Top 3 styling - simple & clean */
        .rank-1 td:first-child {
            background-color: #FFD700;
            font-weight: bold;
            font-size: 11px;
        }
        
        .rank-2 td:first-child {
            background-color: #C0C0C0;
            font-weight: bold;
            font-size: 11px;
        }
        
        .rank-3 td:first-child {
            background-color: #CD7F32;
            font-weight: bold;
            font-size: 11px;
            color: #fff;
        }

        .rank-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 2px;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #000;
            text-align: center;
            font-size: 9px;
            color: #666;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            color: #666;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $event->title }}</h1>
        <p>Rankings Report</p>
        @if($selectedCategory)
            <p style="font-weight: bold; margin-top: 5px;">Category: {{ $selectedCategory }}</p>
        @endif
        <p style="margin-top: 5px;">Generated on {{ now()->format('d M Y, h:i A') }}</p>
    </div>

    @foreach($scoresByCategory as $category => $scores)
        <div class="category-section">
            <div class="category-title">{{ $category }}</div>

            @if($scores->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th style="width: 8%;">Rank</th>
                            <th style="width: 25%;">Participant</th>
                            <th style="width: 17%;">Group</th>
                            <th style="width: 12%;">Round 1</th>
                            <th style="width: 12%;">Round 2</th>
                            <th style="width: 12%;">Round 3</th>
                            <th style="width: 14%;">Average</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($scores as $score)
                            <tr class="{{ $score->rank <= 3 ? 'rank-' . $score->rank : '' }}">
                                <td><strong>{{ $score->rank }}</strong></td>
                                <td><strong>{{ $score->peserta->nama_penuh }}</strong></td>
                                <td>{{ $score->group->name }}</td>
                                <td>{{ number_format($score->round1, 2) }}</td>
                                <td>{{ $score->round2 ? number_format($score->round2, 2) : '-' }}</td>
                                <td>{{ $score->round3 ? number_format($score->round3, 2) : '-' }}</td>
                                <td><strong>{{ number_format($score->average, 2) }}</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="no-data">No participants found in this category.</div>
            @endif
        </div>
    @endforeach

    <div class="footer">
        <p>This is a computer-generated document. No signature is required.</p>
        <p>{{ $event->title }} | Rankings Report</p>
    </div>
</body>
</html>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Participant List - {{ $event->title }}</title>
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
            line-height: 1.4;
            padding: 20px;
        }

        .header {
            border-bottom: 3px solid #000;
            padding-bottom: 15px;
            margin-bottom: 20px;
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
        }

        .event-info {
            margin-bottom: 25px;
            border: 1px solid #000;
            padding: 12px;
        }

        .event-info table {
            width: 100%;
        }

        .event-info td {
            padding: 4px 8px;
            font-size: 10px;
        }

        .event-info-label {
            font-weight: bold;
            width: 120px;
        }

        .participants-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            border: 1px solid #000;
        }

        .participants-table thead {
            background-color: #000;
            color: #fff;
        }

        .participants-table thead th {
            padding: 10px 8px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            border: 1px solid #000;
        }

        .participants-table tbody td {
            padding: 8px;
            border: 1px solid #000;
            font-size: 10px;
        }

        .participants-table tbody tr:nth-child(even) {
            background-color: #f5f5f5;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            text-align: center;
        }

        .badge-completed {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .badge-pending {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
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
            padding: 30px;
            color: #666;
            font-style: italic;
        }

        .unique-id {
            font-family: 'Courier New', monospace;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <div class="header">
        <h1>{{ $event->title }}</h1>
        <p>Participant Registration List</p>
    </div>

    <!-- Event Information -->
    <div class="event-info">
        <table>
            <tr>
                <td class="event-info-label">Event Date:</td>
                <td>{{ $event->start_date->format('d M Y') }} - {{ $event->end_date->format('d M Y') }}</td>
                <td class="event-info-label">Venue:</td>
                <td>{{ $event->venue ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="event-info-label">Generated Date:</td>
                <td>{{ $generatedDate }}</td>
                <td class="event-info-label">Total Participants:</td>
                <td><strong>{{ $totalParticipants }}</strong></td>
            </tr>
        </table>
    </div>

    <!-- Participants Table -->
    <table class="participants-table">
        <thead>
            <tr>
                <th style="width: 5%;">No.</th>
                <th style="width: 30%;">Participant Name</th>
                <th style="width: 20%;">Category</th>
                <th style="width: 18%;">Unique ID</th>
                <th style="width: 15%;">Payment Status</th>
                <th style="width: 12%;">Registration Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($participants as $index => $p)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td><strong>{{ $p->nama_penuh }}</strong></td>
                    <td>{{ $p->pivot->category_name }}</td>
                    <td><span class="unique-id">{{ $p->pivot->unique_id }}</span></td>
                    <td>
                        @if ($p->pivot->status_bayaran === 'complete')
                            <span class="badge badge-completed">Completed</span>
                        @else
                            <span class="badge badge-pending">Pending</span>
                        @endif
                    </td>
                    <td>{{ $p->pivot->created_at ? $p->pivot->created_at->format('d/m/Y') : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="no-data">
                        No participants have registered for this event yet.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p>This is a computer-generated document. No signature is required.</p>
        <p>{{ $event->title }} | Generated on {{ $generatedDate }}</p>
    </div>
</body>
</html>
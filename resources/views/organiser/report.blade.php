<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Event Report - {{ $event->title }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        h1 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f4f4f4; }
        .paid { color: green; font-weight: bold; }
        .pending { color: red; font-weight: bold; }
    </style>
</head>

<body>
    <h1>Event Report</h1>
    <p><strong>Event:</strong> {{ $event->title }}</p>
    <p><strong>Date:</strong> {{ $event->start_date->format('d/m/Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Full Name</th>
                <th>Category</th>
                <th>Payment Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($participants as $index => $p)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $p->nama_penuh }}</td>
                    <td>{{ $p->pivot->kategori_nama }}</td>
                    <td class="{{ $p->pivot->status_bayaran === 'complete' ? 'paid' : 'pending' }}">
                        {{ ucfirst($p->pivot->status_bayaran) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
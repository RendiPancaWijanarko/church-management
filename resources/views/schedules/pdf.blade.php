<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Pelayanan - {{ $monthName }} {{ $year }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid #4f46e5;
        }

        .header h1 {
            font-size: 20px;
            color: #4f46e5;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 12px;
            color: #666;
        }

        .period-info {
            background: #f3f4f6;
            padding: 10px 15px;
            margin-bottom: 20px;
            border-left: 4px solid #4f46e5;
        }

        .period-info strong {
            color: #4f46e5;
        }

        .category-section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }

        .category-header {
            background: #4f46e5;
            color: white;
            padding: 8px 12px;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        table thead {
            background: #e5e7eb;
        }

        table th {
            padding: 8px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #d1d5db;
            font-size: 10px;
        }

        table td {
            padding: 6px 8px;
            border: 1px solid #d1d5db;
            font-size: 10px;
        }

        table tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: bold;
            background: #3b82f6;
            color: white;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            font-size: 9px;
            color: #6b7280;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            color: #9ca3af;
            font-style: italic;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>JADWAL PELAYANAN GKI GRAND WISATA</h1>
        <p>Manajemen Pelayanan by CV FlyHigh Sinergi Indonesia</p>
    </div>

    <div class="period-info">
        <strong>Periode:</strong> {{ $monthName }} {{ $year }}
        @if ($session)
            &nbsp;|&nbsp;<strong>Sesi:</strong> {{ $session }}
        @endif
        &nbsp;|&nbsp;<strong>Dicetak:</strong> {{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY HH:mm') }}
    </div>

    @forelse($schedules as $categoryId => $categorySchedules)
        @php
            $category = $categories->find($categoryId);
        @endphp

        <div class="category-section">
            <div class="category-header">
                {{ $category->name }} ({{ $categorySchedules->count() }} Jadwal)
            </div>

            <table>
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="30%">Tanggal</th>
                        <th width="10%">Sesi</th>
                        <th width="25%">Pelayan</th>
                        <th width="10%">Waktu</th>
                        <th width="20%">Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categorySchedules as $index => $schedule)
                        <tr>
                            <td style="text-align: center;">{{ $index + 1 }}</td>
                            <td>{{ $schedule->formatted_date }}</td>
                            <td style="text-align: center;">
                                <span class="badge">{{ $schedule->service_session }}</span>
                            </td>
                            <td><strong>{{ $schedule->servant->name }}</strong></td>
                            <td style="text-align: center;">
                                @if ($schedule->service_time)
                                    {{ \Carbon\Carbon::parse($schedule->service_time)->format('H:i') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $schedule->notes ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @empty
        <div class="no-data">
            Tidak ada jadwal untuk periode ini
        </div>
    @endforelse

    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh Sistem Manajemen Pelayanan by CV FlyHigh Sinergi Indonesia</p>
        <p>&copy; {{ date('Y') }} - Semua hak dilindungi</p>
    </div>
</body>

</html>

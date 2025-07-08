<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekapitulasi Hasil Kuis</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            text-align: center;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Rekapitulasi Hasil Pengerjaan Kuis</h1>
        @if(!empty($startDate) && !empty($endDate))
            <p>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
        @else
            <p>Periode: Semua Waktu</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 10%;">Ranking</th>
                <th style="width: 45%;">Nama Siswa</th>
                <th style="width: 25%;">NISN</th>
                <th style="width: 20%;">Total Nilai</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($results as $index => $result)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $result['siswa_nama'] }}</td>
                    <td class="text-center">{{ $result['siswa_nisn'] }}</td>
                    <td class="text-center">{{ $result['total_nilai'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data yang tersedia.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ $tanggalCetak }}
    </div>
</body>
</html>

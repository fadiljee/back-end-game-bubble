<!DOCTYPE html>
<html>
<head>
    <title>History Hasil Kuis Per Sesi</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 6px; text-align: center; }
        th { background-color: #eee; }
    </style>
</head>
<body>
    <h2>History Hasil Kuis Per Sesi</h2>
    @if($startDate && $endDate)
        <p>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
    @endif
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>NISN</th>
                <th>Total Nilai</th>
                <th>Tanggal Pengerjaan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($groupedResults as $index => $session)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $session['nama'] }}</td>
                    <td>{{ $session['nisn'] }}</td>
                    <td>{{ $session['total_nilai'] }}</td>
                    <td>{{ \Carbon\Carbon::parse($session['tanggal_pengerjaan'])->format('d M Y, H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <p>Dicetak pada: {{ $tanggalCetak }}</p>
</body>
</html>

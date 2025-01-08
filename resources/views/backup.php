public function print_laporan_bulanan_pendidik(Request $request)
{
    $tanggalAwal = $request->tanggal_awal;
    $tanggalAkhir = $request->tanggal_akhir;

    $tanggalMulai = Carbon::parse($tanggalAwal);
    $tanggalSelesai = Carbon::parse($tanggalAkhir);

    $selectStatements = [];
    while ($tanggalMulai->lte($tanggalSelesai)) {
        $hari = $tanggalMulai->day;
        $selectStatements[] = "MAX(CASE 
            WHEN DAY(absensi_pendidik.tanggal) = $hari THEN 
            CONCAT(
                IFNULL(absensi_pendidik.jam_masuk, 'Tidak Hadir'), 
                IF(absensi_pendidik.jam_masuk IS NOT NULL, 
                    CONCAT(' (', status_hadir.status_hadir, ')'), 
                    ''
                )
            )
            ELSE '' END) as tgl_$hari";
        $tanggalMulai->addDay();
    }

    // Query Guru
    $queryGuru = DB::table('guru')
        ->selectRaw('
            guru.id as id_pendidik,
            users.name as nama,
            "Guru" as jenis_pengguna,
            (CASE 
                WHEN kepsek.id IS NOT NULL THEN "Kepala Sekolah"
                WHEN waka.id IS NOT NULL THEN waka.jabatan
                ELSE "Guru"
            END) as jabatan,
            ' . implode(', ', $selectStatements)
        )
        ->leftJoin('users', 'guru.id_user', '=', 'users.id')
        ->leftJoin('waka', 'guru.id', '=', 'waka.id_guru')
        ->leftJoin('kepsek', 'guru.id', '=', 'kepsek.id_guru')
        ->leftJoin('absensi_pendidik', function ($join) use ($tanggalAwal, $tanggalAkhir) {
            $join->on('guru.id', '=', 'absensi_pendidik.id_guru')
                ->whereBetween('absensi_pendidik.tanggal', [$tanggalAwal, $tanggalAkhir]);
        })
        ->leftJoin('status_hadir', 'absensi_pendidik.id_status_hadir', '=', 'status_hadir.id')
        ->groupByRaw('guru.id, users.name, kepsek.id, waka.id');

    // Query Karyawan
    $queryKaryawan = DB::table('karyawan')
        ->selectRaw('
            karyawan.id as id_pendidik,
            users.name as nama,
            "Karyawan" as jenis_pengguna,
            COALESCE(karyawan.tugas, "Karyawan") as jabatan,
            ' . implode(', ', $selectStatements)
        )
        ->leftJoin('users', 'karyawan.id_user', '=', 'users.id')
        ->leftJoin('absensi_pendidik', function ($join) use ($tanggalAwal, $tanggalAkhir) {
            $join->on('karyawan.id', '=', 'absensi_pendidik.id_karyawan')
                ->whereBetween('absensi_pendidik.tanggal', [$tanggalAwal, $tanggalAkhir]);
        })
        ->leftJoin('status_hadir', 'absensi_pendidik.id_status_hadir', '=', 'status_hadir.id')
        ->groupByRaw('karyawan.id, users.name, karyawan.tugas');

    // Gabungkan data
    $rekap = $queryGuru->unionAll($queryKaryawan)
        ->orderBy('nama')
        ->get();

    // Format data harian untuk keperluan view
    foreach ($rekap as $data) {
        $keteranganPerHari = [];
        for ($i = 1; $i <= 31; $i++) {
            $key = "tgl_$i";
            if (!empty($data->$key)) {
                $keteranganPerHari[$key] = $data->$key;
            } else {
                $keteranganPerHari[$key] = '-';
            }
        }
        $data->keterangan_per_hari = $keteranganPerHari;
    }

    return view('laporan.print_laporan_bulanan_pendidik', compact('tanggalAwal', 'tanggalAkhir', 'rekap'));
}



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Absensi Bulanan</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { padding: 8px; text-align: center; border: 1px solid black; font-size: 10px; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Rekap Absensi Bulan</h2>
    <h3>Periode: {{ \Carbon\Carbon::parse($tanggalAwal)->translatedFormat('d F Y') }} - {{ \Carbon\Carbon::parse($tanggalAkhir)->translatedFormat('d F Y') }}</h3>
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Jabatan</th>
                @php
                    $start = \Carbon\Carbon::parse($tanggalAwal);
                    $end = \Carbon\Carbon::parse($tanggalAkhir);
                @endphp
                @while ($start <= $end)
                    <th>{{ $start->day }}</th>
                    @php $start->addDay(); @endphp
                @endwhile
                <th>Jumlah Hadir</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rekap as $data)
                <tr>
                    <td>{{ $data->nama }}</td>
                    <td>{{ $data->jabatan }}</td>
                    @php
                        $totalHadir = 0;
                        $start = \Carbon\Carbon::parse($tanggalAwal);
                        $end = \Carbon\Carbon::parse($tanggalAkhir);
                    @endphp
                    @while ($start <= $end)
                        <td>
                            @if (isset($data->keterangan_per_hari['tgl_'.$start->day]) && $data->keterangan_per_hari['tgl_'.$start->day] !== '-')
                                {{ $data->keterangan_per_hari['tgl_'.$start->day] }}
                                @php $totalHadir++; @endphp
                            @else
                                -
                            @endif
                        </td>
                        @php $start->addDay(); @endphp
                    @endwhile
                    <td>{{ $totalHadir }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <script> window.print(); </script>
</body>
</html>

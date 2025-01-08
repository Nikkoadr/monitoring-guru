public function print_laporan_bulanan_pendidik(Request $request)
{
    $tanggalAwal = $request->tanggal_awal;
    $tanggalAkhir = $request->tanggal_akhir;

    $selectStatements = [];
    $tanggalMulai = Carbon::parse($tanggalAwal);
    $tanggalSelesai = Carbon::parse($tanggalAkhir);

    while ($tanggalMulai->lte($tanggalSelesai)) {
        $hari = $tanggalMulai->day;
        $selectStatements[] = "MAX(CASE WHEN DAY(absensi_pendidik.tanggal) = $hari THEN CONCAT(absensi_pendidik.jam_masuk, '-', IFNULL(absensi_pendidik.jam_keluar, '00:00:00')) ELSE '' END) as tgl_$hari";
        $tanggalMulai->addDay();
    }

    // Query untuk Guru
    $queryGuru = DB::table('guru')
        ->selectRaw('
            users.name as nama,
            guru.id as id_pendidik,
            "Guru" as jenis_pengguna,
            (CASE 
                WHEN kepsek.id IS NOT NULL THEN "Kepala Sekolah"
                WHEN waka.id IS NOT NULL THEN waka.jabatan
                ELSE "Guru" 
            END) as jabatan,
            (CASE 
                WHEN kepsek.id IS NOT NULL OR waka.id IS NOT NULL THEN "Hadir" 
                ELSE "Tidak Hadir" 
            END) as status_kehadiran,
            ' . implode(', ', $selectStatements))
        ->leftJoin('users', 'guru.id_user', '=', 'users.id')
        ->leftJoin('waka', 'guru.id', '=', 'waka.id_guru')
        ->leftJoin('kepsek', 'guru.id', '=', 'kepsek.id_guru')
        ->leftJoin('absensi_pendidik', function ($join) use ($tanggalAwal, $tanggalAkhir) {
            $join->on('guru.id', '=', 'absensi_pendidik.id_guru')
                ->whereBetween('absensi_pendidik.tanggal', [$tanggalAwal, $tanggalAkhir]);
        })
        ->groupByRaw('guru.id, users.name, kepsek.id, waka.id');

    // Query untuk Karyawan
    $queryKaryawan = DB::table('karyawan')
        ->selectRaw('
            users.name as nama,
            karyawan.id as id_pendidik,
            "Karyawan" as jenis_pengguna,
            (CASE 
                WHEN karyawan.tugas IS NOT NULL THEN karyawan.tugas
                ELSE "Karyawan"
            END) as jabatan,
            (CASE 
                WHEN absensi_pendidik.id_karyawan IS NOT NULL THEN "Hadir"
                ELSE "Tidak Hadir"
            END) as status_kehadiran,
            ' . implode(', ', $selectStatements))
        ->leftJoin('users', 'karyawan.id_user', '=', 'users.id')
        ->leftJoin('absensi_pendidik', function ($join) use ($tanggalAwal, $tanggalAkhir) {
            $join->on('karyawan.id', '=', 'absensi_pendidik.id_karyawan')
                ->whereBetween('absensi_pendidik.tanggal', [$tanggalAwal, $tanggalAkhir]);
        })
        ->groupByRaw('karyawan.id, users.name, karyawan.tugas');

    // Gabungkan hasil query
    $rekap = $queryGuru->unionAll($queryKaryawan)
        ->orderBy('nama')
        ->get();

    // Proses keterangan per hari
    foreach ($rekap as $data) {
        $keteranganPerHari = [];
        for ($i = 1; $i <= 31; $i++) {
            $key = "tgl_$i";
            $tanggal = Carbon::createFromFormat('Y-m-d', $tanggalAwal)->startOfMonth()->addDays($i - 1);
            if (isset($data->$key) && $data->$key !== '') {
                if ($data->jenis_pengguna === 'Guru' && $data->status_kehadiran === 'Hadir') {
                    $keteranganPerHari[$key] = 'Hadir';
                } elseif ($data->jenis_pengguna === 'Karyawan') {
                    $keteranganPerHari[$key] = 'Hadir';
                } else {
                    $adaKbm = DB::table('kbm')
                        ->where('id_guru', $data->id_pendidik)
                        ->whereDate('tanggal', $tanggal->format('Y-m-d'))
                        ->exists();
                    $keteranganPerHari[$key] = $adaKbm ? 'Masuk Kelas' : 'Masuk tapi tidak masuk kelas';
                }
            }
        }
        $data->keterangan_per_hari = $keteranganPerHari;
    }

    return view('laporan.print_laporan_bulanan_pendidik', compact('tanggalAwal', 'tanggalAkhir', 'rekap'));
}
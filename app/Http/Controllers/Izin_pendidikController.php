<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Izin_pendidikController extends Controller
{
public function index()
{
    $data_izin_pendidik = DB::table('izin_pendidik')
        ->leftJoin('guru', 'izin_pendidik.id_guru', '=', 'guru.id')
        ->leftJoin('users as user_guru', 'guru.id_user', '=', 'user_guru.id')
        ->leftJoin('karyawan', 'izin_pendidik.id_karyawan', '=', 'karyawan.id')
        ->leftJoin('users as user_karyawan', 'karyawan.id_user', '=', 'user_karyawan.id')
        ->join('status_izin', 'izin_pendidik.id_status_izin', '=', 'status_izin.id')
        ->select(
            'izin_pendidik.*',
            DB::raw("CASE 
                WHEN guru.id IS NOT NULL THEN user_guru.name
                WHEN karyawan.id IS NOT NULL THEN user_karyawan.name
                ELSE 'Tidak Diketahui' 
            END as nama_pemohon"),
            'status_izin.nama_status_izin as status_izin'
        )
        ->whereDate('izin_pendidik.tanggal', '=', now()->toDateString())
        ->orderBy('izin_pendidik.tanggal', 'desc')
        ->orderBy('izin_pendidik.created_at', 'desc')
        ->get();

    return view('izin_pendidik.data_izin_pendidik', compact('data_izin_pendidik'));
}

   // Fungsi untuk ACC izin
    public function acc(Request $request, $id)
    {
        // Ambil data izin berdasarkan ID
        $izin = DB::table('izin_pendidik')->where('id', $id)->first();

        if ($izin) {
            // Ambil jenis izin dari query parameter
            $jenisIzin = $request->query('jenis_izin');

            // Validasi apakah jenis izin valid (3 = Izin Biasa, 4 = Izin Sakit)
            if (!in_array($jenisIzin, [3, 4])) {
                return redirect()->back()->with('error', 'Jenis izin tidak valid.');
            }

            // Update status izin menjadi "ACC" (id_status_izin = 2)
            DB::table('izin_pendidik')->where('id', $id)->update(['id_status_izin' => 2]);

            // Masukkan data ke tabel absensi_pendidik
            DB::table('absensi_pendidik')->insert([
                'id_guru' => $izin->id_guru,
                'id_karyawan' => $izin->id_karyawan,
                'id_status_hadir' => $jenisIzin, // Jenis izin (3 = Izin Biasa, 4 = Izin Sakit)
                'tanggal' => $izin->tanggal,
                'jam_masuk' => now()->format('H:i:s'),
                'lokasi_masuk' => 'Default Lokasi', // Sesuaikan lokasi
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->back()->with('success', 'Izin berhasil di-ACC dengan jenis izin yang dipilih.');
        }

        return redirect()->back()->with('error', 'Data izin tidak ditemukan.');
    }


    // Fungsi untuk menolak izin
    public function reject($id)
    {
        $izin = DB::table('izin_pendidik')->where('id', $id)->first();
        if ($izin) {
            $absensi = DB::table('absensi_pendidik')
                ->where('id_guru', $izin->id_guru)
                ->orWhere('id_karyawan', $izin->id_karyawan)
                ->where('tanggal', $izin->tanggal)
                ->first();

            if ($absensi) {
                DB::table('absensi_pendidik')->where('id', $absensi->id)->delete();
            }

            DB::table('izin_pendidik')->where('id', $id)->update(['id_status_izin' => 3]);

            return redirect()->back()->with('success', 'Izin berhasil ditolak dan data absensi telah dihapus.');
        }

        return redirect()->back()->with('error', 'Data izin tidak ditemukan.');
    }

    public function request_izin_pendidik()
    {
        return view('izin_pendidik.request_izin_pendidik');
    }

    public function post_request_izin_pendidik(Request $request)
        {
            $request->validate([
                'alasan' => 'required|string|max:255',
                'file' => 'nullable|file|mimes:pdf,jpeg,png,jpg',
            ]);

            // Proses untuk menyimpan alasan
            $alasan = $request->input('alasan');

            // Mendapatkan user yang sedang login
            $user = Auth::user();

            // Cek apakah user adalah guru atau karyawan
            $idGuru = null;
            $idKaryawan = null;

            // Jika user adalah seorang guru
            $guru = DB::table('guru')->where('id_user', $user->id)->first();
            if ($guru) {
                $idGuru = $guru->id;
            } else {
                // Jika user adalah seorang karyawan
                $karyawan = DB::table('karyawan')->where('id_user', $user->id)->first();
                if ($karyawan) {
                    $idKaryawan = $karyawan->id;
                }
            }

            // Jika tidak ditemukan data guru atau karyawan, kirimkan error
            if (!$idGuru && !$idKaryawan) {
                return redirect()->back()->with('error', 'Anda tidak terdaftar sebagai guru atau karyawan.');
            }

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = $file->getClientOriginalName();
                $filePath = Storage::disk(env('STORAGE_DISK'))->put('file_izin_pendidik/' . $fileName, file_get_contents($file));
            }
            // Simpan data izin ke database
            DB::table('izin_pendidik')->insert([
                'alasan' => $alasan,
                'file' => isset($filePath) ? $filePath : null,
                'id_guru' => $idGuru,
                'id_karyawan' => $idKaryawan,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->back()->with('success', 'Permohonan izin berhasil dikirim.');
        }
}

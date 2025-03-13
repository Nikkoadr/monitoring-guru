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
            ->leftJoin('users as pemberi_izin', 'izin_pendidik.id_user_yang_menyetujui', '=', 'pemberi_izin.id')
            ->select(
                'izin_pendidik.*',
                DB::raw("CASE 
                    WHEN guru.id IS NOT NULL THEN user_guru.name
                    WHEN karyawan.id IS NOT NULL THEN user_karyawan.name
                    ELSE 'Tidak Diketahui' 
                END as nama_pemohon"),
                'status_izin.nama_status_izin as status_izin',
                'pemberi_izin.name as nama_pemberi_izin'
            )
            ->whereDate('izin_pendidik.tanggal', '=', now()->toDateString())
            ->orderBy('izin_pendidik.tanggal', 'desc')
            ->orderBy('izin_pendidik.created_at', 'desc')
            ->get();

        return view('izin_pendidik.data_izin_pendidik', compact('data_izin_pendidik'));
    }

    public function acc(Request $request, $id)
    {
        $izin = DB::table('izin_pendidik')->where('id', $id)->first();

        if ($izin) {
            $jenisIzin = $request->query('jenis_izin');

            if (!in_array($jenisIzin, [3, 4])) {
                return redirect()->back()->with('error', 'Jenis izin tidak valid.');
            }

            DB::table('izin_pendidik')->where('id', $id)->update(['id_status_izin' => 2, 'id_user_yang_menyetujui' => Auth::user()->id]);

            DB::table('absensi_pendidik')->insert([
                'id_guru' => $izin->id_guru,
                'id_karyawan' => $izin->id_karyawan,
                'id_status_hadir' => $jenisIzin,
                'tanggal' => $izin->tanggal,
                'jam_masuk' => now()->format('H:i:s'),
                'lokasi_masuk' => 'Default Lokasi',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            return redirect()->back()->with('success', 'Izin berhasil di-ACC dengan jenis izin yang dipilih.');
        }

        return redirect()->back()->with('error', 'Data izin tidak ditemukan.');
    }

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
        $user = Auth::user();
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        $izin_guru = DB::table('izin_pendidik')
            ->join('guru', 'izin_pendidik.id_guru', '=', 'guru.id')
            ->join('users as user_guru', 'guru.id_user', '=', 'user_guru.id')
            ->join('status_izin', 'izin_pendidik.id_status_izin', '=', 'status_izin.id')
            ->select(
                'izin_pendidik.*',
                'user_guru.name as nama_pemohon',
                'status_izin.nama_status_izin as status_izin',
                DB::raw("'Guru' as role")
            )
            ->where('guru.id_user', $user->id)
            ->whereBetween('izin_pendidik.tanggal', [$startOfMonth, $endOfMonth]);

        $izin_karyawan = DB::table('izin_pendidik')
            ->join('karyawan', 'izin_pendidik.id_karyawan', '=', 'karyawan.id')
            ->join('users as user_karyawan', 'karyawan.id_user', '=', 'user_karyawan.id')
            ->join('status_izin', 'izin_pendidik.id_status_izin', '=', 'status_izin.id')
            ->select(
                'izin_pendidik.*',
                'user_karyawan.name as nama_pemohon',
                'status_izin.nama_status_izin as status_izin',
                DB::raw("'Karyawan' as role")
            )
            ->where('karyawan.id_user', $user->id)
            ->whereBetween('izin_pendidik.tanggal', [$startOfMonth, $endOfMonth]);

        $data_izin_pendidik = $izin_guru->union($izin_karyawan)
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('izin_pendidik.request_izin_pendidik', compact('data_izin_pendidik'));
    }

    public function post_request_izin_pendidik(Request $request)
    {
        $request->validate([
            'alasan' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf,jpeg,png,jpg',
        ]);

        $alasan = $request->input('alasan');
        $user = Auth::user();
        $today = now()->toDateString();

        $idGuru = null;
        $idKaryawan = null;

        $guru = DB::table('guru')->where('id_user', $user->id)->first();
        if ($guru) {
            $idGuru = $guru->id;
        } else {
            $karyawan = DB::table('karyawan')->where('id_user', $user->id)->first();
            if ($karyawan) {
                $idKaryawan = $karyawan->id;
            }
        }

        if (!$idGuru && !$idKaryawan) {
            return redirect()->back()->with('error', 'Anda tidak terdaftar sebagai guru atau karyawan.');
        }

        $sudahAbsen = DB::table('absensi_pendidik')
            ->whereDate('tanggal', $today)
            ->where(function ($query) use ($idGuru, $idKaryawan) {
                if ($idGuru) {
                    $query->where('id_guru', $idGuru);
                }
                if ($idKaryawan) {
                    $query->orWhere('id_karyawan', $idKaryawan);
                }
            })
            ->exists();

        if ($sudahAbsen) {
            return redirect()->back()->with('error', 'Anda sudah melakukan absensi hari ini dan tidak bisa mengajukan izin.');
        }

        $fileName = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            Storage::disk(env('STORAGE_DISK'))->put('file_izin_pendidik/' . $fileName, file_get_contents($file));
        }

        DB::table('izin_pendidik')->insert([
            'tanggal' => now(),
            'jam_izin' => now()->format('H:i:s'),
            'id_status_izin' => 1,
            'alasan' => $alasan,
            'file' => $fileName,
            'id_guru' => $idGuru,
            'id_karyawan' => $idKaryawan,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Permohonan izin berhasil dikirim.');
    }
}

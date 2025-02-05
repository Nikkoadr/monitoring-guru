@extends('layouts.main')
@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                @php
                                    $is_kepsek = DB::table('kepsek')
                                                ->join('guru', 'kepsek.id_guru', '=', 'guru.id')
                                                ->where('guru.id_user', Auth::user()->id)
                                                ->exists();
                                @endphp
                                @if($is_kepsek || Auth::user()->can('admin'))
                                <h5>Daftar Kelas dan Status KBM</h5>
                                <p><strong>Tanggal:</strong> {{ $hariIni }}</p>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama Kelas</th>
                                            <th>Status KBM</th>
                                            <th>Jumlah Hadir</th>
                                            <th>Jumlah Belum Hadir</th>
                                            <th>Total Siswa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($kelas as $index => $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->nama_kelas }}</td>
                                                <td>{{ $item->status }}</td>
                                                <td>{{ $item->jumlah_hadir }}</td>
                                                <td>{{ $item->jumlah_belum_hadir }}</td>
                                                <td>{{ $item->total_siswa }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">Tidak ada data kelas.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-end"><strong>Total Kelas yang mulai KBM:</strong></td>
                                            <td colspan="3" class="text-start">{{ $totalKelasHadir }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-end"><strong>Total Jumlah Siswa Hadir:</strong></td>
                                            <td colspan="3" class="text-start">{{ $totalJumlahHadir }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-end"><strong>Total Jumlah Siswa Belum Hadir:</strong></td>
                                            <td colspan="3" class="text-start">{{ $totalJumlahBelumHadir }}</td>
                                        </tr>
                                    </tfoot>
                                </table>

                                @elseif (Auth::user()->can('siswa') && isset($userKelas))
                                    <p class="card-title">
                                        Assalaamu’alaikum Warahmatullaahi Wabarakaatuh. <b>{{ Auth::user()->name }}</b>
                                        Kelas: <b>{{ $userKelas->nama_kelas }}</b> Selamat belajar!
                                    </p>
                                @elseif (Auth::user()->can('guru') || Auth::user()->can('karyawan'))
                                    <p class="card-title">
                                        Assalaamu’alaikum Warahmatullaahi Wabarakaatuh. {{ Auth::user()->name }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script>
    @if (session()->has('error'))
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 5000
    });
        Toast.fire({
        icon: 'error',
        title: '{{ session('error') }}'
        })
    @endif
</script>
@endsection

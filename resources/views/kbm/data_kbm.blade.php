@extends('layouts.main')
@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Kegiatan Belajar Mengajar</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Data Kegiatan Belajar Mengajar</li>
                    </ol>
                </div>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Data Kegiatan Belajar Mengajar</h3>
                                <a href="/form_tambah_kbm" class="btn btn-primary float-right">Tambah KBM</a>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Guru</th>
                                        <th>Kelas</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Jam Ke</th>
                                        <th>Foto Masuk</th>
                                        <th>Jam Masuk</th>
                                        <th>Foto Keluar</th>
                                        <th>Jam Keluar</th>
                                        <th>keterangan</th>
                                        <th>Menu</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($data_kbm as  $kbm)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $kbm->tanggal }}</td>
                                            <td>{{ $kbm->nama_guru }}</td>
                                            <td>{{ $kbm->nama_kelas }}</td>
                                            <td>{{ $kbm->nama_mapel }}</td>
                                            <td>{{ $kbm->jam_ke }}</td>
                                            <td>{{ $kbm->foto_masuk }}</td>
                                            <td>{{ $kbm->jam_masuk }}</td>
                                            <td>{{ $kbm->foto_keluar }}</td>
                                            <td>{{ $kbm->jam_keluar }}</td>
                                            <td>{{ $kbm->keterangan }}</td>
                                            <td>
                                                <a href="/form_edit_kbm_{{ $kbm->id }}" class="btn btn-info float-right m-1">Edit</a>
                                                <button class="btn btn-danger float-right m-1" onclick="confirmDelete({{ $kbm->id }})">Hapus</button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script>
    @if (session()->has('success'))
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 5000
    });
        Toast.fire({
        icon: 'success',
        title: '{{ session('success') }}'
        })
    @endif
</script>
<script>
    function confirmDelete(roleId) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data tidak dapat dikembalikan setelah dihapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `/hapus_kbm_${roleId}`;
            }
        });
    }
</script>
@endsection

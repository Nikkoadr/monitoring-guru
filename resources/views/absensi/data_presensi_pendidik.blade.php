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
                    <h1 class="m-0">Data Presensi Pendidik</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Data Presensi Pendidik</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <!-- Form Filter -->
                    <form action="{{ url('data_presensi_pendidik') }}" method="GET">
                        <div class="form-row align-items-center">
                            <div class="col-auto">
                                <label for="tanggal" class="col-form-label">Tanggal:</label>
                            </div>
                            <div class="col-auto">
                                <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ $tanggal }}">
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </div>
                    </form>
                    <!-- End Form Filter -->

                    <!-- Tombol Tambah -->
                    <div class="mt-3">
                        <a href="/form_tambah_presensi_pendidik" class="btn btn-success"><i class="fa-solid fa-plus"></i> Tambah</a>
                    </div>

                    <!-- Tabel Data -->
                    <div class="card mt-3">
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Pendidik</th>
                                        <th>Tanggal</th>
                                        <th>Jam Masuk</th>
                                        <th>Jam Keluar</th>
                                        <th>Status Hadir</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->nama_pendidik }}</td>
                                        <td>{{ $item->tanggal }}</td>
                                        <td>{{ $item->jam_masuk }}</td>
                                        <td>{{ $item->jam_keluar }}</td>
                                        <td>{{ $item->status_hadir }}</td>
                                        <td>
                                            <a href="/form_edit_presensi_pendidik_{{ $item->id }}" class="btn btn-warning"><i class="fa-solid fa-pencil"></i></a>
                                            <button class="btn btn-danger" onclick="confirmDelete({{ $item->id }})"><i class="fa-solid fa-trash"></i></button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Data tidak ditemukan.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- End Tabel Data -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
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
                window.location.href = `/hapus_presensi_pendidik_${roleId}`;
            }
        });
    }
</script>
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

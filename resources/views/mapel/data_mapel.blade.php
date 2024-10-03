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
                    <h1 class="m-0">Database</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Data Mapel</li>
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
                                <h3 class="card-title">Data Mapel</h3>
                                <a href="/form_tambah_mapel" class="btn btn-primary float-right">Tambah Mapel</a>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <th>No</th>
                                        <th>Nama Mata pelajaran</th>
                                        <th>Guru Pengampu</th>
                                        <th>Menu</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($data_mapel as  $mapel)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $mapel->nama_mapel }}</td>
                                            <td>
                                                @foreach ($mapel->guru as $guru)
                                                    <!-- Nama guru diambil dari relasi user -->
                                                    <span>{{ $guru->user->name ?? 'Guru Tidak Ditemukan' }}</span><br>
                                                @endforeach
                                            </td>
                                            <td>
                                                <a href="/form_edit_mapel_{{ $mapel->id }}" class="btn btn-info float-right m-1">Edit</a>
                                                <a href="/form_tambah_guru_pengampu_{{ $mapel->id }}" class="btn btn-warning float-right m-1">Tambah</a>
                                                <button class="btn btn-danger float-right m-1" onclick="confirmDelete({{ $mapel->id }})">Hapus</button>
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
                window.location.href = `/hapus_mapel_${roleId}`;
            }
        });
    }
</script>
@endsection

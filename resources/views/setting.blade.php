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
                    <h1 class="m-0">Setting</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Setting</li>
                    </ol>
                </div>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-6">
                        <div class="card">
                            <div class="card-body">
                                <form action="/import_data" method="POST" enctype="multipart/form-data" class="form-horizontal">
                                    @csrf
                                    <label for="file">Import Master Data :</label>
                                        <div class="form-group">
                                        <div class="input-group">
                                            <div class="custom-file">
                                            <input type="file" class="custom-file-input @error('file') is-invalid @enderror" id="file" name="file">
                                            <label class="custom-file-label" for="file">Pilih File</label>
                                            </div>
                                            <div class="input-group-append">
                                            <button type="submit" class="input-group-text">Upload</button>
                                            </div>
                                        </div>
                                        @error('file')
                                            <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        </div>
                                </form>
                            </div>
                            {{-- <div class="card-body">
                                <form action="/edit_setting" method="POST">
                                    @csrf
                                    @method('put')
                                    <input type="hidden" name="id" value="">
                                    <div class="form-group">
                                        <label for="namaLokasi">Nama Lokasi:</label>
                                        <input type="text" class="form-control" id="namaLokasi" name="namaLokasi" value="">
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="latitude">Latitude:</label>
                                            <input type="text" class="form-control" id="latitude" name="latitude" value="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="longitude">Longitude:</label>
                                            <input type="text" class="form-control" id="longitude" name="longitude" value="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="radius">Radius:</label>
                                        <input type="text" class="form-control" id="radius" name="radius" value="">
                                    </div>
                                    <div class="form-group">
                                        <label for="radius">Limit Absen Harian</label>
                                        <input type="time" class="form-control" id="limit_absen" name="limit_absen" value="">
                                    </div>
                                    <button style="float: right" type="submit" class="btn btn-primary">Edit</button>
                                </form>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
<script src="{{ asset('assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
<script>
$(function () {
    bsCustomFileInput.init();
});
</script>
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
                window.location.href = `/hapus_setting_${roleId}`;
            }
        });
    }
</script>
@endsection
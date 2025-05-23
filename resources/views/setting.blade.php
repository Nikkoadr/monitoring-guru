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
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="/edit_setting/{{ $setting->id }}" method="POST">
                                    @csrf
                                    @method('put')
                                    <div class="form-group">
                                        <label for="nama_aplikasi">Nama Lokasi:</label>
                                        <input type="text" class="form-control @error('nama_aplikasi') is-invalid @enderror" id="nama_aplikasi" name="nama_aplikasi" value="{{ $setting->nama_aplikasi }}">
                                        @error('nama_aplikasi')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="lokasi_latitude">Latitude:</label>
                                            <input type="text" class="form-control  @error('lokasi_latitude') is-invalid @enderror" id="lokasi_latitude" name="lokasi_latitude" value="{{ $setting->lokasi_latitude }}">
                                            @error('lokasi_latitude')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="lokasi_longitude">Longitude:</label>
                                            <input type="text" class="form-control @error('lokasi_longitude') is-invalid @enderror" id="lokasi_longitude" name="lokasi_longitude" value="{{ $setting->lokasi_longitude }}">
                                            @error('lokasi_longitude')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="radius_lokasi">Radius:</label>
                                        <input type="text" class="form-control @error('radius_lokasi') is-invalid @enderror" id="radius_lokasi" name="radius_lokasi" value="{{ $setting->radius_lokasi }}">
                                        @error('radius_lokasi')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="mulai_presensi">Mulai Absensi</label>
                                        <input type="time" class="form-control @error('mulai_presensi') is-invalid @enderror" id="mulai_presensi" name="mulai_presensi" value="{{ $setting->mulai_presensi }}">
                                        @error('mulai_presensi')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="limit_presensi">Limit Presensi Harian</label>
                                        <input type="time" class="form-control @error('limit_presensi') is-invalid @enderror" id="limit_presensi" name="limit_presensi" value="{{ $setting->limit_presensi }}">
                                        @error('limit_presensi')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <button style="float: right" type="submit" class="btn btn-primary">Edit</button>
                                </form>
                            </div>
                        </div>
                        <div class="col-12">
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
                            </div>
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
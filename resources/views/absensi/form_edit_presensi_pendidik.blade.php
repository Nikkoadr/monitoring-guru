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
                    <li class="breadcrumb-item">Data Presensi Pendidik</li>
                    <li class="breadcrumb-item active">Edit</li>
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
                                <h3 class="card-title">Form Edit Presensi Pendidik</h3>
                            </div>
                            <div class="card-body">
                                <form action="/update_presensi_pendidik_{{ $data->id }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="form-group">
                                        <label for="pendidik">Nama Pendidik:</label>
                                        <input class="form-control @error('pendidik') is-invalid @enderror" 
                                            type="text" 
                                            id="pendidik" 
                                            name="pendidik" 
                                            autocomplete="off" 
                                            value="{{ old('pendidik', $data->nama_pendidik) }}" 
                                            readonly 
                                            required>
                                        @error('pendidik')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="tanggal">Tanggal:</label>
                                        <input class="form-control @error('tanggal') is-invalid @enderror" 
                                            type="date" 
                                            id="tanggal" 
                                            name="tanggal" 
                                            value="{{ old('tanggal', $data->tanggal) }}" 
                                            required>
                                        @error('tanggal')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="jam_masuk">Jam Masuk:</label>
                                        <input class="form-control @error('jam_masuk') is-invalid @enderror" 
                                            type="time" 
                                            id="jam_masuk" 
                                            name="jam_masuk" 
                                            value="{{ old('jam_masuk', $data->jam_masuk) }}">
                                        @error('jam_masuk')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="jam_keluar">Jam Keluar:</label>
                                        <input class="form-control @error('jam_keluar') is-invalid @enderror" 
                                            type="time" 
                                            id="jam_keluar" 
                                            name="jam_keluar" 
                                            value="{{ old('jam_keluar', $data->jam_keluar) }}">
                                        @error('jam_keluar')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="foto_masuk">Foto Bukti Masuk:</label>
                                        <div class="custom-file">
                                            <input type="file" 
                                                class="custom-file-input @error('foto_masuk') is-invalid @enderror" 
                                                id="foto_masuk" 
                                                name="foto_masuk" 
                                                accept="image/*">
                                            <label class="custom-file-label" for="foto_masuk">Pilih File</label>
                                            @error('foto_masuk')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="foto_keluar">Foto Bukti Keluar:</label>
                                        <div class="custom-file">
                                            <input type="file" 
                                                class="custom-file-input @error('foto_keluar') is-invalid @enderror" 
                                                id="foto_keluar" 
                                                name="foto_keluar" 
                                                accept="image/*">
                                            <label class="custom-file-label" for="foto_keluar">Pilih File</label>
                                            @error('foto_keluar')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="id_status_hadir">Status Kehadiran:</label>
                                        <select class="form-control @error('id_status_hadir') is-invalid @enderror" 
                                                name="id_status_hadir" 
                                                id="id_status_hadir" 
                                                required>
                                            <option value="">Pilih Status</option>
                                            @foreach ($status_hadir as $item)
                                                <option value="{{ $item->id }}" 
                                                        {{ old('id_status_hadir', $data->id_status_hadir) == $item->id ? 'selected' : '' }}>
                                                    {{ $item->nama_status_hadir }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('id_status_hadir')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <button class="btn btn-primary mt-2 float-right" type="submit">Simpan</button>
                                    <a href="/data_presensi_pendidik" class="btn btn-danger mt-2 float-right mr-2">Batal</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
<script src="{{ asset('assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<script>
$(function () {
    bsCustomFileInput.init();
});
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
@endsection

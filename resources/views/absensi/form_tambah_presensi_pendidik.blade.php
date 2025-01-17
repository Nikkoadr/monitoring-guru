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
                    <li class="breadcrumb-item active">tambah</li>
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
                                <h3 class="card-title">Form Tambah Presensi Pendidik</h3>
                            </div>
                            <div class="card-body">
                        <form action="/admin_post_presensi_pendidik" method="post" enctype="multipart/form-data">
                            @csrf

                            <label for="pendidik">Nama Pendidik:</label>
                            <input class="form-control @error('id_pendidik') is-invalid @enderror" type="text" id="pendidik" name="pendidik" autocomplete="off" required>
                            <input type="hidden" id="id_pendidik" name="id_pendidik">
                            <div class="suggestions dropdown-item" id="suggestions"></div>
                            @error('id_pendidik')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <label for="tanggal">Tanggal:</label>
                            <input class="form-control @error('tanggal') is-invalid @enderror" type="date" id="tanggal" name="tanggal" required>
                            @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <label for="jam_masuk">Jam Masuk:</label>
                            <input class="form-control @error('jam_masuk') is-invalid @enderror" type="time" id="jam_masuk" name="jam_masuk">
                            @error('jam_masuk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <label for="jam_keluar">Jam Keluar:</label>
                            <input class="form-control @error('jam_keluar') is-invalid @enderror" type="time" id="jam_keluar" name="jam_keluar">
                            @error('jam_keluar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div class="form-group">
                                <label for="foto_masuk">Foto Bukti:</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('foto_masuk') is-invalid @enderror" id="foto_masuk" name="foto_masuk" accept="image/*">
                                    <label class="custom-file-label" for="file">Pilih File</label>
                                </div>
                                @error('foto_masuk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <label for="id_status_hadir">Status Kehadiran:</label>
                            <select class="form-control @error('id_status_hadir') is-invalid @enderror" name="id_status_hadir" id="id_status_hadir" required>
                                <option value="">Pilih Status</option>
                                @foreach ($status_hadir as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_status_hadir }}</option>
                                @endforeach
                            </select>
                            @error('id_status_hadir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

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
<script>
    document.getElementById('pendidik').addEventListener('input', function () {
        let query = this.value;
        if (query.length > 1) {
            fetch(`/get_user?q=${query}`)
                .then(response => response.json())
                .then(data => {
                    let suggestions = document.getElementById('suggestions');
                    suggestions.innerHTML = '';
                    data.forEach(user => {
                        let suggestion = document.createElement('div');
                        suggestion.textContent = user.name;
                        suggestion.addEventListener('click', function () {
                            document.getElementById('pendidik').value = user.name;
                            document.getElementById('id_pendidik').value = user.id;
                            suggestions.innerHTML = '';
                        });
                        suggestions.appendChild(suggestion);
                    });
                });
        } else {
            document.getElementById('suggestions').innerHTML = '';
        }
    });
</script>
@endsection

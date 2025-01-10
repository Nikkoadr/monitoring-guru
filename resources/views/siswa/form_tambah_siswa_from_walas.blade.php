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
                    <li class="breadcrumb-item active">Data Kelas</li>
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
                                <h3 class="card-title">Form Siswa Baru</h3>
                            </div>
                            <div class="card-body">
                                <form action="/post_siswa" method="post">
                                @csrf
                                    <label for="id_jurusan">Jurusan:</label>
                                    <input type="hidden" id="id_jurusan" name="id_jurusan" value="{{ $data_kelas->id_jurusan }}">
                                    <input type="text" class="form-control" id="id_kelas" name="id_kelas" value="{{ $data_jurusan->nama_jurusan }}" readonly>
                                    <label for="id_kelas">Kelas:</label>
                                    <input class="form-control" type="hidden" id="id_kelas" name="id_kelas" value="{{ $data_kelas->id }}">
                                    <input class="form-control" type="text" id="id_kelas" name="id_kelas" value="{{ $data_kelas->nama_kelas }}" readonly>
                                    <label for="nama_siswa">Nama Siswa:</label>
                                    <input class="form-control" type="text" id="nama_siswa" name="nama_siswa" autocomplete="off">
                                    <input type="hidden" id="id_user" name="id_user">
                                    <div class="suggestions dropdown-item" id="suggestions"></div>
                                    <button class="btn btn-primary mt-2 float-right" type="submit">Simpan</button>
                                    <a href="/data_siswa" class="btn btn-danger mt-2 float-right mr-2">Batal</a>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script>
    document.getElementById('nama_siswa').addEventListener('input', function () {
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
                            document.getElementById('nama_siswa').value = user.name;
                            document.getElementById('id_user').value = user.id;
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

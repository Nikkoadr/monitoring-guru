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
                    <li class="breadcrumb-item">Data karyawan</li>
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
                                <h3 class="card-title">Form Tambah Karyawan</h3>
                            </div>
                            <div class="card-body">
                                <form action="/post_karyawan" method="post">
                                    @csrf
                                    <label for="karyawan">Nama Karyawan:</label>
                                    <input class="form-control" type="text" id="karyawan" name="karyawan" autocomplete="off">
                                    <input type="hidden" id="id_user" name="id_user">
                                    <div class="suggestions dropdown-item" id="suggestions"></div>
                                    <label for="tugas">Tugas:</label>
                                    <input class="form-control" type="text" id="tugas" name="tugas">
                                    <button class="btn btn-primary mt-2 float-right" type="submit">Simpan</button>
                                    <a href="/data_karyawan" class="btn btn-danger mt-2 float-right mr-2">Batal</a>
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
    document.getElementById('karyawan').addEventListener('input', function () {
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
                            document.getElementById('karyawan').value = user.name;
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

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
                                <h3 class="card-title">Form Tambah Mapel</h3>
                            </div>
                            <div class="card-body">
                            <form action="/post_guru_pengampu_{{ $mapel->id }}" method="post" id="guruForm">
                                @csrf
                                <label for="nama_mapel">Nama Mata Pelajaran:</label>
                                <input class="form-control" type="text" id="nama_mapel" name="nama_mapel" value="{{ $mapel->nama_mapel }}" readonly autocomplete="off">
                                <label for="guru">Guru Pengampu:</label>
                                <input class="form-control" type="text" id="guru" name="guru" autocomplete="off">
                                <input type="hidden" id="id_guru" name="id_guru">
                                <div class="suggestions dropdown-item" id="suggestions"></div>
                                <button class="btn btn-primary mt-2 float-right" type="submit">Simpan</button>
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
    document.getElementById('guru').addEventListener('input', function () {
        let query = this.value;
        if (query.length > 1) {
            fetch(`/get_guru?q=${query}`)
                .then(response => response.json())
                .then(data => {
                    let suggestions = document.getElementById('suggestions');
                    suggestions.innerHTML = '';
                    data.forEach(guru => {
                        let suggestion = document.createElement('div');
                        suggestion.textContent = guru.name;
                        suggestion.addEventListener('click', function () {
                            document.getElementById('guru').value = guru.name;
                            document.getElementById('id_guru').value = guru.id;
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

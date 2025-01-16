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
                    <li class="breadcrumb-item">Data Wakil Kepala Sekolah</li>
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
                                <h3 class="card-title">Form Tambah Wakil Kepala Sekolah</h3>
                            </div>
                            <div class="card-body">
                                <form action="/post_waka" method="post">
                                    @csrf
                                    <label for="waka">Nama Waka:</label>
                                    <input class="form-control" type="text" id="waka" name="waka" autocomplete="off">
                                    <input type="hidden" id="id_guru" name="id_guru">
                                    <div class="suggestions dropdown-item" id="suggestions"></div>
                                    <label for="jabatan">Jabatan:</label>
                                    <input class="form-control" type="text" id="jabatan" name="jabatan">
                                    <button class="btn btn-primary mt-2 float-right" type="submit">Simpan</button>
                                    <a href="/data_waka" class="btn btn-danger mt-2 float-right mr-2">Batal</a>
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
    document.getElementById('waka').addEventListener('input', function () {
        let query = this.value;
        if (query.length > 1) {
            fetch(`/get_guru?q=${query}`)
                .then(response => response.json())
                .then(data => {
                    let suggestions = document.getElementById('suggestions');
                    suggestions.innerHTML = '';
                    data.forEach(user => {
                        let suggestion = document.createElement('div');
                        suggestion.textContent = user.name;
                        suggestion.addEventListener('click', function () {
                            document.getElementById('waka').value = user.name;
                            document.getElementById('id_guru').value = user.id;
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

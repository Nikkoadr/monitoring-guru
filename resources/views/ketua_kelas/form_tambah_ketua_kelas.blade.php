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
                    <li class="breadcrumb-item active">Data Siswa</li>
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
                                    <h3 class="card-title">Form Tambah Ketua Kelas</h3>
                                </div>
                                <div class="card-body">
                                    <form action="/post_ketua_kelas" method="post">
                                        @csrf
                                        <div class="form-group">
                                            <label for="kelas">Pilih Kelas:</label>
                                            <select class="form-control" id="kelas" name="id_kelas">
                                                <option value="">-- Pilih Kelas --</option>
                                                @foreach($data_kelas as $kelas)
                                                    <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="siswa">Nama Siswa:</label>
                                            <input type="text" class="form-control" id="siswa" name="siswa">
                                            <input type="hidden" id="siswa_id" name="id_siswa">
                                            <div id="suggestions" class="autocomplete-list"></div>
                                        </div>
                                        <div class="form-grup">
                                            <label for="keterangan">Keterangan:</label>
                                            <select class="form-control" name="keterangan" id="keterangan">
                                                <option value="Ketua Kelas">Ketua Kelas</option>
                                                <option value="Wakil Ketua Kelas">Wakil Ketua Kelas</option>
                                                <option value="Bendahara">Bendahara</option>
                                                <option value="Sekretaris">Sekretaris</option>
                                            </select>
                                        </div>
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
    document.getElementById('siswa').addEventListener('input', function () {
        let query = this.value;
        let kelasId = document.getElementById('kelas').value;
        if (query.length > 1 && kelasId) {
            fetch(`/get_ketua_kelas_by_kelas?q=${query}&id_kelas=${kelasId}`)
                .then(response => response.json())
                .then(data => {
                    let suggestions = document.getElementById('suggestions');
                    suggestions.innerHTML = '';
                    data.forEach(siswa => {
                        let suggestion = document.createElement('div');
                        suggestion.textContent = siswa.name;
                        suggestion.addEventListener('click', function () {
                            document.getElementById('siswa').value = siswa.name;
                            document.getElementById('siswa_id').value = siswa.id;
                            suggestions.innerHTML = ''; // Clear suggestions setelah dipilih
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

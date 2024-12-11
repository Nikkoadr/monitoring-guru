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
                                    <select class="form-control" name="id_jurusan" id="id_jurusan">
                                        <option value="">Pilih Jurusan</option>
                                        @foreach ($data_jurusan as $jurusan)
                                            <option value="{{ $jurusan->id }}">{{ $jurusan->nama_jurusan }}</option>
                                        @endforeach
                                    </select>
                                    <label for="id_kelas">Kelas:</label>
                                    <select class="form-control" name="id_kelas" id="id_kelas" disabled>
                                        <option value="">Pilih Kelas</option>
                                    </select>
                                    <label for="nama_siswa">Nama Siswa:</label>
                                    <input class="form-control" type="text" id="nama_siswa" name="nama_siswa" autocomplete="off">
                                    <input type="hidden" id="id_user" name="id_user">
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Ketika jurusan dipilih
        $('#id_jurusan').change(function() {
            var jurusanId = $(this).val();
            if (jurusanId) {
                // Lakukan AJAX untuk mendapatkan kelas sesuai jurusan
                $.ajax({
                    url: '/get_kelas_by_jurusan_' + jurusanId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#id_kelas').empty().append('<option value="">Pilih Kelas</option>');
                        if (data.length > 0) {
                            $.each(data, function(key, kelas) {
                                $('#id_kelas').append('<option value="' + kelas.id + '">' + kelas.nama_kelas + '</option>');
                            });
                            $('#id_kelas').prop('disabled', false); // Enable dropdown kelas
                        } else {
                            $('#id_kelas').append('<option value="">Kelas Tidak Tersedia</option>');
                            $('#id_kelas').prop('disabled', true);
                        }
                    }
                });
            } else {
                $('#id_kelas').empty().append('<option value="">Pilih Kelas</option>').prop('disabled', true);
            }
        });
    });
</script>
@endsection

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
                                <h3 class="card-title">Form Edit Siswa</h3>
                            </div>
                            <div class="card-body">
                                <form action="/update_siswa_{{ $data_siswa->id }}" method="post">
                                @csrf
                                @method('put')
                                    <label for="id_jurusan">Jurusan:</label>
                                    <select class="form-control" name="id_jurusan" id="id_jurusan">
                                        <option value="">Pilih Jurusan</option>
                                        @foreach ($data_jurusan as $jurusan)
                                            <option value="{{ $jurusan->id }}" {{ $data_siswa->id_jurusan == $jurusan->id ? 'selected' : '' }}>{{ $jurusan->nama_jurusan }}</option>
                                        @endforeach
                                    </select>
                                    
                                    <label for="id_kelas">Kelas:</label>
                                    <select class="form-control" name="id_kelas" id="id_kelas" disabled>
                                        <option value="">Pilih Kelas</option>
                                    </select>
                                    
                                    <label for="nama_siswa">Nama Siswa:</label>
                                    <input class="form-control" type="text" id="nama_siswa" name="nama_siswa" value="{{ $data_siswa->nama_siswa }}" autocomplete="off">
                                    
                                    <input type="hidden" id="id_user" name="id_user" value="{{ $data_siswa->id_user }}">
                                    
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
<script>
    $(document).ready(function() {
        // Function to load kelas based on selected jurusan
        function loadKelas(jurusanId, selectedKelasId = null) {
            if (jurusanId) {
                $.ajax({
                    url: '/get_kelas_by_jurusan_' + jurusanId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#id_kelas').empty().append('<option value="">Pilih Kelas</option>');
                        if (data.length > 0) {
                            $.each(data, function(key, kelas) {
                                let selected = selectedKelasId && selectedKelasId == kelas.id ? 'selected' : '';
                                $('#id_kelas').append('<option value="' + kelas.id + '" ' + selected + '>' + kelas.nama_kelas + '</option>');
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
        }

        // Load kelas on page load if jurusan and kelas are already selected
        var initialJurusanId = '{{ $data_siswa->id_jurusan }}';
        var initialKelasId = '{{ $data_siswa->id_kelas }}';
        if (initialJurusanId) {
            loadKelas(initialJurusanId, initialKelasId);
        }

        // Load kelas dynamically when jurusan is changed
        $('#id_jurusan').change(function() {
            var jurusanId = $(this).val();
            loadKelas(jurusanId);
        });
    });
</script>
@endsection

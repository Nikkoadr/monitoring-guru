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
                    <li class="breadcrumb-item active">Data Guru</li>
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
                                <h3 class="card-title">Form Tambah Guru</h3>
                            </div>
                            <div class="card-body">
                                <form id="guruForm">
                                    <label for="guru">Nama Guru:</label>
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
                    data.forEach(user => {
                        let suggestion = document.createElement('div');
                        suggestion.textContent = user.name;
                        suggestion.addEventListener('click', function () {
                            document.getElementById('guru').value = user.name;
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

    document.getElementById('guruForm').addEventListener('submit', function (e) {
                e.preventDefault();
                let idGuru = document.getElementById('id_guru').value;
                
                if (idGuru) {
                    fetch('/post_guru', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            id_guru: idGuru
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.redirect) {
                            Swal.fire({
                                title: 'Sukses!',
                                text: 'Data berhasil disimpan.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.href = data.redirect;
                            });
                        } else {
                            Swal.fire({
                                title: 'Terjadi Kesalahan!',
                                text: data.error || 'Silakan coba lagi.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            title: 'Kesalahan Jaringan!',
                            text: 'Terjadi kesalahan jaringan: ' + error,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    });
                } else {
                    Swal.fire({
                        title: 'Pilih Guru!',
                        text: 'Pilih guru dari daftar!',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                }
            });
</script>
@endsection

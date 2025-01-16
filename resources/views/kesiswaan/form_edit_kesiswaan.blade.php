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
                    <li class="breadcrumb-item">Data Kesiswaan</li>
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
                                <h3 class="card-title">Form Edit Kesiswaan</h3>
                            </div>
                            <div class="card-body">
                                <form action="/update_kesiswaan_{{ $data_kesiswaan->id }}" method="post">
                                @csrf
                                @method('put')
                                    <label class="form-label" for="kesiswaan">Nama Kesiswaan:</label>
                                    <input class="form-control" type="text" value="{{ $data_kesiswaan->nama_kesiswaan }}" readonly required>
                                    <label class="form-label" for="tugas">Jabatan:</label>
                                    <input class="form-control" type="text" id="tugas" name="tugas" value="{{ $data_kesiswaan->tugas }}" required>
                                    <button class="btn btn-primary mt-2 float-right" type="submit">Simpan</button>
                                    <a href="/data_kesiswaan" class="btn btn-danger mt-2 float-right mr-2">Batal</a>
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
@endsection

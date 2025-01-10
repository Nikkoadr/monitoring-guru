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
                                <h3 class="card-title">Form Edit Kelas</h3>
                            </div>
                            <div class="card-body">
                                <form action="/update_kelas_{{ $data_kelas->id }}" method="post">
                                @method('put')
                                @csrf
                                    <label for="jurusan">Jurusan:</label>
                                    <select class="form-control @error('id_jurusan') is-invalid @enderror" name="id_jurusan" id="id_jurusan">
                                        @foreach ($data_jurusan as $jurusan)
                                            <option value="{{ $jurusan->id }}" {{ $data_kelas->id_jurusan == $jurusan->id ? 'selected' : '' }}>{{ $jurusan->nama_jurusan }}</option>
                                        @endforeach
                                    </select>
                                    <label for="nama_kelas">Nama Kelas:</label>
                                    <input class="form-control @error('nama_kelas') is-invalid @enderror" type="text" id="nama_kelas" name="nama_kelas" value="{{ $data_kelas->nama_kelas }}" autocomplete="off">
                                    @error('nama_kelas')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <button class="btn btn-primary mt-2 float-right" type="submit">Simpan</button>
                                    <a href="/data_kelas" class="btn btn-danger mt-2 float-right mr-2">Batal</a>
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
@endsection

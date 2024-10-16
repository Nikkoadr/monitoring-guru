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
                                <select class="form-control" id="kelas" name="kelas">
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach($kelas as $kls)
                                        <option value="{{ $kls->id }}">{{ $kls->nama_kelas }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="siswa">Nama Siswa:</label>
                                <select class="form-control" id="siswa" name="siswa">
                                    <option value="">-- Pilih Siswa --</option>
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

@endsection
@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
@endsection

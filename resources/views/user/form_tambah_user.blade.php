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
                    <li class="breadcrumb-item active">Data User</li>
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
                                <h3 class="card-title">Form Tambah User</h3>
                            </div>
                            <div class="card-body">
                                <form action="/post_user" method="post">
                                @csrf
                                    <label for="gelar_depan">Gelar Depan:</label>
                                    <input class="form-control" type="text" id="gelar_depan" name="gelar_depan" autocomplete="off">
                                    <label for="name">Nama Lengkap:</label>
                                    <input class="form-control" type="text" id="name" name="name" autocomplete="off">
                                    <label for="gelar_belakang">Gelar Belakang:</label>
                                    <input class="form-control" type="text" id="gelar_belakang" name="gelar_belakang" autocomplete="off">
                                    <label for="email">Email:</label>
                                    <input class="form-control" type="email" id="email" name="email" autocomplete="off">
                                    <label for="password">Password:</label>
                                    <input class="form-control" type="password" id="password" name="password" autocomplete="off">
                                    <button class="btn btn-primary mt-2 float-right" type="submit">Simpan</button>
                                    <a href="/data_user" class="btn btn-danger mt-2 float-right mr-2">Batal</a>
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

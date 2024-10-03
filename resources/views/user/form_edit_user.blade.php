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
                                <h3 class="card-title">Form Edit User</h3>
                            </div>
                            <div class="card-body">
                                <form action="/update_edit_user_{{ $data_user->id }}" method="post">
                                @method('put')
                                @csrf
                                    <label for="id_role">Role:</label>
                                    <select class="form-control @error('id_role') is-invalid @enderror" id="id_role" name="id_role">
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" {{ $data_user->id_role == $role->id ? 'selected' : '' }}>
                                                {{ $role->nama_role }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_role')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <label for="gelar_depan">Gelar Depan:</label>
                                    <input class="form-control @error('gelar_depan') is-invalid @enderror" type="text" id="gelar_depan" name="gelar_depan" value="{{ $data_user->gelar_depan }}" autocomplete="off">
                                    @error('gelar_depan')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <label for="name">Nama lengkap:</label>
                                    <input class="form-control @error('name') is-invalid @enderror" type="text" id="name" name="name" value="{{ $data_user->name }}" autocomplete="off">
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <label for="gelar_belakang">Gelar Belakang:</label>
                                    <input class="form-control @error('gelar_belakang') is-invalid @enderror" type="text" id="gelar_belakang" name="gelar_belakang" value="{{ $data_user->gelar_belakang }}" autocomplete="off">
                                    @error('gelar_belakang')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <label for="email">Email:</label>
                                    <input class="form-control @error('email') is-invalid @enderror" type="text" id="email" name="email" value="{{ $data_user->email }}" autocomplete="off">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
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
@endsection

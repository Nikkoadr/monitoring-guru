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
                    <h1 class="m-0">Edit Izin</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Edit Izin</li>
                    </ol>
                </div>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Form Edit Izin Keluar Lingkungan Sekolah</h3>
                            </div>
                            <div class="card-body">
                                <form action="/update_izin_siswa_{{ $data_izin_siswa->id }}" method="post">
                                @csrf
                                @method('patch')
                                <label for="nama_siswa">Nama Siswa:</label>
                                <input type="text" class="form-control" name="nama_siswa" id="nama_siswa" value="{{ $data_izin_siswa->nama_siswa }}" readonly>
                                <label for="tanggal">Tanggal: </label>
                                <input type="date" class="form-control" name="tanggal" id="tanggal" value="{{ $data_izin_siswa->tanggal }}" required>
                                <label for="jam_keluar">Jam keluar: </label>
                                <input type="time" class="form-control" name="jam_keluar" id="jam_keluar" value="{{ $data_izin_siswa->jam_keluar }}" required>
                                <label for="jam_kembali" class="form-label">Jam Kembali:</label>
                                <input type="time" class="form-control" name="jam_kembali" id="jam_kembali" value="{{ $data_izin_siswa->jam_kembali }}" required>
                                    <label for="alasan">Alasan:</label>
                                    <textarea class="form-control" name="alasan" id="alasan" rows="5"   required>{{ $data_izin_siswa->alasan }}</textarea>
                                <label for="id_status_izin" class="form-label">Status:</label>
                                <select class="form-control" name="id_status_izin" id="id_status_izin" required>
                                    <option value="">Pilih Status</option>
                                    @foreach ($status_izin as $status)
                                        <option value="{{ $status->id }}" {{ $data_izin_siswa->id_status_izin == $status->id ? 'selected' : '' }}>{{ $status->nama_status_izin }}</option>
                                    @endforeach
                                </select>
                                    <button class="btn btn-primary mt-2 float-right" type="submit">Edit Izin</button>
                                    <a href="/data_izin_siswa" class="btn btn-danger mt-2 float-right mr-2">Batal</a>
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

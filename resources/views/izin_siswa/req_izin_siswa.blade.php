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
                    <h1 class="m-0">Request Izin</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Request Izin</li>
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
                                <h3 class="card-title">Form Request Izin Keluar Lingkungan Sekolah</h3>
                            </div>
                            <div class="card-body">
                                <form action="/post_izin_siswa" method="post">
                                @csrf
                                    <label for="alasan">Alasan:</label>
                                    <textarea class="form-control" name="alasan" id="alasan" rows="5" required></textarea>
                                    <button class="btn btn-primary mt-2 float-right" type="submit">Ajukan Izin</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Jam</th>
                                    <th>Alasan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data_izin_siswa as $izin_siswa)
                                    <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $izin_siswa->tanggal }}</td>
                                    <td>{{ $izin_siswa->jam_keluar }}</td>
                                    <td>{{ $izin_siswa->alasan }}</td>
                                    <td>{{ $izin_siswa->status_izin }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
@endsection

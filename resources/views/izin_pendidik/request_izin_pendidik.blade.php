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
                    <h1 class="m-0">Form Request Izin</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item">Data Izin Pendidik</li>
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
                                <h3 class="card-title">Form Izin Tidak Masuk Kerja</h3>
                            </div>
                            <div class="card-body">
                                <form action="/post_request_izin_pendidik" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('put')
                                    <div class="form-group">
                                        <label for="alasan">Alasan Tidak Hadir:</label>
                                        <textarea class="form-control" name="alasan" id="alasan" cols="30" rows="10" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="file">Upload File Pendukung:</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="file" name="file" accept="application/pdf, image/*, application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint">
                                            <label class="custom-file-label" for="file">Pilih File</label>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
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
<script src="{{ asset('assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
<script>
$(function () {
    bsCustomFileInput.init();
});
</script>
@endsection

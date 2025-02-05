@extends('layouts.main')
@section('link')
<!-- DataTables -->
<link rel="stylesheet" href="assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
<link rel="stylesheet" href="assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">

@endsection
@section('content')
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0">Rekapitulasi Absensi</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Admin</a></li>
            <li class="breadcrumb-item active">Rekap</li>
        </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div><!-- /.content-header -->
    <!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Pilih Bulan Untuk Mencetak Laporan ( 1 - 31 bulan Sekarang )</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form method="POST" action="print_laporan_bulanan_kelas" target="_blank">
                            @csrf
                            @method('put')
                            <div class="form-row">
                                <div class="form-group col-6">
                                    <label for="tanggal_awal" class="col-form-label">Tanggal Awal</label>
                                    <input id="tanggal_awal" type="date" class="form-control @error('tanggal_awal') is-invalid @enderror" name="tanggal_awal" required autofocus>
                                    @error('tanggal_awal')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-6">
                                    <label for="tanggal_akhir" class="col-form-label">Tanggal Akhir</label>
                                    <input id="tanggal_akhir" type="date" class="form-control @error('tanggal_akhir') is-invalid @enderror" name="tanggal_akhir" required>
                                    @error('tanggal_akhir')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary float-right">Print Laporan</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>

{{-- <section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Pilih Bulan Untuk Mengunduh Excel Laporan( 25 Bulan Lalu - 24 bulan Sekarang )</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form method="POST" action="downloadLaporanBulanan" target="_blank">
                            @csrf
                            @method('put')
                            <div class="form-row">
                                <div class="form-group col-6">
                                    <label for="tanggal_awal" class="col-form-label">Tanggal Awal</label>
                                    <input id="tanggal_awal" type="date" class="form-control @error('tanggal_awal') is-invalid @enderror" name="tanggal_awal" required autofocus>
                                    @error('tanggal_awal')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-6">
                                    <label for="tanggal_akhir" class="col-form-label">Tanggal Akhir</label>
                                    <input id="tanggal_akhir" type="date" class="form-control @error('tanggal_akhir') is-invalid @enderror" name="tanggal_akhir" required>
                                    @error('tanggal_akhir')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary float-right">Download Excel</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section> --}}

<!-- /.content -->
</div>
@endsection
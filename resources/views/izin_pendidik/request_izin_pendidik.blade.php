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
                        <li class="breadcrumb-item active">Request Izin</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <!-- Form Request Izin -->
            <div class="row">
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

            <!-- Riwayat Izin -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Riwayat Izin Anda</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tanggal</th>
                                        <th>Alasan</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data_izin_pendidik as $izin)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ \Carbon\Carbon::parse($izin->tanggal)->format('d-m-Y') }}</td>
                                        <td>{{ $izin->alasan }}</td>
                                        <td>
                                            @if ($izin->status_izin === 'Disetujui')
                                                <span class="badge badge-success">{{ $izin->status_izin }}</span>
                                            @elseif ($izin->status_izin === 'Ditolak')
                                                <span class="badge badge-danger">{{ $izin->status_izin }}</span>
                                            @else
                                                <span class="badge badge-warning">{{ $izin->status_izin }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Belum ada riwayat izin.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<script>
$(function () {
    bsCustomFileInput.init();
});
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script>
    @if (session()->has('success'))
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: '{{ session('success') }}',
        timer: 5000,
        showConfirmButton: false
    });
    @endif
</script>
@endsection

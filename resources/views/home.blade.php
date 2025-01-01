@extends('layouts.main')
@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                @can('admin')
                                <h5>Daftar Kelas dan Status KBM</h5>
                                <p><strong>Tanggal:</strong> {{ $hariIni }}</p>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama Kelas</th>
                                            <th>Status KBM</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($kelas as $index => $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->nama_kelas }}</td>
                                                <td>{{ $item->status }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center">Tidak ada data kelas.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                @endcan
                                @can('guru' || 'siswa' || 'karyawan')
                                    <p class="card-title">Assalaamu’alaikum Warahmatullaahi Wabarakaatuh. {{ Auth::user()->name }}</p>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script>
    @if (session()->has('error'))
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 5000
    });
        Toast.fire({
        icon: 'error',
        title: '{{ session('error') }}'
        })
    @endif
</script>
@endsection

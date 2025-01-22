@extends('layouts.main')

@section('css')
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Data Izin Pendidik</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Data Izin Pendidik</li>
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
                            <h3 class="card-title">Data Izin Pendidik</h3>
                        </div>
                        <div class="card-body">
                            <table id="table_izin_pendidik" class="table table-bordered table-striped">
                                <thead>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Jam Izin</th>
                                    <th>Nama Pemohon</th>
                                    <th>Alasan</th>
                                    <th>Status Izin</th>
                                    <th>File Pendukung</th>
                                    <th>Yang Memberi Izin</th>
                                    <th>Menu</th>
                                </thead>
                                <tbody>
                                    @foreach ($data_izin_pendidik as $izin_pendidik)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $izin_pendidik->tanggal }}</td>
                                        <td>{{ $izin_pendidik->jam_izin }}</td>
                                        <td>{{ $izin_pendidik->nama_pemohon }}</td>
                                        <td>{{ $izin_pendidik->alasan }}</td>
                                        <td>{{ $izin_pendidik->status_izin }}</td>
                                        <td><a target="_blank" href="{{ asset('storage/file_izin_pendidik/'.$izin_pendidik->file) }}">lihat</a></td>
                                        <td>{{ $izin_pendidik->nama_pemberi_izin }}</td>
                                        <td>
                                            <!-- Tombol ACC -->
                                            <button class="btn btn-success float-right m-1" onclick="confirmAcc({{ $izin_pendidik->id }})">
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                            <!-- Tombol Tolak -->
                                            <button class="btn btn-danger float-right m-1" onclick="confirmReject({{ $izin_pendidik->id }})">
                                                <i class="fa-solid fa-xmark"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
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
<!-- DataTables & Plugins -->
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('assets/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
<script src="{{ asset('assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>

<script>
$(function () {
    $("#table_izin_pendidik").DataTable({
        "responsive": true, 
        "lengthChange": true, 
        "autoWidth": true, 
        "pageLength": 50,
        "aLengthMenu": [
            [25, 50, 100, 200, -1],
            [25, 50, 100, 200, "All"]
        ],
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#table_izin_pendidik_wrapper .col-md-6:eq(0)');
});
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script>
    @if (session()->has('success'))
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 5000
    });
        Toast.fire({
        icon: 'success',
        title: '{{ session('success') }}'
        })
    @endif
</script>
<script>
    function confirmAcc(id) {
        Swal.fire({
            title: 'Pilih Jenis Izin',
            input: 'select',
            inputOptions: {
                '3': 'Izin',
                '4': 'Sakit'
            },
            inputPlaceholder: 'Pilih jenis izin',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ACC',
            cancelButtonText: 'Batal',
            inputValidator: (value) => {
                if (!value) {
                    return 'Anda harus memilih jenis izin!';
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect ke route untuk ACC dengan tambahan parameter jenis izin
                const jenisIzin = result.value; // Nilai dari input (3 atau 4)
                window.location.href = `/izin_pendidik/acc/${id}?jenis_izin=${jenisIzin}`;
            }
        });
    }

    function confirmReject(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data akan ditolak!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Tolak!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect ke route untuk Tolak
                window.location.href = `/izin_pendidik/reject/${id}`;
            }
        });
    }
</script>
@endsection

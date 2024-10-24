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
                    <h1 class="m-0">Kegiatan Belajar Mengajar</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Data Kegiatan Belajar Mengajar</li>
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
                                <h3 class="card-title">Data Kegiatan Belajar Mengajar</h3>
                                @if($user->id_role == '1' || $isKmKelas)
                                <a href="/form_tambah_kbm" class="btn btn-primary float-right">Tambah KBM</a>
                                @endif
                            </div>
                            <div class="card-body">
                                <table id="table_kbm" class="table table-bordered table-striped">
                                    <thead>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Guru</th>
                                        <th>Kelas</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Jam Ke</th>
                                        <th>Foto Masuk</th>
                                        <th>Jam Masuk</th>
                                        <th>Foto Keluar</th>
                                        <th>Jam Keluar</th>
                                        <th>keterangan</th>
                                        <th class="text-center">Menu</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($data_kbm as  $kbm)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ \Carbon\Carbon::parse($kbm->tanggal)->translatedFormat('d F Y') }}</td>
                                            <td>{{ $kbm->nama_guru }}</td>
                                            <td>{{ $kbm->nama_kelas }}</td>
                                            <td>{{ $kbm->nama_mapel }}</td>
                                            <td>{{ $kbm->jam_ke }}</td>
                                            <td>
                                                <a href="{{ asset('/storage/foto_masuk_kbm/'.$kbm->foto_masuk) }}" target="_blank"><img src="{{ asset('/storage/foto_masuk_kbm/'.$kbm->foto_masuk) }}" width="100px"></a>
                                            </td>
                                            <td>{{ $kbm->jam_masuk }}</td>
                                            <td>
                                                @if($kbm->foto_keluar == null)
                                                <span class="badge badge-danger">Belum Ada</span>
                                                @else
                                                <a href="{{ asset('/storage/foto_keluar_kbm/'.$kbm->foto_keluar) }}" target="_blank"><img src="{{ asset('/storage/foto_keluar_kbm/'.$kbm->foto_keluar) }}" width="100px"></a>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($kbm->jam_keluar == null)
                                                    00:00:00
                                                @else
                                                {{ $kbm->jam_keluar }}
                                                @endif
                                            </td>
                                            <td>{{ $kbm->keterangan }}</td>
                                            
                                            @if($user->id_role == '1')
                                                <td class="text-center">
                                                    <a href="/form_edit_kbm_{{ $kbm->id }}" class="btn btn-info m-1"><i class="fa-solid fa-pen-to-square"></i></a>
                                                    <button class="btn btn-danger m-1" onclick="confirmDelete({{ $kbm->id }})"><i class="fa-solid fa-trash-can"></i></button>
                                                </td>
                                            @elseif($user->id_role == '2' || $isWalas)
                                                @if ($kbm->jam_keluar == null)
                                                <td class="text-center">
                                                <a href="/lihat_presensi_{{ $kbm->id }}" class="btn btn-info m-1"><i class="fa-solid fa-eye"></i></a>
                                                </td>
                                                @else
                                                <td>
                                                    <span class="badge badge-success">Selesai</span>
                                                </td>
                                                @endif
                                            @elseif($isKmKelas)
                                                @if ($kbm->jam_keluar == null)
                                                <td class="text-center">
                                                    <a href="/presensi_siswa_{{ $kbm->id }}" class=" btn btn-info m-1"><i class="fa-solid fa-hand-point-up"></i></i></a>
                                                    <a href="/form_selesai_kbm_{{ $kbm->id }}" class="btn btn-primary m-1"><i class="fa-regular fa-circle-check"></i></a>
                                                </td>
                                                @else
                                                <td>
                                                    <span class="badge badge-success">Selesai</span>
                                                </td>
                                                @endif
                                            @elseif($user->id_role == '4')
                                                @if ($kbm->jam_keluar == null)
                                                <td class="text-center">
                                                    <a href="/absen_kbm_{{ $kbm->id }}" class=" btn btn-info m-1"><i class="fa-solid fa-hand-point-up"></i></i></a>
                                                </td>
                                                @else
                                                <td>
                                                    <span class="badge badge-success">Selesai</span>
                                                </td>
                                                @endif
                                            @endif
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
<!-- DataTables  & Plugins -->
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
$("#table_kbm").DataTable({
    "responsive": true, 
    "lengthChange": true, 
    "autoWidth": true, 
    "pageLength": 50,
    "aLengthMenu": [
        [25, 50, 100, 200, -1],
        [25, 50, 100, 200, "All"]
    ],
    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
}).buttons().container().appendTo('#table_kbm_wrapper .col-md-6:eq(0)');
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
    @if (session()->has('error'))
        Swal.fire({
            icon: 'error',
            title: '{{ session('error') }}',
            showConfirmButton: true, // Show the confirm button
        });
    @endif
</script>
<script>
    function confirmDelete(roleId) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data tidak dapat dikembalikan setelah dihapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `/hapus_kbm_${roleId}`;
            }
        });
    }
</script>
@endsection

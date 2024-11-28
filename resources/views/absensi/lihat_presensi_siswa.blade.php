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
                    <h1 class="m-0">KBM</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Lihat Presensi Siswa</li>
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
                                <h3 class="card-title">Lihat Presensi Siswa</h3>
                            </div>
                            <div class="card-body">
                                <table id="table_presensi_siswa" class="table table-bordered table-striped">
                                    <thead>
                                        <th>No</th>
                                        <th>Kelas</th>
                                        <th>Nama Siswa</th>
                                        <th>Foto</th>
                                        <th>Jam Masuk</th>
                                        <th>Status</th>
                                        <th>Menu</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($data_absensi as $absensi)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $absensi->nama_kelas }}</td>
                                            <td>{{ $absensi->nama_siswa }}</td>
                                            <td><img width="100px" src="{{ asset('/storage/foto_absensi_siswa/'.$absensi->foto) }}" alt="foto_presensi"></td>
                                            <td>{{ $absensi->jam_hadir }}</td>
                                            <td>{{ $absensi->status_hadir }}</td>
                                            <td>
                                                <select class="form-select form-control status-selector" data-id="{{ $absensi->id }}">
                                                    <option value="1" {{ $absensi->status_hadir === 'Hadir' ? 'selected' : '' }}>Hadir</option>
                                                    <option value="2" {{ $absensi->status_hadir === 'Alfa' ? 'selected' : '' }}>Alfa</option>
                                                    <option value="3" {{ $absensi->status_hadir === 'Izin' ? 'selected' : '' }}>Izin</option>
                                                    <option value="4" {{ $absensi->status_hadir === 'Sakit' ? 'selected' : '' }}>Sakit</option>
                                                    <option value="5" {{ $absensi->status_hadir === 'Bolos' ? 'selected' : '' }}>Bolos</option>
                                                </select>
                                            </td>
                                        </th>
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
$("#table_presensi_siswa").DataTable({
    "responsive": true, 
    "lengthChange": true, 
    "autoWidth": true, 
    "pageLength": 50,
    "aLengthMenu": [
        [25, 50, 100, 200, -1],
        [25, 50, 100, 200, "All"]
    ],
    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
}).buttons().container().appendTo('#table_presensi_siswa_wrapper .col-md-6:eq(0)');
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
                window.location.href = `/hapus_role_${roleId}`;
            }
        });
    }
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectors = document.querySelectorAll('.status-selector');

    selectors.forEach(selector => {
        selector.addEventListener('change', function () {
            const id_siswa = selector.getAttribute('data-id');
            const id_status_hadir = this.value;
            console.log('absensi_siswa_id:', id_siswa);
            console.log('New Status:', id_status_hadir);

            // Kirim data ke server menggunakan fetch
            fetch('/update-status-absensi', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({
                    id_siswa: id_siswa,
                    id_status_hadir: id_status_hadir
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Gagal memperbarui status');
                }
                return response.json();
            })
            .then(data => {
                alert(data.message);
            })
            .catch(error => {
                console.error('Terjadi kesalahan:', error);
                alert('Gagal memperbarui status. Silakan coba lagi.');
            });
        });
    });
});
</script>
@endsection

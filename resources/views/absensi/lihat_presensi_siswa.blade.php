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
                                            <td class="jam-hadir">{{ $absensi->jam_hadir }}</td>
                                            <td class="status-hadir">{{ $absensi->status_hadir }}</td>
                                            <td>
                                                <select class="form-select form-control status-selector" data-id_siswa="{{ $absensi->id_siswa }}">
                                                    <option value="2" {{ $absensi->id_status_hadir == 2 ? 'selected' : '' }}>Alfa</option>
                                                    <option value="1" {{ $absensi->id_status_hadir == 1 ? 'selected' : '' }}>Hadir</option>
                                                    <option value="3" {{ $absensi->id_status_hadir == 3 ? 'selected' : '' }}>Izin</option>
                                                    <option value="4" {{ $absensi->id_status_hadir == 4 ? 'selected' : '' }}>Sakit</option>
                                                    <option value="5" {{ $absensi->id_status_hadir == 5 ? 'selected' : '' }}>Bolos</option>
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
            const id_kbm = '{{ $id }}'; // Pastikan id_kbm dikirim dari controller ke view
            const id_kelas = '{{ $id_kelas }}'; // Pastikan id_kbm dikirim dari controller ke view
            const id_siswa = this.getAttribute('data-id_siswa');
            const id_status_hadir = this.value;

            console.log('id_kbm:', id_kbm);
            console.log('id_kelas:', id_kelas);
            console.log('id_siswa:', id_siswa);
            console.log('id_status_hadir:', id_status_hadir);

            // Kirim data ke server menggunakan fetch
            fetch('/update-status-absensi', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({
                    id_kbm: id_kbm,
                    id_kelas: id_kelas,
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
                var Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 5000
                    });
                        Toast.fire({
                        icon: 'success',
                        title: 'Absensi berhasil diperbarui'
                        })

                // Perbarui kolom terkait di baris yang sama
                const row = selector.closest('tr'); // Cari baris tabel terkait
                const jamHadirCell = row.querySelector('.jam-hadir'); // Kolom jam hadir
                const statusHadirCell = row.querySelector('.status-hadir'); // Kolom status hadir

                // Perbarui teks berdasarkan respons
                if (jamHadirCell) {
                    jamHadirCell.textContent = data.jam_hadir; // Update jam hadir
                }
                if (statusHadirCell) {
                    statusHadirCell.textContent = data.status_hadir; // Update status hadir
                }
            })
            .catch(error => {
                console.error('Terjadi kesalahan:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Gagal memperbarui status. Silakan coba lagi.',
                    timer: 2000,
                    showConfirmButton: false
                });
            });
        });
    });
});
</script>
@endsection

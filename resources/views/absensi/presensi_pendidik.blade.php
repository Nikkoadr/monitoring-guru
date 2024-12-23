@extends('layouts.main')
@section('css')
<!-- Ionicons -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    .kamera,
    .kamera video {
        display: inline-block;
        width: 100% !important;
        margin: auto;
        height: auto !important;
        border-radius: 15px;
    }
</style>
<style>
    #map {
        height: 620px;
        }
</style>
@endsection
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0">Absensi</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Admin</a></li>
            <li class="breadcrumb-item active">Absensi</li>
        </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- Bagian Kamera dan Tombol Absen -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Kamera Anda</h3>
                    </div>
                    <div class="card-body">
                        <input type="hidden" id="lokasi">
                        <div class="kamera">
                            <!-- Isi dengan elemen kamera jika ada -->
                        </div>
                        <div class="row mt-3">
                        <div class="col">
                            @if($cek > 0)
                                <button id="ambil_presensi" class="btn btn-danger btn-block">
                                    <i class="fa-solid fa-camera-retro"></i> Absen Pulang
                                </button>
                            @else
                                @if($jam > $setting->limit_presensi)
                                    <button id="tombolLimit" class="btn btn-primary btn-block disabled">
                                        <i class="fa-solid fa-camera-retro"></i> Absen Masuk
                                    </button>
                                    @else
                                    <button id="ambil_presensi" class="btn btn-primary btn-block">
                                        <i class="fa-solid fa-camera-retro"></i> Absen Masuk
                                    </button>
                                @endif
                            @endif
                        </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bagian Peta -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Peta Lokasi</h3>
                    </div>
                    <div class="card-body">
                        <section id="map"></section>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</div>

</div>
<!-- /.content-wrapper -->
@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
        Webcam.set({
        width: 320,
        height: 240,
        image_format: 'jpeg',
        flip_horiz: true,
        jpeg_quality: 60
    });
    Webcam.attach( '.kamera' );
</script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var lokasiInput = document.getElementById('lokasi');

        function initMap(lat, lng) {
            if (lokasiInput) {
                lokasiInput.value = lat + "," + lng;
            }

            var map = L.map('map').setView([lat, lng], 16);
            var lokasiPresensiLat = parseFloat("{{ $setting->lokasi_latitude }}");
            var lokasiPresensiLng = parseFloat("{{ $setting->lokasi_longitude }}");
            var lokasiPresensiRadius = parseFloat("{{ $setting->radius_lokasi }}");

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            L.marker([lat, lng]).addTo(map);
            L.circle([lokasiPresensiLat, lokasiPresensiLng], {
                color: 'red',
                fillColor: '#f03',
                fillOpacity: 0.3,
                radius: lokasiPresensiRadius
            }).addTo(map);
        }

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function (position) {
                    initMap(position.coords.latitude, position.coords.longitude);
                },
                function (error) {
                    console.error('Error getting geolocation:', error);
                    alert('Tidak dapat mengambil lokasi. Pastikan GPS diaktifkan.');
                },
                { timeout: 10000 }
            );
        } else {
            console.error('Geolocation is not supported by this browser.');
            alert('Browser Anda tidak mendukung GPS.');
        }

        document.getElementById("ambil_presensi")?.addEventListener("click", function () {
            Webcam.snap(function (url) {
                kirimPresensi(url);
            });
        });

        function kirimPresensi(foto) {
            var lokasi = lokasiInput?.value || "";
            $.ajax({
                type: 'POST',
                url: '/post_presensi_pendidik',
                data: {
                    _token: "{{ csrf_token() }}",
                    foto: foto,
                    lokasi: lokasi
                },
                cache: false,
                success: function (respond) {
                    var { status, message } = respond;
                    if (status === "success") {
                        Swal.fire("Terimakasih", message, "success");
                        setTimeout(() => {
                            location.href = '/home';
                        }, 2000);
                    } else {
                        Swal.fire("Opss..!!!", message, "error");
                    }
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error:', error);
                    Swal.fire("Error", "Terjadi kesalahan saat mengirim presensi.", "error");
                }
            });
        }
    });
</script>
<script>
$("#tombolpulang").click(function() {
    var Toast = Swal.fire({
        title: "Opss..!!!",
        text: "Maaf Belum Waktunya Pulang ya !",
        icon: "error"
    });
});
</script>
<script>
$("#tombolLimit").click(function() {
    var Toast = Swal.fire({
        title: "Maaf !",
        text: "Absen masuknya sudak tidak bisa karena terlalu siang",
        icon: "error"
    });
});
</script>
@endsection

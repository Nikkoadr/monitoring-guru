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
                        </div>
                        <div class="row mt-3">
                        <div class="col">
                            @if(Auth::user()->dataset_wajah)
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
                            @else
                                <button id="rekam_wajah" class="btn btn-warning btn-block">
                                    <i class="fa-solid fa-video"></i> Rekam Wajah
                                </button>
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
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs"></script>
<script src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api/dist/face-api.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", async function () {
    if (typeof faceapi === "undefined") {
        console.error("face-api.js belum dimuat dengan benar!");
        return;
    }

    // Inisialisasi Webcam.js
    Webcam.set({
        width: 320,
        height: 240,
        image_format: 'jpeg',
        flip_horiz: true,
        jpeg_quality: 90
    });
    Webcam.attach('.kamera');

    // Muat model Face API
    try {
        await faceapi.nets.tinyFaceDetector.loadFromUri('/models');
        await faceapi.nets.faceLandmark68Net.loadFromUri('/models');
        await faceapi.nets.faceRecognitionNet.loadFromUri('/models');
        console.log("Model Face API berhasil dimuat");
    } catch (error) {
        console.error("Gagal memuat model Face API:", error);
        Swal.fire("Error", "Gagal memuat model Face API!", "error");
        return;
    }

    // Tangkap wajah dan proses dengan Face API
    document.getElementById("rekam_wajah")?.addEventListener("click", function () {
        Swal.fire("Proses Rekam", "Harap tunggu, sistem sedang mengenali wajah...", "info");

        Webcam.snap(async function (imageUri) {
            const img = new Image();
            img.src = imageUri;
            img.onload = async function () {
                const canvas = document.createElement("canvas");
                canvas.width = img.width;
                canvas.height = img.height;
                const ctx = canvas.getContext("2d");
                ctx.drawImage(img, 0, 0, img.width, img.height);

                const detection = await faceapi.detectSingleFace(canvas, new faceapi.TinyFaceDetectorOptions())
                    .withFaceLandmarks()
                    .withFaceDescriptor();

                if (!detection) {
                    Swal.fire("Gagal!", "Wajah tidak terdeteksi, coba lagi!", "error");
                    return;
                }

                const descriptor = Array.from(detection.descriptor);

                $.ajax({
                    type: "POST",
                    url: "/simpan_dataset_wajah",
                    data: {
                        _token: "{{ csrf_token() }}",
                        dataset_wajah: JSON.stringify(descriptor)
                    },
                    success: function (response) {
                        Swal.fire("Berhasil!", response.message, "success").then(() => location.reload());
                    },
                    error: function () {
                        Swal.fire("Error", "Gagal menyimpan wajah!", "error");
                    }
                });
            };
        });
    });
});
</script>
<script>
Webcam.set({
    width: 320,
    height: 240,
    image_format: 'jpeg',
    flip_horiz: true,
    jpeg_quality: 60
});
Webcam.attach('.kamera');

document.addEventListener("DOMContentLoaded", function () {
    let lokasiInput = document.getElementById('lokasi');
    let userLatitude = null;
    let userLongitude = null;
    
    async function initMap(lat, lng) {
        userLatitude = lat;
        userLongitude = lng;
        
        if (lokasiInput) lokasiInput.value = `${lat},${lng}`;

        let map = L.map('map').setView([lat, lng], 16);
        let lokasiPresensiLat = parseFloat("{{ $setting->lokasi_latitude }}");
        let lokasiPresensiLng = parseFloat("{{ $setting->lokasi_longitude }}");
        let lokasiPresensiRadius = parseFloat("{{ $setting->radius_lokasi }}");

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

    function checkLocation(lat, lng) {
        let lokasiPresensiLat = parseFloat("{{ $setting->lokasi_latitude }}");
        let lokasiPresensiLng = parseFloat("{{ $setting->lokasi_longitude }}");
        let lokasiPresensiRadius = parseFloat("{{ $setting->radius_lokasi }}");
        
        let distance = getDistance(lat, lng, lokasiPresensiLat, lokasiPresensiLng);
        return distance <= lokasiPresensiRadius;
    }

    function getDistance(lat1, lon1, lat2, lon2) {
        const R = 6371000;
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
            Math.sin(dLon / 2) * Math.sin(dLon / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c;
    }

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            position => initMap(position.coords.latitude, position.coords.longitude),
            error => Swal.fire('Error', 'Tidak dapat mengambil lokasi. Pastikan GPS diaktifkan.', 'error'),
            { timeout: 10000 }
        );
    } else {
        Swal.fire('Error', 'Browser Anda tidak mendukung GPS.', 'error');
    }

    document.getElementById("ambil_presensi")?.addEventListener("click", async function () {
        Swal.fire("Proses Presensi", "Sistem sedang mengenali wajah...", "info");

        Webcam.snap(async function (url) {
            const img = new Image();
            img.src = url;
            img.onload = async function () {
                const detection = await faceapi.detectSingleFace(img, new faceapi.TinyFaceDetectorOptions())
                    .withFaceLandmarks()
                    .withFaceDescriptor();
                
                if (!detection) {
                    Swal.fire("Gagal!", "Wajah tidak terdeteksi, coba lagi!", "error");
                    return;
                }
                
                const descriptorBaru = Array.from(detection.descriptor);
                
                $.ajax({
                    type: "GET",
                    url: "/ambil_dataset_wajah",
                    success: function (response) {
                        if (response.status !== "success") {
                            Swal.fire("Error", response.message || "Gagal mengambil data wajah!", "error");
                            return;
                        }

                        try {
                            let descriptorTersimpan = JSON.parse(response.data).map(Number);

                            if (!Array.isArray(descriptorTersimpan) || descriptorTersimpan.length !== 128) {
                                throw new Error("Data wajah tidak valid!");
                            }

                            const distance = faceapi.euclideanDistance(descriptorBaru, descriptorTersimpan);

                            if (distance < 0.6) {
                                if (checkLocation(userLatitude, userLongitude)) {
                                    kirimPresensi(url);
                                } else {
                                    Swal.fire("Gagal!", "Anda berada di luar area presensi!", "error");
                                }
                            } else {
                                Swal.fire("Gagal!", "Wajah tidak cocok dengan data tersimpan!", "error");
                            }
                        } catch (err) {
                            Swal.fire("Error", "Format data wajah tidak valid!", "error");
                            console.error("Parsing Error:", err);
                        }
                    },
                    error: function (xhr) {
                        Swal.fire("Error", "Gagal mengambil data wajah!", "error");
                        console.error("AJAX Error:", xhr.responseText);
                    }
                });
            };
        });
    });

    function kirimPresensi(foto) {
        let lokasi = lokasiInput?.value || "";
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
                if (respond.status === "success") {
                    Swal.fire("Terimakasih", respond.message, "success");
                    setTimeout(() => location.href = '/home', 2000);
                } else {
                    Swal.fire("Opss..!!!", respond.message, "error");
                }
            },
            error: function () {
                Swal.fire("Error", "Terjadi kesalahan saat mengirim presensi.", "error");
            }
        });
    }
});

$("#tombolpulang").click(function() {
    var Toast = Swal.fire({
        title: "Opss..!!!",
        text: "Maaf Belum Waktunya Pulang ya !",
        icon: "error"
    });
});

$("#tombolLimit").click(function() {
    var Toast = Swal.fire({
        title: "Maaf !",
        text: "Absen masuknya sudak tidak bisa karena terlalu siang",
        icon: "error"
    });
});
</script>
@endsection

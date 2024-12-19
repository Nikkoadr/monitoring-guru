@extends('layouts.main')
@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>
    #map {
        margin-bottom: 10px;
        margin-top: 10px;
        height: 120px;
        border-radius: 15px;
        }
</style>
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
                    <li class="breadcrumb-item active">Absensi Kegiatan Belajar mengajar</li>
                    </ol>
                </div>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-6">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Request Kehadiran</h3>
                            </div>
                                <div class="card-body">
                                    <p>
                                        Dengan penuh rasa tanggung jawab, saya yang bertanda tangan di bawah ini, <strong>{{ Auth::user()->name }}</strong>, siswa dari kelas <strong>{{ $kelas->nama_kelas }}</strong>, ingin menyampaikan laporan kehadiran saya dalam kegiatan belajar mengajar untuk mata pelajaran <strong>{{ $mapel->nama_mapel }}</strong>, yang diampu oleh Bapak/Ibu <strong>{{ $guru->nama_guru }}</strong>. Kehadiran ini saya laporkan tepat pada tanggal <strong>{{ $data_kbm->tanggal }}</strong>, di jam pelajaran ke-<strong>{{ $data_kbm->jam_ke }}</strong>. Saya mengikuti kegiatan pembelajaran tersebut dengan penuh antusiasme dan kesungguhan.
                                    </p>
                                    <p>
                                        Sebagai bukti konkret atas kehadiran saya dalam kegiatan tersebut, saya melampirkan foto dokumentasi yang diambil secara langsung saat proses belajar berlangsung. Foto ini merupakan bagian yang tak terpisahkan dari form permohonan ini, dan saya berharap bukti tersebut dapat membantu memvalidasi kehadiran saya dalam mata pelajaran yang dipelajari.
                                    </p>
                                    <p>
                                        Dengan penuh harap dan hormat, saya memohon agar laporan ini dapat diterima dan diproses sesuai ketentuan yang berlaku. Semoga Bapak/Ibu guru berkenan meninjau permohonan ini, dan saya berkomitmen untuk selalu aktif dalam mengikuti setiap sesi pelajaran dengan baik dan bertanggung jawab.
                                    </p>
                                    <p>
                                        Demikian laporan ini saya sampaikan. Atas perhatian dan kebijaksanaan Bapak/Ibu, saya ucapkan terima kasih yang sebesar-besarnya.
                                    </p>
                                    <div id="camera-container" class="text-center mt-2">
                                        <h5><b>Ambil Foto:</b></h5>
                                        <video id="video" width="320" height="240" autoplay class="border"></video><br>
                                        <button type="button" id="capture" class="btn btn-primary mt-2">Ambil Foto</button>
                                    </div>
                                    <div id="preview-container" class="text-center mt-3" style="display: none;">
                                        <h4>Foto telah diambil:</h4>
                                        <img id="preview-image" src="" alt="Preview Foto" class="img-thumbnail" width="320" height="240"><br>
                                        <button type="button" id="retake" class="btn btn-warning mt-2">Foto Ulang</button>
                                    </div>
                                    <canvas id="canvas" style="display: none;"></canvas>
                                    <div id="map"></div>
                                    <form action="/tambah_presensi" method="post">
                                    @csrf
                                    <input type="hidden" name="id_kbm" value="{{ $data_kbm->id }}">
                                    <input type="hidden" name="id_kelas" id="id_kelas" value="{{ $data_kbm->id_kelas }}" required>
                                    <input type="hidden" name="foto" id="foto_data" required>
                                    <input type="hidden" name="lokasi" id="lokasi" required>
                                    <button class="btn btn-primary mt-2 float-right" type="submit">Absen</button>
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
<script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
const captureButton = document.getElementById('capture');
const retakeButton = document.getElementById('retake');
const previewContainer = document.getElementById('preview-container');
const previewImage = document.getElementById('preview-image');
const cameraContainer = document.getElementById('camera-container');
const fotoData = document.getElementById('foto_data');

let currentStream;
let currentCamera = 'user';  // Default ke kamera belakang

// Fungsi untuk mengakses kamera
async function startCamera(cameraType) {
    if (currentStream) {
        currentStream.getTracks().forEach(track => track.stop()); // Hentikan stream sebelumnya
    }

    try {
        currentStream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: cameraType }
        });
        video.srcObject = currentStream;
    } catch (err) {
        console.error("Error accessing camera: ", err);
    }
}

// Panggil fungsi untuk memulai kamera
startCamera(currentCamera);

// Fungsi untuk mengambil foto
captureButton.addEventListener('click', function () {
    const context = canvas.getContext('2d');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    context.drawImage(video, 0, 0, canvas.width, canvas.height);

    const dataURL = canvas.toDataURL('image/png');
    fotoData.value = dataURL;

    // Tampilkan preview gambar
    previewImage.src = dataURL;
    previewContainer.style.display = 'block';  // Tampilkan div preview
    cameraContainer.style.display = 'none';    // Sembunyikan kamera
});

// Fungsi untuk mengambil ulang foto
retakeButton.addEventListener('click', function () {
    previewContainer.style.display = 'none';   // Sembunyikan preview gambar
    cameraContainer.style.display = 'block';   // Tampilkan kembali kamera
    startCamera(currentCamera);  // Mulai ulang kamera
});
</script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    var lokasi = document.getElementById('lokasi');
    function initMap(latitude, longitude) {
    lokasi.value = latitude + "," + longitude;
        var map = L.map('map').setView([latitude, longitude], 16);
        var lokasi_absen_latitude = "-6.362998"
        var lokasi_absen_longitude = "108.113522"
        var lokasi_absen_radius = "100"
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);
        var marker = L.marker([latitude, longitude]).addTo(map);
        var circle = L.circle([lokasi_absen_latitude, lokasi_absen_longitude], {
        color: 'red',
        fillColor: '#f03',
        fillOpacity: 0.3,
        radius: lokasi_absen_radius
    }).addTo(map);
    }

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function (position) {
                initMap(position.coords.latitude, position.coords.longitude);
            },
            function (error) {
                console.error('Error getting geolocation:', error);
            },
            { timeout: 10000 }
        );
    }
</script>
@endsection

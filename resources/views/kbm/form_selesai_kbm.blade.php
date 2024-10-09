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
                    <h1 class="m-0">Database</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Form Tambah Kegiatan Belajar Mengajar</li>
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
                                <h3 class="card-title">Form Kegiatan Belajar Mengajar</h3>
                            </div>
                            <div class="card-body">
                                <form action="/update_selesai_kbm_{{ $id }}" method="POST" enctype="multipart/form-data">
                                    @method('put')
                                    @csrf
                                    <label class="form-label" for="jam_keluar">Jam keluar:</label>
                                    <input type="time" class="form-control" name="jam_keluar" id="jam_keluar" value="{{ $jam_sekarang }}" readonly>

                                    <div id="camera-container" class="text-center mt-2">
                                        <h5><b>Ambil Foto:</b></h5>
                                        <video id="video" width="320" height="240" autoplay class="border"></video><br>
                                        <button type="button" id="capture" class="btn btn-primary mt-2">Ambil Foto</button>
                                        <button type="button" id="switch-camera" class="btn btn-secondary mt-2">Switch Kamera</button>
                                    </div>

                                    <div id="preview-container" class="text-center mt-3" style="display: none;">
                                        <h4>Foto telah diambil:</h4>
                                        <img id="preview-image" src="" alt="Preview Foto" class="img-thumbnail" width="320" height="240"><br>
                                        <button type="button" id="retake" class="btn btn-warning mt-2">Foto Ulang</button>
                                    </div>

                                    <canvas id="canvas" style="display: none;"></canvas>
                                    <input type="hidden" name="foto_keluar" id="foto_keluar_data">
                                    <button class="btn btn-primary mt-2 float-right" type="submit">Simpan</button>
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
const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
const captureButton = document.getElementById('capture');
const retakeButton = document.getElementById('retake');
const switchCameraButton = document.getElementById('switch-camera');
const previewContainer = document.getElementById('preview-container');
const previewImage = document.getElementById('preview-image');
const cameraContainer = document.getElementById('camera-container');
const fotoMasukData = document.getElementById('foto_keluar_data');

let currentStream;
let currentCamera = 'environment';  // Default ke kamera belakang

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
    fotoMasukData.value = dataURL;

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

// Fungsi untuk switch kamera
switchCameraButton.addEventListener('click', function () {
    // Toggle kamera antara 'environment' (belakang) dan 'user' (depan)
    currentCamera = (currentCamera === 'environment') ? 'user' : 'environment';
    startCamera(currentCamera);  // Mulai ulang dengan kamera yang dipilih
});
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

@if ($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            html: `
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>`,
            confirmButtonText: 'OK'
        });
    </script>
@endif
@endsection

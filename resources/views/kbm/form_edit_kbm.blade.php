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
                                <form action="/update_kbm_{{ $data_kbm->id }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('put')
                                    <label for="tanggal" class="form-label">Tanggal: </label>
                                    <input type="date" class="form-control" name="tanggal" id="tanggal" value="{{ $data_kbm->tanggal }}" required>
                                    <label for="jam_ke" class="form_label">Jam Ke: </label>
                                    <select class="form-control" name="jam_ke" id="jam_ke" required>
                                        <option value="">Pilih Jam Ke</option>
                                        <option value="1" {{ $data_kbm->jam_ke == 1 ? 'selected' : '' }}>1</option>
                                        <option value="2" {{ $data_kbm->jam_ke == 2 ? 'selected' : '' }}>2</option>
                                        <option value="3" {{ $data_kbm->jam_ke == 3 ? 'selected' : '' }}>3</option>
                                        <option value="4" {{ $data_kbm->jam_ke == 4 ? 'selected' : '' }}>4</option>
                                        <option value="5" {{ $data_kbm->jam_ke == 5 ? 'selected' : '' }}>5</option>
                                    </select>
                                    <label for="jam_masuk" class="form_label">Jam Masuk: </label>
                                    <input type="time" class="form-control" name="jam_masuk" id="jam_masuk" value="{{ $data_kbm->jam_masuk }}" required>
                                    <label for="jam_keluar" class="form_label">Jam Keluar: </label>
                                    <input type="time" class="form-control" name="jam_keluar" id="jam_keluar" value="{{ $data_kbm->jam_keluar }}">
                                    <label class="form-label" for="keterangan">Keterangan: </label>
                                    <textarea class="form-control" name="keterangan" id="keterangan" rows="5" required >{{ $data_kbm->keterangan }}</textarea>
                                    <button class="btn btn-primary mt-2 float-right" type="submit">Simpan</button>
                                    <a href="/kbm" class="btn btn-danger mt-2 float-right mr-2">Batal</a>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#id_mapel').on('change', function() {
            var mapelId = $(this).val();
            if (mapelId) {
                $.ajax({
                    url: '/get_guru_by_mapel_' + mapelId,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('#id_guru').empty();
                        $('#id_guru').append('<option value="">Pilih Guru Mata Pelajaran</option>');
                        $.each(data, function(key, value) {
                            $('#id_guru').append('<option value="' + value.id + '">' + value.nama_guru + '</option>');
                        });
                    }
                });
            } else {
                $('#id_guru').empty();
                $('#id_guru').append('<option value="">Pilih Guru Mata Pelajaran</option>');
            }
        });
    });
</script>
<script>
const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
const captureButton = document.getElementById('capture');
const retakeButton = document.getElementById('retake');
const switchCameraButton = document.getElementById('switch-camera');
const previewContainer = document.getElementById('preview-container');
const previewImage = document.getElementById('preview-image');
const cameraContainer = document.getElementById('camera-container');
const fotoMasukData = document.getElementById('foto_masuk_data');

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
@endsection

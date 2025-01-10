@extends('layouts.main')
@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
.flip-card
{
    transform: rotateY(180deg);
    -webkit-transform:rotateY(180deg); /* Safari and Chrome */
    -moz-transform:rotateY(180deg); /* Firefox */
}
</style>
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
                                <form action="/tambah_kbm" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <label class="form-label" for="tanggal">Tanggal:</label>
                                    <input type="date" class="form-control" name="tanggal" id="tanggal" value="{{ $tanggal_sekarang }}" readonly>

                                    <label class="form-label" for="jam_masuk">Jam Masuk:</label>
                                    <input type="time" class="form-control" name="jam_masuk" id="jam_masuk" value="{{ $jam_sekarang }}" readonly>

                                    <label class="form-label" for="jam_ke">Jam Ke:</label>
                                    <select class="form-control" name="jam_ke" id="jam_ke" required>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                    <label class="form-label" for="id_kelas">Kelas:</label>
                                    @if ($isKmKelas)
                                        <select class="form-control" name="id_kelas" id="id_kelas">
                                            <option value="{{ $data_kelas->id }}">{{ $data_kelas->nama_kelas }}</option>
                                        </select>
                                    @elseif(Gate::allows('admin'))
                                        <select class="form-control" name="id_kelas" id="id_kelas" required>
                                            @foreach($data_kelas as $kelas)
                                                <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                    <label class="form-label" for="id_mapel">Mata Pelajaran:</label>
                                    <select class="form-control" name="id_mapel" id="id_mapel" required>
                                        <option value="">Pilih Mata Pelajaran</option>
                                        @foreach ($data_mapel as $mapel)
                                            <option value="{{ $mapel->id }}">{{ $mapel->nama_mapel }}</option>
                                        @endforeach
                                    </select>
                                    <label class="form-label" for="id_guru">Guru Mata Pelajaran:</label>
                                    <select class="form-control" name="id_guru" id="id_guru" required>
                                        <option value="">Pilih Guru Mata Pelajaran</option>
                                    </select>
                                    <label class="form-label" for="keterangan">keterangan:</label>
                                    <textarea class="form-control" name="keterangan" id="keterangan" rows="5" required></textarea>
                                    <div id="camera-container" class="text-center mt-2">
                                        <h5><b>Ambil Foto:</b></h5>
                                        <video id="video" width="320" height="240" autoplay class="border flip-card"></video><br>
                                        <button type="button" id="capture" class="btn btn-primary mt-2">Ambil Foto</button>
                                        <button type="button" id="switch-camera" class="btn btn-secondary mt-2">Switch Kamera</button>
                                    </div>

                                    <div id="preview-container" class="text-center mt-3" style="display: none;">
                                        <h4>Foto telah diambil:</h4>
                                        <img id="preview-image" src="" alt="Preview Foto" class="img-thumbnail flip-card" width="320" height="240"><br>
                                        <button type="button" id="retake" class="btn btn-warning mt-2">Foto Ulang</button>
                                    </div>

                                    <canvas id="canvas" style="display: none;"></canvas>
                                    <input type="hidden" name="foto_masuk" id="foto_masuk_data" required>
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

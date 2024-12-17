<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Izin Keluar Sekolah</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .container {
            width: 100%;
            max-width: 21cm;
            margin: 0 auto;
            padding: 20px;
            text-align: center;
            border: 1px solid #000; /* Tambahkan border untuk tampilan seperti kertas */
        }

        /* Header Section */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header img {
            width: 100px;
            height: auto;
        }

        .title {
            text-align: center;
            flex-grow: 1;
        }

        .title h1, .title h2 {
            margin: 5px 0;
            color: #0073e6; /* Warna biru untuk judul */
        }

        .title h1 {
            font-size: 15px;
            font-weight: bold;
        }

        .title h2 {
            font-size: 20px;
            font-weight: bold;
        }

        .title h3, .title p {
            margin: 2px 0;
            font-size: 12px;
        }

        /* Garis Pembatas */
        .divider {
            border-top: 2px solid black;
            border-bottom: 1px solid black;
            margin: 10px 0;
        }

        /* Content Section */
        .content {
            font-size: 14px;
            text-align: left;
            line-height: 1.6;
        }

        .content p {
            margin: 10px 0;
        }

        .info {
            margin-left: 30px;
        }

        /* Footer Section */
        .footer {
            text-align: end;
            margin-right: 60px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <!-- Logo Kiri -->
            <img src="{{ asset('assets/dist/img/dikdasmenmuh.png') }}" alt="Logo Kiri">
            <!-- Judul Tengah -->
            <div class="title">
                <h1>MAJELIS PENDIDIKAN DASAR MENENGAH DAN PENDIDIKAN NON FORMAL</h1>
                <h2>SMK MUHAMMADIYAH KANDANGHAUR</h2>
                <h3>SMK PUSAT KEUNGGULAN (PK)</h3>
                <p>Terakreditasi "A" (Unggul) | Nomor: 1857/022/BAN-SM/SK/2022</p>
                <p>Konsentrasi Keahlian: TKR, TSM, TPL, TEI, TKJ, FKK</p>
                <p>Jl. Raya Karanganyar No. 28/A, Kec. Kandanghaur, Kab. Indramayu | Telp: 081122207770</p>
                <p>Email: smkmuhkandanghaur@gmail.com | Website: smkmuhkandanghaur.sch.id</p>
            </div>
            <!-- Logo Kanan -->
            <img style="width: 80px" src="{{ asset('assets/dist/img/logo.png') }}" alt="Logo Kanan">
        </div>

        <!-- Garis Pembatas -->
        <div class="divider"></div>

        <!-- Isi Surat -->
        <div class="content">
            <h2 style="text-align: center">SURAT IZIN KELUAR SEKOLAH SEMENTARA</h2>
            <p>Dengan ini kami sampaikan bahwa:</p>
                <table style="margin-left: 80px">
                    <tr>
                        <td><b>Nama Siswa</b></td>
                        <td>:</td>
                        <td>{{ $data_izin_siswa->nama_siswa }}</td>
                    </tr>
                    <tr>
                        <td><b>Kelas</b></td>
                        <td>:</td>
                        <td>{{ $data_izin_siswa->nama_kelas }}</td>
                    </tr>
                    <tr>
                        <td><b>Keperluan</b></td>
                        <td>:</td>
                        <td>{{ $data_izin_siswa->alasan }}</td>
                    </tr>
                    <tr>
                        <td><b>Tanggal</b></td>
                        <td>:</td>
                        <td>{{ $data_izin_siswa->tanggal }}</td>
                    </tr>
                    <tr>
                        <td><b>Waktu Keluar</b></td>
                        <td>:</td>
                        <td>{{ $data_izin_siswa->jam_keluar }} WIB</td>
                    </tr>
                    <tr>
                        <td><b>Waktu Kembali</b></td>
                        <td>:</td>
                        <td>{{ $data_izin_siswa->jam_kembali }} WIB</td>
                    </tr>
                </table>
            <p>Telah diberikan izin untuk keluar dari lingkungan sekolah sementara waktu dalam rangka keperluan yang telah disebutkan di atas.</p>
            <p>Demikian surat izin ini dibuat dengan sebenar-benarnya dan diberikan untuk dapat digunakan sebagaimana mestinya. Atas perhatian dan pengertiannya, kami ucapkan terima kasih.</p>
        </div>
        <div class="footer">
            <div>
                <p>
                    {!! QrCode::size(100)->backgroundColor(255,255,255)->generate('https://absensi.smkmuhkandanghaur.sch.id/scan/izin/'.$data_izin_siswa->id) !!}
                </p>
            </div>
        </div>
    </div>
</body>
</html>

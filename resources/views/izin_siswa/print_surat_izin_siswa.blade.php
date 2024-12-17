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
            padding: 10px;
            text-align: center;
            border: none; /* Hilangkan border untuk cetak */
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
            color: #0073e6;
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
            font-size: 12px; /* Ukuran font lebih kecil */
            text-align: left;
            line-height: 1.4;
        }

        .content p {
            margin: 8px 0;
        }

        table {
            margin-left: 50px;
            font-size: 12px;
        }

        /* Footer Section */
        .footer {
            text-align: end;
            margin-right: 20px;
        }

        /* Print Styling */
        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .container {
                border: none;
                margin: 0;
                padding: 0;
            }

            @page {
                size: A4;
                margin: 2; /* Nol margin di seluruh halaman cetak */
            }

            .header img {
                width: 80px;
            }

            .footer {
                margin-right: 10px;
            }

            .content {
                font-size: 11px; /* Sesuaikan ukuran font untuk cetak */
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <img src="{{ asset('assets/dist/img/dikdasmenmuh.png') }}" alt="Logo Kiri">
            <div class="title">
                <h1>MAJELIS PENDIDIKAN DASAR MENENGAH DAN PENDIDIKAN NON FORMAL</h1>
                <h2>SMK MUHAMMADIYAH KANDANGHAUR</h2>
                <h3>SMK PUSAT KEUNGGULAN (PK)</h3>
                <p>Terakreditasi "A" (Unggul) | Nomor: 1857/022/BAN-SM/SK/2022</p>
                <p>Konsentrasi Keahlian: TKR, TSM, TPL, TEI, TKJ, FKK</p>
                <p>Jl. Raya Karanganyar No. 28/A, Kec. Kandanghaur, Kab. Indramayu | Telp: 081122207770</p>
                <p>Email: smkmuhkandanghaur@gmail.com | Website: smkmuhkandanghaur.sch.id</p>
            </div>
            <img style="width: 80px" src="{{ asset('assets/dist/img/logo.png') }}" alt="Logo Kanan">
        </div>

        <!-- Garis Pembatas -->
        <div class="divider"></div>
        <!-- Isi Surat -->
        <div class="content">
            <h3 style="text-align: center"><b>SURAT IZIN KELUAR SEKOLAH SEMENTARA</b></h3>
            <p>Dengan ini kami sampaikan bahwa:</p>
            <table>
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
                <tr>
                    <td><b>Status Izin</b></td>
                    <td>:</td>
                    <td>{{ $data_izin_siswa->status_izin }}</td>
                </tr>
            </table>
            <p>Telah diberikan izin untuk keluar dari lingkungan sekolah sementara waktu dalam rangka keperluan yang telah disebutkan di atas.</p>
            <p>Demikian surat izin ini dibuat dengan sebenar-benarnya dan diberikan untuk dapat digunakan sebagaimana mestinya.</p>
        </div>
        <!-- Footer -->
        <div class="footer">
            <p>
                {!! QrCode::size(80)->backgroundColor(255,255,255)->generate('https://absensi.smkmuhkandanghaur.sch.id/scan/izin/'.$data_izin_siswa->id) !!}
            </p>
        </div>
    </div>
</body>
<script>
    window.onload = function() {
        window.print();
    };
</script>
</html>

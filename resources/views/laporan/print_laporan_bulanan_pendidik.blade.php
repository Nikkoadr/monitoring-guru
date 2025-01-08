<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
<title>Rekap Absensi Bulan {{ \Carbon\Carbon::createFromFormat('Y-m-d', $tanggalAwal)->translatedFormat('d F Y') }} - {{ \Carbon\Carbon::createFromFormat('Y-m-d', $tanggalAkhir)->translatedFormat('d F Y') }}</title>

    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h2, h3 {
            text-align: left;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        th, td {
            padding: 8px;
            text-align: center;
            font-size: 10px;
        }

        th {
            background-color: #f2f2f2;
        }
        td.saturday,
        td.sunday {
            background-color: red;
            color: white;
        }
    </style>
</head>
<body>
<section>
    <div class="page-landscape">
        <table>
            <tr>
                <td style="padding: 1px" width="100px" align="center" valign="middle">
                    <img src="{{asset('assets/dist/img/dikdasmenmuh.png')}}" width="100%">
                </td>
                <td style="padding: 1px" align="center" valign="middle">
                    <b style="color:#007bff;font-size:13pt !important;">MAJELIS PENDIDIKAN DASAR MENENGAH DAN PENDIDIKAN NON FORMAL</b><br>
                    <b style="color:#007bff;font-size:13pt !important;">PIMPINAN WILAYAH MUHAMMADIYAH JAWA BARAT</b><br>
                    <b style="color:#007bff;font-size:15pt !important;">SMK MUHAMMADIYAH KANDANGHAUR</b><br>
                    <b style="color:#007bff;;font-size:15pt !important;">SMK PUSAT KEUNGGULAN (PK)</b><br>
                    <b>Terakreditasi "A" (Unggul)</b><br>
                    <b>Nomor : 18572022/BAN-SM/SK/2022</b>
                </td>
                <td style="padding: 1px" width="100px" align="center" valign="middle">
                    <img src="{{asset('assets/dist/img/logo.png')}}" width="70%">
                </td>
            </tr>
            <tr>
                <td style="padding: 1px" colspan="3" align="center">
                    <small style="font-size:8pt !important;">Konsentrasi Keahlian : Teknik Kendaraan Ringan (TKR),Teknik Sepeda Motor(TSM), Teknik Pengelasan (TPL), Teknik Elektronika Industri(TEI), Teknik Komputer dan Jaringan(TKJ), Farmasi Klinis Dan Komunitas(FKK)</small><br>
                    <small style="font-size:8pt !important;">Jl. Raya Karanganyar No. 28/A Kec. Kandanghaur Kab. Indramayu 45254 Telp. 081122207770, email : smkmuhkdh@gmail.com website : https://www.smkmuhkandanghaur.sch.id</small>
                </td>
            </tr>
        </table>
        <div style="height:5px;border-bottom:solid 2px black;border-top:solid 1px black;margin:10px 0"></div>
        <div style="text-align:center; margin:10px">
            <b style="font-size:20pt !important;">Laporan Bulanan</b>
        </div>
<h3>Periode : {{ \Carbon\Carbon::createFromFormat('Y-m-d', $tanggalAwal)->translatedFormat('d F Y') }} - {{ \Carbon\Carbon::createFromFormat('Y-m-d', $tanggalAkhir)->translatedFormat('d F Y') }}</h3>
<table style="border: 1px solid black;">
    <thead>
        <tr>
            <th style="border: 1px solid black;" rowspan="2">Nama</th>
            @php
                $jumlahHari = \Carbon\Carbon::createFromFormat('Y-m-d', $tanggalAwal)
                            ->diffInDays(\Carbon\Carbon::createFromFormat('Y-m-d', $tanggalAkhir)) + 1;
            @endphp
            <th style="border: 1px solid black;" colspan="{{ $jumlahHari }}">Tanggal</th>
            <th style="border: 1px solid black;" rowspan="2">Jumlah</th>
            <th style="border: 1px solid black;" rowspan="2">Keterangan</th>
        </tr>
        <tr>
            @php
                $start = \Carbon\Carbon::createFromFormat('Y-m-d', $tanggalAwal);
                $end = \Carbon\Carbon::createFromFormat('Y-m-d', $tanggalAkhir);
            @endphp
            @while ($start <= $end)
                <th style="border: 1px solid black;">{{ $start->day }}</th>
                @php
                    $start->addDay();
                @endphp
            @endwhile
        </tr>
    </thead>
    <tbody>
    @foreach ($rekap as $data)
        <tr>
            <td style="border: 1px solid black;">{{ $data->nama }}</td>
            @php
                $start = \Carbon\Carbon::createFromFormat('Y-m-d', $tanggalAwal);
                $end = \Carbon\Carbon::createFromFormat('Y-m-d', $tanggalAkhir);
                $total = 0;
            @endphp
            @while ($start <= $end)
                <td style="border: 1px solid black;">
                    @if ($data->{'tgl_'.$start->day})
                        @php
                            list($jamMasuk, $jamKeluar) = explode('-', $data->{'tgl_'.$start->day});
                        @endphp
                            H
                        @php
                            $total++;
                        @endphp
                    @endif
                </td>
                @php
                    $start->addDay();
                @endphp
            @endwhile
            <td style="border: 1px solid black;">{{ $total }}</td>
        <td style="border: 1px solid black;">
            1
        </td>
    @endforeach
</tbody>
</table>
</div>
</section>
</body>
    <script> window.print(); </script>
</html>
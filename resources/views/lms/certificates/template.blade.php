<!DOCTYPE html>
<html>

<head>
    <title>Sertifikat</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        body {
            font-family: "Arial", sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            width: 257mm;
            height: 190mm;
            padding: 10mm 20mm;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
            background-image: url("{{ asset('files/backgroud-certificate.png') }}");
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        }

        .header {
            border-bottom: 2px solid #1a73e8;
            padding-bottom: 10px;
            margin-bottom: 20px;
            text-align: center;
        }

        .header-logo {
            height: 70px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .header-logo img {
            height: 60px;
        }

        .header-info {
            text-align: center;
        }

        .title {
            font-size: 26px;
            font-weight: bold;
            text-align: center;
            letter-spacing: 5px;
            color: #1a73e8;
            margin: 0;
        }

        .subtitle {
            font-size: 15px;
            text-align: center;
            margin: 10px 0;
            color: #333;
        }

        .content {
            text-align: center;
        }

        .description {
            font-size: 18px;
            margin: 10px 0 10px 0;
            color: #555;
        }

        .name {
            font-size: 26px;
            font-weight: bold;
            color: #1a73e8;
            margin: 15px 0;
        }

        .role {
            font-size: 24px;
            font-weight: bold;
            color: #1a73e8;
            margin: 10px 0;
        }

        .footer {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .footer-left {
            text-align: left;
        }

        .footer-left p {
            margin: 5px 0;
        }

        .signature {
            margin-top: 20px;
        }

        .signature img {
            width: 150px;
        }

        .footer-right {
            text-align: right;
        }

        .footer-right img {
            width: 100px;
            height: 100px;
        }

        .bg-pattern,
        .decorative-shape {
            display: none;
        }

        @media print {
            body {
                margin: 0;
            }

            .container {
                width: 297mm;
                height: 210mm;
                padding: 0;
                box-shadow: none;
            }

            .bg-pattern,
            .decorative-shape {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="header-logo">
                <img src="https://simardav2.slemankab.go.id/img/sleman.png" alt="Logo" style="margin: 0 auto;" />
            </div>
            <div class="header-info">
                <div class="title">S E R T I F I K A T</div>
                <div class="subtitle">Nomor: 800/31</div>
            </div>
        </div>

        <div class="content">
            <div class="description">Diberikan Kepada:</div>
            <div class="name">{{ $user->nama_lengkap }}</div>
            <div class="subtitle">NIP. {{ $user->nip }}</div>
            <div class="role">Sebagai: Peserta</div>
            <div class="description">
                {{ $course->course_name }}
                sejumlah {{ $course->detail_course->total_hours }} JP
            </div>
        </div>
        <div class="footer">
            <div class="footer-left">
                <p>Sleman, {{ now()->toFormattedDateString() }}</p>
                <p>Kepala Badan Kepegawaian,</p>
                <p>Pendidikan dan Pelatihan</p>
                <div class="signature">
                    <img src="{{ $qrCodePath }}" alt="Signature" />
                </div>
                <p>R. BUDI PRAMONO, S.IP, M.Si</p>
                <p>Pembina Utama Muda, IV/c</p>
                <p>NIP 196906121998031016</p>
            </div>
            {{-- <div class="footer-right">
                <img src="{{ $qrCodePath }}" alt="QR Code">
            </div> --}}
        </div>
        {{-- <div class="bg-pattern"></div>
        <div class="decorative-shape"></div> --}}
    </div>
</body>

</html>
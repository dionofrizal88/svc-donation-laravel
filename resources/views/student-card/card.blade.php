<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Pelajar</title>
    <style>
        .card {
            height: 9677.6px;
            font-family: Arial, sans-serif;
        }

        .header {
            color: #B8D576;
            margin-bottom: 20px;
            text-align: center;
        }

        .photo {
            width: 8cm;
            height: 8cm;
            border-radius: 50%;
            border: 1px solid green;
            margin: 0 auto;
            overflow: hidden;
            margin-top: 100px;
            margin-bottom: 20px;
        }

        .photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .body-card {
            color: #B8D576;
            text-align: center;
        }

        .info {
            margin-top: 20px;
        }

        .info p {
            margin: 10px;
            font-size: 2em;
        }

        .footer-1 {
            position: absolute;
            text-align: center;
            width: 100%;
            background-color: #B8D576;
            color: white;
            left: 0;
            bottom: 0;
            width: 100%;
        }

        .qr-code-number {
            font-size: 20px;
        }

        .qr-code-number p {
            margin: 0;
        }

        .qr-code {
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .footer-2 {
            padding-top: 840px;
            text-align: center;
            width: 100%;
            color: white;
            left: 0;
            bottom: 0;
            width: 100%;
        }

        .footer-2 .bg {
            padding: 50px;
            background-color: #B8D576;
        }

    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h2>MTS Sekolah ABC</h2>
        </div>
        <div class="body-card">
            <div class="photo">
                <img src="{{ storage_path('app/' . $photo) }}" alt="Foto" width="100%" height="100%" />
            </div>
            <div class="info">
                <p><b>{{ $name }}</b></p>
                <p>NIS: {{ $student_number }}</p>
            </div>
        </div>
        <div class="footer-1">
            <div class="qr-code-number">
                <p>Nomor Pintro Card</p>
                <p><b>{{ $card_number }}</b></p>
            </div>
            <div class="qr-code">
                <img src="data:image/png;base64,{{ base64_encode(QrCode::format('png')->size(100)->generate($student_number)) }}" alt="QR Code">
            </div>
        </div>

        <div class="footer-2"> 
            <div class="bg">
                <p>MTS Sekolah ABC</p>
                <p><a href="www.sekolahabc.sch.id">www.sekolahabc.sch.id</a></p>
            </div>
        </div>
    </div>
</body>
</html>

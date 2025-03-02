<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Kartu Pelajar</title>
</head>
<body>
    <h1>Form Input Kartu Pelajar</h1>

    <form action="{{ route('student-card.generate') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div>
            <label for="nama">Nama:</label>
            <input type="text" name="name" id="nama" required>
        </div>

        <div>
            <label for="no_mahasiswa">No Mahasiswa:</label>
            <input type="text" name="student_number" id="no_mahasiswa" required>
        </div>

        <div>
            <label for="foto">Foto:</label>
            <input type="file" name="photo" id="foto" accept="image/*" required>
        </div>

        <div>
            <button type="submit">Generate</button>
        </div>
    </form>
</body>
</html>

<?php
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari formulir
    $nama = htmlspecialchars($_POST['nama']);
    $email = htmlspecialchars($_POST['email']);
    $nomor = htmlspecialchars($_POST['nomor']);
    $subjek = htmlspecialchars($_POST['subjek']);
    $pesan = htmlspecialchars($_POST['pesan']);

    // Format data yang akan disimpan
    $data = "Nama: $nama\nEmail: $email\nNomor: $nomor\nSubjek: $subjek\nPesan: $pesan\n\n";

    // Tentukan nama file teks
    $file = 'messages.txt';

    // Simpan data ke dalam file teks
    file_put_contents($file, $data, FILE_APPEND | LOCK_EX);

    // Pesan sukses
    $message = "Pesan Anda telah berhasil dikirim.";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Form Pengiriman Pesan</title>
</head>
<body>
    
    <?php
    if (!empty($message)) {
        echo "<p>$message</p>";
    }
    ?>
</body>
</html>
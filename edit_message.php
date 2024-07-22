<?php
if (isset($_POST['index']) && isset($_POST['nama']) && isset($_POST['email']) && isset($_POST['nomor']) && isset($_POST['subjek']) && isset($_POST['pesan'])) {
    $index = (int)$_POST['index'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $nomor = $_POST['nomor'];
    $subjek = $_POST['subjek'];
    $pesan = $_POST['pesan'];

    $filename = "messages.txt";
    if (!file_exists($filename)) {
        die("File not found");
    }

    $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    // Cari batas pesan
    $messageLines = [];
    $message = [];
    foreach ($lines as $line) {
        if (strpos($line, 'Nama:') === 0 && !empty($message)) {
            $messageLines[] = $message;
            $message = [];
        }
        $message[] = $line;
    }
    if (!empty($message)) {
        $messageLines[] = $message;
    }

    // Ubah pesan yang diinginkan
    if (isset($messageLines[$index])) {
        $messageLines[$index] = [
            "Nama: $nama",
            "Email: $email",
            "Nomor: $nomor",
            "Subjek: $subjek",
            "Pesan: $pesan"
        ];
    }

    // Simpan kembali ke file
    $newLines = [];
    foreach ($messageLines as $message) {
        foreach ($message as $line) {
            $newLines[] = $line;
        }
        $newLines[] = ""; // Tambahkan baris kosong di antara pesan
    }
    $result = file_put_contents($filename, implode("\n", $newLines));

    if ($result === false) {
        die("Failed to save data");
    }

    // Redirect kembali ke halaman utama
    header("Location: display_messages.php");
    exit();
} else {
    die("Invalid input");
}
?>
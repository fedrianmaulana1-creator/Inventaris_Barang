<?php
session_start();
header('Content-Type: application/json');
$conn = mysqli_connect("127.0.0.1", "root", "", "inventaris_barang");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = $_POST['inputEmail'] ?? '';
    $password = $_POST['inputPassword'] ?? '';

    $queryUser = mysqli_query($conn, "SELECT * FROM login WHERE email='$email'");
    if (mysqli_num_rows($queryUser) === 0) {
        echo json_encode(['status' => 'error', 'message' => 'Email tidak ditemukan!']);
        exit;
    }

    $dataUser = mysqli_fetch_assoc($queryUser);
    if ($dataUser['password'] !== $password) {
        echo json_encode(['status' => 'error', 'message' => 'Password Anda salah!']);
        exit;
    }
    $_SESSION['log'] = 'True';
    $_SESSION['email'] = $dataUser['email'];
    echo json_encode(['status' => 'success', 'message' => 'Berhasil Login']);
}
?>
<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
$conn = mysqli_connect("127.0.0.1", "root", "", "inventaris_barang");

if (!$conn) {
    echo json_encode(['status' => 'error', 'message' => 'Koneksi Database Gagal']);
    exit;
}
?>
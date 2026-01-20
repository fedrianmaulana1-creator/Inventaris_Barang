<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "inventaris_barang");

if (mysqli_connect_errno()) {
    echo "Koneksi database gagal : " . mysqli_connect_error();
}

if(isset($_POST['addnewbarang'])){
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];
    $stock = $_POST['stock'];
    $idkategori = $_POST['idkategori']; 

    mysqli_query($conn, "INSERT INTO stock (namabarang, deskripsi, stock, idkategori) VALUES ('$namabarang','$deskripsi','$stock', '$idkategori')");
    header('location:index.php');
}

function responseJSON($status, $message, $data = null) {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => $status,
        'message' => $message,
        'data' => $data
    ]);
    exit();
}

?>
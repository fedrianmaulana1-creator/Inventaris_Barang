<?php
require '../function.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
switch ($method) {
    case 'GET':
        $data = [];
        $query = mysqli_query($conn, "SELECT * FROM kategori ORDER BY idkategori DESC");
        while ($row = mysqli_fetch_assoc($query)) {
            $data[] = $row;
        }
        echo json_encode(['status' => 'success', 'data' => $data]);
        break;

    case 'POST':
        $nama = mysqli_real_escape_string($conn, $_POST['namakategori']);
        $desk = mysqli_real_escape_string($conn, $_POST['deskripsi']);
        $query = mysqli_query($conn, "INSERT INTO kategori (namakategori, deskripsi) VALUES ('$nama', '$desk')");
        echo json_encode(['status' => $query ? 'success' : 'error','message' => 'berhasil menambahkan kategori']);
        break;

    case 'PUT':
        parse_str(file_get_contents("php://input"), $_PUT);
        $id   = mysqli_real_escape_string($conn, $_PUT['idkategori']);
        $nama = mysqli_real_escape_string($conn, $_PUT['namakategori']);
        $desk = mysqli_real_escape_string($conn, $_PUT['deskripsi']);
        $query = mysqli_query($conn, "UPDATE kategori SET namakategori='$nama', deskripsi='$desk' WHERE idkategori='$id'");
        echo json_encode(['status' => $query ? 'success' : 'error', 'message' => 'berhasil memperbarui kategori']);
        break;

    case 'DELETE':
        parse_str(file_get_contents("php://input"), $_DELETE);
        $id = mysqli_real_escape_string($conn, $_DELETE['idkategori']);
        $query = mysqli_query($conn, "DELETE FROM kategori WHERE idkategori='$id'");
        echo json_encode(['status' => $query ? 'success' : 'error', 'message' => 'Berhasil menghapus kategori']);
        break;
}
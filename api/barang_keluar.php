<?php
header("Content-Type: application/json");
require '../function.php';

$method = $_SERVER['REQUEST_METHOD'];
switch ($method) {
    case 'GET':
        $mulai = isset($_GET['mulai']) ? mysqli_real_escape_string($conn, $_GET['mulai']) : '';
        $selesai = isset($_GET['selesai']) ? mysqli_real_escape_string($conn, $_GET['selesai']) : '';
        $sql = "SELECT k.*, s.namabarang, kat.namakategori 
                FROM keluar k 
                JOIN stock s ON k.idbarang = s.idbarang 
                LEFT JOIN kategori kat ON s.idkategori = kat.idkategori";

        if (!empty($mulai) && !empty($selesai)) {
            $sql .= " WHERE k.tanggal BETWEEN '$mulai 00:00:00' AND '$selesai 23:59:59'";
        }

        $sql .= " ORDER BY k.tanggal DESC";

        $query = mysqli_query($conn, $sql);
        $data = [];
        while($row = mysqli_fetch_assoc($query)) { 
            $data[] = $row; 
        }
        
        echo json_encode(["status" => "success", "data" => $data]);
        break;

   case 'POST':
        $idb = isset($_POST['idbarang']) ? $_POST['idbarang'] : '';
        $qty = isset($_POST['qty']) ? (int)$_POST['qty'] : 0;
        $penerima = isset($_POST['penerima']) ? mysqli_real_escape_string($conn, $_POST['penerima']) : '';

        if (empty($idb) || $qty <= 0) {
            echo json_encode(["status" => "error", "message" => "Data tidak lengkap!"]);
            exit;
        }
        $query_stok = mysqli_query($conn, "SELECT stock FROM stock WHERE idbarang='$idb'");
        $cek_stok = mysqli_fetch_array($query_stok);

        if (!$cek_stok) {
            echo json_encode(["status" => "error", "message" => "Barang tidak ditemukan di database! (ID: $idb)"]);
            exit;
        }
        if ($cek_stok['stock'] < $qty) {
            echo json_encode(["status" => "error", "message" => "Stok tidak cukup! Tersisa: " . $cek_stok['stock']]);
            exit;
        }
        mysqli_begin_transaction($conn);
        $ins = mysqli_query($conn, "INSERT INTO keluar (idbarang, qty, penerima) VALUES ('$idb', '$qty', '$penerima')");
        $upd = mysqli_query($conn, "UPDATE stock SET stock = stock - $qty WHERE idbarang = '$idb'");

        if ($ins && $upd) {
            mysqli_commit($conn);
            echo json_encode(["status" => "success", "message" => "Berhasil menambahkan barang keluar"]);
        } else {
            mysqli_rollback($conn);
            echo json_encode(["status" => "error", "message" => "Gagal simpan data ke database"]);
        }
        break;

    case 'PUT':
        parse_str(file_get_contents("php://input"), $_PUT);
        $idk = $_PUT['idkeluar'];
        $idb_baru = $_PUT['idbarang'];
        $qty_baru = (int)$_PUT['qty'];
        $penerima = mysqli_real_escape_string($conn, $_PUT['penerima']);
        $lama = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM keluar WHERE idkeluar='$idk'"));
        $idb_lama = $lama['idbarang'];
        $qty_lama = $lama['qty'];

        mysqli_begin_transaction($conn);
        mysqli_query($conn, "UPDATE stock SET stock = stock + $qty_lama WHERE idbarang = '$idb_lama'");
        $cek = mysqli_fetch_array(mysqli_query($conn, "SELECT stock FROM stock WHERE idbarang='$idb_baru'"));
        if ($cek['stock'] < $qty_baru) {
            mysqli_rollback($conn);
            echo json_encode(["status" => "error", "message" => "Stok barang tidak cukup untuk perubahan ini!"]);
            exit;
        }
        mysqli_query($conn, "UPDATE stock SET stock = stock - $qty_baru WHERE idbarang = '$idb_baru'");
        mysqli_query($conn, "UPDATE keluar SET idbarang='$idb_baru', qty='$qty_baru', penerima='$penerima' WHERE idkeluar='$idk'");

        mysqli_commit($conn);
        echo json_encode(["status" => "success", "message" => "Berhasil memperbarui barang keluar "]);
        break;

    case 'DELETE':
        parse_str(file_get_contents("php://input"), $_DELETE);
        $idk = $_DELETE['idk'];
        $idb = $_DELETE['idb'];
        $qty = $_DELETE['qty'];

        mysqli_begin_transaction($conn);
        $del = mysqli_query($conn, "DELETE FROM keluar WHERE idkeluar='$idk'");
        $upd = mysqli_query($conn, "UPDATE stock SET stock = stock + $qty WHERE idbarang = '$idb'");

        if ($del && $upd) {
            mysqli_commit($conn);
            echo json_encode(["status" => "success", "message" => "Berhasil menghapus barang keluar"]);
        } else {
            mysqli_rollback($conn);
            echo json_encode(["status" => "error", "message" => "Gagal hapus data"]);
        }
        break;
}
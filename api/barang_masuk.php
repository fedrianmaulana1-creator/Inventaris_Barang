<?php
header("Content-Type: application/json");
require '../function.php';

$method = $_SERVER['REQUEST_METHOD'];
switch($method) {
        case 'GET':
            $query = mysqli_query($conn, "SELECT m.*, s.namabarang, s.idbarang, k.namakategori FROM masuk m 
                                        JOIN stock s ON s.idbarang = m.idbarang 
                                        LEFT JOIN kategori k ON s.idkategori = k.idkategori 
                                        ORDER BY idmasuk DESC");
            $data = [];
           while($row = mysqli_fetch_assoc($query)) { 
    $data[] = [
        "idmasuk" => (int)$row['idmasuk'],
        "idbarang" => (int)$row['idbarang'],
        "qty" => (int)$row['qty'],
        "namabarang" => $row['namabarang'],
        "keterangan" => $row['keterangan'],
        "tanggal" => $row['tanggal'],
        "namakategori" => $row['namakategori']
    ]; 
}
            echo json_encode(["status" => "success", "data" => $data]);
            break;

        case 'POST':
            $idb = $_POST['barangnya'];
            $qty = (int)$_POST['qty'];
            $ket = mysqli_real_escape_string($conn, $_POST['keterangan']);

            mysqli_begin_transaction($conn);
            try {
                mysqli_query($conn, "INSERT INTO masuk (idbarang, keterangan, qty) VALUES ('$idb', '$ket', '$qty')");
                mysqli_query($conn, "UPDATE stock SET stock = stock + $qty WHERE idbarang = '$idb'");
                
                mysqli_commit($conn);
                echo json_encode(["status" => "success", "message" => "Berhasil Menambah Barang masuk"]);
            } catch (Exception $e) {
                mysqli_rollback($conn);
                echo json_encode(["status" => "error", "message" => "Gagal menyimpan data"]);
            }
            break;

        case 'PUT':
            parse_str(file_get_contents("php://input"), $_PUT);
            $idm = $_PUT['idmasuk'];
            $idb_baru = $_PUT['idbarang']; 
            $qty_baru = (int)$_PUT['qty'];
            $ket_baru = mysqli_real_escape_string($conn, $_PUT['keterangan']);
            $cek_data_lama = mysqli_query($conn, "SELECT * FROM masuk WHERE idmasuk='$idm'");
            $data_lama = mysqli_fetch_assoc($cek_data_lama);
            $idb_lama = $data_lama['idbarang'];
            $qty_lama = $data_lama['qty'];

            mysqli_begin_transaction($conn);
            try {
                if ($idb_baru != $idb_lama) {
                    mysqli_query($conn, "UPDATE stock SET stock = stock - $qty_lama WHERE idbarang = '$idb_lama'");
                    mysqli_query($conn, "UPDATE stock SET stock = stock + $qty_baru WHERE idbarang = '$idb_baru'");
                } 
                else {
                    $selisih = $qty_baru - $qty_lama;
                    mysqli_query($conn, "UPDATE stock SET stock = stock + $selisih WHERE idbarang = '$idb_baru'");
                }
                mysqli_query($conn, "UPDATE masuk SET idbarang='$idb_baru', qty='$qty_baru', keterangan='$ket_baru' WHERE idmasuk='$idm'");
                mysqli_commit($conn);
                echo json_encode(["status" => "success", "message" => "Berhasil Memperbarui Barang masuk"]);
            } catch (Exception $e) {
                mysqli_rollback($conn);
                echo json_encode(["status" => "error", "message" => "Gagal update data: " . $e->getMessage()]);
            }
            break;

            case 'DELETE':
                parse_str(file_get_contents("php://input"), $_DELETE);
                $idm = $_DELETE['idm'];
                $idb = $_DELETE['idb'];
                $qty = (int)$_DELETE['qty'];

                mysqli_begin_transaction($conn);
                try {
                    mysqli_query($conn, "UPDATE stock SET stock = stock - $qty WHERE idbarang = '$idb'");
                    mysqli_query($conn, "DELETE FROM masuk WHERE idmasuk = '$idm'");             
                    mysqli_commit($conn);
                    echo json_encode(["status" => "success", "message" => "Berhasil Menghapus Barang masuk"]);
                } catch (Exception $e) {
                    mysqli_rollback($conn);
                    echo json_encode(["status" => "error", "message" => "Gagal menghapus data"]);
                }
                break;
        }
    ?>
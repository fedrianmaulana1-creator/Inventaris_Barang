<?php
error_reporting(0); 
require '../function.php'; 
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
switch($method) {
        case 'GET':
            $query_barang = "SELECT b.*, k.namakategori as kategori 
                            FROM stock b 
                            LEFT JOIN kategori k ON b.idkategori = k.idkategori";
            $barang = mysqli_query($conn, $query_barang);
            
            $data_barang = [];
            while($row = mysqli_fetch_assoc($barang)) {
                $data_barang[] = [
                    'idb' => $row['idbarang'],
                    'nama' => $row['namabarang'],
                    'idk' => $row['idkategori'],
                    'kategori' => $row['kategori'] ?? 'Tanpa Kategori',
                    'deskripsi' => $row['deskripsi'],
                    'stok' => $row['stock']
                ];
            }
            $kategori_query = mysqli_query($conn, "SELECT * FROM kategori");
            $data_kategori = [];
            while($rowk = mysqli_fetch_assoc($kategori_query)) {
                $data_kategori[] = [
                    'idk' => $rowk['idkategori'], 
                    'nama' => $rowk['namakategori'] 
                ];
            }
            $s1 = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(stock) as total FROM stock"));
            $s2 = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(*) as total FROM kategori"));
            $s3 = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(qty) as total FROM masuk WHERE DATE(tanggal) = CURDATE()"));
            $s4 = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(qty) as total FROM keluar WHERE DATE(tanggal) = CURDATE()"));
            echo json_encode([
                'status' => 'success',
                'data' => [
                    'barang' => $data_barang,
                    'kategori_list' => $data_kategori,
                    'statistik' => [
                        'total_barang' => $s1['total'] ?? 0,
                        'total_kategori' => $s2['total'] ?? 0,
                        'stat_masuk' => $s3['total'] ?? 0, 
                        'stat_keluar' => $s4['total'] ?? 0  
                    ]
                ]
            ]);
            break;

        case 'POST':
            $namabarang = $_POST['namabarang'] ?? '';
            $idkategori = $_POST['idkategori'] ?? '';
            $deskripsi  = $_POST['deskripsi'] ?? '';
            $stock      = $_POST['stock'] ?? 0;

            if (!empty($namabarang) && !empty($idkategori)) {
                $insert = mysqli_query($conn, "INSERT INTO stock (namabarang, idkategori, deskripsi, stock) VALUES ('$namabarang', '$idkategori', '$deskripsi', '$stock')");
                if ($insert) {
                    echo json_encode(['status' => 'success', 'message' => 'Berhasil menambah barang']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan ke database']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap']);
            }
            break;

        case 'PUT':
            parse_str(file_get_contents("php://input"), $_PUT);
            $idb        = $_PUT['idb'] ?? '';
            $namabarang = $_PUT['namabarang'] ?? '';
            $idkategori = $_PUT['idkategori'] ?? '';
            $deskripsi  = $_PUT['deskripsi'] ?? '';
            $stock      = $_PUT['stock'] ?? 0;
            if (!empty($idb) && !empty($namabarang)) {
                $update = mysqli_query($conn, "UPDATE stock SET namabarang='$namabarang', idkategori='$idkategori', deskripsi='$deskripsi', stock='$stock' WHERE idbarang='$idb'");
                if ($update) {
                    echo json_encode(['status' => 'success', 'message' => 'Berhasil memperbarui barang']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Gagal update database']);
                }
            }
            break;

        case 'DELETE':
            parse_str(file_get_contents("php://input"), $_DELETE);
            $idb = mysqli_real_escape_string($conn, $_DELETE['idb'] ?? '');
            if (!empty($idb)) {
                $delete = mysqli_query($conn, "DELETE FROM stock WHERE idbarang='$idb'");
                if($delete) {
                    echo json_encode(['status' => 'success', 'message' => 'Berhasil menghapus barang']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus data']);
                }
            }
            break;

        default:
            echo json_encode(['status' => 'error', 'message' => 'Method tidak diizinkan']);
            break;
    }
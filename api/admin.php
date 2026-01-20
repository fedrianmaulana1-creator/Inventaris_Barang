<?php
require '../function.php';
header('Content-Type: application/json');

$headers = getallheaders();
$apiKey = $headers['Authorization'] ?? '';
if ($apiKey !== 'RahasiaInventaris123') {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
switch($method) {
   case 'GET':
    $query = mysqli_query($conn, "SELECT iduser, email, password, image FROM login");
    $data = mysqli_fetch_all($query, MYSQLI_ASSOC);
    echo json_encode(['status' => 'success', 'data' => $data]);
    break;

    case 'POST': 
        $hiddenMethod = $_POST['_method'] ?? 'POST';
        $email = $_POST['email'];
        $password = $_POST['password'];
        $nama_file = '';
        if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
            $ekstensi_diperbolehkan = ['png', 'jpg', 'jpeg'];
            $x = explode('.', $_FILES['file']['name']);
            $ekstensi = strtolower(end($x));
            $file_tmp = $_FILES['file']['tmp_name'];
            $nama_file = time() . '_' . $_FILES['file']['name']; 

            if (in_array($ekstensi, $ekstensi_diperbolehkan)) {
                move_uploaded_file($file_tmp, '../assets/img/' . $nama_file);
            }
        }

        if ($hiddenMethod === 'PUT') {
            $id = $_POST['iduser'];
            $sql = "UPDATE login SET email='$email'";
            if (!empty($password)) $sql .= ", password='$password'";
            if (!empty($nama_file)) $sql .= ", image='$nama_file'";
            $sql .= " WHERE iduser='$id'";

            if (mysqli_query($conn, $sql)) {
                echo json_encode(['status' => 'success', 'message' => 'Berhasil memperbarui admin']);
            }
        } else {
            $sql = "INSERT INTO login (email, password, image) VALUES ('$email', '$password', '$nama_file')";
            if (mysqli_query($conn, $sql)) {
                http_response_code(201);
                echo json_encode(['status' => 'success', 'message' => 'Berhasil Menambah admin']);
            }
        }
        break;

        case 'DELETE':
            $json = file_get_contents("php://input");
            $_DELETE = json_decode($json, true);
            $id = $_DELETE['iduser'] ?? '';
            if (!empty($id)) {
                $sql = "DELETE FROM login WHERE iduser='$id'";
                if (mysqli_query($conn, $sql)) {
                    echo json_encode(['status' => 'success', 'message' => 'berhasil menghapus admin']);
                } else {
                    http_response_code(500);
                    echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus database']);
                }
            } else {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'ID tidak ditemukan atau format salah']);
            }
            break;
    }
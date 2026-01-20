<?php
header("Content-Type: application/json");
require '../function.php';
    
        if ($type == 'tambah_admin') {
            $email = mysqli_real_escape_string($conn, $_POST['email']);
            $password = mysqli_real_escape_string($conn, $_POST['password']);

            $query = "INSERT INTO login (email, password) VALUES ('$email', '$password')";
            if (mysqli_query($conn, $query)) {
                echo json_encode(['status' => 'success', 'message' => 'Berhasil menambah admin']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Gagal menambah admin']);
            }
            exit;
        }

        if ($type == 'edit_admin') {
            $id = mysqli_real_escape_string($conn, $_POST['iduser']);
            $email = mysqli_real_escape_string($conn, $_POST['email']);
            $password = mysqli_real_escape_string($conn, $_POST['password']);

            $update_foto = "";
            if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != "") {
                $nama_file = $_FILES['file']['name'];
                $file_tmp = $_FILES['file']['tmp_name'];
                $ekstensi_boleh = ['png', 'jpg', 'jpeg'];
                $x = explode('.', $nama_file);
                $ekstensi = strtolower(end($x));
                $nama_file_baru = rand(1, 999) . '-' . $nama_file;

                if (in_array($ekstensi, $ekstensi_boleh)) {
                    if (move_uploaded_file($file_tmp, '../assets/img/' . $nama_file_baru)) {
                        $update_foto = ", image='$nama_file_baru'";
                        
                        $query_lama = mysqli_query($conn, "SELECT image FROM login WHERE iduser='$id'");
                        $data_lama = mysqli_fetch_array($query_lama);
                        if ($data_lama['image'] != '' && $data_lama['image'] != 'user.png' && file_exists('../assets/img/' . $data_lama['image'])) {
                            unlink('../assets/img/' . $data_lama['image']);
                        }
                    }
                } else {
                    echo json_encode(['status' => 'error', 'msg' => 'Format harus PNG/JPG']);
                    exit;
                }
            }

            if (!empty($password)) {
                $sql = "UPDATE login SET email='$email', password='$password' $update_foto WHERE iduser='$id'";
            } else {
                $sql = "UPDATE login SET email='$email' $update_foto WHERE iduser='$id'";
            }

            echo json_encode(['status' => mysqli_query($conn, $sql) ? 'success' : 'error']);
            exit;
        }

        if ($type == 'hapus_admin') {
            $id = mysqli_real_escape_string($conn, $_POST['iduser']);
            $query_foto = mysqli_query($conn, "SELECT image FROM login WHERE iduser='$id'");
            $data_foto = mysqli_fetch_array($query_foto);
            
            if (mysqli_query($conn, "DELETE FROM login WHERE iduser='$id'")) {
                if ($data_foto['image'] != '' && $data_foto['image'] != 'user.png' && file_exists('../assets/img/' . $data_foto['image'])) {
                    unlink('../assets/img/' . $data_foto['image']);
                }
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error']);
            }
            exit;
        }

    echo json_encode($response);
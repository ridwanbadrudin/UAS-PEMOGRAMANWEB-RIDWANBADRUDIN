<?php
require('../koneksi.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemId = $_POST['id'];
    $nama = $_POST['nama'];
    $npm = $_POST['npm'];
    $kelas = $_POST['kelas'];
    $timestamp = time();

    if (isset($_FILES['gambar'])) {
        $uploadDir = '../../upload/';
        $uploadFile = $uploadDir . $timestamp . basename($_FILES['gambar']['name']);
        $item_gambar = $_FILES['gambar']['name'];

        $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
        if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png" && $imageFileType != "gif") {
            echo json_encode(['status' => 'error', 'message' => 'Only file or image formatted JPG, JPEG, PNG, and GIF allowed.']);
            die();
        }

        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $uploadFile)) {
            try {
                $sqlPrevGambar = 'SELECT * FROM mahasiswa where id = ?';
                $stmtPrevGambar = $db_connection->prepare($sqlPrevGambar);
                $stmtPrevGambar->execute([$itemId]);
                $gambar = $stmtPrevGambar->fetch(PDO::FETCH_ASSOC);

                if ($gambar) {
                    $prevImagePath = $uploadDir . $gambar['gambar'];
                    if (file_exists($prevImagePath)) {
                        unlink($prevImagePath);
                    }
                    $sql = 'UPDATE `mahasiswa` SET `gambar` = ?, `nama` = ?, `npm` = ?, `kelas` = ? WHERE `id` = ?';
                    $connect = $db_connection->prepare($sql);
                    $connect->execute([$timestamp . $item_gambar, $nama, $npm, $kelas, $itemId]);
                    if ($connect->rowCount() > 0) {
                        echo json_encode(['status' => 'success', 'message' => 'Successfully update data']);
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Successfully update data']);
                    }
                    exit();
                }
            } catch (PDOException $e) {
                echo json_encode(['status' => 'error', 'message' => 'Error database ' . $e->getMessage()]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error while upload image']);
        }
    } else {
        try {
            $sql = 'UPDATE `mahasiswa` SET `nama` = ?, `npm` = ?, `kelas` = ? WHERE `id` = ?';
            $connect = $db_connection->prepare($sql);
            $connect->execute([$nama, $npm, $kelas, $itemId]);
            if ($connect->rowCount() > 0) {
                echo json_encode(['status' => 'success', 'message' => 'Successfully update data']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error update data']);
            }
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Error database' . $e->getMessage()]);
        }
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Request isn\'t post' . $e->getMessage()]);
}

function updateItemWithImage($itemId, $item_gambar, $item_judul, $item_deskripsi, $item_harga)
{
    global $db_connection;
}

function updateItemWithoutImage($itemId, $item_judul, $item_deskripsi, $item_harga)
{
    global $db_connection;
}

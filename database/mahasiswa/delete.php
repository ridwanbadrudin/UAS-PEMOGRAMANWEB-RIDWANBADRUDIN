<?php
require_once('../koneksi.php');

$id = $_POST['id'];
$uploadDir = '../../upload/';

try {
    $sql_check_gambar = "SELECT * FROM `mahasiswa` WHERE `id` = ?";
    $statement_check_gambar = $db_connection->prepare($sql_check_gambar);
    $statement_check_gambar->execute([$id]);
    $gambar = $statement_check_gambar->fetch(PDO::FETCH_ASSOC);

    if ($gambar) {
        $sql = 'DELETE FROM `mahasiswa` WHERE id = ?';
        $connect = $db_connection->prepare($sql);
        $connect->execute([$id]);
        $imageDir = $uploadDir . $gambar['gambar'];

        if (file_exists($imageDir)) {
            unlink($imageDir);
            if ($connect->rowCount() > 0) {
                echo json_encode(['status' => 'success', 'message' => 'Successfully delete data']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed delete data']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed delete image']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'There\' no images need to be deleted']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'success', 'message' => $e->getMessage()]);
}

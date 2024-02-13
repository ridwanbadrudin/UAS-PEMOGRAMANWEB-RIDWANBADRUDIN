<?php
header("Access-Control-Allow-Origin: *");
header("Cache-Control: no-cache, no-store, max-age=0, must-revalidate");
header("X-Content-Type-Options: nosniff");
include '../koneksi.php';

$nama = $_POST['nama'];
$npm = $_POST['npm'];
$kelas = $_POST['kelas'];
$userid = $_POST['userid'];
$gambar = $_FILES['gambar']['name'];
$timestamp = time();

$uploadDirectory = '../../upload/';
$uploadedFileName = $uploadDirectory . $timestamp . basename($gambar);

$imageFileType = strtolower(pathinfo($gambar, PATHINFO_EXTENSION));

try {
    if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png" && $imageFileType != "gif") {
        echo json_encode(['status' => 'error', 'message' => 'Only FIle Image with Formatted JPG, JPEG, PNG, and GIF Allowed.']);
        die();
    }
    if (move_uploaded_file($_FILES['gambar']['tmp_name'], $uploadedFileName)) {
        $sql = "INSERT INTO `mahasiswa` (`id`, `nama`, `npm`, `kelas`, `userid`, `gambar`) VALUES (?, ?, ?, ?, ?, ?)";
        $statement = $db_connection->prepare($sql);
        $statement->execute([null, $nama, $npm, $kelas, $userid, $timestamp . $gambar]);

        if ($statement->rowCount() > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Image successfully uploaded and data successfully upladed']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error adding data']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error moving uploaded file']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'General error' . $e->getMessage()]);
}

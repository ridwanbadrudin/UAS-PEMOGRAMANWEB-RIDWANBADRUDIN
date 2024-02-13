<?php
header("Access-Control-Allow-Origin: *");
include '../koneksi.php';

$username = $_POST["username"];
$email = $_POST["email"];
$password = $_POST["password"];

if (isset($username) && isset($password) && isset($email)) {
    // Cek apakah username sudah ada dalam database
    $sql_check_username = "SELECT * FROM `user` WHERE `username` = ?";
    $statement_check_username = $db_connection->prepare($sql_check_username);
    $statement_check_username->execute([$username]);

    if ($statement_check_username->rowCount() > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Username already taken']);
    } else {
        // Cek apakah email sudah ada dalam database
        $sql_check_email = "SELECT * FROM `user` WHERE `email` = ?";
        $statement_check_email = $db_connection->prepare($sql_check_email);
        $statement_check_email->execute([$email]);

        if ($statement_check_email->rowCount() > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Email already taken']);
        } else {
            // Jika username dan email belum digunakan, lakukan penyisipan data ke dalam database
            $sql_insert_user = "INSERT INTO `user`(`id`, `username`, `email`, `password`) VALUES (?,?,?,?)";
            $statement_insert_user = $db_connection->prepare($sql_insert_user);
            $statement_insert_user->execute([null, $username, $email, sha1($password)]);

            if ($statement_insert_user->rowCount() > 0) {
                echo json_encode(['status' => 'success', 'message' => 'Registrasi successful']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Registrasi failed']);
            }
        }
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Request is invalid']);
}


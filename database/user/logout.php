<?php
header("Access-Control-Allow-Origin: *");
include '../koneksi.php';

$session = $_POST["session"];

if (isset($session)) {
    $sql = "UPDATE `user` SET `token` = NULL WHERE `token` = ?";
    $statement = $db_connection->prepare($sql);
    $statement->execute([$session]);
    $result = $statement->rowCount();

    if ($result > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Logout successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => "Session is invalid" . $result]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Request is invalid']);
}

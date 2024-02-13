<?php
header("Access-Control-Allow-Origin: *");
include '../koneksi.php';

$session = $_POST["session"];

if (isset($session)) {
    $sql = "SELECT `id`, `username`, `email`, `nama`, `token` FROM user WHERE `token` = ?";
    $statement = $db_connection->prepare($sql);
    $statement->execute([$session]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo json_encode(['status' => 'success', 'user' => $user]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'There\'s no user with this session: ' . $session]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Couldn\'t get session']);
}

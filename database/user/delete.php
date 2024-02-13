<?php
header("Access-Control-Allow-Origin: *");
include '../koneksi.php';


if (isset($_POST["session"])) {
    $session = $_POST["session"];

    try {
        $sqlOldPassword = 'SELECT `password` FROM `user` where `token` = ?';
        $stmtOldPassword = $db_connection->prepare($sqlOldPassword);
        $stmtOldPassword->execute([$session]);
        $user = $stmtOldPassword->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if ($user['password'] === sha1($_POST['currentPassword'])) {
                $sql = "DELETE FROM `user` WHERE `token` = ?";
                $statement = $db_connection->prepare($sql);
                $statement->execute([$session]);

                if ($statement->rowCount() > 0) {
                    echo json_encode(['status' => 'success', 'message' => 'Successfully delete this account']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to delete this account']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Password not match']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'There\'s no user with this session : ' . $session]);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'success', 'message' => 'General Error']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Couldn\'t get session']);
}

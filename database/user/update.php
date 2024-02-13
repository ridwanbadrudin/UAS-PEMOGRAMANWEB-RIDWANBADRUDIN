<?php
header("Access-Control-Allow-Origin: *");
include '../koneksi.php';

$status = 'error';
$message = 'no message';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['session'])) {
        $session = $_POST['session'];
        $checkPasswordQuery = "SELECT * FROM `user` WHERE `token` = ?";
        $stmt = $db_connection->prepare($checkPasswordQuery);
        $stmt->execute([$session]);

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $currentPassword = $user['password'];
            $oldPassword = sha1($_POST['currentPassword']);

            if ($currentPassword === $oldPassword) {
                $updateQuery = "UPDATE `user` SET";
                $params = array();
                $values = array();

                if (isset($_POST['name'])) {
                    $updateQuery .= " `nama` = :name,";
                    $params[':name'] = $_POST['name'];
                    $message = "Update Name";
                }
                if (isset($_POST['email'])) {
                    $session = $_POST['session'];
                    $checkPasswordQuery = "SELECT * FROM `user` WHERE `email` = ?";
                    $stmt = $db_connection->prepare($checkPasswordQuery);
                    $stmt->execute([$_POST['email']]);
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($user) {
                        echo json_encode(array("status" => 'error', "message" => 'Email already taken'));
                        die();
                    } else {
                        $updateQuery .= " `email` = :email,";
                        $params[':email'] = $_POST['email'];
                        $message = "Update Email";
                    }
                }
                if (isset($_POST['newPassword'])) {
                    $updateQuery .= " `password` = :newPassword,";
                    $params[':newPassword'] = sha1($_POST['newPassword']);
                    $message = "Update Password";
                }
                $updateQuery = rtrim($updateQuery, ",");

                $updateQuery .= " WHERE `token` = :session";
                $params[':session'] = $session;

                $stmt = $db_connection->prepare($updateQuery);
                foreach ($params as $param => $value) {
                    $stmt->bindValue($param, $value, PDO::PARAM_STR);
                }

                if ($stmt->execute()) {
                    $status = "success";
                    $message .= " Data successsfully updated";
                } else {
                    $status = "error";
                    $message .= " Error while update data : " . $stmt->errorInfo();
                }
            } else {
                if ($_POST['passwordLama'] == null) {
                    $status = "error";
                    $message = " Wrong password";
                } else {
                    $status = "error";
                    $message = " Password isn\'t match";
                }
            }
        } else {
            $status = "error";
            $message = "Session is invalid";
        }
    } else {
        $status = "error";
        $message = "Couldn\'t get session";
    }
} else {
    $status = "error";
    $message = "Request is invalid";
}

echo json_encode(array("status" => $status, "message" => $message));

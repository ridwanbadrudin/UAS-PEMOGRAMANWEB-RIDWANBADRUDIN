<?php
header("Access-Control-Allow-Origin: *");
include '../koneksi.php';
if (isset($_POST['userid'])) {
    try {
        $sql = "SELECT * FROM `mahasiswa` WHERE `userid` = ?";
        $statement = $db_connection->prepare($sql);
        $statement->execute([$_POST['userid']]);
    
        $data = array();
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            array_push($data, $row);
        }
    
        echo json_encode(['status' => 'success', 'message' => 'Successfully load data', 'mahasiswa' => $data]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Request is invalid : ' . $e]);
    }
}


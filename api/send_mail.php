<?php

header('Content-Type: application/json');
session_start();

$input = json_decode(file_get_contents('php://input'), true);

if ($input) {
    $message = $input['message'] ?? '';
    
    $log = "[" . date('Y-m-d H:i:s') . "] Support Request from " . $_SESSION['user']['email'] . ": " . $message . "\n";
    file_put_contents('support_logs.txt', $log, FILE_APPEND);

    echo json_encode(["status" => "success", "message" => "Message envoyé au support !"]);
} else {
    echo json_encode(["status" => "error"]);
}
?>
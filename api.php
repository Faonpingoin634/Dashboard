<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$jsonFile = 'data.json';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (file_exists($jsonFile)) {
        echo file_get_contents($jsonFile);
    } else {
        echo json_encode(["projects" => [], "tasks" => []]);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $inputData = file_get_contents("php://input");
    
    if (!empty($inputData)) {
        if(file_put_contents($jsonFile, $inputData, LOCK_EX)) {
            echo json_encode(["message" => "Sauvegarde réussie dans le JSON"]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Erreur d'écriture dans le fichier"]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["message" => "Aucune donnée reçue"]);
    }
    exit;
}
?>
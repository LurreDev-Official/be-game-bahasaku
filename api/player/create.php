<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../models/Player.php';

$database = new Database();
$db = $database->getConnection();
$player = new Player($db);

$data = json_decode(file_get_contents("php://input"));

if(
    !empty($data->username) &&
    !empty($data->email) &&
    !empty($data->password)
) {
    $player->username = $data->username;
    $player->email = $data->email;
    $player->password = $data->password;

    if($player->create()) {
        http_response_code(201);
        echo json_encode(array("message" => "Player was created."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create player."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create player. Data is incomplete."));
}
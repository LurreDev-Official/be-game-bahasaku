<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

include_once '../config/database.php';
include_once '../models/Player.php';

$database = new Database();
$db = $database->getConnection();
$player = new Player($db);

$player->id = isset($_GET['id']) ? $_GET['id'] : die();

if($player->readOne()) {
    $player_arr = array(
        "id" => $player->id,
        "username" => $player->username,
        "email" => $player->email,
        "created_at" => $player->created_at
    );

    http_response_code(200);
    echo json_encode($player_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "Player does not exist."));
}
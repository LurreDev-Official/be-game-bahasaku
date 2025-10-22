<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../models/Player.php';

$database = new Database();
$db = $database->getConnection();
$player = new Player($db);

$stmt = $player->read();
$num = $stmt->rowCount();

if($num > 0) {
    $players_arr = array();
    $players_arr["records"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $player_item = array(
            "id" => $id,
            "username" => $username,
            "email" => $email,
            "created_at" => $created_at
        );
        array_push($players_arr["records"], $player_item);
    }

    http_response_code(200);
    echo json_encode($players_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "No players found."));
}
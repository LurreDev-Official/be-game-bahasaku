<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

include_once '../../config/database.php';
include_once '../../models/GameRanking.php';

$database = new Database();
$db = $database->getConnection();
$ranking = new GameRanking($db);

$ranking->id = isset($_GET['id']) ? $_GET['id'] : die();

if($ranking->readOne()) {
    $ranking_arr = array(
        "id" => $ranking->id,
        "player_id" => $ranking->player_id,
        "score" => $ranking->score,
        "level" => $ranking->level,
        "created_at" => $ranking->created_at
    );

    http_response_code(200);
    echo json_encode($ranking_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "Ranking does not exist."));
}
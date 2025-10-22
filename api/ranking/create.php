<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../models/GameRanking.php';

$database = new Database();
$db = $database->getConnection();
$ranking = new GameRanking($db);

$data = json_decode(file_get_contents("php://input"));

if(
    !empty($data->player_id) &&
    !empty($data->score) &&
    !empty($data->level)
) {
    $ranking->player_id = $data->player_id;
    $ranking->score = $data->score;
    $ranking->level = $data->level;

    if($ranking->create()) {
        http_response_code(201);
        echo json_encode(array("message" => "Ranking was created."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create ranking."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create ranking. Data is incomplete."));
}
<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../models/GameRanking.php';

$database = new Database();
$db = $database->getConnection();
$ranking = new GameRanking($db);

$data = json_decode(file_get_contents("php://input"));

$ranking->id = $data->id;
$ranking->score = $data->score;
$ranking->level = $data->level;

if($ranking->update()) {
    http_response_code(200);
    echo json_encode(array("message" => "Ranking was updated."));
} else {
    http_response_code(503);
    echo json_encode(array("message" => "Unable to update ranking."));
}
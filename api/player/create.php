<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../models/Player.php';

$database = new Database();
$db = $database->getConnection();
$player = new Player($db);

$data = json_decode(file_get_contents("php://input"));

// Fungsi untuk generate email otomatis (username + domain gamebahasaku.com)
function generateEmail($username) {
    $domain = 'gamebahasaku.com'; // Domain tetap
    return $username . '@' . $domain;
}

// Fungsi untuk generate password otomatis (8 karakter alfanumerik)
function generatePassword($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $password;
}

if (!empty($data->username)) {
    // Cek apakah username sudah ada di database
    $player->username = $data->username;
    if ($player->usernameExists()) {  // Assuming usernameExists method checks if username already exists
        // Jika username sudah ada, ambil data pemain dan kirimkan kembali
        $existingPlayer = $player->getPlayerByUsername();  // Assuming getPlayerByUsername fetches player details by username
        http_response_code(200);
        echo json_encode(array(
            "message" => "Player already exists.",
            "player_id" => $existingPlayer['id'],
            "username" => $existingPlayer['username'],
            "email" => $existingPlayer['email']
        ));
    } else {
        // Generate email dan password otomatis
        $email = generateEmail($data->username);
        $password = generatePassword();

        $player->email = $email;
        $player->password = password_hash($password, PASSWORD_DEFAULT); // Hash password untuk keamanan

        if ($player->create()) {
            // Mengambil ID player yang baru saja disimpan
            $player_id = $player->id;

            http_response_code(201);
            echo json_encode(array(
                "message" => "Player was created.",
                "player_id" => $player_id,
                "generated_email" => $email,
                "generated_password" => $password
            ));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to create player."));
        }
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create player. Username is required."));
}
?>

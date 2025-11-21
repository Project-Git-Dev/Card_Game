<?php
session_start();
require_once 'UserModel.php';

$db = new Database();

$username = $_POST['username'];
$password = $_POST['password'];

if ($db->verifyUser($username, $password)) {
    $userStats = $db->getUserStats($username);
    file_put_contents("session_debug.txt", print_r($_SESSION, true));


    $_SESSION['user_id'] = $userStats['id'];
    $_SESSION['username'] = $username;

    echo json_encode([
        "success" => true,
        "username" => $username,
        "score" => $userStats['score'],
        "level" => $userStats['level']
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Ongeldige gebruikersnaam of wachtwoord."
    ]);
}
?>

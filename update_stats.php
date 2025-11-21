<?php
session_start();
require_once '../Model/UserModel.php';

header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Niet ingelogd']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$username = $_SESSION['username'];
$userModel = new UserModel();

$response = [];

if (isset($data['win']) && $data['win']) {
    $userModel->addWin($username);
    $response['win'] = true;
}
if (isset($data['gamesPlayed']) && $data['gamesPlayed']) {
    $userModel->incrementGamesPlayed($username);
    $response['gamesPlayed'] = true;
}
if (isset($data['cardsPlayed'])) {
    $userModel->incrementCardsPlayed($username, intval($data['cardsPlayed']));
    $response['cardsPlayed'] = $data['cardsPlayed'];
}
if (isset($data['level'])) {
    $userModel->updateLevel($username, intval($data['level']));
    $response['levelUpdated'] = true;
}
if (isset($data['score'])) {
    $userModel->updateScore($username, intval($data['score']));
    $response['scoreUpdated'] = true;
}

echo json_encode(['status' => 'success', 'updates' => $response]);

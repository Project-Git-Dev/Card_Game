<?php
session_start();

// Check of gebruiker ingelogd is
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo "Niet ingelogd";
    exit;
}

// Database verbinding
$host = "localhost";
$user = "root";
$password = "";
$dbname = "card_game"; // of jouw database naam

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    http_response_code(500);
    echo "Databaseverbinding mislukt";
    exit;
}

// JSON uitlezen
$data = json_decode(file_get_contents("php://input"), true);

// Validatie
$score = isset($data['score']) ? (int)$data['score'] : 0;
$level = isset($data['level']) ? (int)$data['level'] : 1;

if ($score < 0 || $level < 1) {
    http_response_code(400);
    echo "Ongeldige data";
    exit;
}

$user_id = $_SESSION['user_id'];

// Update query
$stmt = $conn->prepare("UPDATE users SET score = ?, level = ? WHERE id = ?");
$stmt->bind_param("iii", $score, $level, $user_id);

if ($stmt->execute()) {
    echo json_encode(["message" => "Progress succesvol opgeslagen"]);
} else {
    http_response_code(500);
    echo "Fout bij opslaan progress";
}

$stmt->close();
$conn->close();
?>

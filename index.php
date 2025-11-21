<?php
session_start();
require_once '../Model/UserModel.php';

$userModel = new UserModel();
$stats = null;
$avatar = null;
$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;

if ($username) {
    $stats = $userModel->getUserStats($username);
    $avatar = isset($stats['profile_image']) ? $stats['profile_image'] : null;
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Mystic Geheimen</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        const currentUser = <?= json_encode(isset($username) ? $username : "gast") ?>;
        const userStats = <?= json_encode(isset($stats) ? $stats : []) ?>;
    </script>
</head>
<body>

<!-- Navbar -->
<div id="navbar">
    <div id="hamburger">
        <div class="line"></div>
        <div class="line"></div>
        <div class="line"></div>
    </div>
    <div id="nav-menu">
        <ul>
            <li><a href="about.html">About</a></li>
            <?php if (isset($_SESSION['username'])): ?>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="logout.php">Uitloggen</a></li>
            <?php else: ?>
                <li><a href="login.html">Login</a></li>
                <li><a href="register.html">Registratie</a></li>
            <?php endif; ?>
        </ul>
    </div>

    <!-- Welkom + avatar -->
    <?php if (isset($_SESSION['username'])): ?>
        <div id="welcome-message">
            Welkom, <?= htmlspecialchars($_SESSION['username']) ?>!
            <?php if (!empty($avatar)): ?>
                <img src="avatars/<?= htmlspecialchars($avatar) ?>" alt="Avatar">
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Titel -->
<h1>Mystic Geheimen</h1>

<!-- Speler info -->
<div id="player-info">
    <h2>Score: <span id="score-count">0</span></h2>
    <h2>Level: <span id="level-count">1</span></h2>
</div>

<!-- Leaderboard -->
<div id="leaderboard">
    <h3>🏆 Leaderboard</h3>
    <ol id="leaderboard-list">Laden...</ol>
</div>

<!-- Speler gezondheid -->
<div id="health-bar">
    <p>Speler Gezondheid: <span id="player-health-count">20</span></p>
    <div id="player-health-bar" class="health-bar">
        <div id="player-health" class="health"></div>
    </div>
</div>

<!-- Hand van speler -->
<div id="player-hand"></div>

<!-- Mana -->
<div class="mana">Mana: <span id="mana-count">0</span></div>

<!-- Knoppen -->
<button id="end-turn">Einde Beurt</button>
<button id="reset-game">Reset Spel</button>

<!-- AI Gezondheid -->
<div id="ai-health-bar" class="health-bar">
    <p>A.I. Gezondheid: <span id="ai-health-count">20</span></p>
    <div id="ai-health" class="health ai-health-green"></div>
</div>
</body>
<!-- Scripts -->
<script src="script.js"></script>
</html>

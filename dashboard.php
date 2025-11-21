<?php
session_start();
require_once '../Model/UserModel.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit;
}

$userModel = new UserModel();
$stats = $userModel->getUserStats($_SESSION['username']);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Dashboard – <?= htmlspecialchars($_SESSION['username']) ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<!-- Navbar -->
<div id="navbar">
    <ul>
        <li><a href="index.php">🏠 Terug naar Spel</a></li>
        <li><a href="logout.php">🔓 Uitloggen</a></li>
    </ul>
</div>

<h1>📊 Dashboard</h1>
<h2>Welkom, <?= htmlspecialchars($_SESSION['username']) ?>!</h2>

<div class="stats-container">
    <p><strong>🏆 Overwinningen:</strong> <?= $stats['wins'] ?></p>
    <p><strong>🎮 Gespeelde spellen:</strong> <?= $stats['games_played'] ?></p>
    <p><strong>🃏 Gespeelde kaarten:</strong> <?= $stats['cards_played'] ?></p>
</div>

<h3>Kies je avatar:</h3>

<form action="set_avatar.php" method="POST">
    <div class="avatar-select">
        <?php
        $avatars = ['avatar1.png', 'avatar2.png', 'avatar3.png', 'avatar4.png', 'avatar5.png', 'avatar6.png'];
        foreach ($avatars as $avatar) {
            echo '<label>';
            echo '<input type="radio" name="avatar" value="' . $avatar . '" required>';
            echo '<img src="avatars/' . $avatar . '" alt="Avatar" class="avatar-option">';
            echo '</label>';
        }
        ?>
    </div>
    <button type="submit">Opslaan</button>
</form>

<?php if (!empty($stats['profile_image'])): ?>
    <h4>Jouw huidige avatar:</h4>
    <<img src="avatars/<?= htmlspecialchars($stats['profile_image']) ?>" alt="Avatar" width="100">


<?php endif; ?>

</body>
</html>

<?php
class UserModel {
    private $pdo;

    public function __construct($host = "localhost", $dbname = "card_game", $username = "root", $password = "") {
        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
        }
    }

    public function userExists($username) {
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch() !== false;
    }

    public function registerUser($username, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        return $stmt->execute([$username, $hashedPassword]);
    }

    public function validateLogin($username, $password) {
        $stmt = $this->pdo->prepare("SELECT password FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user && password_verify($password, $user['password']);
    }

    public function getUserStats($username) {
        $stmt = $this->pdo->prepare("SELECT id, wins, games_played, cards_played, profile_image, level, score FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addWin($username) {
        $stmt = $this->pdo->prepare("UPDATE users SET wins = wins + 1 WHERE username = ?");
        return $stmt->execute([$username]);
    }

    public function incrementGamesPlayed($username) {
        $stmt = $this->pdo->prepare("UPDATE users SET games_played = games_played + 1 WHERE username = ?");
        return $stmt->execute([$username]);
    }

    public function incrementCardsPlayed($username, $amount = 1) {
        $stmt = $this->pdo->prepare("UPDATE users SET cards_played = cards_played + ? WHERE username = ?");
        return $stmt->execute([$amount, $username]);
    }

    // ✅ Avatar opslaan
    public function updateProfileImage($username, $imageName) {
        $stmt = $this->pdo->prepare("UPDATE users SET profile_image = ? WHERE username = ?");
        return $stmt->execute([$imageName, $username]);
    }

    // ✅ Nieuw: Level updaten
    public function updateLevel($username, $level) {
        $stmt = $this->pdo->prepare("UPDATE users SET level = ? WHERE username = ?");
        return $stmt->execute([$level, $username]);
    } //


    // ✅ Nieuw: Score updaten
    public function updateScore($username, $score) {
        $stmt = $this->pdo->prepare("UPDATE users SET score = ? WHERE username = ?");
        return $stmt->execute([$score, $username]);
    }
}
?>

<?php
session_start();
require_once '../Model/UserModel.php';

$userModel = new UserModel("localhost", "card_game", "root", "");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = trim($_POST['username']);
    $pass = trim($_POST['password']);
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($action === "register") {
        if ($userModel->userExists($user)) {
            echo "Username already taken. <a href='../View/register.html'>Try again</a>";
        } else {
            if ($userModel->registerUser($user, $pass)) {
                echo "Registration successful. <a href='../View/login.html'>Go to login</a>";
            } else {
                echo "Error in registration.";
            }
        }
    } elseif ($action === "login") {
        if ($userModel->validateLogin($user, $pass)) {
            $_SESSION['username'] = $user;
            header("Location: ../View/index.php");
            exit;
        } else {
            echo "Invalid login. <a href='../View/login.html'>Try again</a>";
        }
    }
}
?>

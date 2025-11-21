<?php
session_start();
require_once '../Model/UserModel.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit;
}

if (isset($_POST['avatar'])) {
    $avatar = basename($_POST['avatar']); // beveiliging


    $allowedAvatars = ['avatar1.png', 'avatar2.png', 'avatar3.png', 'avatar4.png', 'avatar5.png', 'avatar6.png'];
    if (!in_array($avatar, $allowedAvatars)) {
        die("Ongeldige avatar geselecteerd.");
    }

    $userModel = new UserModel();
    $userModel->updateProfileImage($_SESSION['username'], $avatar);

    header("Location: dashboard.php");
    exit;
}
?>

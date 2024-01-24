<?php
session_start();
include 'libs/HtmlSpecialChars.php';
include 'libs/UserSession.php';
include 'libs/Database.php';

$usersession = new UserSession();

if ($usersession->getUserRole() != 'Administrateur') {
    header('Location: login-portal.php');
}

$database = new Database();
$connect = $database->getConnect();

$query = $connect->prepare("SELECT * FROM users");
$query->execute();
$users = $query->fetchAll();

// Inclusion du fichier admin-listing.phtml
include 'admin-gestion.phtml';
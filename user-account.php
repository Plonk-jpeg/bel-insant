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

// Récupération du paramètre "categorie" de l'URL
$categorie = isset($_GET['id']) ? $_GET['id'] : null;

// Utilisation d'une requête préparée pour éviter les injections SQL
$query = $connect->prepare('SELECT * FROM users WHERE userId = ?');
$query->execute([$categorie]);
$users = $query->fetchAll();
// Inclusion du fichier admin-group-listing.phtml
include 'user-account.phtml';


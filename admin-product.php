<!--admin-product.php-->
<?php
session_start();

include 'checkAccess.php';
$usersession = new UserSession();

$database = new Database();
$connect = $database->getConnect();

// Vérification des rôles "Administrateur" et "Collaborateurs" dans la base de données
$query = $connect->prepare("SELECT COUNT(*) AS count FROM users WHERE userRole IN ('Administrateur', 'Collaborateurs')");
$query->execute();
$result = $query->fetch();

if ($result['count'] < 2) {
    // Aucun des rôles n'est présent dans la base de données, rediriger vers login-portal.php
    header('Location: login-portal.php');
    exit();
}

$query = $connect->prepare("SELECT DISTINCT groupeProduits FROM produits ORDER BY groupeProduits DESC");
$query->execute();
$products = $query->fetchAll();

// Inclusion du fichier admin-listing.phtml
include 'admin-product.phtml';
?>
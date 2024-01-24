<!--admin-group-listing.php-->
<?php
session_start();
include 'libs/HtmlSpecialChars.php';
include 'libs/UserSession.php';
include 'libs/Database.php';

$usersession = new UserSession();

$longueurMax = 2;
function limiterCaracteres($chaine, $longueurMax)
{
    if (strlen($chaine) > $longueurMax) {
        return substr($chaine, 0, $longueurMax) . '...';
    }
    return $chaine;
}

$database = new Database();
$connect = $database->getConnect();

$categorie = isset($_GET['categorie']) ? $_GET['categorie'] : null;

// Récupérer les lignes sélectionnées de la session
$selectedRows = isset($_SESSION['selectedProducts'][$categorie]) ? $_SESSION['selectedProducts'][$categorie] : [];

if ($categorie == null) {
    $query = $connect->prepare('SELECT * FROM produits');
    $query->execute();
    $products = $query->fetchAll();
} else {
    $query = $connect->prepare('SELECT * FROM produits WHERE groupeProduits = ?');
    $query->execute([$categorie]);
    $products = $query->fetchAll();
}
// Inclusion du fichier admin-group-listing.phtml
include 'admin-group-listing.phtml';
?>
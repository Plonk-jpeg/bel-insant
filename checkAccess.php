<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include 'libs/HtmlSpecialChars.php';
include 'libs/UserSession.php';
include 'libs/Database.php';

const ERROR_PASSWORD_INCORRECT = "errorpass";
const ERROR_USERNAME_INCORRECT = "errorusername";
const ERROR_CSRF = "errorcsrf";

$errorMessages = array(); // espace pour tableau pour stocker les messages d'erreur

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // vérification du token anti-CSRF
    if (
        isset($_POST['csrf_token']) &&
        isset($_SESSION['csrf_token']) &&
        $_POST['csrf_token'] === $_SESSION['csrf_token']
    ) {
        // si le token est valide, le formulaire sera traité

        // récupération des données du formulaire
        $userName = _escape($_POST['userName']);
        $userPswd = _escape($_POST['userPswd']);

        // Instancie la classe Database
        $database = new Database();
        // vérification de la connexion à la base de données
        $connect = $database->getConnect();

        $usersession = new UserSession();

        if ($connect) {
            // connexion réussie

            // requête SQL pour les noms des utilisateurs stockés dans la base de données et comparaison entre la base et le formulaire
            $query = $connect->prepare('SELECT * FROM users WHERE userName = ?');

            if ($query->execute([$userName]) === false) {
                // message d'erreur si l'exécution de la requête résulte dans un échec
                die("Erreur lors de l'exécution de la requête : " . print_r($query->errorInfo(), true));
            }

            $user = $query->fetch();

            if ($user) {
                if (password_verify($userPswd, $user['userPswd'])) {
                    // Authentification réussie
                    $usersession->create($user['userId'], $user['userName'], $user['userRole']);

                    if ($user['userRole'] == 'Collaborateurs') {
                        // Si le rôle est "Collaborateurs", redirigez directement vers la liste des produits
                        header('Location: admin-product.php');
                        exit();
                    } elseif ($user['userRole'] == 'Administrateur') {
                        // Si le rôle est "Admin", redirigez vers le tableau de bord administrateur
                        header('Location: admin-portal.php');
                        exit();
                    }
                } else {
                    // Informations d'authentification incorrectes
                    $errorMessages[] = ERROR_PASSWORD_INCORRECT;
                }
            } else {
                // Nom d'utilisateur incorrect
                $errorMessages[] = ERROR_USERNAME_INCORRECT;
            }

            // Rediriger vers login-portal.php avec les messages d'erreur dans l'URL
            header('Location: login-portal.php?error=' . implode(',', $errorMessages));
            exit();
        } else {
            // Problème de connexion à la base de données
            $errorMessages[] = "Erreur de connexion à la base de données.";
        }
    } else {
        // Le token anti-CSRF est invalide
        $errorMessages[] = ERROR_CSRF;
    }
}

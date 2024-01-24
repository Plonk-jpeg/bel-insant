<!--login-portal.php->>
<?php
session_start();
$csrfToken = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $csrfToken;
include 'checkAccess.php';
?>
<script src="js/script.js"></script>
<?php
// ppermet l'affichage des message erreur si ils existent
if (isset($_GET['error'])) {
    $errorMessages = explode(',', $_GET['error']);

    // affiche les messages d'erreur
    foreach ($errorMessages as $errorMessage) {
        switch ($errorMessage) {
            case 'ERROR_PASSWORD_INCORRECT':
                echo '<p style="color: red;">Mot de passe incorrect. Vérifiez votre mot de passe.</p>';
                break;

            case 'ERROR_USERNAME_INCORRECT':
                echo '<p style="color: red;">Nom d\'utilisateur incorrect. Vérifiez votre nom d\'utilisateur.</p>';
                break;

            case 'ERROR_CSRF':
                echo '<p style="color: red;">Erreur de token anti-CSRF. Veuillez réessayer.</p>';
                break;

            default:
                break;
        }
    }
}
?>

<!--formulaire de connexion-->
<script src="js/script.js"></script>
<form action="checkAccess.php" method="post">
    <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
    <label for="login">Compte :</label>
    <input type="text" id="login" placeholder="Compte" name="userName" required>

    <div class="password-container">
        <label for="password">Mot de passe :</label>
        <input type="password" id="password" placeholder="Mot de passe" name="userPswd" required>
        <span class="toggle-password" id="togglePasswordIcon">👁️</span>
    </div>

    <button type="submit">Connexion</button>
</form>
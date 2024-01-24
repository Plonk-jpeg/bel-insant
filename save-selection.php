<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = isset($_POST['idProduits']) ? $_POST['productId'] : null;
    $isSelected = isset($_POST['isSelected']) ? ($_POST['isSelected'] == '1') : false;

    if ($productId !== null) {
        // Initialisez le tableau des sélections s'il n'existe pas
        $_SESSION['selectedProducts'][$_POST['categorie']] = $_SESSION['selectedProducts'][$_POST['categorie']] ?? [];

        // Mettez à jour la sélection
        if ($isSelected) {
            $_SESSION['selectedProducts'][$_POST['categorie']][] = $productId;
        } else {
            $key = array_search($productId, $_SESSION['selectedProducts'][$_POST['categorie']]);
            if ($key !== false) {
                unset($_SESSION['selectedProducts'][$_POST['categorie']][$key]);
            }
        }
    }
}

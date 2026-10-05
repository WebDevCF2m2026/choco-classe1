<?php
// path: controller/PrivateController.php
// typage strict
declare(strict_types=1);

use model\manager\UserManager;

// contrôleur de l'espace connecté

if(isset($_GET['pg']) && $_GET['pg'] == 'deconnexion') {

    UserManager::deconnectUser();

} elseif(isset($_GET['pg']) && $_GET['pg'] == 'connexion') {
    // déjà connecté : pas besoin de la page de connexion
    header('Location: '.RACINE_URL.'/');
    exit;

} else {
    // en attendant l'administration, on affiche les pages publiques
    require RACINE_PATH.'/controller/PublicController.php';
}

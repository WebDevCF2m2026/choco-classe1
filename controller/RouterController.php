<?php
// path: controller/RouterController.php
// typage strict
declare(strict_types=1);

if(isset($_SESSION['user_id'], $_SESSION['user_login'])) {
    // si l'utilisateur est connecté
    require_once RACINE_PATH.'/controller/PrivateController.php';
} else {
    // sinon, on inclut le contrôleur de l'espace public
    require_once RACINE_PATH.'/controller/PublicController.php';

}
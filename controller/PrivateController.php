<?php
// path: controller/PrivateController.php
// typage strict
declare(strict_types=1);

use model\manager\UserManager;

// contrôleur de l'espace connecté

// jeton CSRF pour les formulaires de l'espace connecté
if(!isset($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}

$pg = $_GET['pg'] ?? '';

if($pg == 'deconnexion') {
    // méthode statique pour fermer la session de l'utilisateur
    UserManager::deconnectUser();

} elseif($pg == 'connexion') {
    // déjà connecté : pas besoin de la page de connexion
    header('Location: '.RACINE_URL.'/');
    exit;

} elseif($pg == 'admin' && $_SESSION['user_role'] === 'admin') {
    // administration des articles, réservée aux admins
    require RACINE_PATH.'/controller/AdminController.php';

} elseif($pg == 'admin' || $pg == 'profil') {
    // page de profil (les simples utilisateurs qui vont sur /admin arrivent ici)
    $userManager = new UserManager($db);
    $user = $userManager->getUserById((int) $_SESSION['user_id']);

    // l'utilisateur a été supprimé entre-temps : on ferme la session
    if($user === null) {
        UserManager::deconnectUser();
    }

    require RACINE_PATH.'/view/private/profil.view.php';

} else {
    // les autres pages sont les pages publiques
    require RACINE_PATH.'/controller/PublicController.php';
}

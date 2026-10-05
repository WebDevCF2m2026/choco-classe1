<?php
// path: controller/PublicController.php
// typage strict
declare(strict_types=1);

use model\manager\ArticleManager;
use model\manager\UserManager;
use model\mapping\UserMapping;


$articleManager = new ArticleManager($db);

if(isset($_GET['pg'],$_GET['slug']) && $_GET['pg'] == 'article') {
    $slug = $_GET['slug'];
    $article = $articleManager->getArticleBySlug($slug);
    require RACINE_PATH.'/view/public/article.view.php';

} elseif(isset($_GET['pg']) && $_GET['pg'] == 'connexion') {
    $error = null;
    $login = '';

    // création du jeton CSRF s'il n'existe pas encore
    if(!isset($_SESSION['token'])) {
        $_SESSION['token'] = bin2hex(random_bytes(32));
    }

    // si le formulaire a été envoyé
    if(isset($_POST['user_login'], $_POST['user_pwd'], $_POST['token'])
        && is_string($_POST['user_login']) && is_string($_POST['user_pwd']) && is_string($_POST['token'])) {
        $login = trim($_POST['user_login']);
        $pwd = trim($_POST['user_pwd']);
        $userMapping = new UserMapping(['user_login' => $login, 'user_pwd' => $pwd]);

        if(!hash_equals($_SESSION['token'], $_POST['token'])) {
            $error = "Session expirée, veuillez réessayer.";
        } elseif($login === '' || $pwd === '') {
            $error = "Veuillez remplir tous les champs.";
        } else {
            $userManager = new UserManager($db);
            $user = $userManager->connectUser($userMapping);

            if($user === null) {
                // message volontairement vague pour ne pas indiquer si le login existe
                $error = "Identifiant ou mot de passe incorrect.";
            } else {
                // méthode statique pour créer la session de l'utilisateur
                UserManager::sessionUser($user);
            }
        }
    }

    require RACINE_PATH.'/view/public/connexion.view.php';

} else {
    $allArticles = $articleManager->getAllArticles();

// affichage de la page d'accueil
    require RACINE_PATH.'/view/public/homepage.view.php';
}

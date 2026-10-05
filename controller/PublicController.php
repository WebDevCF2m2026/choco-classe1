<?php
// path: controller/PublicController.php
// typage strict
declare(strict_types=1);

use model\manager\ArticleManager;


$articleManager = new ArticleManager($db);

if(isset($_GET['pg'],$_GET['slug']) && $_GET['pg'] == 'article') {
    $slug = $_GET['slug'];
    $article = $articleManager->getArticleBySlug($slug);
    require RACINE_PATH.'/view/public/article.view.php';
} else {
    $allArticles = $articleManager->getAllArticles();

// affichage de la page d'accueil
    require RACINE_PATH.'/view/public/homepage.view.php';
}


<?php
// path: controller/PublicController.php
// typage strict
declare(strict_types=1);

use model\manager\ArticleManager;


$articleManager = new ArticleManager($db);

$allArticles = $articleManager->getAllArticles();

// affichage de la page d'accueil
require RACINE_PATH.'/view/public/homepage.view.php';
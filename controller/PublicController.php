<?php
// path: controller/PublicController.php
// typage strict
declare(strict_types=1);

use model\manager\ArticleManager;


$articleManager = new ArticleManager($db);

$allArticles = $articleManager->getAllArticles();

var_dump($allArticles); // Affiche le tableau d'objets ArticleMapping
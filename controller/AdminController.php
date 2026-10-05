<?php
// path: controller/AdminController.php
// typage strict
declare(strict_types=1);

use model\manager\ArticleManager;
use model\mapping\ArticleMapping;

// contrôleur du CRUD des articles, réservé aux admins
// routes : /admin, /admin/create, /admin/update/{id}, /admin/delete/{id}

// sécurité : on vérifie à nouveau le rôle
if(!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: '.RACINE_URL.'/');
    exit;
}

$articleManager = new ArticleManager($db);

$action = $_GET['slug'] ?? '';
$id = isset($_GET['id']) && ctype_digit($_GET['id']) ? (int) $_GET['id'] : 0;

// récupère une valeur texte du formulaire (vide si absente ou trafiquée)
$postValue = fn(string $key): string => isset($_POST[$key]) && is_string($_POST[$key]) ? $_POST[$key] : '';

// vérifie le jeton CSRF du formulaire envoyé
$tokenIsValid = fn(): bool => hash_equals($_SESSION['token'], $postValue('token'));

// message à afficher après une redirection
$setFlash = function (string $message, string $type = 'success'): void {
    $_SESSION['flash'] = ['message' => $message, 'type' => $type];
};

$redirectToList = function (): never {
    header('Location: '.RACINE_URL.'/admin');
    exit;
};

if($action == 'create' || ($action == 'update' && $id > 0)) {
    // ---------- création / modification ----------
    $error = null;
    $isUpdate = $action == 'update';

    if($isUpdate) {
        $existing = $articleManager->getArticleById($id);
        if($existing === null) {
            $setFlash("Cet article n'existe pas.", 'error');
            $redirectToList();
        }
        // valeurs actuelles pour pré-remplir le formulaire
        $form = [
            'article_title' => $existing->getArticleTitle(),
            'article_text' => $existing->getArticleText(),
            'article_status' => $existing->getArticleStatus(),
        ];
    } else {
        $form = ['article_title' => '', 'article_text' => '', 'article_status' => 'en attente'];
    }

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        // on garde la saisie pour la réafficher en cas d'erreur
        $form = [
            'article_title' => $postValue('article_title'),
            'article_text' => $postValue('article_text'),
            'article_status' => $postValue('article_status'),
        ];

        if(!$tokenIsValid()) {
            $error = "Session expirée, veuillez réessayer.";
        } else {
            try {
                // les setters de ArticleMapping valident les données (exception si invalide)
                $article = new ArticleMapping($form + [
                    'article_id' => $isUpdate ? $id : null,
                    'user_user_id' => $_SESSION['user_id'],
                ]);

                if($isUpdate) {
                    $articleManager->updateArticle($article);
                    $setFlash("L'article a bien été modifié.");
                } else {
                    $articleManager->insertArticle($article);
                    $setFlash("L'article a bien été créé.");
                }
                $redirectToList();
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }
    }

    $statusList = ArticleMapping::getStatusList();
    require RACINE_PATH.'/view/private/article-form.view.php';

} elseif($action == 'delete' && $id > 0) {
    // ---------- suppression (uniquement en POST avec jeton) ----------
    if($_SERVER['REQUEST_METHOD'] !== 'POST' || !$tokenIsValid()) {
        $setFlash("Suppression refusée, veuillez réessayer.", 'error');
    } elseif($articleManager->deleteArticle($id)) {
        $setFlash("L'article a bien été supprimé.");
    } else {
        $setFlash("Cet article n'existe pas.", 'error');
    }
    $redirectToList();

} else {
    // ---------- liste des articles ----------
    $articles = $articleManager->getAllArticlesAdmin();

    // message flash éventuel, affiché une seule fois
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);

    require RACINE_PATH.'/view/private/admin.view.php';
}

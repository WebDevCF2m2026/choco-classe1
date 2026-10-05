<?php
// path: view/inc/header.view.php
// en-tête commun à toutes les pages
// variable attendue : $title (titre de la page, facultatif)
$pageTitle = isset($title) ? htmlspecialchars($title) . ' | Choco' : 'Choco';
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $pageTitle ?></title>
    <link rel="stylesheet" href="<?= RACINE_URL ?>/css/style.css">
</head>
<body>
<header class="site-header">
    <div class="container header-inner">
        <a class="logo" href="<?= RACINE_URL ?>/">Choco</a>

        <!-- menu burger en CSS pur (sans JavaScript) -->
        <input type="checkbox" id="nav-toggle" class="nav-toggle">
        <label for="nav-toggle" class="nav-toggle-label" aria-label="Ouvrir le menu">
            <span></span>
        </label>

        <nav class="site-nav">
            <ul>
                <li><a href="<?= RACINE_URL ?>/">Accueil</a></li>
                <?php if (isset($_SESSION['user_login'])): ?>
                    <li><a href="<?= RACINE_URL ?>/admin">Administration</a></li>
                    <li><a class="btn btn-outline" href="<?= RACINE_URL ?>/deconnexion">Déconnexion</a></li>
                <?php else: ?>
                    <li><a class="btn" href="<?= RACINE_URL ?>/connexion">Connexion</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>

<main class="container site-main">

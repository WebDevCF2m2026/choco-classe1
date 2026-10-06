<?php
// path: view/public/article.view.php
// page de détail d'un article
// variable attendue : $article (objet ArticleMapping ou null si introuvable)

if ($article === null) {
    // article inexistant ou non publié : erreur 404 (avant tout affichage)
    http_response_code(404);
    $title = 'Article introuvable';
} else {
    $title = $article->getArticleTitle();
    $user = $article->getUser();
    // nom complet de l'auteur s'il existe, sinon son login
    $author = $user?->getUserFullName() ?? $user?->getUserLogin() ?? 'Anonyme';
    $date = new DateTime($article->getArticleCreateAt());
}

require RACINE_PATH . '/view/inc/header.view.php';
?>

<a class="back-link" href="<?= RACINE_URL ?>/">&larr; Retour aux articles</a>

<?php if ($article === null): ?>
    <section class="empty">
        <h1>Article introuvable</h1>
        <p>Cet article n'existe pas ou n'est plus disponible.</p>
    </section>
<?php else: ?>
    <article class="article-detail">
        <header class="article-header">
            <h1><?= htmlspecialchars($article->getArticleTitle()) ?></h1>
            <p class="card-meta">
                Par <strong><?= htmlspecialchars($author) ?></strong>
                le <time datetime="<?= $date->format('Y-m-d') ?>"><?= $date->format('d/m/Y') ?></time>
            </p>
        </header>

        <div class="article-content">
            <?= nl2br(htmlspecialchars($article->getArticleText())) ?>
        </div>
    </article>
<?php endif; ?>

<?php
require RACINE_PATH . '/view/inc/footer.view.php';

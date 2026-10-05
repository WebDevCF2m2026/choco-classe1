<?php
// path: view/public/homepage.view.php
// page d'accueil : liste des articles publiés
// variable attendue : $allArticles (tableau d'objets ArticleMapping)
$title = 'Accueil';
require RACINE_PATH . '/view/inc/header.view.php';
?>

<section class="hero">
    <h1>Les derniers articles</h1>
    <p>Retrouvez ici tous les articles publiés, du plus récent au plus ancien.</p>
</section>

<?php if (empty($allArticles)): ?>
    <p class="empty">Aucun article n'a encore été publié.</p>
<?php else: ?>
    <div class="articles-grid">
        <?php foreach ($allArticles as $article):
            $user = $article->getUser();
            // nom complet de l'auteur s'il existe, sinon son login
            $author = $user?->getUserFullName() ?? $user?->getUserLogin() ?? 'Anonyme';
            $date = new DateTime($article->getArticleCreateAt());
            $url = RACINE_URL . '/article/' . htmlspecialchars($article->getArticleSlug());
        ?>
            <article class="card">
                <h2 class="card-title">
                    <a href="<?= $url ?>"><?= htmlspecialchars($article->getArticleTitle()) ?></a>
                </h2>
                <p class="card-meta">
                    Par <strong><?= htmlspecialchars($author) ?></strong>
                    le <time datetime="<?= $date->format('Y-m-d') ?>"><?= $date->format('d/m/Y') ?></time>
                </p>
                <p class="card-text"><?= nl2br(htmlspecialchars($article->getArticleText())) ?>…</p>
                <a class="card-link" href="<?= $url ?>">Lire la suite &rarr;</a>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php
require RACINE_PATH . '/view/inc/footer.view.php';

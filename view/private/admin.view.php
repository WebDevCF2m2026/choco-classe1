<?php
// path: view/private/admin.view.php
// liste des articles pour l'administration
// variables attendues : $articles (tableau d'ArticleMapping), $flash (message ou null)
$title = 'Administration';
require RACINE_PATH . '/view/inc/header.view.php';

// classe CSS du badge selon le statut
$badges = ['publié' => 'badge-success', 'en attente' => 'badge-warning', 'désactivé' => 'badge-muted'];
?>

<section class="page-head">
    <div>
        <h1>Administration</h1>
        <p><?= count($articles) ?> article<?= count($articles) > 1 ? 's' : '' ?> au total</p>
    </div>
    <a class="btn" href="<?= RACINE_URL ?>/admin/create">+ Nouvel article</a>
</section>

<?php if ($flash): ?>
    <p class="alert alert-<?= $flash['type'] === 'error' ? 'error' : 'success' ?>" role="status">
        <?= htmlspecialchars($flash['message']) ?>
    </p>
<?php endif; ?>

<?php if (empty($articles)): ?>
    <p class="empty">Aucun article pour le moment.</p>
<?php else: ?>
    <div class="table-wrap">
        <table class="admin-table">
            <thead>
            <tr>
                <th>Titre</th>
                <th>Auteur</th>
                <th>Statut</th>
                <th>Création</th>
                <th><span class="sr-only">Actions</span></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($articles as $article):
                $user = $article->getUser();
                $author = $user?->getUserFullName() ?? $user?->getUserLogin() ?? 'Anonyme';
                $status = $article->getArticleStatus();
                $date = new DateTime($article->getArticleCreateAt());
                $id = $article->getArticleId();
            ?>
                <tr>
                    <td data-label="Titre" class="cell-title"><?= htmlspecialchars($article->getArticleTitle()) ?></td>
                    <td data-label="Auteur"><?= htmlspecialchars($author) ?></td>
                    <td data-label="Statut">
                        <span class="badge <?= $badges[$status] ?? 'badge-muted' ?>"><?= htmlspecialchars($status) ?></span>
                    </td>
                    <td data-label="Création">
                        <time datetime="<?= $date->format('Y-m-d') ?>"><?= $date->format('d/m/Y') ?></time>
                    </td>
                    <td class="cell-actions">
                        <?php if ($status === 'publié'): ?>
                            <a href="<?= RACINE_URL ?>/article/<?= htmlspecialchars($article->getArticleSlug()) ?>">Voir</a>
                        <?php endif; ?>
                        <a href="<?= RACINE_URL ?>/admin/update/<?= $id ?>">Modifier</a>
                        <form action="<?= RACINE_URL ?>/admin/delete/<?= $id ?>" method="post"
                              onsubmit="return confirm('Supprimer définitivement cet article ?');">
                            <input type="hidden" name="token" value="<?= htmlspecialchars($_SESSION['token']) ?>">
                            <button type="submit" class="link-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php
require RACINE_PATH . '/view/inc/footer.view.php';

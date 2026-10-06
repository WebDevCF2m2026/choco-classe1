<?php
// path: view/private/article-form.view.php
// formulaire de création et de modification d'un article
// variables attendues : $article (objet ArticleMapping, sans id pour une création),
// $statusList (statuts autorisés), $error (message ou null)
$isUpdate = $article->getArticleId() !== null;
$title = $isUpdate ? "Modifier l'article" : 'Nouvel article';
$action = $isUpdate ? RACINE_URL . '/admin/update/' . $article->getArticleId() : RACINE_URL . '/admin/create';
require RACINE_PATH . '/view/inc/header.view.php';
?>

<a class="back-link" href="<?= RACINE_URL ?>/admin">&larr; Retour à l'administration</a>

<section class="form-card form-card-wide">
    <h1><?= htmlspecialchars($title) ?></h1>

    <?php if (!empty($error)): ?>
        <p class="alert alert-error" role="alert"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form action="<?= $action ?>" method="post" class="form">
        <input type="hidden" name="token" value="<?= htmlspecialchars($_SESSION['token']) ?>">

        <div class="form-group">
            <label for="article_title">Titre</label>
            <input type="text" id="article_title" name="article_title" maxlength="180" required
                   value="<?= htmlspecialchars($article->getArticleTitle() ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="article_text">Texte</label>
            <textarea id="article_text" name="article_text" rows="12" required><?= htmlspecialchars($article->getArticleText() ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label for="article_status">Statut</label>
            <select id="article_status" name="article_status">
                <?php foreach ($statusList as $status): ?>
                    <option value="<?= htmlspecialchars($status) ?>" <?= $article->getArticleStatus() === $status ? 'selected' : '' ?>>
                        <?= htmlspecialchars(ucfirst($status)) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-actions">
            <a class="btn btn-outline" href="<?= RACINE_URL ?>/admin">Annuler</a>
            <button type="submit" class="btn"><?= $isUpdate ? 'Enregistrer' : "Créer l'article" ?></button>
        </div>
    </form>
</section>

<?php
require RACINE_PATH . '/view/inc/footer.view.php';

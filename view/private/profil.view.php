<?php
// path: view/private/profil.view.php
// page de profil de l'utilisateur connecté
// variable attendue : $user (objet UserMapping)
$title = 'Mon profil';
require RACINE_PATH . '/view/inc/header.view.php';

$name = $user->getUserFullName() ?? $user->getUserLogin();
// initiales pour l'avatar
$initials = '';
foreach (array_slice(preg_split('/\s+/u', trim($name)), 0, 2) as $part) {
    $initials .= mb_strtoupper(mb_substr($part, 0, 1));
}
?>

<section class="form-card profile-card">
    <div class="avatar" aria-hidden="true"><?= htmlspecialchars($initials) ?></div>
    <h1><?= htmlspecialchars($name) ?></h1>
    <p class="form-intro">
        <span class="badge <?= $user->getUserRole() === 'admin' ? 'badge-success' : 'badge-muted' ?>">
            <?= $user->getUserRole() === 'admin' ? 'Administrateur' : 'Utilisateur' ?>
        </span>
    </p>

    <dl class="profile-list">
        <div>
            <dt>Identifiant</dt>
            <dd><?= htmlspecialchars($user->getUserLogin()) ?></dd>
        </div>
        <div>
            <dt>Nom complet</dt>
            <dd><?= htmlspecialchars($user->getUserFullName() ?? 'Non renseigné') ?></dd>
        </div>
        <div>
            <dt>Email</dt>
            <dd><?= htmlspecialchars($user->getUserEmail()) ?></dd>
        </div>
    </dl>

    <?php if ($user->getUserRole() === 'admin'): ?>
        <a class="btn btn-block" href="<?= RACINE_URL ?>/admin">Gérer les articles</a>
    <?php endif; ?>
</section>

<?php
require RACINE_PATH . '/view/inc/footer.view.php';

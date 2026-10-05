<?php
// path: view/public/connexion.view.php
// page de connexion
// variables attendues : $error (message d'erreur ou null), $login (login saisi), $_SESSION['token'] (jeton CSRF)
$title = 'Connexion';
require RACINE_PATH . '/view/inc/header.view.php';
?>

<section class="form-card">
    <h1>Connexion</h1>
    <p class="form-intro">Connectez-vous pour accéder à votre espace.</p>

    <?php if (!empty($error)): ?>
        <p class="alert alert-error" role="alert"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form action="<?= RACINE_URL ?>/connexion" method="post" class="form">
        <!-- jeton contre les attaques CSRF -->
        <input type="hidden" name="token" value="<?= htmlspecialchars($_SESSION['token']) ?>">

        <div class="form-group">
            <label for="login">Identifiant</label>
            <input type="text" id="login" name="user_login" maxlength="50" required autofocus
                   autocomplete="username" value="<?= htmlspecialchars($login ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="pwd">Mot de passe</label>
            <input type="password" id="pwd" name="user_pwd" required autocomplete="current-password">
        </div>

        <button type="submit" class="btn btn-block">Se connecter</button>
    </form>
</section>

<?php
require RACINE_PATH . '/view/inc/footer.view.php';

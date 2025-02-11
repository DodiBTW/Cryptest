<?php
$title = "Login - Cryptest";
include 'views/layouts/header.php';
?>

<div class="login-container">
    <p class="subtitle">Connectez-vous à votre compte</p>

    <?php if (!empty($error)) : ?>
        <p class="error-message"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form action="/?page=login" method="POST">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="unsername" name="username" required>
        </div>

        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>
        </div>

        <button type="submit" class="btn">Se connecter</button>

        <p class="register-link">Pas encore de compte ? <a href="/?page=register">S'inscrire</a></p>
    </form>
</div>
<?php
$title = "Register - Cryptest";
include 'views/layouts/header.php';
?>

<div class="login-container">
    <p class="subtitle">Créer un compte</p>

    <?php if (!empty($error)) : ?>
        <p class="error-message"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form action="/?page=register" method="POST">
        <div class="form-group">
            <label for="username">Nom d'utilisateur</label>
            <input type="text" id="username" name="username" required>
        </div>

        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>
        </div>

        <button type="submit" class="btn">S'inscrire</button>

        <p class="register-link">Déjà un compte ? <a href="/?page=login">Se connecter</a></p>
    </form>
</div>

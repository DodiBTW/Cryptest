<?php
$title = "Accueil - Cryptest";
include 'views/layouts/header.php';
?>

<div class="container2">
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Symbole</th>
                <th>Prix actuel (USD)</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tokens as $token): ?>
                <tr>
                    <td><?php echo htmlspecialchars($token['name']); ?></td>
                    <td><?php echo htmlspecialchars($token['symbol']); ?></td>
                    <td>$<?php echo htmlspecialchars($token['price']); ?></td>
                    <td>
                        <a href="/?page=<?php echo htmlspecialchars($token['name']); ?>">
                            <button class="btn">Voir</button>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
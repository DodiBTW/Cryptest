<?php
$title = "Mon Wallet - Cryptest";
include 'views/layouts/header.php';

$user_helper = new UserHelper();
$wallet_helper = new WalletHelper();
$balance_helper = new BalanceHelper();
$price_helper = new PriceHelper();
$token_helper = new TokenHelper();

if (!isset($_SESSION['user'])) {
    header("Location: /?page=login");
    exit;
}

$id = (int)$user_helper->get_user_id();
$username = $_SESSION['user'];
$balance = $balance_helper->get_wallet_balance();
$tokens = $wallet_helper->get_wallet_tokens($id);
?>

<div class="wallet-container">
    <h2>Mon Wallet</h2>

    <div class="wallet-balance">
        <p>💰 Solde restant à investir : <span><?= number_format($balance, 2) ?> €</span></p>
    </div>

    <h3>Mes Placements</h3>
    <table class="wallet-table">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Symbole</th>
                <th>Adresse</th>
                <th>Quantité</th>
                <th>Valeur (€)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tokens as $token): ?>
                <tr>
                    <td><?= htmlspecialchars($token['name']) ?></td>
                    <td><?= htmlspecialchars($token['symbol']) ?></td>
                    <td><?= htmlspecialchars($token['address']) ?></td>
                    <td><?= number_format($token['amount'], 4) ?></td>
                    <td><?= number_format($token['amount'] * $price_helper->get_current_price($token_helper->get_token_by_name($token['name'])['id'])['price']) ?> €</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="/?page=logout" class="logout-button">Déconnexion</a>
</div>

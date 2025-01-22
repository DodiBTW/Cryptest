<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="public/css/global.css" rel="stylesheet" />
    <title><?= $title ?? 'Cryptest' ?></title>
</head>
<body>
    <header>
        <div class="brand">
            <h1>Cryptest</h1>
        </div>
        <nav class="navbar">
            <ul>
                <li><a href="?page=home">Accueil</a></li>
                <li><a href="?page=cryptocurrencies">Cryptomonnaies</a></li>
                <li><a href="?page=wallet">Portfolio</a></li>
            </ul>
        </nav>
    </header>
</body>
</html>

# Lancement du server
`php -S localhost:3000`

# Lancement du script python `./db/fetch_price.php`
Cette commande va lancer le script en background.
`nohup php ./db/fetch_price.php > output.log 2>&1 &`
Pour arrêter le script python :
Pour trouver lequel kill, se servir de cette commande:
`ps aux | grep php`
Puis:
`kill -9 12312` ( par exemple )


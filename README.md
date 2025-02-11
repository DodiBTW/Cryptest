# 🚀 Lancement du Projet Cryptest

Ce guide explique comment démarrer le serveur PHP et exécuter le script de mise à jour des prix en arrière-plan.

---

## 🔥 Lancer le Serveur PHP

Exécutez :

```bash
php -S localhost:3000
```

Assurez-vous d'être dans le répertoire racine du projet.

---

## 📡 Lancer le Script de Mise à Jour des Prix

Exécutez :

```bash
nohup php ./db/fetch_price.php > output.log 2>&1 &
```

Le script tourne en arrière-plan et enregistre les logs dans `output.log`.

---

## 🛑 Arrêter le Script

Liste des processus PHP :

```bash
ps aux | grep php
```

Tuer un processus avec son ID :

```bash
kill -9 <PID>
```

---

## 🛠️ Débogage

Consulter les logs :

```bash
tail -f output.log
```

---

🚀 **Projet prêt à l'emploi !**


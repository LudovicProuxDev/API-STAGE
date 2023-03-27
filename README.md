Symfony-API-STAGES
===

## Version PHP de l'API<br>
- 7.4.33
```
php -v
```

<br>

## Version Symfony de l'API<br>
- 5.4.19
```
symfony -v
```

<br>

## Clonage du dépôt<br>
Choisir l'emplacement de la copie du dossier sur le disque pour effectuer le clonage<br>

SSH :<br>

```
git clone git@github.com:LudovicProuxDev/API-STAGE.git
```

HTTPS :<br>

```
git clone https://github.com/LudovicProuxDev/API-STAGE.git
```
<br>

## Répertoire et ouverture du dossier
```
cd /<Nom dossier>/API-STAGE
code .
```

<br>

## Instalation des dépendances PHP
```
composer install
composer require symfony/apache-pack
composer require symfony/maker-bundle --dev
composer require symfony/serializer-pack
composer require doctrine/annotations
composer require orm
```

<br>

## Documentation de l'API
Voir dossier 'CollectionPostman'<br>

<br>

## Endpoints de l'API<br>

Affichage des étudiants :<br>
`'http://<Adresse IP>/API-STAGE/public/api/student'`<br>
Affichage des entreprises :<br>
`'http://<Adresse IP>/API-STAGE/public/api/company'`<br>
Affichage des stages :<br>
`'http://<Adresse IP>/API-STAGE/public/api/internship'`<br>

<br>

## Changement d'adresse<br>
Remplacer l'adresse IP `'172.30.48.209'` par votre adresse IP locale<br>
Exemple : `'127.0.0.1'`
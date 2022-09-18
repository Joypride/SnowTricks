# SnowTricks

[![forthebadge](https://forthebadge.com/images/badges/built-with-love.svg)](http://forthebadge.com)
[![SymfonyInsight](https://insight.symfony.com/projects/36172c16-2a0e-44c2-a230-968ae0247675/small.svg)](https://insight.symfony.com/projects/36172c16-2a0e-44c2-a230-968ae0247675)

### Installation

- Clonez le Repository.

- Téléchargez et installez les librairies avec la commande ``composer install``

- Configurez vos variables d'environnement dans le fichier .env

- Créez une base de données sur votre SGBD ou avec la commande ``php bin/console doctrine:database:create``

- Importez le fichier snowtricks.sql ou commencez la migration avec la commande ``php bin/console doctrine:migrations:migrate`` et insérez les fixtures avec la commande ``php bin/console doctrine:fixtures:load``

- Lancez l'éxecution du projet avec la commande ``symfony server:start``

## Versions

Ce site a été réalisé avec:
- Symfony 5.4.7
- Composer 2.1.9
- PHP 8.0.12
- Bootstrap 4.1.3
- jQuery 3.6.0
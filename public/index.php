<?php
// path: /public/index.php
// typage strict
declare(strict_types=1);

use model\MyPDO;

// démarrage de la session
session_start();

// inclusion du fichier de configuration si config-prod.php existe
require_once (file_exists('../config-prod.php') ? '../config-prod.php' : '../config-dev.php');

// Autoload fonctionnel avec les namespaces personnels,
// ne fonctionne qu'en PHP Orienté Objet (fait main, on pourrait
// utiliser Composer pour y ajouter nos dépendances)
// et avec une arborescence de fichiers respectant les namespaces
spl_autoload_register(function ($class) {
    $class = str_replace('\\', '/', $class);
    require RACINE_PATH.'/' .$class . '.php';
});

// Connexion à la base de données en singleton, on ne peut pas faire de new MyPDO() car le constructeur est protégé
$db = MyPDO::getInstance();
// ne recrée pas une nouvelle instance, mais retourne l'instance existante
// $db2 = MyPDO::getInstance();

// var_dump($db,$db2);

// Appel du contrôleur de test
require_once RACINE_PATH."/controller/TestController.php";
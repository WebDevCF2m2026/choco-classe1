<?php
// path: /public/index.php
// typage strict
declare(strict_types=1);

// démarrage de la session
session_start();

// inclusion du fichier de configuration si config-prod.php existe
require_once (file_exists('../config-prod.php') ? '../config-prod.php' : '../config-dev.php');
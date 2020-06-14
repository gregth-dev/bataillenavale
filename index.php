<?php

declare(strict_types=1);

require 'peps/core/Autoload.php';

use cfg\CfgApp;
use peps\core\Autoload;
use peps\core\Cfg;
use peps\core\DBAL;
use peps\core\Router;
use peps\core\SessionDB;

// ************************************
// Contrôleur frontal de l'application.
// ************************************

// Initialiser l'autoload (à faire en PREMIER).
Autoload::init();

// Initialiser la configuration (à faire en DEUXIEME).
CfgApp::HOSTS[filter_input(INPUT_SERVER, 'SERVER_NAME', FILTER_SANITIZE_STRING)]::init();

// Initialiser la connexion DB (à faire AVANT d'initialiser SessionDB).
DBAL::init(
	Cfg::get('dbDriver'),
	Cfg::get('dbHost'),
	Cfg::get('dbPort'),
	Cfg::get('dbName'),
	Cfg::get('dbLog'),
	Cfg::get('dbPwd'),
	Cfg::get('dbCharset')
);

// Initialiser la session (à faire APRES avoir initialiser DBAL).
//session_start(); // DEBUG: gestion par défaut de PHP avec des fichiers.
SessionDB::init(
	Cfg::get('sessionTimeout'),
	Cfg::get('sessionMode')
);

// Router la requête du client (à faire en DERNIER).
Router::route();

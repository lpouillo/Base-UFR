<?php
/*
 * Créer le 29 décembre 2008
 * Dernière modification le 26 août 2009
 * ***************************************************
 * Script principal de l'application
 * ***************************************************
 * Cette page est appelée pour toutes les pages sites
 *
 * 
 * Merci à Alexandre Degoutin pour son aide précieuse ...
 * 
 * ***************************************************
 */

// Récupération de la configuration 
require_once ('app/includes/config.php');

// Démarrage de la session
session_start();
		
// Récupération des fonctions de bases 
require_once ('app/includes/common/fonctions.php');
$debut = getmicrotime();

// Connexion à la base de données
require_once ('app/includes/common/db_connect.php');

// Identification de l'utilisateur
require_once ('app/includes/common/user_connect.php');

// Récupération des informations de la page (nom, droits d'accès, contenu)
require_once ('app/includes/common/data_page.php'); 

// Exécution des requètes 
require_once ('app/includes/common/execution_requete.php');

// Construction de la page
require_once ('app/includes/common/render_page.php'); 

// Deconnexion de la base de données
require_once ('app/includes/common/db_close.php');


?>

	

<?php
/*
 * Created on 29 déc. 2008
 *
 * Connexion à la base de donnée MySQL
 */

// connexion à la base ufr 
$db_base_ufr=mysql_connect($db_host,$db_user,$db_password) 
	or die('Echec de connexion au serveur de base de données ('.$db_host.') avec l\'utilisateur '.$db_user.'. Contactez les adminitrateurs ... ');
	
// sélection de la base
$db_test = mysql_select_db($db_name,$db_base_ufr) 
	or die('Impossible d\'utiliser la base '.$db_name.'. Contactez les adminitrateurs ... ');

// spécification du charset par défaut pour éviter les problèmes d'accent
mysql_query("SET NAMES utf8",$db_base_ufr)
	or die('Impssible de sélectionner le charset utf8. Contactez les adminitrateurs ... ');

?>

<?php
/*
 * Created on 2 sept. 2008
 *
 * Partie haute du template du site, 
 * se décompose en :
 *  - un conteneur global de largeur 100%
 *  - une div header de 80px de haut
 *  - une div menu de 155px de large
 *  - une div content 
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
<head>
	<LINK rel="SHORTCUT ICON" href="favicon.ico">
	<title>
	<?php
	echo $titre_page;
	?>
	</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<link rel="stylesheet" type="text/css" media="screen" href="public/css/main.css"  title="CSS global" />
	<script language="javascript" type="text/javascript" src="public/javascript/mootools-1.2.2-core-yc.js"></script>
	<script language="javascript" type="text/javascript" src="public/javascript/mootools-1.2.2.2-more.js"></script>
	<script language="javascript" type="text/javascript" src="public/javascript/main.js"> </script>
	<script language="javascript" type="text/javascript" src="public/javascript/DatePicker.js"> </script>
</head>

<body onload="check_redirection();<?php 
	// Mettre le focus au bon endroit pour certaines pages
	switch ($page) {
		case 'connexion':
			echo 'document.form_login.login.focus();';
		break;
	}
	?>">
	<!-- Contenuer global de toute la page -->
	<div id="conteneur">
	<!-- Contient le bandeau du site -->
		<div id="header">
			<a href="index.php" title="Accueil du site">
			<img border=0 src="public/images/bando.png" alt="Accueil" title="Retourner à l'accueil du site"/></a>
		</div>
	
		<?php
	
		if (isset($_SESSION['id_user'])) {
			if ($beta) {
				echo '<div id="bug_report">
					<img src="public/images/icons/danger.png" /> Avertissement : Importante mise à jour sur la base. <br/>
					N\'hésitez pas à signaler des <a href="#" onclick="popupForm(\'bug_report\',\'accueil\');"><img border="0" src="public/images/icons/bug.png" height="11px;"/>bugs</a>
				 </div>';
			}
			// Contient le menu de gauche de la page
			echo '<div id="menu">';
			require_once('app/includes/common/menu.php');
			echo '</div><div id="content">';
		} else {
			// Fais prendre la largeur totale de la page si pas de menu
			echo '</div><div id="content_large">';
		}			
		?>
		<!-- affiche le log d'execution des requètes envoyées à la page -->
		<div id="log_requete" class="'.$log_request_class.'" style="float:left;"><?php echo $log_request?></div>
		<!-- affiche l'icone et le message de la page issu de la base de données (voir data_page.php) -->

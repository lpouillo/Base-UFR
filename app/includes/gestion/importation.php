<?php
$html='<div class="content_tab"><h2><img src="public/images/icons/importation.png"/> Importation de données</h2>';
if (empty($_POST['type_importation'])) {
	$html.='<h3>Données des tables primaires</h3>';
	$html.='<form method="post" action="#" id="choix_importation_csv">			
			<input type="hidden" name="force_template" value="yes"/>
			<input type="hidden" name="page" value="gestion"/>
			<input type="hidden" name="section" value="importation"/>
			<input type="hidden" name="type_importation" value="table"/><p>';
	$i_entite=0;
	foreach($menu['entites'] as $entite => $data) {
		$i_entite++;
		if ($entite !='annexes') {
			$html.='<input type="submit" name="table" value="'.$entite.'" checked="checked"/> ';
		}	
		if ($i_entite>6) {
			$html.= '<br/>';
			$i_entite=0;
		}
	}
	$html.='</p></form>
		<h3>Fichier spécifiques</h3>';	
	$html.='<h3>Depuis le sed</h3>';
	
	$html.='</div>';
	
} else {
	echo '<pre>';
	print_r($_POST['']);
	echo '</pre>';/*
	switch ($_POST['type_importation']) {
		case 'liste_enseignants':
			require_once('app/inc/importation/liste_enseignants.php');	
		break;
		case 'sed_choix_modules':
			// Tranfert des infos des étudiants
			$s_backup_l_etudiant_ue="CREATE TABLE base_ufr.l_etudiant_ue_".date('Ymd')." LIKE  base_ufr.l_etudiant_ue;";
			$if_create=mysql_query($s_backup_l_etudiant_ue) or die(mysql_error());
			$if_save=mysql_query("INSERT INTO base_ufr.l_etudiant_ue_".date('Ymd')." SELECT * FROM	base_ufr.l_etudiant_ue")
					or die(mysql_error());
			
			if ($if_create and $if_save) {
				$html.='<p>Table l_etudiant_ue de base_ufr sauvegardée</p>';
			} else {
				die (mysql_error());
			}
			$s_truncate_l_etudiant_ue="TRUNCATE base_ufr.l_etudiant_ue";
			if (mysql_query($s_truncate_l_etudiant_ue)) {
				$html.='<p>Table l_etudiant_ue de base_ufr vidée</p>';
			}
			$s_insert_infos="INSERT INTO `base_ufr`.`l_etudiant_ue` 
								SELECT * FROM `sed`.`l_etudiant_ue` ";
			if (mysql_query($s_insert_infos)) {
				$html.='<p>Table base_ufr.l_etudiant_ue mise à jour à partir de sed.l_etudiant_ue</p>';
			}
		break;
		case 'infos_ue':
			$html.='<h3>Importation des informations sur les Unités d\'Enseignement</h3>';
			require_once('app/inc/importation/infos_ue.php');
		break;
		case 'infos_etudiant':
			$html.='<h3>Importation des informations sur les Unités d\'Enseignement</h3>';
			require_once('app/inc/importation/infos_etudiant.php');
		break;
		case 'scolarite_etudiant':
			$html.='<h3>Importation des informations sur les Unités d\'Enseignement</h3>';
			require_once('app/inc/importation/scolarite_etudiant.php');
		break;
		case 'notes_ue':
			$html.='<h3>Importation d\'un fichier de note pour une UE</h3>';
			require_once('app/inc/importation/notes_ue.php');
		break;
		case 'stages_entreprises':
			$html.='<h3>Importation des stages en entreprises</h3>';
			require_once('app/inc/importation/stages_entreprises.php');
		break;
		default:
		$html.='Non implémenté';
	}*/
}
?>

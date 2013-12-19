<?php
/*
 * Created on 4 nov. 2008
 *
 */
$html='<div class="content_tab"><h2><img src="public/images/icons/update.png"/>
	Mises à jour</h2>';

if (empty($_POST['type_update'])) {
	$html.='<h3>Internes</h3>';
	$html .='<form id="update_interne" method="post" action="index.php">
			<input type="hidden" name="page" value="gestion"/>
			<input type="hidden" name="section" value="update"/>
			<input type="hidden" name="type_update" value="internes"/>
			<p><input name="element_a_mettre_a_jour" type="submit" value="Lancer l\'attribution des UE" onclick="submitForm(\'update_interne\')"/>
			<input name="element_a_mettre_a_jour"type="submit" value="Calculer les moyennes" onclick="submitForm(\'update_interne\')"/></p></form>';

	$html.='<form method="POST" action="#">
			<h3>Mise à jour du site des étudiants et diplomés</h3>
			<p>
			<input type="hidden" name="force_template" value="yes"/>
			<input type="hidden" name="type_update" value="sed"/>
			<input type="hidden" name="page" value="gestion"/>
			<input type="hidden" name="section" value="update"/>
			<input type="submit" name="cible" value="ETUDIANTS"/>
			<input type="submit" name="cible" value="STAGES"/>
			<input type="submit" name="cible" value="EMPLOIS"/>
			</p>
			</form>';
	
	
	
	$html.='<form method="POST" action="#">
			<h3>Mise à jour des wikis</h3>
			<p>
			<input type="hidden" name="force_template" value="yes"/>
			<input type="hidden" name="type_update" value="sites"/>
			<input type="hidden" name="page" value="gestion"/>
			<input type="hidden" name="section" value="update"/>
			<input type="submit" name="cible" value="step.ipgp.fr"/>
			<input type="submit" name="cible" value="master.ipgp.fr"/>
			<input type="submit" name="cible" value="iup.ipgp.fr"/>
			<input type="submit" name="cible" value="ed109.ipgp.fr"/>
			</p>
			</form>';
	
	

} else {
	echo '<pre>';
	print_r($_POST);
	echo '</pre>';
	switch($_POST['type_update']) {
		case 'internes':
			switch($_POST['element_a_mettre_a_jour']) {
				case 'Calculer les moyennes':
					require_once('app/includes/update/calcul_des_moyennes.php');
					$html.='calcul des moyennes effectué';		
				break;
				case 'attribution_des_ues':
					$html.='attribution des ues par défaut</h3>';
					require_once('app/inc/mises_a_jour/attribution_des_ues.php');
				break;
				default:
				$html.'</h3><p>Non implémenté</p>';
				break;
			}
		break;
		case 'sites':
			switch($_POST['cible']) {
				case 'ed109.ipgp.fr':
					require_once ('app/inc/mises_a_jour/ed109.ipgp.fr.php');
				break;
				default:
				$html .= 'Cette mise à jour n\'est pas disponible';
			}
		break;
		case 'sed':
			require_once ('app/inc/mises_a_jour/sed.ipgp.fr.php');
		break;
	}
}

?>


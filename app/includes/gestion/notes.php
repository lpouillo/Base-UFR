<?
$html='<div class="content_tab"><h2><img src="public/images/icons/notes.png"/> Gestion des notes</h2>';
if (empty($_POST['action_notes'])) {
	$html.='<h3><img src="public/images/icons/importation.png"/> Importation des notes d\'une UE</h3>
				<form method="post" action="#" id="action_notes">
				<input type="hidden" name="page" value="gestion"/>
				<input type="hidden" name="section" value="notes"/>
				<input type="hidden" name="action_notes" value="notes_ue" /> 
				<p><input type="submit" onclick="submitForm(\'action_notes\')" value="Démarrer l\'importation"/></p>
				</form>';	
	$html.='<h3>
				<img src="public/images/icons/update.png"/> Calcul des moyennes et des classements</h3>
				<form method="post" action="#" id="calcul_moyenne">
				<input type="hidden" name="page" value="gestion"/>
				<input type="hidden" name="section" value="notes"/>
				<input type="hidden" name="action_notes" value="calcul_moyenne"/>
				<p><input type="submit" value="Mettre à jour" onclick="submitForm(\'calcul_moyenne\')"/></p></form></li>';
} else {
	switch($_POST['action_notes']) {
		case 'notes_ue':
			$html.='<h3><img src="public/images/icons/importation.png"/> Importation des notes de l\'ue</h3>';
			require_once('app/includes/import/notes_ue.php');
		break;
		case 'calcul_moyenne':
			$html.='<h3><img src="public/images/icons/update.png"/> Calcul des moyennes et des classements</h3>';
			require_once('app/includes/update/calcul_des_moyennes.php');	
	}

}
$html.='</div>';

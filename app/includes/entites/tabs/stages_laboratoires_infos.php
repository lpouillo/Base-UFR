<?php

$table='Stages_Laboratoires';
$champs=array('id_stage_laboratoire','date_in','date_modif','sujet','description',
	'id_etudiant','date_debut','date_fin','id_photo','infos_complementaires');
$filtres=array('id_stage_laboratoire' => $id);
$infos=recuperation_donnees(generation_requete($table,$champs,$filtres,$ordre));

$html.='<table border="0">
			<tr>
				<th>id_stage_laboratoire </th><td>'.$infos[0]['id_stage_laboratoire'].'</td>
				<th>Date de creation</th><td>'.$infos[0]['date_in'].'</strong></td>
				<th>Date de modification </th><td>'.$infos[0]['date_modif'].'</strong></th>
				<td rowspan="4"><div id="photo"><img height="120px" src="public/images/photos/ue_inconnue.jpg"/ alt="photographie"/></div></td>
			</tr>
			<tr>
				<th>Intitulé</th><td colspan="6"><input id="sujet" name="sujet" value="'.$infos[0]['sujet'].'" size="70"/></td>
			</tr>
			<tr>
				<th>Date de début</th><td colspan="2">'.champ_date('date_debut',$infos[0]['date_debut']).'</td>
				<th>Date de fin</th><td colspan="2">'.champ_date('date_fin',$infos[0]['date_fin']).'</td>
			</tr>
			<tr>
				<th colspan="2">Étudiant</th><td colspan="5">'.generation_select('id_etudiant','Etudiants',array('id_etudiant','CONCAT(nom,\' \',prenom)'),$infos[0]['id_etudiant']).'</td>

			</tr>	
			<tr>
				<td colspan="7"><h3>Détails</h3></td>
			</tr>	
			<tr>
				<th colspan="2">Résumé</th><td colspan="5"><textarea id="description" name="description" rows="10" cols="120">'.$infos[0]['description'].'</textarea></td>
			</tr>
			<tr>
				<th colspan="2">Informations complémentaires</th><td colspan="5"><textarea id="infos_complementaires" name="infos_complementaires" rows="3" cols="120">'.$infos[0]['infos_complementaires'].'</textarea></td>
			</tr>
		</table>';
?>

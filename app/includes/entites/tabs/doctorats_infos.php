<?php 
$table='Doctorats';
$champs=array('id_doctorat','date_in','date_modif','id_etudiant','titre','sujet','date_debut','date_fin','id_cotutelle');
$filtres=array('id_doctorat' => $id);
$infos=recuperation_donnees(generation_requete($table,$champs,$filtres,$ordre));

$html.='<table border="0">
			<tr>
				<th>id_doctorat </th><td>'.$infos[0]['id_doctorat'].'</td>
				<th>Date de creation</th><td>'.$infos[0]['date_in'].'</strong></td>
				<th>Date de modification </th><td>'.$infos[0]['date_modif'].'</strong></th>
				<td rowspan="4"><div id="photo"><img height="120px" src="public/images/photos/ue_inconnue.jpg"/ alt="photographie"/></div></td>
			</tr>
			<tr>
				<th>Intitulé</th><td colspan="6"><input id="sujet" name="sujet" value="'.$infos[0]['titre'].'" size="70"/></td>
			</tr>
			<tr>
				<th>Date de début</th><td>'.champ_date('date_debut',$infos[0]['date_debut']).'</td>
				<th>Date de fin</th>'.champ_date('date_fin',$infos[0]['date_fin']).'</td>
				<th>Étudiant</th><td>'.generation_select('id_etudiant','Etudiants',array('id_etudiant','CONCAT(nom,\' \',prenom)'),$infos[0]['id_etudiant']).'</td>
				
			</tr>
			<tr>
				<th>Gratification</th><td><input id="gratification" name="gratification" value="'.$infos[0]['gratification'].'" size="10"/></td>
				<th>Trouvé</th><td>'.generation_select('id_trouve_stage','a_trouve_stage',array('id_trouve_stage','libelle'),$infos[0]['id_trouve_stage']).'</td>
				<th>Emploi</th><td><input id="emploi" name="emploi" value="'.$infos[0]['emploi'].'"/></td>
			</tr>
			<tr>
				<td colspan="7"><h3>Détails</h3></td>
			</tr>		
			<tr>
				<th colspan="2">Résumé</th><td colspan="5"><textarea id="description" name="description" rows="10" cols="115">'.$infos[0]['description'].'</textarea></td>
			</tr>
			<tr>
				<th colspan="2">Informations complémentaires</th><td colspan="5"><textarea id="infos_complementaires" name="infos_complementaires" rows="3" cols="115">'.$infos[0]['infos_complementaires'].'</textarea></td>
			</tr>
			<tr>
				<td colspan="7"><h3>Soutenance et rapport</h3></td>
			</tr>		
			<tr>
				<th>Date</th><td colspan="2"><input id="date_soutenance" name="date_soutenance" value="'.$infos[0]['date_soutenance'].'"/></td>
				<th>Heure</th><td colspan="2"><input id="heure_soutenance" name="heure_soutenance" value="'.$infos[0]['heure_soutenance'].'"/></td>
			</tr>		
			<tr>
				<th>Confidentialité rapport</th><td colspan="2">'.generation_oui_non('confidentialite_rapport',$infos[0]['confidentialite_rapport']).'</td>
				<th>Confidentialité soutenance</th><td colspan="2">'.generation_oui_non('confidentialite_soutenance',$infos[0]['confidentialite_soutenance']).'</td>
			</tr>
		</table>';

?>
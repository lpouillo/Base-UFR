<?
$table='Stages_Entreprises';
$champs=array('id_stage_entreprise','date_in','date_modif','sujet','description','id_etudiant','id_entreprise','date_debut','date_fin','gratification','id_trouve_stage','confidentialite_rapport',
			'confidentialite_soutenance','date_soutenance','heure_soutenance','informations_complementaires','emploi');
$filtres=array('id_stage_entreprise' => $id);
$infos=recuperation_donnees(generation_requete($table,$champs,$filtres,$ordre));

$html.='<table border="0">
			<tr>
				<th>id_stage_entreprise </th><td>'.$infos[0]['id_stage_entreprise'].'</td>
				<th>Date de creation</th><td>'.$infos[0]['date_in'].'</strong></td>
				<th>Date de modification </th><td>'.$infos[0]['date_modif'].'</strong></th>
				<td rowspan="4"><div id="photo"><img height="120px" src="public/images/photos/ue_inconnue.jpg"/ alt="photographie"/></div></td>
			</tr>
			<tr>
				<th>Intitulé</th><td colspan="6">'.normal_input('sujet',$infos[0]['sujet'],70).'</td>
			</tr>
			<tr>
				<th>Entreprise</th><td colspan="6">'.generation_select('id_entreprise','Entreprises',array('id_entreprise','nom'),$infos[0]['id_entreprise']).'</td>
			</tr>
			<tr>
				<th>Date de début</th><td>'.champ_date('date_debut',$infos[0]['date_debut']).'</td>
				<th>Date de fin</th><td>'.champ_date('date_fin',$infos[0]['date_fin']).'</td>
				<th>Étudiant</th><td>'.generation_select('id_etudiant','Etudiants',array('id_etudiant','CONCAT(nom,\' \',prenom)'),$infos[0]['id_etudiant']).'</td>
				
			</tr>
			<tr>
				<th>Gratification</th><td>'.normal_input('gratification',$infos[0]['gratification'],10).'</td>
				<th>Trouvé</th><td>'.generation_select('id_trouve_stage','a_trouve_stage',array('id_trouve_stage','libelle'),$infos[0]['id_trouve_stage']).'</td>
				<th>Emploi</th><td>'.normal_input('emploi',$infos[0]['emploi'],25).'</td>
			</tr>
			<tr>
				<td colspan="7"><h3>Détails</h3></td>
			</tr>		
			<tr>
				<th colspan="2">Résumé</th><td colspan="5"><textarea '.$ro.' id="description" name="description" rows="10" cols="115">'.$infos[0]['description'].'</textarea '.$ro.'></td>
			</tr>
			<tr>
				<th colspan="2">Informations complémentaires</th><td colspan="5"><textarea '.$ro.' id="infos_complementaires" name="infos_complementaires" rows="3" cols="115">'.$infos[0]['infos_complementaires'].'</textarea '.$ro.'></td>
			</tr>
			<tr>
				<td colspan="7"><h3>Soutenance et rapport</h3></td>
			</tr>		
			<tr>
				<th>Date</th><td colspan="2">'.champ_date('date_soutenance',$infos[0]['date_soutenance']).'</td>
				<th>Heure</th><td colspan="2">'.normal_input('heure_soutenance',$infos[0]['heure_soutenance'],20).'</td>
			</tr>		
			<tr>
				<th>Confidentialité rapport</th><td colspan="2">'.generation_oui_non('confidentialite_rapport',$infos[0]['confidentialite_rapport']).'</td>
				<th>Confidentialité soutenance</th><td colspan="2">'.generation_oui_non('confidentialite_soutenance',$infos[0]['confidentialite_soutenance']).'</td>
			</tr>
		</table>';
?>

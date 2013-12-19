<?php 
$table='Emplois';
$champs=array('id_emploi','date_in','date_modif','libelle','id_entreprise_mere','id_entreprise','id_type_contrat','id_fonction','domaine','date_debut','date_fin','description' );
$filtres=array('id_emploi' => $id);
$infos=recuperation_donnees(generation_requete($table,$champs,$filtres,$ordre));

$html.='<table border="0">
			<tr>
				<th>id_emploi </th><td>'.$infos[0]['id_emploi'].'</td>
				<th>Date de creation</th><td>'.$infos[0]['date_in'].'</strong></td>
				<th>Date de modification </th><td>'.$infos[0]['date_modif'].'</strong></th>
			</tr>
			<tr>
				<th>Intitulé</th><td colspan="6">'.normal_input('libelle',$infos[0]['libelle'],60).'</td>	</tr>
			<tr>
				<th>Date de début</th><td>'.champ_date('date_debut',$infos[0]['date_debut']).'</td>
				<th>Date de fin</th><td>'.champ_date('date_fin',$infos[0]['date_fin']).'</td>
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
		</table>';

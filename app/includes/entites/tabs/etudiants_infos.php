<?php
$table='Etudiants';
if ($mode=='rw') {
	$champs=array('id_etudiant','date_in','date_modif','uid','id_civilite','nom','prenom','id_situation','id_photo',
	'numero_ine','numero_etudiant','date_naissance','id_ville_naissance','id_departement_naissance','id_pays_naissance','id_nationalite',
	'email_ipgp','email_p7','email_perso','telephone_scol','telephone_perm','telephone_mobile','adresse_scol','code_postal_scol','id_ville_scol','id_pays_scol',
	'adresse_perm','code_postal_perm','id_ville_perm','id_pays_perm');
} else {
	$champs=array('id_etudiant','date_in','date_modif','uid','id_civilite','nom','prenom','id_photo',
	'numero_ine','numero_etudiant','date_naissance','email_ipgp','email_p7');
}
$filtres=array('id_etudiant' => $id);
$infos=recuperation_donnees(generation_requete($table,$champs,$filtres,$ordre));

if ($mode=='rw') {
	$html.='
		<table>
			<tr>
				<th>id_etudiant </th><td>'.$infos[0]['id_etudiant'].'</td>
				<th>Civilité</th><td colspan="3">'.generation_select('id_civilite','a_civilite',array('id_civilite','libelle'),$infos[0]['id_civilite']).'</td>
				<td rowspan="5">'.get_photo($infos[0]['id_photo']).'</td> 
			</tr>
			<tr>
				<th>Date de creation</th><td>'.$infos[0]['date_in'].'</strong></td>
				<th>Nom</th><td colspan="3">'.normal_input('nom',$infos[0]['nom'],30).'</td>
			</tr>
			<tr>
				<th>Date de modification </th><td>'.$infos[0]['date_modif'].'</strong></th>
				<th>Prénom</th><td colspan="3">'.normal_input('prenom',$infos[0]['prenom'],30).'</td>
			</tr>
			<tr>
				<th>uid</th><td><input id="uid" name="uid" value="'.$infos[0]['uid'].'" size="8" /></td>
				<th>Date de naissance</th><td>'.champ_date('date_naissance',$infos[0]['date_naissance']).'</td>
				<th>Nationalité</th><td>'.generation_select('id_nationalite','a_nationalite',array('id_nationalite','libelle'),$infos[0]['id_nationalite']).'</td>
			</tr>
			<tr>
				<th>Numéro INE</th><td><input id="numero_ine" name="numero_ine" value="'.$infos[0]['numero_ine'].'" size="15"/></td>
				<th>Numéro étudiant</th><td><input id="numero_etudiant" name="numero_etudiant" value="'.$infos[0]['numero_etudiant'].'" size="10"/></td>
			</tr>
			<tr>
				<td colspan="6"><h3>Contact</h3></td>
			</tr>
			<tr>
				<th>Email IPGP</th><td><input id="email_ipgp" name="email_ipgp" value="'.$infos[0]['email_ipgp'].'" size="15"/></td>
				<th>Email Paris Diderot</th><td><input id="email_p7" name="email_p7" value="'.$infos[0]['email_p7'].'" size="15"/></td>
				<th>Email Personnel</th><td><input id="email_perso" name="email_perso" value="'.$infos[0]['email_perso'].'" size="15"/></td>
			</tr>
			<tr>
				<th>Téléphone mobile</th><td><input id="telephone_mobile" name="telephone_mobile" value="'.$infos[0]['telephone_mobile'].'" size="15"/></td>
				<th>téléphone scolaire</th><td><input id="telephone_scol" name="telephone_scol" value="'.$infos[0]['telephone_scol'].'" size="15"/></td>
				<th>Téléphone permanent</th><td><input id="telephone_perm" name="telephone_perm" value="'.$infos[0]['telephone_perm'].'" size="15"/></td>
			</tr>
			<tr>
				<td colspan="6"><h3>Adresses</h3></td>
			</tr>
			<tr>
				<th rowspan="3">Année scolaire</th><td colspan="3" rowspan="3"><textarea '.$ro.' id="adresse_scol" name="adresse_scol" rows="3" cols="45">'.$infos[0]['adresse_scol'].'</textarea '.$ro.'></td>
				<th>Code postal</th><td><input id="code_postal_scol" name="code_postal_scol" value="'.$infos[0]['code_postal_scol'].'" size="10"/></td>
			</tr>
			<tr>
				<th>Ville</th><td>'.generation_select('id_ville_scol','a_ville',array('id_ville','libelle'),$infos[0]['id_ville_scol']).'</td>
			</tr>
			<tr>
				<th>Pays</th><td>'.generation_select('id_pays_scol','a_pays',array('id_pays','libelle'),$infos[0]['id_pays_scol']).'</td>
			</tr>
			<tr>
				<th rowspan="3">Permanente</th><td colspan="3" rowspan="3"><textarea '.$ro.' id="adresse_perso"  name="adresse_perso" rows="3" cols="45">'.$infos[0]['adresse_perso'].'</textarea '.$ro.'></td>
				<th>Code postal</th><td><input id="code_postal_perso" name="code_postal_perso" value="'.$infos[0]['code_postal_perso'].'" size="10"/></td>
			</tr>
			<tr>
				<th>Ville</th><td>'.generation_select('id_ville_perso','a_ville',array('id_ville','libelle'),$infos[0]['id_ville_perso']).'</td>
			</tr>
			<tr>
				<th>Pays</th><td>'.generation_select('id_pays_perso','a_pays',array('id_pays','libelle'),$infos[0]['id_pays_perso']).'</td>
			</tr>
		</table>';
} else {

	$html.='<table>
			<tr>
				<th>id_etudiant </th><td>'.$infos[0]['id_etudiant'].'</td>
				<th>Civilité</th><td colspan="2">'.generation_select('id_civilite','a_civilite',array('id_civilite','libelle'),$infos[0]['id_civilite']).'</td>
				<td rowspan="4">'.get_photo($infos[0]['id_photo']).'</td> 
			</tr>
			<tr>
				<th>Date de creation</th><td>'.$infos[0]['date_in'].'</strong></td>
				<th>Nom</th><td colspan="2">'.normal_input('nom',$infos[0]['nom'],30).'</td>
			</tr>
			<tr>
				<th>Date de modification </th><td>'.$infos[0]['date_modif'].'</strong></th>
				<th>Prénom</th><td colspan="2">'.normal_input('prenom',$infos[0]['prenom'],30).'</td>
			</tr>
			<tr>
				<th>uid</th><td>'.normal_input('uid',$infos[0]['uid'],8).'</td>
				<th>Date de naissance</th><td colspan="2">'.champ_date('date_naissance',$infos[0]['date_naissance']).'</td>
			</tr>
			<tr>
				<td colspan="6"><h3>Détails</h3></td>
			</tr>
			<tr>
				<th>Numéro INE</th><td colspan="2">'.normal_input('numero_ine',$infos[0]['numero_ine'],15).'</td>
				<th>Numéro étudiant</th><td colspan="2">'.normal_input('numero_etudiant',$infos[0]['numero_etudiant'],10).'</td>
			</tr>
			<tr>
				<th>Email IPGP</th><td colspan="2">'.normal_input('email_ipgp',$infos[0]['email_ipgp'],25).'</td>
				<th>Email Paris Diderot</th><td colspan="2">'.normal_input('email_p7',$infos[0]['email_p7'],25).'</td>
			</tr>
			<tr>
				
			</tr>
		</table>';
}
?>

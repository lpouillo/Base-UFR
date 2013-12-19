<?php
$table='Entreprises';
$champs=array('id_entreprise','date_in','date_modif','id_entreprise_mere','nom','adresse','code_postal',
	'id_ville','id_pays','telephone','fax','email','www','id_secteur','descriptif','id_photo');
$filtres=array('id_entreprise' => $id);
$infos=recuperation_donnees(generation_requete($table,$champs,$filtres,$ordre));

$html.='<table border="0">
			<tr>
				<th>id_entreprise </th><td>'.$infos[0]['id_entreprise'].'</td>
				<th>Date de creation</th><td>'.$infos[0]['date_in'].'</strong></td>
				<th>Date de modification </th><td>'.$infos[0]['date_modif'].'</strong></th>
				<td rowspan="3">'.get_photo($infos[0]['id_photo']).'</td>
			</tr>
			<tr>
				<th>Intitulé</th><td colspan="5">'.normal_input('nom',$infos[0]['nom'],70).'</td>
			</tr>
			<tr>
				<th>Entreprise mère</th><td colspan="5">'.generation_select('id_entreprise_mere','Entreprises',array('id_entreprise','nom'),$infos[0]['id_entreprise_mere']).'</td>
			</tr>
			<tr>
				<th>Secteur</th><td colspan="4">'.generation_select('id_secteur','a_secteur',array('id_secteur','libelle'),$infos[0]['id_secteur']).'</td>
			</tr>
			<tr>
				<td colspan="7"><h3>Coordonnées</h3></td>
			</tr>
			<tr>
				<th rowspan="3">Adresse</th><td colspan="3" rowspan="3"><textarea id="adresse"  name="adresse" rows="3" cols="60">'.$infos[0]['adresse'].'</textarea></td>
				<th>Code postal</th><td>'.normal_input('code_postal',$infos[0]['code_postal'],8).'</td>
			</tr>
			<tr>
				<th>Ville</th><td>'.generation_select('id_ville','a_ville',array('id_ville','libelle'),$infos[0]['id_ville']).'</td>
			</tr>
			<tr>
				<th>Pays</th><td>'.generation_select('id_pays','a_pays',array('id_pays','libelle'),$infos[0]['id_pays']).'</td>
			</tr>
			<tr>
				<th>Téléphone</th><td colspan="2">'.normal_input('telephone',$infos[0]['telephone'],14).'</td>
				<th>Fax</th><td colspan="2">'.normal_input('fax',$infos[0]['fax'],14).'</td>
			</tr>
			<tr>
				<th>Email</th><td colspan="2">'.normal_input('email',$infos[0]['email'],30).'</td>
				<th>Site web</th><td colspan="2">'.normal_input('www',$infos[0]['www'],30).'</td>
			</tr>
			<tr>
				<td colspan="7"><h3>Description</h3></td>
			</tr>
			<tr>
				<td colspan="7"><textarea id="description"  name="description" rows="5" cols="120">'.$infos[0]['description'].'</textarea></td>
			</tr>
		</table>';
?>
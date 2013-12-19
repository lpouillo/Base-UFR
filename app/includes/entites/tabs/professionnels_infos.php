<?php 
$table='Professionnels';
$champs=array('id_professionnel','date_in','date_modif','id_civilite','nom','prenom','id_fonction','id_entreprise','email','telephone','fax');
$filtres=array('id_professionnel' => $id);
$infos=recuperation_donnees(generation_requete($table,$champs,$filtres,$ordre));

$html.='
<table>
	<tr>
		<th>id_professionel </th><td>'.$infos[0]['id_professionel'].'</td>
		<th>Civilité</th><td colspan="2">'.generation_select('id_civilite','a_civilite',array('id_civilite','libelle'),$infos[0]['id_civilite']).'</td>
		<td rowspan="5">'.get_photo($infos[0]['id_photo']).'</td> 
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
		<th>Entreprise</th><td colspan="4">'.generation_select('id_entreprise','Entreprises',array('id_entreprise','nom'),$infos[0]['id_entreprise']).'</td>
	</tr>
	<tr>
		<th>Fonction</th><td colspan="4">'.generation_select('id_fonction','a_fonction',array('id_fonction','libelle'),$infos[0]['id_fonction']).'</td>
	</tr>
	<tr>
		<td colspan="6"><h3>Contact</h3>
	</tr>
	<tr>
		<th>Téléphone</th><td>'.normal_input('telephone',$infos[0]['telephone'],15).'</td>
		<th>Fax</th><td>'.normal_input('fax',$infos[0]['fax'],15).'</td>
		<th>Email</th><td>'.normal_input('email',$infos[0]['email'],30).'</td>
	</tr>
</table>';
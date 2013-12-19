<?php
$table='Laboratoires';
$champs=array( 'id_laboratoire','date_in','date_modif','nom','adresse','code_postal','id_ville','id_pays',
	'telephone','fax','email','www','id_etablissement','id_directeur');
$filtres=array('id_laboratoire' => $id);
$infos=recuperation_donnees(generation_requete($table,$champs,$filtres,$ordre));

$html.='<table>
			<tr>
				<th>id_laboratoire </th><td>'.$infos[0]['id_laboratoire'].'</td>
				<td></td><td></td>
				<td rowspan="4"><div id="photo"><img height="120px" src="public/images/photos/inconnu.jpg"/ alt="photographie"/></div></td> 
			</tr>
			<tr>
				<th>Date de creation</th><td>'.$infos[0]['date_in'].'</strong></td>
				<th>Nom</th><td colspan="2"><input id="nom" name="nom" value="'.$infos[0]['nom'].'" size="30"/></td>
			</tr>
			<tr>
				<th>Date de modification </th><td>'.$infos[0]['date_modif'].'</strong></th>
				<th>Prénom</th><td colspan="2"><input id="prenom" name="prenom" value="'.$infos[0]['prenom'].'" size="30"/></td>
			</tr>
			<tr>
				<th>uid</th><td><input id="uid" name="uid" value="'.$infos[0]['uid'].'" size="8"/></td>
				<th>Date de naissance</th><td colspan="2"><input id="date_naissance" name="date_naissance" value="'.$infos[0]['date_naissance'].'" size="30"/></td>
			</tr>
			<tr>
				<td colspan="6"><h3>Détails</h3></td>
			</tr>
			<tr>
				<th>Numéro INE</th><td colspan="2"><input id="numero_ine" name="numero_ine" value="'.$infos[0]['numero_ine'].'" size="15"/></td>
				<th>Numéro étudiant</th><td colspan="2"><input id="numero_etudiant" name="numero_etudiant" value="'.$infos[0]['numero_etudiant'].'" size="10"/></td>
			</tr>
			<tr>
				<th>Email IPGP</th><td colspan="2"><input id="email_ipgp" name="email_ipgp" value="'.$infos[0]['email_ipgp'].'" size="15"/></td>
				<th>Email Paris Diderot</th><td colspan="2"><input id="email_p7" name="email_p7" value="'.$infos[0]['email_p7'].'" size="15"/></td>
			</tr>
			
		</table>';
?>
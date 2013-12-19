<?php
/*
 * Created on 20 oct. 2008
 *
 */
			
// Récupération des données
$table='Enseignants';
$champs=array('id_enseignant','date_in','date_modif','uid','id_civilite','nom','prenom','epouse','id_photo',
	'id_statut','id_etablissement','id_laboratoire','id_ufr','CNU',
	'email_pro','email_perso','www',
	'telephone_professionnel','telephone_personnel','telephone_mobile',
	'adresse_pro','code_postal_pro','id_ville_pro','id_pays_pro',
	'adresse_perso','code_postal_perso','id_ville_perso','id_pays_perso','id_grade');
$filtres=array('id_enseignant' => $_SESSION['id_link']);
$infos=recuperation_donnees(generation_requete($table,$champs,$filtres,$ordre));

// Création du formulaire 
$html.='<h2><input onclick="submitForm(\'update_infos\');" type="submit" value="SAUVER"/> <img src="public/images/icons/infos.png"/> Mes informations</h2>';

$html.='<form id="update_infos" method="post" action="index.php"  method="post" >
		<input type="hidden" name="modification_soumise" value="update_infos">
		<input type="hidden" name="page" value="mon_espace">
		<input type="hidden" name="section" value="mes_infos">
		<input type="hidden" name="action" value="modifier">
		<input type="hidden" name="id" value="'.$_SESSION['id_link'].'">
		<input type="hidden" name="div_target" value="content">';
	
$html.= '
	<div class="content_tab">
		<table border=0>
			<tr>
				<th>id_enseignant </th><td>'.$infos[0]['id_enseignant'].'</td>
				<th>Civilité</th><td colspan="2">'.generation_select('id_civilite','a_civilite',array('id_civilite','libelle'),$infos[0]['id_civilite']).'</td>
				<td rowspan="4">'.get_photo($infos[0]['id_photo']).'</td> 
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
				<th>Épouse</th><td colspan="2"><input id="epouse" name="epouse" value="'.$infos[0]['epouse'].'" size="30"/></td>
			</tr>
			<tr>
				<td colspan="6"><h3>Affiliation et statut</h3></td>
			</tr>
			<tr>
				<th>Laboratoire</th><td colspan="3">'.generation_select('id_laboratoire','Laboratoires',array('id_laboratoire','nom'),$infos[0]['id_laboratoire']).'</td>
				<th>Statut</th><td colspan="3">'.generation_select('id_statut','a_statut',array('id_statut','libelle'),$infos[0]['id_statut']).'</td>
			</tr>
			<tr>
				<th>Etablissement</th><td colspan="3">'.generation_select('id_etablissement','Etablissements',array('id_etablissement','nom'),$infos[0]['id_etablissement']).'</td>
				<th>Grade</th><td colspan="3">'.generation_select('id_grade','a_grade',array('id_grade','libelle'),$infos[0]['id_grade']).'</td>
			<tr>
				
				<th>UFR</th><td colspan="3">'.generation_select('id_ufr','a_ufr',array('id_ufr','libelle'),$infos[0]['id_ufr']).'</td>
				<th>CNU</th><td colspan="3"><input id="statut" name="statut" value="'.$infos[0]['statut'].'" size="30"/></td>
			</tr>
			<tr>
				<td colspan="6"><h3>Contact</h3></td>
			</tr>
			<tr>
				<th>Page web personnelle</th><td><input id="www" name="www" value="'.$infos[0]['www'].'" size="25"/></td>
				<th>Email professionnel</th><td><input id="email_pro" name="email_pro" value="'.$infos[0]['email_pro'].'" size="20"/></td>
				<th>Email personnel</th><td><input id="email_perso" name="email_perso" value="'.$infos[0]['email_perso'].'" size="20"/></td>
			</tr>
			<tr>
				<th>Téléphone professionnel</th><td><input id="telephone_professionnel" name="telephone_professionnel" value="'.$infos[0]['telephone_professionnel'].'" size="12"/></td>
				<th>Téléphone mobile</th><td><input id="telephone_mobile" name="telephone_mobile" value="'.$infos[0]['telephone_mobile'].'" size="12"/></td>
				<th>Téléphone personnel</th><td><input id="telephone_personnel" name="telephone_personnel" value="'.$infos[0]['telephone_personnel'].'" size="12"/></td>
			</tr>
			<tr>
				<td colspan="6"><h3>Adresses</h3></td>
			</tr>
			<tr>
				<th rowspan="3">Professionnelle</th><td colspan="3" rowspan="3"><textarea id="adresse_pro" name="adresse_pro" rows="3" cols="45">'.$infos[0]['adresse_pro'].'</textarea></td>
				<th>Code postal</th><td><input id="code_postal_pro" name="code_postal_pro" value="'.$infos[0]['code_postal_pro'].'" size="10"/></td>
			</tr>
			<tr>
				<th>Ville</th><td>'.generation_select('id_ville_pro','a_ville',array('id_ville','libelle'),$infos[0]['id_ville_pro']).'</td>
			</tr>
			<tr>
				<th>Pays</th><td>'.generation_select('id_pays_pro','a_pays',array('id_pays','libelle'),$infos[0]['id_pays_pro']).'</td>
			</tr>
			<tr>
				<th rowspan="3">Personnelle</th><td colspan="3" rowspan="3"><textarea id="adresse_perso"  name="adresse_perso" rows="3" cols="45">'.$infos[0]['adresse_perso'].'</textarea></td>
				<th>Code postal</th><td><input id="code_postal_perso" name="code_postal_perso" value="'.$infos[0]['code_postal_perso'].'" size="10"/></td>
			</tr>
			<tr>
				<th>Ville</th><td>'.generation_select('id_ville_perso','a_ville',array('id_ville','libelle'),$infos[0]['id_ville_perso']).'</td>
			</tr>
			<tr>
				<th>Pays</th><td>'.generation_select('id_pays_perso','a_pays',array('id_pays','libelle'),$infos[0]['id_pays_perso']).'</td>
			</tr>
		</table>
	</div></form>';

?>

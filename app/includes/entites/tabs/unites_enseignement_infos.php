<?php
/* Génére le formulaire pour la modification des infos d'une ue */

// Récupération des données
$table='Unites_Enseignement';
$champs=array('id_ue','date_in','date_modif','intitule','code','id_ufr','id_semestre','ouvert',
				'ects','prerequis','resume','organisation','competences','evaluation','id_photo');
$filtres=array('id_ue' => $id);
$infos=recuperation_donnees(generation_requete($table,$champs,$filtres,$ordre));


$html.='<table border=0>
			<tr>
				<th>id_ue </th><td>'.$infos[0]['id_ue'].'</td>
				<th>Date de creation</th><td>'.$infos[0]['date_in'].'</strong></td>
				<th>Date de modification </th><td>'.$infos[0]['date_modif'].'</strong></th>
				<td rowspan="4">'.get_photo($infos[0]['id_photo']).'</td>
			</tr>
				<th>Intitulé</th><td colspan="5"><textarea id="intitule" name="intitule" rows="1" cols="70">'.$infos[0]['intitule'].'</textarea></td>
			</tr>
			<tr>
				<th>Ouvert</th><td>'.generation_oui_non('ouvert',$infos[0]['ouvert']).'</td>
				<th>UFR de rattachement</th><td colspan="3">'.generation_select('id_ufr','a_ufr',array('id_ufr','libelle'),$infos[0]['id_ufr']).'</td>
			</tr>
			<tr>
				<th>Code</th><td><input id="code" name="code" value="'.$infos[0]['code'].'" size="8"/></td>
				<th>ECTS</th><td><input id="ects" name="ects" value="'.$infos[0]['ects'].'" size="4"/></td>
				<th>Semestre</th><td>'.generation_select('id_semestre','a_semestre',array('id_semestre','libelle'),$infos[0]['id_semestre']).'</td>
			</tr>
			<tr>
				<td colspan="7"><h3>Informations pédagogiques</h3></td>
			</tr>
			<tr>
				<th colspan="2">Pré-requis</th><td colspan="5"><textarea id="prerequis" name="prerequis" rows="1" cols="90">'.$infos[0]['prerequis'].'</textarea></td>
			</tr>
			<tr>
				<th colspan="2">Résumé</th><td colspan="5"><textarea id="resume" name="resume" rows="4" cols="90">'.$infos[0]['resume'].'</textarea></td>
			</tr>
			<tr>
				<th colspan="2">Compétences visées</th><td colspan="5"><textarea id="competences" name="competences" rows="2" cols="90">'.$infos[0]['competences'].'</textarea></td>
			</tr>
			<tr>
				<th colspan="2">Organisation</th><td colspan="5"><textarea id="organisation" name="organisation" rows="1" cols="90">'.$infos[0]['organisation'].'</textarea></td>
			</tr>
			<tr>
				<th colspan="2">Modalités d\'évaluations</th><td colspan="5"><textarea id="evaluation" name="evaluation" rows="1" cols="90">'.$infos[0]['evaluation'].'</textarea></td>
			</tr>
		</table>';
	

?>

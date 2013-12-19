<?php

$html.='<h2><img src="public/images/icons/responsabilite.png"/> Gestion des responsabilité dans l\'UFR</h2>
<div class="content_tab">
		<p><input type="submit" value="Mettre à jour" onclick="submitForm(\'update_responsabilite\');"/>
		 <a class="ajout_entree" onclick="popupForm(\'ajouter_responsabilite\')" href="#">Ajouter une responsabilité</a></p>
		<form action="index.php" method="post" id="update_responsabilite">
		<input type="hidden" name="page" value="admin"/>
		<input type="hidden" name="section" value="responsabilites"/>
		<input type="hidden" name="modification_soumise" value="responsabilites"/>';
		

$s_enseignants="SELECT id_enseignant, CONCAT(nom,' ',prenom) AS nom_prenom FROM Enseignants ORDER BY nom, prenom";
$r_enseignants=mysql_query($s_enseignants);
$enseignants=array();
while ($d_enseignants=mysql_fetch_array($r_enseignants)) {
	$enseignants[$d_enseignants['id_enseignant']]=$d_enseignants['nom_prenom'];
}
$s_resp="SELECT id_responsabilite, libelle, decharge_horaire, id_enseignant FROM Responsabilites
	ORDER BY id_responsabilite";
$r_resp=mysql_query($s_resp)
	or die($_resp.' : '.mysql_error());
$html.='<table>
			<tr>';
$count=0;
while ($d_resp=mysql_fetch_array($r_resp)) {
	$count++;
	$html.='<th>'.$d_resp['libelle'].'</h3></td>
		<td>Décharge : '.normal_input('decharge['.$d_resp['id_responsabilite'].']',$d_resp['decharge_horaire'],3).'
		<select name="resp['.$d_resp['id_responsabilite'].']">
			<option value="0">Non attribuée</option>';
	foreach($enseignants as $id_enseignant => $nom) {
		$sel=($d_resp['id_enseignant']==$id_enseignant)?'selected="selected"':''; 
		$html.='<option value="'.$id_enseignant.'" '.$sel.'>'.$nom.'</option>';
	}
	$html.='</select></td>';
	if ($count>1) {
		$html.='</tr><tr>';
		$count=0;
	}
}

$html.='</tr></table></form></div>';

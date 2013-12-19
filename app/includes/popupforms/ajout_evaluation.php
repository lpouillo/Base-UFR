<?php

// Récupération des années scolaires
$s_annees="SELECT id_annee_scolaire, annee_debut AS annee FROM a_annee_scolaire ORDER BY annee_debut DESC";
$r_annees=mysql_query($s_annees);
$annees=array();
while ($d_annees=mysql_fetch_array($r_annees)) {
	$annees[$d_annees['id_annee_scolaire']]=$d_annees['annee'];
}

// Récupération des types d'évaluations 
$s_type_eval="SELECT id_type_evaluation, libelle FROM a_type_evaluation ORDER BY libelle";
$r_type_eval=mysql_query($s_type_eval);
$type_eval=array();
while ($d_type_eval=mysql_fetch_array($r_type_eval)) {
	$type_eval[$d_type_eval['id_type_evaluation']]=$d_type_eval['libelle'];
}

$html.='<table cellpadding=5><tr><td>Libellé</td><td><input type="text" name="libelle"/></td>
		<td><select name="id_annee_scolaire">';
foreach($annees as $id_annee_scolaire => $libelle) {
	$html.='<option value="'.$id_annee_scolaire.'">'.$libelle.'-'.($libelle+1).'</option>';
}

$html.='</select></td>
		<td>Type</td><td><select name="id_type_evaluation">';
foreach($type_eval as $key => $type) {
	$html.='<option value="'.$key.'">'.$type.'</option>';
}
$html.='</select></td>
		</tr><tr>
		<td>Coefficient</td><td><input type="text" name="coefficient" size="2"/></td>
		<td>Note max</td><td><input type="text" name="note_maximale" size="2"/></td>
		<td>Bonus ?</td><td><input type="checkbox" name="bonus"/></td></tr>
		</table>';
	


?>
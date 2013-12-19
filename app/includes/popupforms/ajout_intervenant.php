<?php
$s_enseignants="SELECT id_enseignant, CONCAT(nom,' ',prenom) AS nom_prenom FROM Enseignants 
	WHERE id_enseignant NOT IN (
		SELECT id_enseignant FROM l_enseignant_ue 
			WHERE id_ue='".$_POST['id']."' AND id_annee_scolaire='".$id_annee_scolaire."')
	ORDER BY nom,prenom";
$r_enseignants=mysql_query($s_enseignants);
$html.='Enseignant <select name="id_new_intervenant">';
while ($d_enseignants=mysql_fetch_array($r_enseignants)) {
	$html.='<option value="'.$d_enseignants['id_enseignant'].'">'.$d_enseignants['nom_prenom'].'</option>';
}
$html.='</select><br/>
	Type : <input type="radio" name="id_situation" value="20"/> Responsable <input type="radio" name="id_situation" value="21" checked="checked"/> Intervenant';


<?php 
$html.='<p><a href="#" onclick="popupForm(\'ajout_encadrant_these\');">Ajouter un encadrant de thèse</a></p>';
$s_encadrants="SELECT E.id_enseignant, CONCAT(E.nom,' ',E.prenom) AS encadrant, T.libelle AS type_encadrant, T.abbreviation AS abbrev_encadrant
				FROM l_encadrant_doctorat ED
				INNER JOIN Enseignants E
					ON E.id_enseignant=ED.id_encadrant
				INNER JOIN a_type_encadrant T
					ON T.id_type_encadrant=ED.id_type_encadrant
				WHERE ED.id_doctorat=".$_POST['id'];
$r_encadrants=mysql_query($s_encadrants);
while ($d_encadrants=mysql_fetch_array($r_encadrants)) {
	$html.='<h3>'.$d_encadrants['type_encadrant'].'</h3>'.
		generation_select($d_encadrants['abbrev_encadrant'],'Enseignants',array('id_enseignant','CONCAT(nom,\' \',prenom)'),$d_encadrants['id_enseignant']);
}
?>

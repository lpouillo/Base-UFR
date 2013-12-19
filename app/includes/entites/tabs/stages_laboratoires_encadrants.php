<?php
// récupération des types d'encadrants
$s_type_encadrant="SELECT id_type_encadrant, libelle, abbreviation FROM a_type_encadrant";
$r_type_encadrant=mysql_query($s_type_encadrant);
$type_encadrants=array();
while ($d_type_encadrant=mysql_fetch_array($r_type_encadrant)) {
	$type_encadrants[$d_type_encadrant['abbreviation']]=array('id_type_encadrant' => $d_type_encadrant['id_type_encadrant'], 'libelle' => $d_type_encadrant['libelle']);
}
// récupération de la liste des enseignants
$s_pro="SELECT E.id_enseignant, CONCAT(E.nom,' ',E.prenom) AS nom_prenom
		FROM Enseignants E
		ORDER BY E.nom";
$r_pro=mysql_query($s_pro);
$enseignants=array();
while ($d_pro=mysql_fetch_array($r_pro)) {
	$enseignants[$d_pro['id_enseignant']]=$d_pro['nom_prenom'];
}

// récupération des données
$s_encadrant_stage="SELECT id_encadrant, id_type_encadrant FROM l_encadrant_stage WHERE id_stage=".$id;
$r_encadrant_stage=mysql_query($s_encadrant_stage);
while ($d_encadrant_stage=mysql_fetch_array($r_encadrant_stage)) {
	$encadrants[$d_encadrant_stage['id_type_encadrant']]=$d_encadrant_stage['id_encadrant'];
}

$encadrants_stage_laboratoire=array('dir_stage_labo','codir_stage_labo_1','codir_stage_labo_2');

if ($encadrants[$type_encadrants['dir_stage_labo']['id_type_encadrant']]==$_SESSION['id_link'] or $_SESSION['group']=='admin' or $_SESSION['group']=='gestionnaire') {
	$mode='rw';
} else {
	$mode='r';
}
foreach ($encadrants_stage_laboratoire as $encadrant) {
	$html .='<h3>'.$type_encadrants[$encadrant]['libelle'].'</h3>';
	
	if ($mode=='rw') {
		$html .='<p><select name="'.$encadrant.'" id="ajout_'.$encadrant.'">
		<option value="0">Non renseigné</option>';
		
		foreach ($enseignants as $id_encadrant => $element) {	
			$sel=($encadrants[$type_encadrants[$encadrant]['id_type_encadrant']]==$id_encadrant)?'selected="selected"':'';
			$html.='<option value="'.$id_encadrant.'" '.$sel.' >'.$element.'</option>';
		}
		$html.='</select> <a onclick="popupForm(\'ajout_'.$encadrant.'\')" href="#">Ajouter un '.$type_encadrants[$encadrant]['libelle'].'</a></p>';
	} else {
		$html.= '<p>'.$enseignants[$encadrants[$type_encadrants[$encadrant]['id_type_encadrant']]].'</p>';
	}
}

?>


<?php
$id_stage=$_POST['id'];
// récupération des types d'encadrants
$s_type_encadrant="SELECT id_type_encadrant, libelle, abbreviation FROM a_type_encadrant";
$r_type_encadrant=mysql_query($s_type_encadrant);
$type_encadrants=array();
while ($d_type_encadrant=mysql_fetch_array($r_type_encadrant)) {
	$type_encadrants[$d_type_encadrant['abbreviation']]=array('id_type_encadrant' => $d_type_encadrant['id_type_encadrant'], 'libelle' => $d_type_encadrant['libelle']);
}
// récupération de la liste des professionnels
$s_pro="SELECT P.id_professionnel, CONCAT(P.nom,' ',P.prenom) AS nom_prenom
		FROM Professionnels P
	
		ORDER BY P.nom";
$r_pro=mysql_query($s_pro);
$professionnels=array();
while ($d_pro=mysql_fetch_array($r_pro)) {
	$professionnels[$d_pro['id_professionnel']]=$d_pro['nom_prenom'];
}
// récupération de la liste des tuteurs
$s_tuteur="SELECT E.id_enseignant, CONCAT(E.nom,' ',E.prenom) AS nom_prenom
		FROM Enseignants E
	
		ORDER BY E.nom";
//echo $s_tuteur;
$r_tuteur=mysql_query($s_tuteur);
$tuteurs=array();
while ($d_tuteur=mysql_fetch_array($r_tuteur)) {
	$tuteurs[$d_tuteur['id_enseignant']]=$d_tuteur['nom_prenom'];
}

// récupération des données
$s_encadrant_stage="SELECT id_encadrant, id_type_encadrant FROM l_encadrant_stage WHERE id_stage=".$id_stage;
$r_encadrant_stage=mysql_query($s_encadrant_stage);
while ($d_encadrant_stage=mysql_fetch_array($r_encadrant_stage)) {
	$encadrants[$d_encadrant_stage['id_type_encadrant']]=$d_encadrant_stage['id_encadrant'];
}

$encadrants_stage_entreprise=array('contact','maitre_stage_entreprise_1','maitre_stage_entreprise_2','tuteur_stage_entreprise_1','tuteur_stage_entreprise_2');



foreach ($encadrants_stage_entreprise as $encadrant) {
	$html .='<h3>'.$type_encadrants[$encadrant]['libelle'].'</h3>';
	
	$array_liste=array();
	switch($encadrant) {
		case 'contact':
		case 'maitre_stage_entreprise_1':
		case 'maitre_stage_entreprise_2':
			$array_liste=$professionnels;
		break;
		case 'tuteur_stage_entreprise_1':
		case 'tuteur_stage_entreprise_2':
			$array_liste=$tuteurs;
		break;
	}

	
	if ($mode=='rw') {
		
		$html .='<select name="'.$encadrant.'" id="ajout_'.$encadrant.'">
			<option value="0">Non renseigné</option>';
		
		foreach ($array_liste as $id_encadrant => $element) {
				
			$sel=($encadrants[$type_encadrants[$encadrant]['id_type_encadrant']]==$id_encadrant)?'selected="selected"':'';
			$html.='<option value="'.$id_encadrant.'" '.$sel.' >'.$element.'</option>';
		}
		$html.='</select> <a onclick="popupForm(\'ajout_'.$encadrant.'\')" href="#">Ajouter un encadrant</a>';
	} else {
		$html.= '<p>'.$array_liste[$encadrants[$type_encadrants[$encadrant]['id_type_encadrant']]].'</p>';
	}
}

?>


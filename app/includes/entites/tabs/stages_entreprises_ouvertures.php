<?php


// Récupération des données
$id_stage_entreprise=$_POST['id'];

// Récupération des niveaux
$s_niveaux="SELECT id_niveau, abbreviation, libelle FROM a_niveau WHERE abbreviation IN ('L1','L2','L3','M1','M2','D') ORDER BY id_niveau";
$r_niveaux=mysql_query($s_niveaux);
$niveaux=array();
while ($d_niveaux=mysql_fetch_array($r_niveaux)) {
	$niveaux[$d_niveaux['abbreviation']]=array($d_niveaux['id_niveau'],$d_niveaux['libelle']);
}

// Récupération des spécialités
$s_specialites="SELECT id_specialite, abbreviation, libelle FROM a_specialite WHERE gestion=1";
$r_specialites=mysql_query($s_specialites);
$specialites=array();
while ($d_specialites=mysql_fetch_array($r_specialites)) {
	$specialites[$d_specialites['abbreviation']]=array($d_specialites['id_specialite'],$d_specialites['libelle']);
}

// Récupération des données des ouvertures de l'ue
$s_ouvertures="SELECT id_niveau, id_specialite, ouvert,id_annee_scolaire FROM l_ouverture_stage
				WHERE id_stage=".$id_stage_entreprise." AND id_type_stage=1";

$r_ouvertures=mysql_query($s_ouvertures);
$ouvertures=array();
while ($d_ouvertures=mysql_fetch_array($r_ouvertures)) {
	$ouvertures[$d_ouvertures['id_annee_scolaire']][$d_ouvertures['id_niveau']][$d_ouvertures['id_specialite']]=$d_ouvertures['ouvert'];
}

$creation_tableau=array(
				'Licence' => array('niveaux' => array('L2','L3'),
									'specialites' => array('gd')),
				'Master' => array('niveaux' => array('M1','M2'),
									'specialites' => array('ge','gs','gl','ssng')),
				);
				
if ($mode=='rw') {
	$html .= '<p>Veuillez sélectionner les options d\'ouverture pour '.$annee.'-'.($annee+1).' :</p>';
} else {
	$html .= '<p>Voici à quelles niveaux/spécialités ces stages sont ouverts en '.$annee.'-'.($annee+1).':</p>';
}

// Création du tableau pour la Licence
$html .='<h3><img src="public/images/icons/bullet_blue.png"/> Licence</h3>';
$html .= '<p><table border="0" class="ouverture_ue"><tr>' .
		'<td>&nbsp;</td>';
foreach ($creation_tableau['Licence']['niveaux'] as $niveau) {
	$html .= '<th title="'.$niveaux[$niveau][1].'">'.$niveau.'</th>';
}
$html.='</tr>';
foreach ($creation_tableau['Licence']['specialites'] as $specialite) {
	// création d'une ligne par spécialité
	$html.='<tr><th>'.$specialite.'</th>';
	foreach ($creation_tableau['Licence']['niveaux'] as $niveau) {
		if ($ouvertures[$id_annee_scolaire][$niveaux[$niveau][0]][$specialites[$specialite][0]]!='') {
			$ouvert=$ouvertures[$id_annee_scolaire][$niveaux[$niveau][0]][$specialites[$specialite][0]];
		} else {
			$ouvert=0;
			$s_update_ouverture="INSERT INTO l_ouverture_stage (`id_stage`,`id_niveau`,`id_specialite`,`id_annee_scolaire`,`id_type_stage`,`ouvert`) 
				VALUES (".$id_stage_entreprise.",".$niveaux[$niveau][0].",".$specialites[$specialite][0].",".$id_annee_scolaire.",1,0)";
			//echo $s_update_ouverture;
			mysql_query($s_update_ouverture) or die(mysql_error());
		}
		if ($mode=='rw') {
			$html.='<td>';
			$sel_0=($ouvert==0)?'selected="selected"':'';
			$sel_1=($ouvert==1)?'selected="selected"':'';
			$html.= '<select name="ouvertures['.$niveaux[$niveau][0].']['.$specialites[$specialite][0].']">
				<option value="0" '.$sel_0.'>Fermé</option>
				<option value="1" '.$sel_1.'>Ouvert</option>
				</select>';
			
			$html.='</td>';	
		} else {
			$aff_sel=($ouvert==1)?'Ouvert':'Fermé';
			$html.='<td>'.$aff_sel.'</td>';	
		}
	}
	$html.='</tr>';
}

$html.='</table></p>';


$html .='<h3><img src="public/images/icons/bullet_red.png"/> Master</h3>';
$html .= '<p margin-left:10px;"><table border="0" class="ouverture_ue"><tr>' .
		'<td>&nbsp;</td>';
foreach ($creation_tableau['Master']['niveaux'] as $niveau) {
	$html .= '<th title="'.$niveaux[$niveau][1].'">'.$niveau.'</th>';
}
$html.='</tr>';

foreach ($creation_tableau['Master']['specialites'] as $specialite) {
	// création d'une ligne par spécialité
	$html.='<tr><th>'.$specialite.'</th>';
	foreach ($creation_tableau['Master']['niveaux'] as $niveau) {
		if ($ouvertures[$id_annee_scolaire][$niveaux[$niveau][0]][$specialites[$specialite][0]]!='') {
			$ouvert=$ouvertures[$id_annee_scolaire][$niveaux[$niveau][0]][$specialites[$specialite][0]];
		} else {
			$ouvert=0;
			$s_update_ouverture="INSERT INTO l_ouverture_stage (`id_stage`,`id_niveau`,`id_specialite`,`id_annee_scolaire`,`id_type_stage`,`ouvert`) 
				VALUES (".$id_stage_entreprise.",".$niveaux[$niveau][0].",".$specialites[$specialite][0].",".$id_annee_scolaire.",1,0)";
			//echo $s_update_ouverture;
			mysql_query($s_update_ouverture) or die(mysql_error());
		}
		if ($mode=='rw') {
			$html.='<td>';
			$sel_0=($ouvert==0)?'selected="selected"':'';
			$sel_1=($ouvert==1)?'selected="selected"':'';
			$html.= '<select name="ouvertures['.$niveaux[$niveau][0].']['.$specialites[$specialite][0].']">
				<option value="0" '.$sel_0.'>Fermé</option>
				<option value="1" '.$sel_1.'>Ouvert</option>
				</select>';
	
			$html.='</td>';	
		} else {
			$aff_sel=($ouvert==1)?'Ouvert':'Fermé';
			$html.='<td>'.$aff_sel.'</td>';	
		}
	}
	$html.='</tr>';
}

$html.='</table></p>';


?>

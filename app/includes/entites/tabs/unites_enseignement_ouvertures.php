<?php

$id_ue=$_POST['id'];
// Récupération des niveaux
$s_niveaux="SELECT id_niveau, abbreviation, libelle FROM a_niveau WHERE abbreviation IN ('L1','L2','L3','M1','M2','D') ORDER BY id_niveau";
$r_niveaux=mysql_query($s_niveaux)
	or die(mysql_error);
$niveaux=array();
while ($d_niveaux=mysql_fetch_array($r_niveaux)) {
	$niveaux[$d_niveaux['abbreviation']]=array($d_niveaux['id_niveau'],$d_niveaux['libelle']);
}

// Récupération des spécialités
$s_specialites="SELECT id_specialite, abbreviation, libelle FROM a_specialite WHERE gestion=1";
$r_specialites=mysql_query($s_specialites)
	or die(mysql_error);
$specialites=array();
while ($d_specialites=mysql_fetch_array($r_specialites)) {
	$specialites[$d_specialites['abbreviation']]=array($d_specialites['id_specialite'],$d_specialites['libelle']);
}

// Récupération des type_ue de master
$s_types_ue="SELECT id_type_ue, abbreviation FROM a_type_ue WHERE classe='standard'";
$r_types_ue=mysql_query($s_types_ue)
	or die(mysql_error);
$types_ue=array();
while ($d_types_ue=mysql_fetch_array($r_types_ue)) {
	$types_ue[$d_types_ue['id_type_ue']]=$d_types_ue['abbreviation'];
}

// Récupération des type_ue de doctorat
$s_types_ue_doctorat="SELECT id_type_ue, abbreviation FROM a_type_ue WHERE classe='doctorant' OR abbreviation='F'";
$r_types_ue_doctorat=mysql_query($s_types_ue_doctorat)
	or die(mysql_error);
$types_ue_doctorat=array();
while ($d_types_ue_doctorat=mysql_fetch_array($r_types_ue_doctorat)) {
	$types_ue_doctorat[$d_types_ue_doctorat['id_type_ue']]=$d_types_ue_doctorat['abbreviation'];
}


// Récupération des données des ouvertures de l'ue
$s_ouvertures="SELECT id_niveau, id_specialite, id_type_ue FROM l_ouverture_ue 
				WHERE id_ue=".$id_ue." AND id_annee_scolaire=".$id_annee_scolaire;

$r_ouvertures=mysql_query($s_ouvertures)
	or die(mysql_error);
$ouvertures=array();
while ($d_ouvertures=mysql_fetch_array($r_ouvertures)) {
	$ouvertures[$d_ouvertures['id_niveau']][$d_ouvertures['id_specialite']]=$d_ouvertures['id_type_ue'];
}

$creation_tableau=array(
				'Licence' => array('niveaux' => array('L1','L2','L3'),
									'specialites' => array('gd','gf','step')),
				'Master' => array('niveaux' => array('M1','M2'),
									'specialites' => array('ge','gs','gl','gm','gc','gp','mpt','ssng')),
				'Doctorat' => array('niveaux' => array('D'),
									'specialites' => array('step'))
				);

if ($mode=='rw') {
	$html .= '<p>Veuillez sélectionner les options d\'ouverture :</p>';
} else {
	$html .= '<p>Voici les options d\'ouverture de l\'ue :</p>';
}

// Création du tableau des ouvertures
$html .= '<table id="ouvertures">
			<tr>
				<th><h3><img src="public/images/icons/bullet_blue.png"/> Licence</h3></th>
				<th><h3><img src="public/images/icons/bullet_red.png"/> Master</h3>
			</tr>
			<tr>
				<td>
				<table border="0" class="ouverture_ue">
					<tr>
						<td>&nbsp;</td>';
foreach ($creation_tableau['Licence']['niveaux'] as $niveau) {
	$html .= '<th title="'.$niveaux[$niveau][1].'">'.$niveau.'</th>';
}
$html.='</tr>';

foreach ($creation_tableau['Licence']['specialites'] as $specialite) {
	// création d'une ligne par spécialité
	$html.='<tr><th>'.$specialite.'</th>';
	foreach ($creation_tableau['Licence']['niveaux'] as $niveau) {
		if ($types_ue[$ouvertures[$niveaux[$niveau][0]][$specialites[$specialite][0]]]!='') {
			$type_ue=$types_ue[$ouvertures[$niveaux[$niveau][0]][$specialites[$specialite][0]]];
		} else {
			$type_ue='F';
			$ouvertures[$niveaux[$niveau][0]][$specialites[$specialite][0]]=8;
			$s_update_ouverture="INSERT INTO l_ouverture_ue (`id_ue`,`id_niveau`,`id_specialite`,`id_annee_scolaire`,`id_type_ue`) 
							VALUES (".$id_ue.",".$niveaux[$niveau][0].",".$specialites[$specialite][0].",".$id_annee_scolaire.",8)";
			mysql_query($s_update_ouverture) or die(mysql_error());
			
		}
		if ($mode=='rw') {
			$html.='<td>';
			$html.= '<select name="ouvertures['.$id_ue.']['.$niveaux[$niveau][0].']['.$specialites[$specialite][0].']">';
			foreach ($types_ue as $key_type => $type) {
				$sel=($ouvertures[$niveaux[$niveau][0]][$specialites[$specialite][0]]==$key_type)?'selected="selected"':'';
				$html .= '<option value="'.$key_type.'" '.$sel.'>'.$type.'</option>';
			}
			$html.='</td>';	
		} else {
			$html.='<td>'.$type_ue.'</td>';	
		}
	}
	$html.='</tr>';
}

$html.='</table></td>
<td rowspan="3"><table border="0" class="ouverture_ue">
	<tr>
		<td>&nbsp;</td>';
foreach ($creation_tableau['Master']['niveaux'] as $niveau) {
	$html .= '<th title="'.$niveaux[$niveau][1].'">'.$niveau.'</th>';
}
$html.='</tr>';

foreach ($creation_tableau['Master']['specialites'] as $specialite) {
	// création d'une ligne par spécialité
	$html.='<tr><th>'.$specialite.'</th>';
	foreach ($creation_tableau['Master']['niveaux'] as $niveau) {
		if ($types_ue[$ouvertures[$niveaux[$niveau][0]][$specialites[$specialite][0]]]!='') {
			$type_ue=$types_ue[$ouvertures[$niveaux[$niveau][0]][$specialites[$specialite][0]]];
		} else {
			$type_ue='F';
			$ouvertures[$niveaux[$niveau][0]][$specialites[$specialite][0]]=8;
			$s_update_ouverture="INSERT INTO l_ouverture_ue (`id_ue`,`id_niveau`,`id_specialite`,`id_annee_scolaire`,`id_type_ue`) 
							VALUES (".$id_ue.",".$niveaux[$niveau][0].",".$specialites[$specialite][0].",".$id_annee_scolaire.",8)";
			mysql_query($s_update_ouverture) or die(mysql_error());
			
		}
		if ($mode=='rw') {
			$html.='<td>';
			$html.= '<select name="ouvertures['.$id_ue.']['.$niveaux[$niveau][0].']['.$specialites[$specialite][0].']">';
			foreach ($types_ue as $key_type => $type) {
				$sel=($ouvertures[$niveaux[$niveau][0]][$specialites[$specialite][0]]==$key_type)?'selected="selected"':'';
				$html .= '<option value="'.$key_type.'" '.$sel.'>'.$type.'</option>';
			}
			$html.='</td>';	
		} else {
			$html.='<td>'.$type_ue.'</td>';	
		}
	}
	$html.='</tr>';
}

$html.='</table></td></tr>
		<tr><th><h3><img src="public/images/icons/bullet_black.png"/> Doctorat</h3></th>
		</tr>
		<tr>
			<td><table border="0" class="ouverture_ue">
				<tr>
		<td>&nbsp;</td>';
foreach ($creation_tableau['Doctorat']['niveaux'] as $niveau) {
	$html .= '<th title="'.$niveaux[$niveau][1].'">'.$niveau.'</th>';
}
$html.='</tr>';
foreach ($creation_tableau['Doctorat']['specialites'] as $specialite) {
	// création d'une ligne par spécialité
	$html.='<tr><th>'.$specialite.'</th>';
	foreach ($creation_tableau['Doctorat']['niveaux'] as $niveau) {
		if ($types_ue_doctorat[$ouvertures[$niveaux[$niveau][0]][$specialites[$specialite][0]]]!='') {
			$type_ue=$types_ue[$ouvertures[$niveaux[$niveau][0]][$specialites[$specialite][0]]];
		} else {
			$type_ue='F';
			$s_update_ouverture="INSERT INTO l_ouverture_ue (`id_ue`,`id_niveau`,`id_specialite`,`id_annee_scolaire`,`id_type_ue`) 
							VALUES (".$id_ue.",".$niveaux[$niveau][0].",".$specialites[$specialite][0].",".$id_annee_scolaire.",8)";
			mysql_query($s_update_ouverture);
		}
		if ($mode=='rw') {
			$html.='<td>';
			$html.= '<select name="ouvertures['.$id_ue.']['.$niveaux[$niveau][0].']['.$specialites[$specialite][0].']">';
			foreach ($types_ue_doctorat as $key_type => $type) {
				$sel=($ouvertures[$niveaux[$niveau][0]][$specialites[$specialite][0]]==$key_type)?'selected="selected"':'';
				$html .= '<option value="'.$key_type.'" '.$sel.'>'.$type.'</option>';
			}
			$html.='</td>';	
		} else {
			$html.='<td>'.$type_ue.'</td>';	
		}
	}
	$html.='</tr>';
}

$html.='</table></td></tr></table>';
?>

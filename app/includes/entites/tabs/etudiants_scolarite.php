<?php
$id_etudiant=$_POST['id'];

// Récupération des années scolaires
$s_annee_scolaire="SELECT id_annee_scolaire, annee_debut FROM a_annee_scolaire ORDER BY annee_debut DESC" ;
$r_annee_scolaire=mysql_query($s_annee_scolaire) 
	or die('Impossible de récupérer les années scolaires :<br/>'.mysql_error());
$annee_scolaire=array();
while ($d_annee_scolaire=mysql_fetch_array($r_annee_scolaire)) {
	$annee_scolaire[$d_annee_scolaire['id_annee_scolaire']]=$d_annee_scolaire['annee_debut'];
}


// Récupération des niveaux
$s_niveaux="SELECT id_niveau, libelle, gestion FROM a_niveau ORDER BY libelle";
$r_niveaux=mysql_query($s_niveaux)
	or die('Impossible de récupérer les niveaux :<br/>'.mysql_error());
$niveaux=array();
while ($d_niveaux=mysql_fetch_array($r_niveaux)) {
	$niveaux[$d_niveaux['gestion']][$d_niveaux['id_niveau']]=$d_niveaux['libelle'];
}


// Récupération des spécialités
$s_specialites="SELECT id_specialite, libelle, gestion FROM a_specialite ORDER BY libelle";
$r_specialites=mysql_query($s_specialites) 
	or die('Impossible de récupérer les spécialités :<br/>'.mysql_error());
$specialites=array();
while ($d_specialites=mysql_fetch_array($r_specialites)) {
	$specialites[$d_specialites['gestion']][$d_specialites['id_specialite']]=$d_specialites['libelle'];
}
// Récupération des établissements
$s_etablissements="SELECT id_etablissement, nom, gestion FROM Etablissements ORDER BY nom";
$r_etablissements=mysql_query($s_etablissements);
$etablissements=array();
while ($d_etablissements=mysql_fetch_array($r_etablissements)) {
	$etablissements[$d_etablissements['gestion']][$d_etablissements['id_etablissement']]=$d_etablissements['nom'];
}
// Récupération des mentions
$s_mentions="SELECT id_mention, libelle FROM a_mention ORDER BY libelle";
$r_mentions=mysql_query($s_mentions) 
	or die ('Impossible de récupérer les mentions :<br/>'.mysql_error());
$mentions=array();
while ($d_mentions=mysql_fetch_array($r_mentions)) {
	$mentions[$d_mentions['id_mention']]=$d_mentions['libelle'];
}
// Récupération du parcours de l'étudiant
$s_parcours="SELECT P.id_etudiant, P.id_annee_scolaire, P.id_niveau, P.id_specialite, P.id_etablissement, P.note_moyenne, P.classement, P.id_mention, P.avis_jury 
			FROM l_parcours_etudiant P
			INNER JOIN a_annee_scolaire A
				ON A.id_annee_scolaire=P.id_annee_scolaire 
			WHERE id_etudiant=".$id_etudiant." 
			ORDER BY A.annee_debut DESC";
$r_parcours=mysql_query($s_parcours);
$parcours=array();
while ($d_parcours=mysql_fetch_array($r_parcours)) {
	$parcours[$d_parcours['id_annee_scolaire']]=array('id_niveau' => $d_parcours['id_niveau'], 'id_specialite' => $d_parcours['id_specialite'],
		'id_etablissement' => $d_parcours['id_etablissement'], 'moyenne' => $d_parcours['note_moyenne'],
		'classement' => $d_parcours['classement'],'id_mention' => $d_parcours['id_mention'], 'avis_jury' => $d_parcours['avis_jury']);
}

$html.= '<p>Voici le parcours de l\'étudiant.';
if ($mode=='rw') {
	$html.=' <a href="#" onClick="popupForm(\'ajout_parcours\',\''.$id_etudiant.'\')">Ajouter un nouvel élément</a>';
}
$html.='</p><table class="parcours">
			<thead>
			<tr>
				<th>Année</th><th>Niveau</th><th>Spécialité</th><th>Etablissement</th><th>Moyenne</th><th>Classement</th><th>Mention</th>
			</tr>
			</thead>';


foreach($parcours as $id_year => $parcours_annee) {
	if ($parcours_annee['id_niveau']!=0 or $parcours_annee['id_specialite']!=0 or $parcours_annee['id_etablissement']!=0) {
		$html.='<tr><th rowspan="2" height="40px" style="vertical-align:top;">'.$annee_scolaire[$id_year].' - '.($annee_scolaire[$id_year]+1).'</th>';
		if ($mode=='r') {
			// Simple affichage des informations
			$niv=(empty($niveaux[0][$parcours_annee['id_niveau']]))?$niveaux[1][$parcours_annee['id_niveau']]:$niveaux[0][$parcours_annee['id_niveau']];				
			$spec=empty($specialites[0][$parcours_annee['id_specialite']])?$specialites[1][$parcours_annee['id_specialite']]:$specialites[0][$parcours_annee['id_specialite']];
			$etab=(empty($etablissements[0][$parcours_annee['id_etablissement']]))?$etablissements[1][$parcours_annee['id_etablissement']]:$etablissements[0][$parcours_annee['id_etablissement']];
			$html.='<td>'.$niv.'</td><td>'.$spec.'</td>';
			$html.='<td>'.$etab.'</td><td>'.$parcours_annee['moyenne'].'</td>';
			$html.='<td>'.$parcours_annee['classement'].'</td><td>'.$mentions[$parcours_annee['id_mention']].'</td>';
			$html.='</tr><tr><td colspan="6" class="avis_jury"><em>Avis du jury</em> : '.$parcours_annee['avis_jury'].'</tr>';
		} elseif ($mode=='rw') {
			// NIVEAU
			$html .='<td><select name="scolarite['.$id_year.'][id_niveau]">';
			$html .='<option value="0">Aucun</option>';
			$html .='<optgroup label="STEP-IPGP">';
			foreach($niveaux[1] as $k_niv => $niveau) {
				$sel_niveau=($parcours_annee['id_niveau']==$k_niv)?'selected="selected"':'';
				$html .='<option value="'.$k_niv.'" '.$sel_niveau.'>'.substr($niveau,0,20).'</option>';
			}
			$html .='</optgroup><optgroup label="Extérieur">';
			foreach($niveaux[0] as $k_niv => $niveau) {
				$sel_niveau=($parcours_annee['id_niveau']==$k_niv)?'selected="selected"':'';
				$html .='<option value="'.$k_niv.'" '.$sel_niveau.'>'.substr($niveau,0,20).'</option>';
			}
			
			$html.='</optgroup></select></td>
					<td><select name="scolarite['.$id_year.'][id_specialite]">';
			// SPECIALITE
			$html .='<option value="0">Aucun</option>';
			$html .='<optgroup label="STEP-IPGP">';
			foreach($specialites[1] as $k_spec => $specialite) {
				$sel_specialite=($parcours_annee['id_specialite']==$k_spec)?'selected="selected"':'';
				$html .='<option value="'.$k_spec.'" '.$sel_specialite.'>'.substr($specialite,0,40).'</option>';
			}
			$html .='</optgroup><optgroup label="Extérieur">';
			foreach($specialites[0] as $k_spec => $specialite) {
				$sel_specialite=($parcours_annee['id_specialite']==$k_spec)?'selected="selected"':'';
				$html .='<option value="'.$k_spec.'" '.$sel_specialite.'>'.substr($specialite,0,40).'</option>';
			}
			$html.='</optgroup></select></td>
					<td><select name="scolarite['.$id_year.'][id_etablissement]">';
			// ETABLISSEMENT
			$html .='<option value="0">Aucun</option>';
			$html .='<optgroup label="STEP-IPGP">';
			foreach($etablissements [1] as $k_etab => $etablissement) {
				$sel_etablissement=($parcours_annee['id_etablissement']==$k_etab)?'selected="selected"':'';
				$html .='<option value="'.$k_etab.'" '.$sel_etablissement.'>'.substr($etablissement,0,40).'</option>';
			}
			$html .='</optgroup><optgroup label="Extérieur">';
			foreach($etablissements[0] as $k_etab => $etablissement) {
				$sel_etablissement=($parcours_annee['id_etablissement']==$k_etab)?'selected="selected"':'';
				$html .='<option value="'.$k_etab.'" '.$sel_etablissement.'>'.substr($etablissement,0,40).'</option>';
			}
			// MOYENNE
			$html.='</optgroup></select></td><td align="center"><input type="text" name="scolarite['.$id_year.'][moyenne]" size="2" value="'.$parcours_annee['moyenne'].'"/></td>';
			// CLASSEMENT
			$html.='<td align="center"><input type="text" name="scolarite['.$id_year.'][classement]" size="2" value="'.$parcours_annee['classement'].'"/></td>';
			// MENTION
			$html.='<td><select name="scolarite['.$id_year.'][id_mention]">';
			$html.='<option value="0">Aucun</option>';
			foreach($mentions as $k_ment => $mention) {
				$sel_mention=($parcours_annee['id_mention']==$k_ment)?'selected="selected"':'';
				$html .='<option value="'.$k_ment.'" '.$sel_mention.'>'.$mention.'</option>';
			}
			// AVIS DU JURY
			$html.='</td></tr><tr><td colspan="6" class="avis_jury"> <em>Avis du jury</em> 
				<textarea rows="2" cols="100" name="scolarite['.$id_year.'][avis_jury]">'.$parcours_annee['avis_jury'].'</textarea>';
		}
		$html.='</tr>';
	}
}


$html.='</table>';

?>

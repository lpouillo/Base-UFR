<?php
$id_ue=$_POST['id'];
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

// Récupération des étudiants par année
$s_etudiants="SELECT id_etudiant, id_annee_scolaire 
			FROM l_etudiant_ue
			WHERE id_ue=".$id_ue;
$r_etudiants=mysql_query($s_etudiants);
$etudiants=array();
while ($d_etudiants=mysql_fetch_array($r_etudiants)) {
	$etudiants[$d_etudiants['id_annee_scolaire']][]=$d_etudiants['id_etudiant'];
}

// Récupération des évaluations et des notes
$s_evaluations_notes="SELECT EV.id_evaluation, EV.libelle, EV.coefficient, EV.id_type_evaluation, 
					EV.note_maximale, EV.bonus, EV.id_annee_scolaire, CONCAT(E.nom,' ',E.prenom) AS etudiant, E.id_etudiant, NO.valeur  
					FROM Evaluations EV
					INNER JOIN l_etudiant_ue CM
						ON EV.id_ue=CM.id_ue
						AND EV.id_annee_scolaire=CM.id_annee_scolaire
					LEFT JOIN Notes NO
						ON NO.id_evaluation=EV.id_evaluation
						AND NO.id_etudiant=CM.id_etudiant
					INNER JOIN Etudiants E 
						ON E.id_etudiant=CM.id_etudiant
					WHERE EV.id_ue=".$id_ue."
					ORDER BY E.nom";

$r_evaluations_notes=mysql_query($s_evaluations_notes)
	or die(mysql_error());
$evaluations=array();
$notes=array();
while ($d_evaluations_notes=mysql_fetch_array($r_evaluations_notes)) {
	$evaluations[$d_evaluations_notes['id_annee_scolaire']][$d_evaluations_notes['id_evaluation']]=array(
											'libelle' => $d_evaluations_notes['libelle'], 
											'coefficient' => $d_evaluations_notes['coefficient'],
											'id_type_evaluation' => $d_evaluations_notes['id_type_evaluation'],
											'note_maximale' => $d_evaluations_notes['note_maximale'],
											'coefficient' => $d_evaluations_notes['coefficient'],
											'bonus' => $d_evaluations_notes['bonus']);
	$notes[$d_evaluations_notes['id_annee_scolaire']][$d_evaluations_notes['id_etudiant']]['etudiant']=$d_evaluations_notes['etudiant'];
	$notes[$d_evaluations_notes['id_annee_scolaire']][$d_evaluations_notes['id_etudiant']]['evaluations'][$d_evaluations_notes['id_evaluation']]=$d_evaluations_notes['valeur'];											
}
// Création du contenu HTML
if ($mode=='rw') {
	$html.='<p><a href="#" onclick="popupForm(\'ajout_evaluation\',\''.$_POST['id'].'\')">Ajouter une nouvelle évaluation</a></p>';
}
foreach ($annees as $id_annee_scolaire => $annee) {
	$html.='<h3><img src="public/images/icons/notes.png"/> '.$annee.'-'.($annee+1).'</h3>';
	if (sizeof($evaluations[$id_annee_scolaire])==0) {
		$html.='<p>Aucune évaluation n\'a été soumise pour cette année.</p>';
	} else {
		$html.='<table class="table_sel">
		<tr>
		<th rowspan="6">Etudiants</th>';
		$ligne_libelle='<th width="50px">Libelle</th>';
		$ligne_type.='<tr><th>Type</th>';
		$ligne_coeff.='<tr><th>Coefficient</th>';
		$ligne_note_max.='<tr><th>Note max.</th>';
		$ligne_bonus.='<tr><th>Bonus ?</th>';
		$ligne_supprimer.='<tr><th>Supprimer</th>';
		foreach($evaluations[$id_annee_scolaire] as $id_evaluation => $eval) {
			$ligne_libelle.='<td style="font-weigth:bold;"><input type="text" name="evaluations['.$id_evaluation.'][libelle]" 
							value="'.$eval['libelle'].'"/></td>';
			$ligne_type.='<td><select name="evaluations['.$id_evaluation.']['.$id_evaluation.']">';
			foreach($type_eval as $key => $type) {
				$sel_type=($key==$eval['id_type_evaluation'])?'selected="selected"':'';
				$ligne_type.='<option value="'.$key.'" '.$sel_type.'>'.$type.'</option>';
			}
			$ligne_type.='</select></td>';
			$ligne_coeff.='<td><input type="text" name="evaluations['.$id_evaluation.'][coefficient]" size="2" value="'.$eval['coefficient'].'"/></td>';
			$ligne_note_max.='<td><input type="text" name="evaluations['.$id_evaluation.'][note_maximale]" size="2" value="'.$eval['note_maximale'].'"/></td>';
			$checked_bonus=($eval['bonus']==1)?'checked="checked"':'';
			$ligne_bonus.='<td><input type="checkbox" name="evaluations['.$id_evaluation.'][bonus]" '.$checked_bonus.'/>
				<input type="hidden" name="evaluations['.$id_evaluation.'][id_annee_scolaire]" value="'.$id_annee_scolaire.'"/></td>';
			$ligne_supprimer.='<td><img src="public/images/icons/supprimer.png" alt="Supprimer" style="cursor:pointer;" 
				onclick="popupForm(\'supprimer_evaluation\',\''.$id_ue.'%'.$id_evaluation.'\');"/>';
		}
		$ligne_libelle .= '<th rowspan="5">Moyenne</th></tr>';
		$ligne_type.='</tr>';
		$ligne_coeff .= '</tr>';
		$ligne_note_max .='</tr>';
		$ligne_bonus .= '</tr>';
		$ligne_supprimer .='</tr>';
		$html .= $ligne_libelle.$ligne_type.$ligne_coeff.$ligne_note_max.$ligne_bonus.$ligne_supprimer;
			
		foreach($notes[$id_annee_scolaire] as $id_etudiant => $evals) {
			$html.='<tr><td>'.$evals['etudiant'].'</td><td>&nbsp;</td>';
			foreach($evals['evaluations'] as $id_evaluation => $note) {
				$html.='<td><input type="text" size="3" name="notes['.$id_evaluation.']['.$id_etudiant.']" value="'.$note.'"/></td>';
			}
			$html.='</tr>';
		}
		
	
		
		$html.='</table>';	
	}
}
/*

// Récupération des différentes évaluations
$s_evaluations="SELECT EV.id_evaluation, EV.libelle, EV.coefficient, EV.id_type_evaluation, " .
				"EV.note_maximale, EV.bonus, EV.id_annee_scolaire
				FROM Evaluations EV 
				WHERE EV.id_ue=".$id_ue."
				ORDER BY EV.id_annee_scolaire DESC";

$r_evaluations=mysql_query($s_evaluations);
$evaluations=array();
$s_select_evaluation='';
$s_join_evaluation='';
while ($d_evaluations=mysql_fetch_array($r_evaluations)) {
	$evaluations[$d_evaluations['id_annee_scolaire']][$d_evaluations['id_evaluation']]=array(
											'libelle' => $d_evaluations['libelle'], 
											'coefficient' => $d_evaluations['coefficient'],
											'id_type_evaluation' => $d_evaluations['id_type_evaluation'],
											'note_maximale' => $d_evaluations['note_maximale'],
											'coefficient' => $d_evaluations['coefficient'],
											'bonus' => $d_evaluations['bonus']);
 	$s_select_evaluation.=", NO".$d_evaluations['id_evaluation'].".valeur AS valeur".$d_evaluations['id_evaluation']; 
 	$s_join_evaluation.=" INNER JOIN Notes NO".$d_evaluations['id_evaluation']." " .
 			"ON (NO".$d_evaluations['id_evaluation'].".id_evaluation=".$d_evaluations['id_evaluation']." " .
 			"AND NO".$d_evaluations['id_evaluation'].".id_etudiant=E.id_etudiant) ";
}

echo '<pre>';
print_r($evaluations);
echo '</pre>';

// Récupération des notes des étudiants aux différentes évaluations
$s_notes_etudiant="SELECT E.id_etudiant, CONCAT(E.nom,' ', E.prenom) AS nom_prenom ".$s_select_evaluation." FROM Etudiants E " .
				$s_join_evaluation .
				" WHERE E.id_etudiant ".
				"IN (
					SELECT id_etudiant
					FROM l_etudiant_ue
					WHERE id_ue=".$id_ue."
				)	ORDER BY E.nom";

$r_notes_etudiant=mysql_query($s_notes_etudiant);
$notes_etudiant=array();
while($d_notes_etudiant=mysql_fetch_array($r_notes_etudiant)) {
	$notes_etudiant[$d_notes_etudiant['id_etudiant']]=array(
				'nom_prenom' => $d_notes_etudiant['nom_prenom']
				);
		
	foreach($evaluations as $id_annee_scolaire => $evaluation) {
		foreach($evaluation as $id_evaluation => $eval) {
			$notes_etudiant[$d_notes_etudiant['id_etudiant']]['eval'.$id_evaluation]=$d_notes_etudiant['valeur'.$id_evaluation];
		}
	}
	
}


/*
if ($mode=='rw') {
	$html.='<p><a href="#" onclick="popupForm(\'ajout_evalutaion\')">Ajouter une nouvelle évaluation</a></p>';
	$html.='<h3><img src="public/images/icons/notes.png"/> Notes présentes dans la base </h3>';
}

foreach($evaluations as $id_annee_scolaire => $evaluation) {
	$html.='Année '.$id_annee_scolaire;
	$html.='<table class="table_sel">
		<tr>
		<th rowspan="5">Etudiant</th>';
	$ligne_libelle='<th>Libelle</th>';
	$ligne_type.='<tr><th>Type</th>';
	$ligne_coeff.='<tr><th>Coefficient</th>';
	$ligne_note_max.='<tr><th>Note max.</th>';
	$ligne_bonus.='<tr><th>Bonus ?</th>';
	foreach($evaluation as $id_annee_scolaire => $eval) {
		$ligne_libelle.='<td style="font-weigth:bold;">'.$eval['libelle'].'</td>';
		$ligne_type.='<td><select name="type_'.$k_eval.'">';
		foreach($type_eval as $key => $type) {
			$sel_type=($key==$eval['id_type_evaluation'])?'selected="selected"':'';
			$ligne_type.='<option value="'.$key.'" '.$sel_type.'>'.$type.'</option>';
		}
		$ligne_type.='</select></td>';
		$ligne_coeff.='<td><input type="text" name="coef_'.$k_eval.'" size="2" value="'.$eval['coefficient'].'"/></td>';
		$ligne_note_max.='<td><input type="text" name="note_max_'.$k_eval.'" size="2" value="'.$eval['note_maximale'].'"/></td>';
		$checked_bonus=($eval['bonus']==1)?'checked="checked"':'';
		$ligne_bonus.='<td><input type="checkbox" name="bonus_'.$k_eval.'" '.$checked_bonus.'/></td>';
	}
	$ligne_libelle .= '<th rowspan="5">Moyenne</th></tr>';
	$ligne_type.='</tr>';
	$ligne_coeff .= '</tr>';
	$ligne_note_max .='</tr>';
	$ligne_bonus .= '</tr>';
	
	$html .= $ligne_libelle.$ligne_type.$ligne_coeff.$ligne_note_max.$ligne_bonus;
	
	
	foreach($notes_etudiant as $id_etudiant => $ligne_etudiant) {
		$html.='<tr><td>'.$ligne_etudiant['nom_prenom'].'</td><td style="background-color:#005959;">&nbsp;</td>';
		foreach($evaluation as $id_evaluation => $eval) {
			$html.='<td align="center"><input type="text" size="2" name="note_'.$id_evaluation.'_'.$id_etudiant.'" ' .
					'value="'.$ligne_etudiant['eval'.$id_evaluation].'"/></td>';
	
		}
		$html.='<td align="center">N/A</td>';	
	}
	
	$html.='</table>';	
}	
	*/	
?>

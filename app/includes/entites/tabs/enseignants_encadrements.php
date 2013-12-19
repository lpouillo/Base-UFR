<?php
$html.='<h3><img src="public/images/icons/stage_laboratoire.png"> Stages en laboratoire</h3>';
$s_stage_L="SELECT S.id_stage_laboratoire, S.sujet, A.libelle AS annee, CONCAT(E.nom,' ',E.prenom) AS etudiant
			FROM l_encadrant_stage ES
			INNER JOIN Stages_Laboratoires S
				ON S.id_stage_laboratoire=ES.id_stage
			INNER JOIN a_annee_scolaire A
				ON ES.id_annee_scolaire=A.id_annee_scolaire
			LEFT JOIN Etudiants E
				ON S.id_etudiant=E.id_etudiant
			WHERE ES.id_type_encadrant IN (1,2,3)
				AND ES.id_encadrant=".$id_enseignant."
			ORDER BY A.id_annee_scolaire DESC";
$stages_L=recuperation_donnees($s_stage_L);
if (sizeof($stages_L)>0) {
	$html.='<table class="table_sel">
				<tr>
					<th width="25px">Détails</th><th>Année</th><th>Sujet</th><th>Étudiant</th>
				</tr>';
			
	foreach ($stages_L as $stage) {
		$html.='<tr>
				<td class="td_selection"><img src="public/images/icons/modifier.png"/></td>
				<td>'.$stage['annee'].'</td>
				<td>'.$stage['sujet'].'</td>
				<td>'.$stage['etudiant'].'</td>
			</tr>';
	}
	$html.='</table>';
}

// TUTEURS DE STAGES EN ENTREPRISES
$html.='<h3><img src="public/images/icons/stage_entreprise.png"> Stages en entreprise</h3>';
$s_stage_P="SELECT S.id_stage_entreprise, S.sujet, A.libelle AS annee, CONCAT(E.nom,' ',E.prenom) AS etudiant
			FROM l_encadrant_stage ES
			INNER JOIN Stages_Entreprises S
				ON S.id_stage_entreprise=ES.id_stage
			INNER JOIN a_annee_scolaire A
				ON ES.id_annee_scolaire=A.id_annee_scolaire
			LEFT JOIN Etudiants E
				ON S.id_etudiant=E.id_etudiant
			WHERE ES.id_type_encadrant IN (7,8)
				AND ES.id_encadrant=".$id_enseignant."
			ORDER BY A.id_annee_scolaire DESC";
$stages_P=recuperation_donnees($s_stage_P);
if (sizeof($stages_P)>0) {
	$html.='<table class="table_sel">
				<tr>
					<th width="25px">Détails</th><th>Année</th><th>Sujet</th><th>Étudiant</th>
				</tr>';
			
	foreach ($stages_P as $stage) {
		$html.='<tr>
				<td><img src="public/images/icons/modifier.png"/></td>
				<td>'.$stage['annee'].'</td>
				<td>'.$stage['sujet'].'</td>
				<td>'.$stage['etudiant'].'</td>
			</tr>';
	}
	$html.='</table>';
}

// TUTEURS DE CAS D'ÉTUDES
$html.='<h3><img src="public/images/icons/cas_etudes.png"> Cas d\'études</h3>';
$s_cas_etudes="SELECT CE.id_cas_etude, CE.sujet, CONCAT(ET.nom,' ',ET.prenom) AS etudiant, 
					ENT.nom, A.libelle AS annee
					FROM Cas_Etudes CE
					LEFT JOIN Etudiants ET 
						ON ET.id_etudiant=CE.id_etudiant 
					LEFT JOIN Entreprises ENT 
						ON ENT.id_entreprise=CE.id_entreprise
					LEFT JOIN l_encadrant_stage ES
						ON ES.id_stage=CE.id_cas_etude
						AND ES.id_encadrant=".$id_enseignant." 
						AND ES.id_type_encadrant IN (9,10) 
						AND ES.id_annee_scolaire=".$id_annee_scolaire."
					INNER JOIN a_annee_scolaire A
						ON ES.id_annee_scolaire=A.id_annee_scolaire";
$cas_etudes=recuperation_donnees($s_cas_etudes);
if (sizeof($cas_etudes)>0) {
	$html.='<table class="table_sel">
				<tr>
					<th width="25px">Détails</th><th>Année</th><th>Sujet</th><th>Étudiant</th>
				</tr>';
			
	foreach ($cas_etudes as $stage) {
		$html.='<tr>
				<td><img src="public/images/icons/modifier.png"/></td>
				<td>'.$stage['annee'].'</td>
				<td>'.$stage['sujet'].'</td>
				<td>'.$stage['etudiant'].'</td>
			</tr>';
	}
	$html.='</table>';
}

$html.='<h3><img src="public/images/icons/doctorant.png"/> Doctorants</h3>';
?>

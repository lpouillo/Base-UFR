<?php
/* Script permettant le calcul des moyennes de chaque étudiant pour chaque module, ainsi que son rang, 
 * sa moyenne générale et son classement final 
 */

/* Initialisation des moyennes et des classements */
$s_init="UPDATE l_etudiant_ue SET moyenne=-3, classement=0, validante=1";
$r_init=mysql_query($s_init) or die(mysql_error());

/* Récupération des nombres d'UE pour chaque année/niveau/spécialité/type */
$s_nue="SELECT * FROM l_parcours_ue";
$r_nue=mysql_query($s_nue);
$n_ue=array();
while ($d_nue=mysql_fetch_array($r_nue)) {
	$n_ue[$d_nue['id_annee_scolaire']][$d_nue['id_niveau']][$d_nue['id_specialite']][$d_nue['id_type_ue']]++;
}

/*echo '<pre>';
print_r($n_ue);
echo '</pre>';*/

/* Sélection de tous les étudiants */ 
$s_etudiants="SELECT E.id_etudiant, CONCAT(E.nom,' ',E.prenom) AS nom_prenom, NI.abbreviation AS niv, SP.abbreviation AS spec FROM Etudiants E 
		INNER JOIN l_parcours_etudiant P ON P.id_etudiant=E.id_etudiant
		INNER JOIN a_niveau NI ON NI.id_niveau=P.id_niveau
		INNER JOIN a_specialite SP ON SP.id_specialite=P.id_specialite
		ORDER BY E.nom";

$r_etudiants=mysql_query($s_etudiants) 
	or die(mysql_error());
$html .='<p>Calcul des moyennes ... ';
while ($d_etudiants=mysql_fetch_array($r_etudiants)) {
	$id_etudiant=$d_etudiants['id_etudiant'];
	$uo=array();
	// Récupération des notes de l'étudiant
	$s_notes="SELECT EM.id_ue, EM.id_type_ue, EM.numero_option, EM.id_annee_scolaire, EV.id_evaluation, EV.coefficient, N.valeur 
			FROM l_etudiant_ue EM 
			INNER JOIN Evaluations EV 
				ON EM.id_ue=EV.id_ue 
			INNER JOIN Notes N 
				ON EV.id_evaluation=N.id_evaluation
				AND N.id_etudiant=EM.id_etudiant 
			WHERE EM.id_etudiant=".$id_etudiant."
			AND N.id_etudiant=".$id_etudiant;
	$r_notes=mysql_query($s_notes);
	$notes=array();
	while ($d_notes=mysql_fetch_array($r_notes)) {
		$notes[$d_notes['id_annee_scolaire']][$d_notes['id_ue']]=
				array(
					'id_type_ue' => $d_notes['id_type_ue'], 
					'numero_option' => $d_notes['numero_option'],
					'evaluations' => array(
						$d_notes['id_evaluation'] => array(						
											'coefficient' => $d_notes['coefficient'],
											'valeur' => $d_notes['valeur'])
										)
					);
	}
	
	// On calcule pour chaque année scolaire
	foreach ($notes as $id_annee_scolaire => $notes_annee) {
		// Pour chaque ue
		foreach ($notes_annee as $id_ue => $notes_ue) {
			$moyenne=0;
			$coeffs=0;
			foreach($notes_ue['evaluations'] as $id_evaluation => $note) {
				if ($note['valeur']>0) {
					$moyenne.=$note['coefficient']*$note['valeur'];
					$coeffs.=$note['coefficient'];
				} else {
					$moyenne=$note['valeur'];
					$coeffs=1;
				}
			}
			$moyenne=$moyenne/$coeffs;	
			$s_update_moyenne="UPDATE l_etudiant_ue SET moyenne=".$moyenne.", validante=1 
								WHERE id_annee_scolaire=".$id_annee_scolaire."
								AND id_ue=".$id_ue."
								AND id_etudiant=".$id_etudiant."
								AND numero_option=".$notes_ue['numero_option'];
			mysql_query($s_update_moyenne)
				or die (mysql_error());
		}
	}
}
/* Détermination des UE optionnelles validantes */
/* SEULEMENT POUR L'ANNEE SCOLAIRE ACTUELLE EN ATTENDANT DE POUVOIR FAIRE UNE TRUC GÉNÉRAL */
$s_uo="SELECT EUE.id_etudiant, EUE.id_annee_scolaire, EUE.numero_option, EUE.id_ue, EUE.moyenne,
		P.id_niveau, P.id_specialite 
		FROM l_etudiant_ue EUE 
		INNER JOIN l_parcours_etudiant P
			ON P.id_etudiant=EUE.id_etudiant
			AND P.id_annee_scolaire=EUE.id_annee_scolaire
		WHERE EUE.id_type_ue=3 AND P.id_niveau IN (6,7) AND EUE.id_annee_scolaire=10";
$r_uo=mysql_query($s_uo);
$uo=array();
while ($d_uo=mysql_fetch_array($r_uo)) {
	$uo[$d_uo['id_etudiant']][$d_uo['id_annee_scolaire']]['id_niveau']=$d_uo['id_niveau'];
	$uo[$d_uo['id_etudiant']][$d_uo['id_annee_scolaire']]['id_specialite']=$d_uo['id_specialite'];
	$uo[$d_uo['id_etudiant']][$d_uo['id_annee_scolaire']]['ues'][$d_uo['id_ue']]=$d_uo['moyenne'];
}

foreach ($uo as $id_etudiant => $uo_annee) {
	foreach ($uo_annee as $id_annee_scolaire => $data) {
			
		$n_uo=$n_ue[$id_annee_scolaire][$data['id_niveau']][$data['id_specialite']][3];
	/*	echo $n_uo.'<pre>';
		print_r($data);
		echo '</pre>';*/
	
		
	} 	

}

$html .='Terminé. </p><p> Calcul du rang dans chaque UE ... ';

// Calcul du rang de l'étudiant dans l'UE
$s_notes_ues="SELECT UE.id_ue, EUE.id_etudiant, EUE.moyenne, EUE.id_annee_scolaire 
			FROM Unites_Enseignement UE
			INNER JOIN l_etudiant_ue EUE
				ON UE.id_ue=EUE.id_ue
			ORDER BY UE.id_ue, EUE.id_annee_scolaire, EUE.moyenne DESC";
$r_notes_ues=mysql_query($s_notes_ues) 
	or die(mysql_error());
$notes_classees=array();
while ($d_notes_ues=mysql_fetch_array($r_notes_ues)) {
	$notes_classees[$d_notes_ues['id_ue']][$d_notes_ues['id_annee_scolaire']][]= $d_notes_ues['id_etudiant'];
	
}
foreach($notes_classees as $id_ue => $note_annee) {
	foreach ($note_annee as $id_annee_scolaire => $etudiants) {
		$classement=0;
		foreach($etudiants as $id_etudiant) {
			$classement++;
			$s_update_rang="UPDATE l_etudiant_ue SET classement=".$classement." 
							WHERE id_etudiant=".$id_etudiant."
							AND id_ue=".$id_ue."
							AND id_annee_scolaire=".$id_annee_scolaire;
			mysql_query($s_update_rang)
				or die(mysql_error());
		}
	}
}


$html .='Terminé. </p><p> Calcul du rang dans la promotion ... ';

// Calcul de la moyenne générale de chacun des étudiants
$s_notes_etudiants="SELECT EUE.id_etudiant, EUE.id_annee_scolaire, EUE.moyenne, UE.ects
					FROM l_etudiant_ue EUE 
					INNER JOIN Unites_Enseignement UE
						ON UE.id_ue=EUE.id_ue";
<?php

$s_etudiants_notes="SELECT E.nom, E.prenom, NI.abbreviation AS niveau, SP.abbrevation AS specialite, E.intitule, NO.valeur
					FROM Etudiants E
					INNER JOIN l_parcours_etudiant P
						ON P.id_etudiant = E.id_etudiant AND P.id_annee_scolaire =".$id_annee_scolaire."
					INNER JOIN a_niveau NI 
						ON P.id_niveau = NI.id_niveau
					INNER JOIN a_specialite SP 
						ON P.id_specialite = SP.id_specialite
					INNER JOIN Notes NO 
						ON NO.id_etudiant = E.id_etudiant
					INNER JOIN Evaluations EV 
						ON NO.id_evaluation = EV.id_evaluation
					INNER JOIN Unites_Enseignement UE 
						ON EV.id_ue = UE.id_ue
					INNER JOIN l_etudiant_ue EM 
						ON UE.id_ue = EM.id_ue AND NO.id_etudiant = EM.id_etudiant
						AND EM.id_annee_scolaire=".$id_annee_scolaire."
					WHERE (NO.valeur <10 AND NO.valeur >-1) OR NO.valeur=-3 OR NO.valeur=-1 
					ORDER BY E.nom";
//echo $s_etudiants_notes;
$r_etudiants_notes=mysql_query($s_etudiants_notes);			

$fname='notes_inferieures_a_8_'.date('Ymd').'_'.$niv.' '.$spec.'.csv';
header("Content-Type: text/csv");
header('Content-disposition: filename="'.$fname.'"');

$string_all="Nom; Prénom; Niveau; Spécialité; Intitulé UE; note \n' \n ";
while ($data=mysql_fetch_array($r_etudiants_notes)) {
	$valeur=($data['valeur']>=0)?$data['valeur']:'absent';
	$string_all.=$data['nom']."; ".$data['prenom']."; ".$data['niveau']."; ".$data['specialite']."; ".$data['intitule']."; ".str_replace('.',',',$valeur)." \n ";
}
echo utf8_decode($string_all);

die();
?>

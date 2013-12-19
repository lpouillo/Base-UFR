<?php
//
$s_notes_licence="SELECT CONCAT( E.nom, ' ',E.prenom) AS etudiant, UE.intitule AS ue, UE.ects, UE.code, EUE.moyenne, NI.libelle AS niveau, SP.libelle AS specialite 
			FROM l_etudiant_ue EUE
			INNER JOIN Etudiants E
				ON EUE.id_etudiant=E.id_etudiant
			INNER JOIN Unites_Enseignement UE
				ON EUE.id_ue=UE.id_ue
			INNER JOIN l_parcours_etudiant P
				ON EUE.id_etudiant=P.id_etudiant
				AND P.id_annee_scolaire=".$id_annee_scolaire."
				AND P.id_niveau IN (2,5)
			INNER JOIN a_niveau NI 
				ON P.id_niveau=NI.id_niveau
			INNER JOIN a_specialite SP
				ON P.id_specialite=SP.id_specialite
			WHERE EUE.moyenne<>-3 AND EUE.id_type_ue<>16 AND EUE.id_annee_scolaire=".$id_annee_scolaire."
			ORDER BY NI.libelle, E.nom, UE.intitule";
$r_notes_licence=mysql_query($s_notes_licence)
	or die(mysql_error());
$notes_licence=array();
while ($d_notes_licence=mysql_fetch_array($r_notes_licence)) {
	$notes_licence[$d_notes_licence['niveau']][$d_notes_licence['specialite']][$d_notes_licence['ue']]['code']=$d_notes_licence['code'];
	$notes_licence[$d_notes_licence['niveau']][$d_notes_licence['specialite']][$d_notes_licence['ue']]['ects']=$d_notes_licence['ects'];
	$notes_licence[$d_notes_licence['niveau']][$d_notes_licence['specialite']][$d_notes_licence['ue']]['etudiants'][$d_notes_licence['etudiant']]=$d_notes_licence['moyenne'];
}

$string='';
foreach($notes_licence as $niveau => $notes_niveau) {
	foreach ($notes_niveau as $specialite => $notes_specialites) {
		$string.=$niveau.' '.$specialite."\n \n".'';
		$etudiants=array();
		$string_ue='"";';
		$string_code='"";';
		foreach ($notes_specialites as $ue => $notes_ue) {
			$string_ue.='"'.$ue.'";';
			$string_code.='"'.$notes_ue['code'].'";';
			$ects[$ue]=$notes_ue['ects'];
			foreach ($notes_ue['etudiants'] as $etudiant => $note) {
				$etudiants[$etudiant][$ue]=$note;
			}			
		}
		$string_ue.='"Moyenne"'."\n";
		$string.=$string_ue.$string_code."\n";
		
		$string_etudiants='';
		
	
		foreach ($etudiants as $etudiant => $note_ue) {
			$string_etudiant='"'.$etudiant.'";';
			$moyenne=0;
			$n_note=0;
			foreach ($note_ue as $ue => $note) {
				//echo $etudiant.' '.$note.'<br/>'; 
				$string_etudiant.='"'.$note.'";';
				$moyenne+=$note*$ects[$ue];
				$n_note+=$ects[$ue];
			}
			$string.=$string_etudiant.'"'.($moyenne/$n_note).'"'."\n";
	
		}
	}
	$string.="\n \n";
} 

/* RATTRAPAGE */
$string.="RATTRAPAGE \n \n";
$s_notes_rattrapage="SELECT CONCAT( E.nom, ' ',E.prenom) AS etudiant, UE.intitule AS ue, UE.ects, UE.code, EUE.moyenne, NI.libelle AS niveau, SP.libelle AS specialite 
			FROM l_etudiant_ue EUE
			INNER JOIN Etudiants E
				ON EUE.id_etudiant=E.id_etudiant
			INNER JOIN Unites_Enseignement UE
				ON EUE.id_ue=UE.id_ue
			INNER JOIN l_parcours_etudiant P
				ON EUE.id_etudiant=P.id_etudiant
				AND P.id_annee_scolaire=".$id_annee_scolaire."
				AND P.id_niveau=5
			INNER JOIN a_niveau NI 
				ON P.id_niveau=NI.id_niveau
			INNER JOIN a_specialite SP
				ON P.id_specialite=SP.id_specialite
			WHERE EUE.moyenne<>-3 AND EUE.id_type_ue=16 AND EUE.id_annee_scolaire=".$id_annee_scolaire."
			ORDER BY NI.libelle, E.nom, UE.intitule";

$r_notes_rattrapage=mysql_query($s_notes_rattrapage)
	or die(mysql_error());
$notes_rattrapage=array();
while ($d_notes_rattrapage=mysql_fetch_array($r_notes_rattrapage)) {
	$notes_rattrapage[$d_notes_rattrapage['niveau']][$d_notes_rattrapage['specialite']][$d_notes_rattrapage['ue']]['code']=$d_notes_rattrapage['code'];
	$notes_rattrapage[$d_notes_rattrapage['niveau']][$d_notes_rattrapage['specialite']][$d_notes_rattrapage['ue']]['ects']=$d_notes_rattrapage['ects'];
	$notes_rattrapage[$d_notes_rattrapage['niveau']][$d_notes_rattrapage['specialite']][$d_notes_rattrapage['ue']]['etudiants'][$d_notes_rattrapage['etudiant']]=$d_notes_rattrapage['moyenne'];
}


foreach($notes_rattrapage as $niveau => $notes_niveau) {
	foreach ($notes_niveau as $specialite => $notes_specialites) {
		
		
		$string.=$niveau.' '.$specialite."\n \n".'';
		$etudiants=array();
		$ues=array();
	
		$string_ue='"";';
		$string_code='"";';
		foreach ($notes_specialites as $ue => $notes_ue) {
			$ues[]=$ue;
			$string_ue.='"'.$ue.'";';
			$string_code.='"'.$notes_ue['code'].'";';
			$ects[$ue]=$notes_ue['ects'];
			foreach ($notes_ue['etudiants'] as $etudiant => $note) {
				$etudiants[$etudiant][$ue]=$note;
			}			
		}
		 
		$string_ue.="\n";
		$string.=$string_ue.$string_code."\n";
		
		$string_etudiants='';
		
		foreach ($etudiants as $etudiant => $note_ue) {
			$string_etudiant='"'.$etudiant.'";';
			foreach ($ues as $intitule) {
				$string_etudiant.='"'.$note_ue[$intitule].'";';
			}
			$string.=$string_etudiant."\n";
	
		}
		$string.="\n \n ";
	}
	
} 



$fname='bilan_notes_Licence_'.date('Ymd').'.csv';
header("Content-Type: text/csv");
header('Content-disposition: filename="'.$fname.'"');

echo utf8_decode($string);
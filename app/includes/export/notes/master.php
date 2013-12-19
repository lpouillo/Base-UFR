<?php

switch($spec) {
	case 'GEI':
		$spec_s="='ge'";
		break;
	case 'G2S':
		$spec_s="='gs'";
		break;
	case 'R':
		$spec_s=" IN ('gc','gp','gl','gm')";
		break;
}

$s_etudiants="SELECT E.id_etudiant, E.nom, E.prenom,
				NI.abbreviation AS niv, SP.abbreviation AS spec
			FROM Etudiants E
			INNER JOIN l_parcours_etudiant P 
				ON P.id_etudiant=E.id_etudiant AND P.id_annee_scolaire=".$id_annee_scolaire."
			INNER JOIN a_niveau NI
				ON NI.id_niveau=P.id_niveau
			INNER JOIN a_specialite SP
				ON SP.id_specialite=P.id_specialite
			WHERE NI.abbreviation='".$niv."' AND SP.abbreviation".$spec_s."
			ORDER BY E.nom";

$r_etudiants=mysql_query($s_etudiants);

$i_etudiant=0;
$stringEtudiant=array();
while ($d_etudiants=mysql_fetch_array($r_etudiants)) {
	$i_etudiant=$d_etudiants['id_etudiant'];
	$uc=array();
	$us=array();
	$uo=array();
	$u2=array();
	$ects=array();
	
	$spec=$d_etudiants['spec'];
	
	$s_ue_notes="SELECT UE.id_ue, UE.intitule, UE.ects, EM.numero_option, EM.id_type_ue, 
				EM.validante, EM.moyenne AS note, EM.classement
				FROM Unites_Enseignement UE
				INNER JOIN l_etudiant_ue EM
					ON EM.id_ue=UE.id_ue 
					AND EM.id_etudiant=".$d_etudiants['id_etudiant']."
					AND EM.id_annee_scolaire=".$id_annee_scolaire."
				ORDER BY EM.id_type_ue";
	$r_ue_notes=mysql_query($s_ue_notes)
		or die(mysql_error());
	while ($d_ue_notes=mysql_fetch_array($r_ue_notes)) {
		// Récupération du nombre d'étudiants qui suivent le module
		$s_n_etudiants="SELECT id_etudiant FROM l_etudiant_ue WHERE id_ue=".$d_ue_notes['id_ue']." AND id_annee_scolaire=".$id_annee_scolaire;
		$r_n_etudiants=mysql_query($s_n_etudiants); 
		$n_etudiants_ue=mysql_num_rows($r_n_etudiants);
		
		switch($d_ue_notes['id_type_ue']) {
			case '4':
				$uc['tc'.$d_ue_notes['numero_option']]=array(
										'intitule' => $d_ue_notes['intitule'], 
										'note' => $d_ue_notes['note'],
										'validante' => $d_ue_notes['validante'],
										'ects' => $d_ue_notes['ects'],
										'classement' => $d_ue_notes['classement'],
										'n_etudiants' => $n_etudiants_ue
				);
				break;
			case '5':
				$us['mod'.$d_ue_notes['numero_option']]=array(
										'intitule' => $d_ue_notes['intitule'], 
										'note' => $d_ue_notes['note'],
										'validante' => $d_ue_notes['validante'],
										'ects' => $d_ue_notes['ects'],
										'classement' => $d_ue_notes['classement'],
										'n_etudiants' => $n_etudiants_ue
				);
				break;
			case '3':
				$uo['opt'.$d_ue_notes['numero_option']]=array(
										'intitule' => $d_ue_notes['intitule'], 
										'note' => $d_ue_notes['note'],
										'validante' => $d_ue_notes['validante'],
										'ects' => $d_ue_notes['ects'],
										'classement' => $d_ue_notes['classement'],
										'n_etudiants' => $n_etudiants_ue
				);
				break;
		}
	}

	$stringEtudiant[$i_etudiant]='';
	
	
	// CALCUL DE LA MOYENNE DES UES DE TRONC COMMUN
	$moyenne_uc[$i_etudiant]=0; 
	$n_uc[$i_etudiant]=0;
	foreach($uc as $k_ue => $ue) {
		$ects['uc']+=$ue['ects'];
		switch ($ue['note']) {
			case -3:
				$note='en attente';	
				$val='en attente';
			break;
			case -2:
				$note='validé';
				$val=$ue['ects'];
			break;
			case -1:
				$note='absent';
				$val=0;
				$ue['ects']=0;
			break;
			default:
				$moyenne_uc[$i_etudiant]+=$ue['ects']/3*$ue['note'];
				$n_uc[$i_etudiant]=$n_uc[$i_etudiant]+$ue['ects']/3;
				$note=number_format($ue['note'],2);
				$val=($note>=8)?$ue['ects']:0;
				$ects['uc_e']+=$ue['ects'];
		}
		$stringEtudiant[$i_etudiant].=$d_etudiants['nom']."; ".$d_etudiants['prenom']."; ".$niv."; ".$spec."; ".
			$ue['intitule']."; ".$k_ue."; ".($ue['ects']/3)." ; oui; ".str_replace('.',',',$note).";".$ue['ects'].";".$val."; ".$ue['classement']." ; ".$ue['n_etudiants']." \n" ;
	}
	/*if ($i_etudiant==1659) {
		echo $moyenne_uc[$i_etudiant].' '.$n_uc[$i_etudiant];
		die();
	}*/
	if ($n_uc[$i_etudiant]!=0) {
		$moyenne_uc[$i_etudiant]=$moyenne_uc[$i_etudiant]/$n_uc[$i_etudiant];
	}
	
	
	$moyenne_us[$i_etudiant]=0; 
	$n_us[$i_etudiant]=0;
	foreach($us as $k_ue => $ue) {
		$ects['us']+=$ue['ects'];
		switch ($ue['note']) {
			case -3:
				$note='en attente';	
				$val='en attente';
			break;
			case -2:
				$note='validé';
				$val=$ue['ects'];
			break;
			case -1:
				$note='absent';
				$val=0;
				$ue['ects']=0;
			break;
			default:
				$moyenne_us[$i_etudiant]+=$ue['note'];
				$n_us[$i_etudiant]++;
				$note=number_format($ue['note'],2);
				$val=($note>=8)?$ue['ects']:0;
				$ects['us_e']+=$ue['ects'];
		}
		$stringEtudiant[$i_etudiant].=$d_etudiants['nom']."; ".$d_etudiants['prenom']."; ".$niv."; ".$spec."; ".
		$ue['intitule']."; ".$k_ue."; ".($ue['ects']/3)." ; oui; ".str_replace('.',',',$note).";".$ue['ects'].";".$val."; ".$ue['classement']." ; ".$ue['n_etudiants']." \n" ;

	}
	if ($n_us[$i_etudiant]!=0) {
		$moyenne_us[$i_etudiant]=$moyenne_us[$i_etudiant]/$n_us[$i_etudiant];
	}
	$moyenne_uo[$i_etudiant]=0; 
	$n_uo[$i_etudiant]=0;
	foreach($uo as $k_ue => $ue) {
		$ects['uo']+=$ue['ects'];
		switch ($ue['note']) {
			case -3:
				$note='en attente';	
				$val_ects='en attente';
			break;
			case -2:
				$note='validé';
				$val=$ue['ects'];
			break;
			case -1:
				$note='absent';
				$val=0;
				$val_ects=0;
				$ue['ects']=0;
			break;
			default:
				if ($ue['validante']) {
					$moyenne_uo[$i_etudiant]+=$ue['note'];
					$n_uo[$i_etudiant]++;
					$val=1;
				} else {
					$val=0;
				}
				$note=number_format($ue['note'],2);
				$val_ects=($note>=8)?$ue['ects']:0;
				$ects['uo_e']+=$ue['ects'];
		}
		
		$validante=($val)?'oui':'non';	
		$stringEtudiant[$i_etudiant].=$d_etudiants['nom']."; ".$d_etudiants['prenom']."; ".$niv."; ".$spec."; ".
		$ue['intitule']."; ".$k_ue."; ".($ue['ects']/3)." ; ".$validante."; ".str_replace('.',',',$note).";".$ue['ects'].";".$val_ects."; ".$ue['classement']." ; ".$ue['n_etudiants']." \n" ;
	
		if ($i_etudiant==1659) {
	//		echo $validante.'<br/>';
		}
	
	}
	
	if ($n_uo[$i_etudiant]!=0) {
		$moyenne_uo[$i_etudiant]=$moyenne_uo[$i_etudiant]/$n_uo[$i_etudiant];
	}
	if (($n_uc[$i_etudiant]+$n_us[$i_etudiant]+$n_uo[$i_etudiant])!=0) {
		$moyenne_generale[$i_etudiant]=($n_uc[$i_etudiant]*$moyenne_uc[$i_etudiant]+$n_us[$i_etudiant]*$moyenne_us[$i_etudiant]+
		$n_uo[$i_etudiant]*$moyenne_uo[$i_etudiant])/($n_uc[$i_etudiant]+$n_us[$i_etudiant]+$n_uo[$i_etudiant]);
	}
	
	$nom[$i_etudiant]=$d_etudiants['nom'];
	$prenom[$i_etudiant]=$d_etudiants['prenom'];
	$niveau[$i_etudiant]=$niv;
	$specialite[$i_etudiant]=$spec;
}
// Détermination des classements des moyennes de l'étudiant
arsort($moyenne_generale);
arsort($moyenne_uc);
arsort($moyenne_us);
arsort($moyenne_uo);

$class=0;
foreach ($moyenne_generale as $id_etudiant => $moy) {
	$class++;
	$class_general[$id_etudiant]=$class;
}
$class=0;
foreach ($moyenne_uc as $id_etudiant => $moy) {
	$class++;
	$class_uc[$id_etudiant]=$class;
}
$class=0;
foreach ($moyenne_us as $id_etudiant => $moy) {
	$class++;
	$class_us[$id_etudiant]=$class;
}
$class=0;
foreach ($moyenne_uo as $id_etudiant => $moy) {
	$class++;
	$class_uo[$id_etudiant]=$class;
}

$notes_g_promo=array();
$notes_uc_promo=array();
$notes_us_promo=array();
$notes_uo_promo=array();


foreach($stringEtudiant as $i_etudiant => &$string) {
	$notes_g_promo[]=$moyenne_generale[$i_etudiant];
	$notes_uc_promo[]=$moyenne_uc[$i_etudiant];
	$notes_us_promo[]=$moyenne_us[$i_etudiant];
	$notes_uo_promo[]=$moyenne_uo[$i_etudiant];
	
	$string.=$nom[$i_etudiant]."; ".$prenom[$i_etudiant]."; ".$niveau[$i_etudiant]."; ".$specialite[$i_etudiant].
		"; MOYENNE GÉNÉRALE ; Y ;".($n_uc[$i_etudiant]+$n_us[$i_etudiant]+$n_uo[$i_etudiant])."; Oui; ".str_replace('.',',',number_format($moyenne_generale[$i_etudiant],2)).
			"; ".($ects['uc']+$ects['us']+$ects['uo'])."; ".($ects['uc_e']+$ects['us_e']+$ects['uo_e'])." ;".$class_general[$i_etudiant]."; ".sizeof($moyenne_generale)." \n".
		$nom[$i_etudiant]."; ".$prenom[$i_etudiant]."; ".$niveau[$i_etudiant]."; ".$specialite[$i_etudiant].
		"; MOYENNE TRONC COMMUN ; Y1 ;".$n_uc[$i_etudiant]."; Oui; ".str_replace('.',',',number_format($moyenne_uc[$i_etudiant],2))."; ".$ects['uc']."; ".$ects['uc_e'].
		"; ".$class_uc[$i_etudiant]."; ".sizeof($moyenne_uc)."; \n".
		$nom[$i_etudiant]."; ".$prenom[$i_etudiant]."; ".$niveau[$i_etudiant]."; ".$specialite[$i_etudiant].
		"; MOYENNE UE SPECIALITÉS ; Y2 ;".$n_us[$i_etudiant]."; Oui; ".str_replace('.',',',number_format($moyenne_us[$i_etudiant],2)).";  ".$ects['us']."; ".$ects['us_e'].
		"; ".$class_us[$i_etudiant]."; ".sizeof($moyenne_us)."; \n".
		$nom[$i_etudiant]."; ".$prenom[$i_etudiant]."; ".$niveau[$i_etudiant]."; ".$specialite[$i_etudiant].
		"; MOYENNE UE OPTIONNELLES ; Y3 ;".$n_uo[$i_etudiant]."; Oui; ".str_replace('.',',',number_format($moyenne_uo[$i_etudiant],2))."; ".$ects['uo']."; ".$ects['uo_e'].
		"; ".$class_uo[$i_etudiant]."; ".sizeof($moyenne_uo)."; \n \n";
}

// Calcul des moyennes de la promotion
$moyenne_g_promo=array_sum($notes_g_promo)/sizeof($notes_g_promo);
$moyenne_uc_promo=array_sum($notes_uc_promo)/sizeof($notes_uc_promo);
$moyenne_us_promo=array_sum($notes_us_promo)/sizeof($notes_us_promo);
$moyenne_uo_promo=array_sum($notes_uo_promo)/sizeof($notes_uo_promo);

$string_final="\n \n ; ;".$niv." ; ".$spec." ; MOYENNE GENERALE PROMOTION ; ; ; ;".str_replace('.',',',number_format($moyenne_g_promo,2))." \n".
			"; ;".$niv." ; ".$spec." ; MOYENNE TRONC COMMUN PROMOTION ; ; ; ;".str_replace('.',',',number_format($moyenne_uc_promo,2))." \n".
			"; ;".$niv." ; ".$spec." ; MOYENNE UE SPECIALITES PROMOTION ; ; ; ;".str_replace('.',',',number_format($moyenne_us_promo,2))." \n".
			"; ;".$niv." ; ".$spec." ; MOYENNE UE OPTIONNELLES PROMOTION ; ; ; ;".str_replace('.',',',number_format($moyenne_uo_promo,2))." \n";

// Génération du fichier
$fname='bilan_notes_'.date('Ymd').'_'.$niv.' '.$spec.'.csv';
header("Content-Type: text/csv");
header('Content-disposition: filename="'.$fname.'"');

$string_all='';
$entetes='Nom; Prénom; Niveau; Spécialité; Intitulé UE; UE; Coeff; Valid; note; ECTS suivis; ECTS validés; Rang ; Effectif';	
$string_all .= $entetes."\n";


foreach($stringEtudiant as $tmpstring) {
	$string_all .= $tmpstring;
}



$string_all .= $string_final;



echo utf8_decode($string_all);

exit;
?>

<?php
$s_enseignant="SELECT E.id_enseignant, CONCAT(E.nom,' ',E.prenom) AS enseignant, L.nom AS laboratoire, ET.nom AS etablissment, ST.libelle AS statut
				FROM Enseignants E
				LEFT JOIN Laboratoires L
					ON L.id_laboratoire=E.id_laboratoire
				LEFT JOIN Etablissements ET
					ON ET.id_etablissement=E.id_etablissement
				LEFT JOIN a_statut ST
					ON ST.id_statut=E.id_statut
				ORDER BY enseignant";
$r_enseignant=mysql_query($s_enseignant);
$string='';
while ($d_enseignant=mysql_fetch_array($r_enseignant)) {
	$total=0;
	
	$s_ue="SELECT UE.intitule, UFR.libelle AS ufr, EUE.heures_cours, EUE.ng_cours, EUE.heures_TD, EUE.ng_TD, EUE.heures_TP, EUE.ng_TP, 
			EUE.heures_colle, EUE.ng_colles, EUE.heures_terrain, EUE.njours_terrain
			FROM l_enseignant_ue EUE
			INNER JOIN Unites_Enseignement UE
				ON EUE.id_ue=UE.id_ue
			INNER JOIN a_ufr UFR
				ON UFR.id_ufr=UE.id_ufr
			WHERE EUE.id_enseignant=".$d_enseignant['id_enseignant']." 
				AND id_annee_scolaire=".$id_annee_scolaire;
	
	
	$r_ue=mysql_query($s_ue);
	$n_ue=mysql_num_rows($r_ue);
	
	$string.='"'.$d_enseignant['enseignant'].' ('.$d_enseignant['statut'].', '.$d_enseignant['laboratoire'].' - '.$d_enseignant['etablissement'].')";'." \n";
	
	if ($n_ue>0) {
			
		$string.= '"Unité d\'enseignement";"UFR";"heures_cours";"ng_cours";"heures_TD";"ng_TD";"heures_TP";"ng_TP";"heures_colle";"ng_colles";"heures_terrain";"njours_terrain";"TOTAL"'."\n";
		
		while ($d_ue=mysql_fetch_array($r_ue)) {
			$string.='"'.$d_ue['intitule'].'";"'.$d_ue['ufr'].'";"'.$d_ue['heures_cours'].'";"'.$d_ue['ng_cours'].'";"'.$d_ue['heures_TD'].'";"'.$d_ue['ng_TD'].'";"'.$d_ue['heures_TP'].'";"'.$d_ue['ng_TP'].'";"'.$d_ue['heures_colle'].'";"'.$d_ue['ng_colles'].'";"'.$d_ue['heures_terrain'].'";"'.$d_ue['njours_terrain'].'";';
			$string.='"'.(1.5*$d_ue['heures_cours']*$d_ue['ng_cours']+$d_ue['heures_TD']*$d_ue['ng_TD']
					+$d_ue['heures_TP']*$d_ue['ng_TP']+$d_ue['heures_colle']*$d_ue['ng_colles']+$d_ue['heures_terrain']*$d_ue['njours_terrain']).'";'."\n";
			$total+=(1.5*$d_ue['heures_cours']*$d_ue['ng_cours']+$d_ue['heures_TD']*$d_ue['ng_TD']
					+$d_ue['heures_TP']*$d_ue['ng_TP']+$d_ue['heures_colle']*$d_ue['ng_colles']+$d_ue['heures_terrain']*$d_ue['njours_terrain']);
		}
		
	}

	$s_responsabilite="SELECT libelle, decharge_horaire FROM Responsabilites WHERE id_enseignant=".$d_enseignant['id_enseignant'];
	
	$r_responsabilite=mysql_query($s_responsabilite);
	$n_responsabilite=mysql_num_rows($r_responsabilite);
	if ($n_responsabilite>0) {
		$string.='"Responsabilité(s) supplémentaire(s)";'." \n";
		while ($d_resp=mysql_fetch_array($r_responsabilite)) {
			$string.='"'.trim($d_resp['libelle']).'";"";"";"";"";"";"";"";"";"";"";"";"'.$d_resp['decharge_horaire']."\" \n";
			$total+=$d_resp['decharge_horaire'];
		}
	}
	
	$s_hors_maquette="SELECT HM.libelle, EHM.decharge, EHM.n_etudiant 
					FROM l_enseignant_hors_maquette EHM
					INNER JOIN a_hors_maquette HM
						ON HM.id_hors_maquette=EHM.id_hors_maquette
					WHERE EHM.id_annee_scolaire=".$id_annee_scolaire." AND id_enseignant=".$d_enseignant['id_enseignant'];
	$r_hors_maquette=mysql_query($s_hors_maquette);
	$n_hors_maquette=mysql_num_rows($r_hors_maquette);
	if ($n_hors_maquette>0) {
		$string.='"Activités hors maquette";'." \n";
		while ($d_hors_maquette=mysql_fetch_array($r_hors_maquette)) {
			$string.='"'.trim($d_hors_maquette['libelle']).'";"";"";"";"";"";"";"";"";"";"";"";"'.($d_hors_maquette['decharge']*$d_hors_maquette['n_etudiant'])."\" \n";
			$total+=($d_hors_maquette['decharge']*$d_hors_maquette['n_etudiant']);
		}
	}
	if ($total>0) {
		$string.='"TOTAL DES HEURES";"";"";"";"";"";"";"";"";"";"";"";"'.$total."\" \n";
	}
	$string.=" \n";
}

$fname='service_enseignant_'.date('Ymd').'.csv';
header("Content-Type: text/csv");
header('Content-disposition: filename="'.$fname.'"');

echo utf8_decode($string);

<?php
$s_etudiants="SELECT E.id_etudiant, CONCAT(E.nom,' ',E.prenom) AS nom_prenom, NI.libelle AS niveau, SP.libelle AS specialite, E.email_ipgp FROM Etudiants E
				INNER JOIN l_parcours_etudiant P
					ON P.id_etudiant=E.id_etudiant
					AND P.id_annee_scolaire=".$id_annee_scolaire."
					AND P.id_niveau IN (2,5,6,7)
				INNER JOIN a_niveau NI
					ON NI.id_niveau=P.id_niveau
				INNER JOIN a_specialite SP
					ON SP.id_specialite=P.id_specialite
				WHERE E.id_etudiant NOT IN (
					SELECT SL.id_etudiant FROM Stages_Laboratoires SL
					INNER JOIN l_ouverture_stage OS
						ON SL.id_stage_laboratoire=OS.id_stage
						AND OS.id_type_stage=0
						AND id_annee_scolaire=".$id_annee_scolaire."
						AND ouvert=1
					GROUP BY SL.id_stage_laboratoire
					UNION 
					SELECT SE.id_etudiant FROM Stages_Entreprises SE
					INNER JOIN l_ouverture_stage OS
						ON SE.id_stage_entreprise=OS.id_stage
						AND OS.id_type_stage=1
						AND id_annee_scolaire=".$id_annee_scolaire."
						AND ouvert=1
					GROUP BY SE.id_stage_entreprise)
				ORDER BY NI.libelle, E.nom, E.prenom";
$r_etudiants=mysql_query($s_etudiants)
	or die('Impossible de récupérer la listes des étudiants sans stage :<br/>'.mysql_error());

// Génération du fichier
$fname='etudiants_sans_stage_'.date('Ymd').'.csv';
header("Content-Type: text/csv");
header('Content-disposition: filename="'.$fname.'"');

$string_all=utf8_decode('id_etudiant; Étudiant; Niveau; Spécialité ; Email étudiant');	
$string_all .= "\n";

while ($d_etudiants=mysql_fetch_array($r_etudiants)) {
	foreach($d_etudiants AS $champ => $valeur) {
		if (!is_int($champ)) {
			$string_all.='"'.utf8_decode($valeur).'";';
		}
	}
	$string_all.="\n";
}
echo $string_all;
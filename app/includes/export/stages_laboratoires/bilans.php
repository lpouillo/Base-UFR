<?php
// Spécialités : Géophysique, Géochimie, Géomatériaux et Géologie et Risques Naturels	Nom 	Spécialité	Équipe / Laboratoire	
// Directeur de stage	co-directeurs 1	co-directeurs 2	Date de soutenance	Heure	Rapporteur 	Rapporteur	JURY	Email dir 1	Email dir 2	Email dir 3	Email Etudiant		
$s_stages="SELECT SL.id_stage_laboratoire, SL.sujet, CONCAT( E.nom, ' ', E.prenom ) AS etudiant, E.email_ipgp AS email_etudiant, NI.libelle AS niveau, SP.libelle AS specialite, L.nom AS laboratoire, 
			CONCAT( D.nom, ' ', D.prenom ) AS directeur, D.email_pro AS email_directeur, CONCAT(CD1.nom,' ',CD1.prenom) AS codirecteur1, CD1.email_pro AS email_codirecteur1,
			CONCAT(CD2.nom,' ',CD2.prenom) AS codirecteur2, CD2.email_pro AS email_codirecteur2
			FROM Stages_Laboratoires SL
			INNER JOIN l_ouverture_stage OS ON OS.id_stage = SL.id_stage_laboratoire
				AND OS.id_type_stage =0
				AND OS.id_annee_scolaire =".$id_annee_scolaire."
				AND OS.ouvert =1
			LEFT JOIN Etudiants E 
				ON E.id_etudiant = SL.id_etudiant
			LEFT JOIN l_parcours_etudiant P 
				ON P.id_etudiant = E.id_etudiant
				AND P.id_annee_scolaire =".$id_annee_scolaire."
			LEFT JOIN a_specialite SP 
				ON P.id_specialite = SP.id_specialite
			LEFT JOIN a_niveau NI
				ON P.id_niveau = NI.id_niveau
			LEFT JOIN l_encadrant_stage ES ON ES.id_stage = SL.id_stage_laboratoire
				AND ES.id_annee_scolaire =".$id_annee_scolaire."
				AND ES.id_type_encadrant =1
			LEFT JOIN Enseignants D 
				ON D.id_enseignant = ES.id_encadrant
			LEFT JOIN Laboratoires L 
				ON L.id_laboratoire = D.id_laboratoire
			LEFT JOIN l_encadrant_stage ES1
				ON ES1.id_stage=SL.id_stage_laboratoire
				AND ES1.id_annee_scolaire=".$id_annee_scolaire."
				AND ES1.id_type_encadrant=2
			LEFT JOIN Enseignants CD1
				ON CD1.id_enseignant=ES1.id_encadrant
			LEFT JOIN l_encadrant_stage ES2
				ON ES2.id_stage=SL.id_stage_laboratoire
				AND ES2.id_annee_scolaire=".$id_annee_scolaire."
				AND ES2.id_type_encadrant=3
			LEFT JOIN Enseignants CD2
				ON CD2.id_enseignant=ES2.id_encadrant
			GROUP BY SL.id_stage_laboratoire";
$r_stages=mysql_query($s_stages)
	or die('Erreur lors de la récupération des infos des stages : <br/>'.mysql_error());
	
// Génération du fichier
$fname='bilan_stages_'.date('Ymd').'.csv';
header("Content-Type: text/csv");
header('Content-disposition: filename="'.$fname.'"');

$string_all=utf8_decode('id_stage_laboratoire; Sujet; Étudiant; Email étudiant; Niveau ; Spécialité ; Laboratoire ; Directeur; Email directeur; Co-directeur 1; Email co-directeur 1; Co-directeur 2; Email co-directeur 2');	
$string_all .= "\n";
while ($d_stages=mysql_fetch_array($r_stages)) {
	foreach($d_stages AS $champ => $valeur) {
		if (!is_int($champ)) {
			$string_all.='"'.utf8_decode($valeur).'";';
		}
	}
	$string_all.="\n";
}

echo $string_all;
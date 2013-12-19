<?php
// Spécialités : Géophysique, Géochimie, Géomatériaux et Géologie et Risques Naturels	Nom 	Spécialité	Équipe / Laboratoire	
// Directeur de stage	co-directeurs 1	co-directeurs 2	Date de soutenance	Heure	Rapporteur 	Rapporteur	JURY	Email dir 1	Email dir 2	Email dir 3	Email Etudiant		
$s_stages="SELECT SE.id_stage_entreprise, CONCAT(T1.nom,' ',T1.prenom) as tuteur1, CONCAT(T2.nom,' ',T2.prenom) as tuteur2, NI.libelle AS niveau, SP.libelle AS specialite, 
			CONCAT( E.nom, ' ', E.prenom ) AS etudiant, E.email_ipgp, E.email_perso, E.telephone_mobile, 
			ENT.nom AS entreprise, CONCAT(ENT.adresse,' ',ENT.code_postal,' ',VI.libelle), CONCAT(M.nom,' ',M.prenom) AS Maitre, M.email AS email_Maitre, M.telephone AS telephone_maitre, 
			F.libelle AS fonction,
			SE.sujet 
			FROM Stages_Entreprises SE
			INNER JOIN l_ouverture_stage OS ON OS.id_stage = SE.id_stage_entreprise
				AND OS.id_type_stage=1
				AND OS.id_annee_scolaire =".$id_annee_scolaire."
				AND OS.ouvert=1
			LEFT JOIN Etudiants E 
				ON E.id_etudiant = SE.id_etudiant
			LEFT JOIN l_parcours_etudiant P 
				ON P.id_etudiant = E.id_etudiant
				AND P.id_annee_scolaire =".$id_annee_scolaire."
			LEFT JOIN a_specialite SP 
				ON P.id_specialite = SP.id_specialite
			LEFT JOIN a_niveau NI
				ON P.id_niveau = NI.id_niveau
			LEFT JOIN l_encadrant_stage ES7 
				ON ES7.id_stage = SE.id_stage_entreprise
				AND ES7.id_annee_scolaire =".$id_annee_scolaire."
				AND ES7.id_type_encadrant=7
			LEFT JOIN Enseignants T1
				ON ES7.id_encadrant=T1.id_enseignant
			LEFT JOIN l_encadrant_stage ES8 ON ES8.id_stage = SE.id_stage_entreprise
				AND ES8.id_annee_scolaire =".$id_annee_scolaire."
				AND ES8.id_type_encadrant=8
			LEFT JOIN Enseignants T2
				ON ES8.id_encadrant=T2.id_enseignant
			LEFT JOIN Entreprises ENT 
				ON ENT.id_entreprise = SE.id_entreprise
			LEFT JOIN a_ville VI
				ON VI.id_ville=ENT.id_ville
			LEFT JOIN l_encadrant_stage ES5
				ON ES5.id_stage=SE.id_stage_entreprise
				AND ES5.id_annee_scolaire=".$id_annee_scolaire."
				AND ES5.id_type_encadrant=5
			LEFT JOIN Professionnels M
				ON ES5.id_encadrant=M.id_professionnel
			LEFT JOIN a_fonction F
				ON M.id_fonction=F.id_fonction
			GROUP BY SE.id_stage_entreprise";
//echo $s_stages;

$r_stages=mysql_query($s_stages)
	or die('Erreur lors de la récupération des infos des stages : <br/>'.mysql_error());
	
// Génération du fichier
$fname='bilan_stagespro_'.date('Ymd').'.csv';
header("Content-Type: text/csv");
header('Content-disposition: filename="'.$fname.'"');

$string_all=utf8_decode('"id_stage_entreprise";"Tuteur 1";"Tuteur 2";"Niveau";"Spécialité";"Étudiant";"Email IPGP";"Email perso";"Téléphone mobile";"Entreprise";"Adresse";"Maitre de stage";"Email Maitre";"Téléphone Maitre";"Fonction";"sujet"');	
$string_all .= "\n";
while ($d_stages=mysql_fetch_array($r_stages)) {
	/*echo '<pre>';
	print_r($d_stages);
	echo '/pre>';*/
	foreach($d_stages AS $champ => $valeur) {
		if (!is_int($champ)) {
			$string_all.='"'.utf8_decode($valeur).'";';
		}
	}
	$string_all.="\n";
}

echo $string_all;

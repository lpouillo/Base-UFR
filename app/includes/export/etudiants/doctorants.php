<?php
$s_docto="SELECT P.id_etudiant 
			FROM l_parcours_etudiant P
			INNER JOIN a_niveau NI
				ON NI.id_niveau=P.id_niveau
			INNER JOIN Etudiants E
				ON E.id_etudiant=P.id_etudiant
			WHERE P.id_annee_scolaire=".$id_annee_scolaire." AND NI.abbreviation IN ('D1','D2','D3','D4','D5','D6')
			ORDER BY E.nom";
$r_docto=mysql_query($s_docto)
	or die(mysql_error());
$string='"Nom";"Prénom";"Année universitaire";"Niveau";"Spécialité";"Établissement";'."\n";
while ($d_docto=mysql_fetch_array($r_docto)) {
	$s_parcours="SELECT E.nom AS Nom, E.prenom AS Prénom, CONCAT(A.annee_debut,'-',A.annee_debut+1) AS `Année universitaire`, NI.libelle AS Niveau, SP.libelle AS Spécialité, ET.nom AS Établissement 
				FROM l_parcours_etudiant P
				INNER JOIN Etudiants E
					ON P.id_etudiant=E.id_etudiant
				INNER JOIN a_annee_scolaire A 
					ON P.id_annee_scolaire=A.id_annee_scolaire
				INNER JOIN a_niveau NI
					ON P.id_niveau=NI.id_niveau
				INNER JOIN a_specialite SP
					ON P.id_specialite=SP.id_specialite
				INNER JOIN Etablissements ET
					ON P.id_etablissement=ET.id_etablissement
				WHERE P.id_etudiant=".$d_docto['id_etudiant']."
				ORDER BY A.annee_debut DESC";
	$r_parcours=mysql_query($s_parcours);
	while ($d_parcours=mysql_fetch_array($r_parcours)) {
		foreach ($d_parcours as $champ => $valeur) {
			if (!is_int($champ)) {
				$string.='"'.$valeur.'";';
			}	
		}
		$string.="\n";
	}
	$string.="\n";
}
$fname='Doctorants_'.date('Ymd').'.csv';
header("Content-Type: text/csv");
header('Content-disposition: filename="'.$fname.'"');

echo utf8_decode($string);

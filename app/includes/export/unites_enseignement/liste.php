<?php
$titre='Liste des étudiants';
$fname='etudiants';
$sql="SELECT E.id_etudiant, CONCAT(E.nom,' ',E.prenom) AS nom_prenom, E.email_ipgp, 
			CONCAT(NNOW.libelle,' ',SNOW.libelle) AS Parcours_actuel, 
			NPAST.libelle AS niveau_past, SPAST.libelle AS spec_past, EPAST.nom AS etab_past
			FROM Etudiants E
			LEFT JOIN l_parcours_etudiant PNOW ON PNOW.id_etudiant = E.id_etudiant
				AND PNOW.id_annee_scolaire =".$id_annee_scolaire."
			LEFT JOIN a_specialite SNOW ON SNOW.id_specialite = PNOW.id_specialite
			LEFT JOIN a_niveau NNOW ON NNOW.id_niveau = PNOW.id_niveau
			LEFT JOIN l_parcours_etudiant PPAST ON PPAST.id_etudiant = E.id_etudiant
				AND PPAST.id_annee_scolaire =".($id_annee_scolaire-1)."
			LEFT JOIN a_specialite SPAST ON SPAST.id_specialite = PPAST.id_specialite
			LEFT JOIN a_niveau NPAST ON NPAST.id_niveau = PPAST.id_niveau
			LEFT JOIN Etablissements EPAST ON EPAST.id_etablissement = PPAST.id_etablissement
			WHERE E.id_etudiant
			IN (
				SELECT id_etudiant
				FROM l_etudiant_ue
				WHERE id_ue=".$id."
				AND id_annee_scolaire=".$id_annee_scolaire."
				)	
			ORDER BY E.nom";
// echo $sql;
$result=mysql_query($sql)
	or die(mysql_error());
$champs=array('id_etudiant','Nom Prénom','Email IPGP','Parcours actuel','Parcours précédent');
while ($data=mysql_fetch_array($result)) {
	$donnees[]=$data;
} 


$string_all='';	
$k_champ=0;
foreach ($champs as $champ) {	
	$string.='"'.$champ.'";';	
	$k_champ++;
}

$string_all .= $string."\n";
for($i_donnee=0;$i_donnee<sizeof($donnees);$i_donnee++) {
	$string='"'.$donnees[$i_donnee]['id_etudiant'].'";"'.$donnees[$i_donnee]['nom_prenom'].'";"'.$donnees[$i_donnee]['email_ipgp'].'";"'.$donnees[$i_donnee]['Parcours_actuel'].'";"'.$donnees[$i_donnee]['niveau_past'].' '.$donnees[$i_donnee]['spec_past'].' ('.$donnees[$i_donnee]['etab_past'].')" ';
		
	$string_all .= substr($string,0,-1)."\n";
}

if ($complet) {
	$fname=$fname.'_'.date('Ymd').'.csv';
	header("Content-Type: text/csv");
	header('Content-disposition: filename="'.$fname.'"');
	echo utf8_decode($string_all);
}

?>

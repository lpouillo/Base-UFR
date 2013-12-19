<?php 
$s_stages="SELECT CONCAT(P.nom,' ',P.prenom) AS professionnel, GROUP_CONCAT(' ',TE.libelle) AS type_encadrant,
		 S.sujet, CONCAT(E.nom,' ',E.prenom) AS etudiant, A.libelle AS annee
		FROM Stages_Entreprises S
		INNER JOIN l_encadrant_stage ES
			ON ES.id_stage=S.id_stage_entreprise
			AND ES.id_type_encadrant IN (4,5,6)
		INNER JOIN Professionnels P
			ON P.id_professionnel=ES.id_encadrant
			AND P.id_entreprise=".$id."
		INNER JOIN a_type_encadrant TE
			ON ES.id_type_encadrant=TE.id_type_encadrant
		LEFT JOIN Etudiants E
			ON E.id_etudiant=S.id_etudiant
		INNER JOIN a_annee_scolaire A
			ON ES.id_annee_scolaire=A.id_annee_scolaire
		GROUP BY S.sujet 
		ORDER BY A.libelle DESC, S.sujet";
$stages=recuperation_donnees($s_stages);
if (sizeof($stages)>0) {
	$html.='<table class="table_sel">
				<tr>
					<th width="25px">Modifier</th><th>Sujet</th><th>Professionnel</th><th>Année</th><th>Étudiant</th>
				</tr>';
			
	foreach ($stages as $stage) {
		$html.='<tr>
				<td class="td_selection"><img src="public/images/icons/modifier.png"/></td>
				<td>'.$stage['sujet'].'</td>
				<td>'.$stage['professionnel'].'</td>
				<td>'.$stage['annee'].'</td>
				<td>'.$stage['etudiant'].'</td>
			</tr>';
	}
	$html.='</table>';
} else {
	$html.='<p>Aucun stage trouvé pour cette entreprise</p>';
}
?>
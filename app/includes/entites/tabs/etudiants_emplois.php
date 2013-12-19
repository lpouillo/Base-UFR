<?
$emplois=recuperation_donnees("SELECT E.id_emploi, E.libelle, E.date_debut, E.date_fin
	FROM Emplois E
	INNER JOIN l_etudiant_emploi EE
		ON EE.id_etudiant=".$id." 
		AND E.id_emploi=EE.id_emploi");

$add=($mode=='rw')?'<a href="#" onClick="popupForm(\'ajouter_emploi\')">Ajouter un nouvel élément</a>':'';
if (sizeof($emplois)==0) {
	$html.='<p>Aucune donnée sur cette étudiant.</p>
	<p>'.$add.'</p>';
} else {
	$html.='<p>'.$add.'</a></p>
		<table class="table_sel"><tr>
			<th>Détails</th><th>Année</th><th>Maître de stage</th><th>Entreprise</th><th>Sujet</th></tr>';
	foreach($stage_entrep as $k_stage => $stage) {
		$html.='<tr>
				<td><img src="public/images/icons/voir.png"/></td>
				<td>'.$stage['annee'].'</td><td>'.$stage['maitre'].'</td><td>'.$stage['entreprise'].'</td><td>'.$stage['sujet'].'</td></tr>';
	}
	$html.='</table>';
}

?>

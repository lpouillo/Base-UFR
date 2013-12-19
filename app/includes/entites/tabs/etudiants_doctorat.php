<?
$doctorats=recuperation_donnees("SELECT id_doctorat, titre, sujet, date_debut, date_fin
	FROM Doctorats
	WHERE id_etudiant=".$id);
$add=($mode=='rw')?'<a href="#" onClick="popupForm(\'ajouter_doctorat\')">Ajouter un nouvel élément</a>':'';
if (sizeof($doctorats)==0) {
	$html.='<p>Aucune donnée sur cette étudiant.</p>
	<p>'.$add.'</p>';
} else {
	$html.='<p>'.$add.'</p>
		<table class="table_sel"><tr>
			<th>Détails</th><th>Année</th><th>Maître de stage</th><th>Entreprise</th><th>Sujet</th></tr>';
	foreach($stage_entrep as $k_stage => $stage) {
		$html.='<tr>
				<td><img src="public/images/icons/voir.png"/></td>
				<td>'.$stage['titre'].'</td><td>'.$stage['sujet'].'</td><td>'.$stage['date_debut'].'</td><td>'.$stage['date_fin'].'</td></tr>';
	}
	$html.='</table>';
}

?>

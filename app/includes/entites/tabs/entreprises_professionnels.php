<?php 
$s_pro="SELECT CONCAT(P.nom,' ',P.prenom) AS professionnel, F.libelle AS fonction
		FROM Professionnels P
		LEFT JOIN a_fonction F
			ON P.id_fonction=F.id_fonction
		WHERE P.id_entreprise=".$id."
		ORDER BY P.nom";
$professionnels=recuperation_donnees($s_pro);
if (sizeof($professionnels)>0) {
	$html.='<table class="table_sel">
				<tr>
					<th width="25px">Modifier</th><th >Professionnel</th><th>Fonction</th>
				</tr>';
			
	foreach ($professionnels as $pro) {
		$html.='<tr>
				<td class="td_selection"><img src="public/images/icons/modifier.png"/></td>
				<td>'.$pro['professionnel'].'</td>
				<td>'.$pro['fonction'].'</td>
			</tr>';
	}
	$html.='</table>';
} else {
	$html.='<p>Aucun professionnel trouvé pour cette entreprise</p>';
}
?>
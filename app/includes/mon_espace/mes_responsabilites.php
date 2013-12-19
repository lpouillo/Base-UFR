<?php
$html.='<h2><img src="public/images/icons/responsabilite.png"/>Mes responsabilités</h2>
<div class="content_tab">';

	
$responsabilites=recuperation_donnees("SELECT id_responsabilite, libelle, decharge_horaire FROM Responsabilites
		WHERE id_enseignant=".$_SESSION['id_link']);
if (sizeof($responsabilites)>0) {
	$html.='<p>Voici la liste de vos responsabilités. N\'hésitez pas à demander des outils spécifiques associés à votre responsabilité.</p>';
} else {
	$html.='<p>Vous n\'avez pas responsabilité spécifique au sein de l\'UFR STEP.</p>';
}
foreach ($responsabilites as $responsabilite) {
	$html.='<h3>'.$responsabilite['libelle'].'</h3>
		<p>décharge horaire : '.$responsabilite['decharge_horaire'].'h</p>';
}
$html.='</div>';
?>

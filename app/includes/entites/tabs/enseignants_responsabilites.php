<?
$responsabilites=recuperation_donnees("SELECT id_responsabilite, libelle, decharge_horaire FROM Responsabilites
		WHERE id_enseignant=".$id);
if (sizeof($responsabilites)>0) {
	$html.='<p>Voici la liste des responsabilités de l\'enseignant.</p>';
} else {
	$html.='<p>Cet enseignant n\'a aucune responsabilité spécifique au sein de l\'UFR STEP.</p>';
		
}
if ($mode=='rw') {
	$html.='<p><a href="#" onClick="popupForm(\'ajout_responsabilite\')">Ajouter une responsabilité </a></p>';
}
foreach ($responsabilites as $responsabilite) {
	$html.='<h3>'.$responsabilite['libelle'].'</h3>
		<p>décharge horaire : '.$responsabilite['decharge_horaire'].'h</p>';
}
?>

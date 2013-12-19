<?php
$id_etudiant=$_POST['id'];
// Récupération et affichage des stages en laboratoire de l'étudiant
$s_stage_lab="SELECT SL.id_stage_laboratoire, SL.sujet, CONCAT( ENS.nom, ' ', ENS.prenom ) AS encadrant,
		LAB.nom AS equipe1, A.libelle AS annee
		FROM Stages_Laboratoires SL
		INNER JOIN l_encadrant_stage ES 
			ON ES.id_stage = SL.id_stage_laboratoire
		INNER JOIN Enseignants ENS 
			ON ENS.id_enseignant = ES.id_encadrant
			AND ES.id_type_encadrant=1
		LEFT JOIN Laboratoires LAB 
			ON ENS.id_laboratoire=LAB.id_laboratoire
		INNER JOIN l_ouverture_stage OS 
			ON OS.id_stage=SL.id_stage_laboratoire
			AND ouvert=1
		INNER JOIN a_annee_scolaire A
			ON OS.id_annee_scolaire=A.id_annee_scolaire			
			WHERE SL.id_etudiant =".$id_etudiant."
		ORDER BY A.libelle DESC"; 

$r_stage_lab=mysql_query($s_stage_lab)
	or die('Impossible de récupérer les infos des stages en laboratoires de l\'étudiant :<br/>'.mysql_error());

$stage_lab=array();
while ($d_stage_lab=mysql_fetch_array($r_stage_lab)) {
	$stage_lab[$d_stage_lab['id_stage_laboratoire']]=array(
														'annee' => $d_stage_lab['annee'],
														'encadrant' => $d_stage_lab['encadrant'],
														'equipe1' => $d_stage_lab['equipe1'],
														'sujet' => $d_stage_lab['sujet']);
}
$html.='<h3><img src="public/images/icons/stage_laboratoire.png"/> Stages en laboratoires</h3>';
$add=($mode=='rw')?'<a href="#" onClick="popupForm(\'ajout_stage_labo\')">Ajouter un nouveau stage</a>':'';
	
if (sizeof($stage_lab)==0) {
	$html.='<p>Aucun stage de laboratoire n\'a été trouvé pour cet étudiant</p>
		<p>'.$add.'</p>';
} else {
	$html.='
	<p>'.$add.'</p><table class="table_sel"><tr>
			<th>Détails</th><th>Année</th><th>Encadrant principal</th><th>Equipe d\'accueil</th><th>Sujet</th></tr>';
	foreach($stage_lab as $k_stage => $stage) {
		$html.='<tr>
				<td class="td_selection"><img onclick="affElement(\''.$k_stage.'\',\'entites\',\'stages_laboratoires\',\'voir\',\'content\');" src="public/images/icons/voir.png"/></td>
				<td>'.$stage['annee'].'</td><td>'.$stage['encadrant'].'</td><td>'.$stage['equipe1'].'</td><td>'.$stage['sujet'].'</td></tr>';
	}
	$html.='</table>';
}

// Récupération et affichage des stages en entreprise de l'étudiant
$s_stage_entrep="SELECT SE.id_stage_entreprise, SE.sujet,
		ENT.nom AS entreprise, CONCAT(P.nom,' ',P.prenom) AS maitre, A.libelle AS annee
		FROM Stages_Entreprises SE
		LEFT JOIN Entreprises ENT
			ON ENT.id_entreprise=SE.id_entreprise
		LEFT JOIN l_encadrant_stage ES
			ON SE.id_stage_entreprise=ES.id_stage
			AND ES.id_type_encadrant=5
		LEFT JOIN Professionnels P
			ON ES.id_encadrant=P.id_professionnel
		LEFT JOIN l_ouverture_stage OS
			ON OS.id_stage=SE.id_stage_entreprise
			AND OS.ouvert=1
		LEFT JOIN a_annee_scolaire A
			ON OS.id_annee_scolaire=A.id_annee_scolaire
		WHERE id_etudiant=".$id_etudiant."
		ORDER BY A.libelle DESC";
		
$r_stage_entrep=mysql_query($s_stage_entrep);
$stage_entrep=array();
while ($d_stage_entrep=mysql_fetch_array($r_stage_entrep)) {
	$stage_entrep[$d_stage_entrep['id_stage_entreprise']]=array(
															'annee' => $d_stage_entrep['annee'],
															'maitre' => $d_stage_entrep['maitre'],
															'entreprise' =>$d_stage_entrep['entreprise'],
															'sujet' => $d_stage_entrep['sujet']);
}
$html.='<h3><img src="public/images/icons/stage_entreprise.png"> Stages en entreprise</h3>';

$add=($mode=='rw')?'<a href="#" onClick="popupForm(\'ajout_stage_labo\')">Ajouter un nouveau stage</a>':'';
if (sizeof($stage_entrep)==0) {
	$html.='<p>Aucun stage de entreprise n\'a été trouvé pour cet étudiant.</p>
		<p>'.$add.'</p>';
} else {
	$html.='
	<p>'.$add.'</p>
	<table class="table_sel"><tr>
			<th>Détails</th><th>Année</th><th>Maître de stage</th><th>Entreprise</th><th>Sujet</th></tr>';
	foreach($stage_entrep as $k_stage => $stage) {
		$html.='<tr>
				<td class="td_selection"><img onclick="affElement(\''.$k_stage.'\',\'entites\',\'stages_entreprises\',\'voir\',\'content\');" src="public/images/icons/voir.png"/></td>
				<td>'.$stage['annee'].'</td><td>'.$stage['maitre'].'</td><td>'.$stage['entreprise'].'</td><td>'.$stage['sujet'].'</td></tr>';
	}
	$html.='</table>';
}
// Récupération et affichage des cas d'études de l'étudiant
?>

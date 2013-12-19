<?php

$html.='<h3>Mise à jour de vos horaires effectuée</h3>
		<p><a href="#" onclick="cancelPopupForm()">Fermez cette fenêtre</a></p>';
$tmp_heures=explode('-',$_POST['action']);
$id_enseignant=$tmp_heures[0];
$id_ue=$tmp_heures[1];
$heures=array();
foreach($tmp_heures as $key => $heure) {
	$tmp=explode('%',$heure);
	$type=substr($tmp[0],strpos($tmp[0],'][')+2,-1);
	$nb=$tmp[1];
	$heures[$type]=$nb;
}
$sql_update="UPDATE l_enseignant_ue SET 
		heures_cours='".$heures['cours']."', heures_TD='".$heures['TD']."', heures_TP='".$heures['TP']."',
		heures_colle='".$heures['colle']."', heures_terrain='".$heures['terrain']."',
		ng_cours='".$heures['ng_cours']."', ng_TD='".$heures['ng_TD']."', ng_TP='".$heures['ng_TP']."', 
		ng_colles='".$heures['ng_colles']."', njours_terrain='".$heures['njours_terrain']."',
		`evolution_n+1`='".$heures['evolution']."'
		WHERE id_enseignant=".$id_enseignant." AND id_ue=".$id_ue." AND id_annee_scolaire=".$id_annee_scolaire;

mysql_query($sql_update)
	or die(mysql_error());
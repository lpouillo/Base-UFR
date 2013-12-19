<?php
$ids=explode('%',$_POST['id']);
$s_ue="SELECT intitule FROM Unites_Enseignement WHERE id_ue='".$ids[0]."'";
$r_ue=mysql_query($s_ue);
$d_ue=mysql_fetch_array($r_ue);

$s_intervenant="SELECT CONCAT(nom,' ',prenom) AS intervenant FROM Enseignants WHERE id_enseignant='".$ids[1]."'";
$r_intervenant=mysql_query($s_intervenant);
$d_intervenant=mysql_fetch_array($r_intervenant);
$html.='<img src="public/images/icons/danger.png" alt="ATTENTION"> Vous allez supprimer <strong>'.$d_intervenant['intervenant'].'</strong> 
	de l\'unité d\'enseignement <em>'.$d_ue['intitule'].'</em>.
	<input type="hidden" name="id_ue" value="'.$ids[0].'"/>
	<input type="hidden" name="id_intervenant" value="'.$ids[1].'"/>';

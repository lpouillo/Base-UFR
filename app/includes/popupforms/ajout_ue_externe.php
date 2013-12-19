<?php
if (empty($_POST['form_submitted'])) {
	$html='<h3>Ajout d\'une UE externe</h3>
		<form id="ajout_ue_externe" target="if_ajout_ue_externe" method="post" enctype="multipart/form-data">';
		
	require_once('app/includes/entites/tabs/unites_enseignement_infos.php');
	$html.='<a href="#" onclick="cancelPopupForm()">Annuler</a>
		
		</form>
		<iframe name="if_ajout_ue_externe" style="display:none;" src="app/pages/blank.html">';
	echo $html;
} else {
	$s_insert_ue="INSERT INTO Unites_Enseignement (`date_in`,`intitule`,`id_ufr`) VALUES (CURDATE(),'".$_POST['intitule']."','".$_POST['id_ufr']."')";
	echo $s_insert_ue;
	$r_insert_ue=mysql_query($s_insert_ue)
		or die(mysql_error());
	$id_ue=mysql_insert_id();
	if ($_SESSION['group']=='enseignant') {
		$to      = 'base-ufr@ipgp.fr';
		$subject = '[Base UFR] MODIFICATION : '.$_SESSION['login'].' a déclaré une nouvelle UE ';
		$message='';
		foreach ($_POST AS $key => $element) {
			$message .= $key.' : '.$element.'<br/>';	
		}
		$headers = 'From: base-ufr@ipgp.fr' . "\r\n" .
		    	    'X-Mailer: PHP/' . phpversion();
		$headers .= 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		
		
		mail($to, $subject, $message, $headers);
	}
	
	
	$s_encadrant="REPLACE INTO l_enseignant_ue (`id_ue`,`id_enseignant`,`id_situation`,`id_annee_scolaire`) 
					VALUES ('".$id_ue."','".$_SESSION['id_link']."','21','".$id_annee_scolaire."')";
	$r_encadrant=mysql_query($s_encadrant)
		or die(mysql_error());
		
	$html='<script>
			parent.cancelPopupForm();
			parent.document.location.href=\'index.php?page=mon_espace&section=mes_cours\';
		</script>';
	echo $html;	
}

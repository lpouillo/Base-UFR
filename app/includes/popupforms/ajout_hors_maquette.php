<?php
if (empty($_POST['form_submitted'])) {
	$html='<h3>Ajout d\'une activité hors maquette</h3>
		<form id="ajout_hors_maquette" target="if_ajout_hors_maquette" method="post">
			<select name="hors_maquette">';
	$s_hors_maquette="SELECT * FROM a_hors_maquette ORDER BY libelle";
	$r_hors_maquette=mysql_query($s_hors_maquette);
	while ($d_hors_maquette=mysql_fetch_array($r_hors_maquette)) {
		$html.='<option value="'.$d_hors_maquette['id_hors_maquette'].'">'.$d_hors_maquette['libelle'].'</option>';
	}
	$html.='</select><br/>
		<input type="submit" name="ajouter l\'UE"/>
		<a href="#" onclick="cancelPopupForm()">Annuler</a>
		<input type="hidden" name="page" value="popupform"/>
		<input type="hidden" name="id" value="ajout_hors_maquette"/>
		<input type="hidden" name="form_submitted" value="yes"/>
		</form>
		<iframe name="if_ajout_hors_maquette" style="display:none;" src="app/pages/blank.html">';
	echo $html;
} else {
	$s_insert_hm="INSERT INTO l_enseignant_hors_maquette (`id_enseignant`,`id_hors_maquette`,`id_annee_scolaire`) VALUES ('".$_SESSION['id_link']."','".$_POST['hors_maquette']."','".$id_annee_scolaire."')";

	$r_insert_hm=mysql_query($s_insert_hm);
	if ($_SESSION['group']=='enseignant') {
		$to      = 'base-ufr@ipgp.fr';
		$subject = '[Base UFR] MODIFICATION : '.$_SESSION['login'].' a déclaré une nouvelle activité hors maquette ';
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
	
	
		
	$html='<script>
			parent.cancelPopupForm();
			parent.document.location.href=\'index.php?page=mon_espace&section=mes_cours\';
		</script>';
	echo $html;	
}
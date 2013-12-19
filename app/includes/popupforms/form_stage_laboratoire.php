<?php 
if (empty($_POST['form_submitted'])) {
?>
	<h3><img src="public/images/icons/stage_laboratoire_ajouter.png"/> Ajouter un nouveau stage en laboratoire</h3>
	<form id="ajout_stage_labo" target="ajout_stage_labo" action="index.php?page=popupform" method="post">
	<?php
	require_once('app/classes/formulaire.class.php');
	$mes_infos = new formulaire('SELECT','Stages_Laboratoires',array('id_stage_laboratoire','date_in','date_modif','sujet'),array($_POST['id']),'','','rw','index.php','modifier');
	echo $mes_infos->html;
	?>
	<input type="hidden" name="id_directeur" value="<?php echo $_SESSION['id_link'];?>"/>
	<input type="hidden" name="form_submitted" value="oui"/> 
	<input type="hidden" name="id" value="<?php echo $_POST['id'];?>"/>
	<p style="text-align:center;"><input type="submit" value="Créer le stage"/> <a href="#" onclick="cancelPopupForm();">Annuler</a></p>
	</form>
	
	<iframe name="ajout_stage_labo" style="display: none;"  src="app/pages/blank.html">
<?php 
} else {
	require_once('app/classes/formulaire.class.php');
	$add_stage_labo = new data('INSERT','Stages_Laboratoires','','','','','');
	$add_stage_labo->execution_requete('ajouter');
	$id_new_stage_labo=mysql_insert_id();
	$s_responsable_stage="INSERT INTO l_encadrant_stage (`id_stage`,`id_encadrant`,`date_in`,`id_annee_scolaire`,`id_type_encadrant`) VALUES
			('".$id_new_stage_labo."','".$_SESSION['id_link']."',CURDATE(),10,1)";	
	
	$r_responsable_stage=mysql_query($s_responsable_stage) or die('impossible d ajouter les encadrant stage');

	if ($_SESSION['group']=='enseignant') {
		$to      = 'base-ufr@ipgp.fr';
		$subject = '[Base UFR] MODIFICATION : '.$_SESSION['login'].' a fait : '.$modification;
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
?>
	
	<script>
		parent.cancelPopupForm();
		parent.document.location.href='index.php?page=mon_espace&section=mes_stages';
	</script>
<?php 	
}
?>
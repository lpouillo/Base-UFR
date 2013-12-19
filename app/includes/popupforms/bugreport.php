<?php
$html.='
	<p> Type de bug :
	<input type="radio" name="type_bug" value="affichage" checked="checked"/> Affichage 
	<input type="radio" name="type_bug" value="donnees"/> Données  
	<input type="radio" name="type_bug" value="autre"/> Autre
	</p>
	<textarea name="descriptif" cols="60" rows="10">Descriptif du bug</textarea>
	<input type="hidden" name="form_submitted" value="oui"/>';
	
	
/*
} else {
	echo '<pre>';
	print_r($_POST);
	echo '</pre>';
	$s_bug="INSERT INTO s_bug (`id_bug`,`date_in`,`login`,`type_bug`,`descriptif`) 
				VALUES ('',CURDATE(),'".$_SESSION['login']."','".secure_mysql($_POST['type_bug'])."','".secure_mysql($_POST['descriptif'])."')";
	echo $s_bug;
	$r_bug=mysql_query($s_bug)
		or die(mysql_error());
	
	$to      = 'pouillou@ipgp.fr';
	$subject = '[Base UFR] BUG : '.$_SESSION['login'].' a soumis un bug';
	$message = $_POST['descriptif'];
	$headers = 'From: base-ufr@ipgp.fr' . "\r\n" .
	    	    'X-Mailer: PHP/' . phpversion();
	$headers .= 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
	
	mail($to, $subject, $message, $headers);
		
		

	?>
	<script>
		parent.cancelPopupForm();
		parent.document.location.href='index.php?page=mon_espace';
	</script>
<?php 	
}*/
?>

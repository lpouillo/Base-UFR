<div class="content_tab">
<?php
if (empty($_POST['type_demande'])) {
?>	
	<form method="post" action="#">
	<table border="0" id="form_contact">
		<tr>
			<td colspan="2"><p>Vous pouvez envoyer un message en remplissant ce formulaire :</p></td>
		</tr>
		<tr>
			<th>Nom</th>
			<td>
	<?php 
	if (isset($_SESSION['id_user'])) {
		echo $_SESSION['login'].'<input type="hidden" name="login" value="'.$_SESSION['login'].'"/>';	
	} else {
		echo '<input type="text" name="login"/>';
	}
	?>		
			</td>
		</tr>
		<tr>
			<th>Email</th>
			<td>
	<?php 
	if (isset($_SESSION['id_user'])) {
		$s_email="SELECT email_pro FROM Enseignants WHERE id_enseignant=".$_SESSION['id_link'];
		$r_email=mysql_query($s_email);
		$d_email=mysql_fetch_array($r_email);
		echo $d_email['email_pro'].'<input type="hidden" name="email" value="'.$d_email['email_pro'].'"/>';
	} else {
		echo '<input type="text" name="email"/>';
	}
	?>
		</tr>
		<tr>
			<th>Type de demande</th>
			<td>
				<ul>
					<li><input type="radio" name="type_demande" value="bug" checked="checked"/> Rapporter un bug </li>
					<li><input type="radio" name="type_demande" value="fonctionnalités" />Demander de fonctionnalités</li>
					<li><input type="radio" name="type_demande" value="autre" /> Autre</li>
				</ul>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<textarea  name="message" cols="60" rows="15"></textarea>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="text-align: center;">
				<input type="submit" value="Envoyez mon message" />
			</td>
		</tr>
	</table>
	<input type="hidden" name="page" value="contact"/>
	<input type="hidden" name="force_template" value="yes"/>
	</form>
<?php 
} else {
	$s_insert_demande="INSERT INTO s_demandes (`id_demande`,`date_in`,`login`,`email`,`type_demande`,`message`) VALUES 
					('',CURDATE(),'".secure_mysql($_POST['login'])."','".secure_mysql($_POST['email'])."'
					,'".secure_mysql($_POST['type_demande'])."','".secure_mysql($_POST['message'])."')";
	$r_insert_demande=mysql_query($s_insert_demande)
	 	or die(mysql_error());
	$to .= $admin_email;
    // Sujet
    $subject = '[Base UFR] '.$_POST['login'].' a effectué une demande '.$_POST['type_demande'];

    // message
    $message = $_POST['message'];
    // Pour envoyer un mail HTML, l'en-tête Content-type doit être définifin
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'To:'.$to."\r\n";
	if (mail($to,$subject,$message,$headers)) {
		echo '<p>Le message a bien été envoyé aux administrateurs</p>';
	}
	
}
?>
</div>

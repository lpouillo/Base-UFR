<?php
/*
 * Created on 2 sept. 2008
 *
 * Page affichant le formulaire de connexion
 */

if (empty($_SESSION['id_user'])) {	
	echo '<div class="content_tab">
			<center>Vous devez être connecté pour accéder à ce site web.';
	if ($login_error) {
		echo '<center><img src="public/images/icons/danger.png"/> Mauvais login ou mot de passe';
	}
	?>
		<form action="index.php" method="post" name="form_login">
			<table cellspacing="10" cellpadding="3" border="0">
			<tr>
			<td>Login</td>
			<td><input type="text" name="login"/>
			</tr>
			<tr>
			<td>Password</td>
			<td><input type="password" name="password"/>
			</tr>
			<tr>
			<td align="right" colspan="2"><input type="submit" value="OK"/></td>
			</tr>
			</table>
		</form> 
		</center>
	</div>
	<?php
} else {
	echo '<center>Vous êtes actuellement déjà connecté avec le compte <strong>'.$_SESSION['login'].'</strong>. Cliquez 
		<a href="index.php?page=deconnexion">ici</a> pour vous déconnecter et pouvoir vous reconnecter sous un autre nom.</center>';
}
?>

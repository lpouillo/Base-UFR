<?php
switch($_SESSION['group']) {
	case 'admin': 
		$wpName='WikiSysop';
		$wpPassword='$serac*';
	break;
	default:
		die('Erreur de protection générale');
}
//echo $_SESSION['group'];
?>

<form id="frm_connexion_wikiadmin" target="wikiadminFrame" method="post"
	action="https://enseignant.ipgp.fr/wikiadmin/index.php?title=Sp%C3%A9cial:Connexion&amp;action=submitlogin&amp;type=login&amp;returnto=Accueil">
<input type="hidden" name="wpName" value="<?php echo $wpName?>"/>
<input type="hidden" name="wpPassword" value="<?php echo $wpPassword?>"/>
</form>
<iframe id="wikiadminFrame" name="wikiadminFrame" width="100%" height="1000px" style="border:0px solid red;" onload=""/>



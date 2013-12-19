<?php
switch($_SESSION['group']) {
	case 'enseignant': 
		$wpName='ipgp';
		$wpPassword='+ipgp-';
	break;
	case 'gestionnaire':
		$wpName='Gestionnaire';
		$wpPassword='++gestion--';
	break;
	case 'admin': 
		$wpName='WikiSysop';
		$wpPassword='$serac*';
	break;
}
//echo $_SESSION['group'];
?>

<form id="frm_connexion_wiki" target="wikiFrame" method="post"
	action="https://enseignant.ipgp.fr/wiki/index.php?title=Sp%C3%A9cial:Connexion&amp;action=submitlogin&amp;type=login&amp;returnto=Accueil">
<input type="hidden" name="wpName" value="<?php echo $wpName?>"/>
<input type="hidden" name="wpPassword" value="<?php echo $wpPassword?>"/>
</form>

<iframe id="wikiFrame" name="wikiFrame" width="100%" height="1000px" style="border:0px solid red;" onload=""/>



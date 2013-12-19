<?php
switch ($section) {
	case 'default':
		$html=default_accueil($menu['admin']);
	break;
	case 'connexions':
	case 'droits':
	case 'nettoyage':
	case 'bugs':
	case 'utilisateurs':
	case 'fichiers_prives':
	case 'responsabilites':
		include('app/includes/admin/'.$section.'.php');
	break;
	default:
	echo '<div class="content_tab">Cette section n\'est pas implémentée pour l\'instant ...</div>';
}

echo $html;
?>

<?php
switch ($section) {
	case 'default':
		$html=default_accueil($menu['gestion']);
	break;
	case 'outils':
	case 'scolarite':
	case 'update':
	case 'notes':
	case 'importation':
	case 'annonces':
		include('app/includes/gestion/'.$section.'.php');
	break;	
	case 'documentation':
		include('app/pages/'.$section.'.php');
	break;
	default:
	echo '<div class="content_tab">Cette section n\'est pas implémentée pour l\'instant ...</div>';
}

echo $html;
?>

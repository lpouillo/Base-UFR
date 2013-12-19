<?php
/*
 * Created on 4 sept. 2008
 *
 */ 
switch ($section) {
	case 'default':
		$html=default_accueil($menu['enseignant']);
	break;
	case 'mon_planning':
	case 'mes_doctorats':
	case 'mes_infos':
	case 'mes_cours':
	case 'mes_etudiants':
	case 'mes_stages':
	case 'mes_responsabilites';
		include('app/includes/mon_espace/'.$section.'.php');
	break;
	case 'documentation':
		include('app/pages/documentation.php');
	break;
	default:			
		$html='<div class="content_tab">Cette section n\'est pas implémentée pour l\'instant ...</div>';
		
}
echo $html;


?>

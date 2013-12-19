<?php 

switch ($nom_requete[2]) {
	case 'bilans':
		require_once('app/includes/export/stages_laboratoires/bilans.php');
	break;
}
?>

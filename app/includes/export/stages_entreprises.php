<?php 

switch ($nom_requete[2]) {
	case 'bilans':
		require_once('app/includes/export/stages_entreprises/bilans.php');
	break;
}
?>

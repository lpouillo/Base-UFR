<?
ini_set('memory_limit', '64M');

if (empty($_SESSION['id_user'])) {
	die ('Vous n\'êtes pas connecté. <a href="index.php?page=connexion">Connexion</a>');
}
if (isset($_SESSION['id_annee_scolaire'])) {
	$id_annee_scolaire=$_SESSION['id_annee_scolaire'];
}
$nom_requete=explode('_',$_GET['requete']);

require_once('app/classes/fpdf/fpdf.php');
require_once('app/classes/fpdf/fonctions.php');

if (in_array('vue',$nom_requete)) {
	switch($nom_requete[0]) {
		case 'unites':
			$section='unites_enseignement';
		break;
		case 'stages':
			if($nom_requete[1]=='laboratoires') {
				$section='stages_laboratoires';
			} else {
				$section='stages_entreprises';
			}
		break;
		default:
			$section=$nom_requete[0];
	}
	$sql=$_SESSION[$section.'_last'];
	
	require_once('app/includes/export/last.php');
	
} else {
	switch($nom_requete[0]) {
		case 'notes':
		case 'enseignants':
		case 'etudiants':
		case 'etablissements':
		case 'entreprises':
		case 'laboratoires':
		case 'professionnels':
			require_once('app/includes/export/'.$nom_requete[0].'.php');
		break;
		case 'unites':
			require_once('app/includes/export/unites_enseignement.php');
		break;
		case 'stages':
			if($nom_requete[1]=='laboratoires') {
				require_once('app/includes/export/stages_laboratoires.php');
			} else {
				require_once('app/includes/export/stages_entreprises.php');
			}
		break;
		case 'cas':
			require_once('app/includes/export/cas_etudes.php');
		break;
		default:
			die($_GET['requete'].' non implémenté');	
	}	
}
?>


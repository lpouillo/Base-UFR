<?
$niv=$nom_requete[1];
if ($niv=='licence') {
	require_once('app/includes/export/notes/licence.php');
} else {
	$spec=$nom_requete[2];
	require_once('app/includes/export/notes/master.php');	
}
?>

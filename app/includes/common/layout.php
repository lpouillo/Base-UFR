<?php 

if (empty($_POST['page']) or $_POST['force_template']=='yes') {
	// inclusion du top
	require_once ('app/inc/common/top.php');
}

if (empty($_POST['filtrage_soumis'])) {
	echo '<div id="log_requete" class="'.$log_request_class.'" style="float:left;">'.$log_request.'</div>';
	echo '<h1 id="titre_page">' .
			'<img height="14px" src="public/images/icons/'.$icone.'"/>&nbsp;&nbsp;';
	echo $message_page;
	echo ' </h1>';
}
// Récupération du contenu de la page
require_once ('app/pages/'.$page.'.php'); 



if (empty($_POST['page']) or $_POST['force_template']=='yes') {
	// inclusion du bottom
	require_once ('app/inc/common/bottom.php');
}


$fin = getmicrotime();
echo '<div id="temps_chargement">Charg. '.(round($fin-$debut, 5)*1000).'ms</div>';
?>

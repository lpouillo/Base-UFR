<?php
// Construction de la page 
switch($page) {
	case 'export':
	case 'popupform':
		require_once('app/pages/'.$page.'.php');
	break;
	default:
		header('Content-Type: text/html; charset=utf-8');
		if (empty($_POST['page']) or $_POST['force_template']=='yes') {
			require_once ('app/includes/common/top.php');
		}
		if (empty($_POST['filtrage_soumis'])) {
		?>
			<h1 id="titre_page"><img height="14px" src="public/images/icons/<?php echo $icone?>"/>&nbsp;&nbsp;
			<?php echo $message_page;?>
			</h1>
		<?
		}
		// Récupération du contenu de la page
		require_once ('app/pages/'.$page.'.php'); 
		if (empty($_POST['page']) or $_POST['force_template']=='yes') {
			require_once ('app/includes/common/bottom.php');
		}
		// Récupération du temps de génération de la page pour l'optimisation
		$fin = getmicrotime();
		echo '<div id="temps_chargement">Charg. '.(round($fin-$debut, 5)*1000).'ms</div>';
		// redirection vers l'accueil si page non trouvée ou deconnexion ou non autorisée
		switch ($page) {
			case 'not_found':
			case 'deconnexion':
			case 'non_autorise':
				echo '<div id="redirection" style="display:none;">Redirection</div>';
			break;
		} 
		echo '<div id="update_titre_page" style="display:none;">'.$titre_page.'</div>';
}
?>

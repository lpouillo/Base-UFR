<?php

//echo $section.' '.$action.' '.$id.' '.$_POST['active_tab'];
switch ($section) {
	case 'default':
		$html.=default_accueil($menu['entites']);
	break;
	case 'etudiants':
	case 'enseignants':
	case 'unites_enseignement':
	case 'stages_laboratoires':
	case 'stages_entreprises':
	case 'cas_etudes':
	case 'doctorats':
	case 'etablissements':
	case 'laboratoires':
	case 'entreprises':
	case 'professionnels':
	case 'emplois':
		// Récupération des paramètres spécifiques
		include('app/includes/entites/'.$section.'.php');

		if (empty($_POST['filtrage_soumis'])) {
			$html='<div id="content_tab" class="content_tab">';
		}
		
		$texte_bouton='Valider';
			
		switch($action) {
			case 'voir':
			case 'modifier':
			case 'ajouter':
			case 'supprimer':
				$id=$_POST['id'];
			break;
			default:
			$html.='<h2><img src="public/images/icons/'.$params['common']['icone_titre'].'.png"/> '.$params['common']['titre'].'</h2>
				<p>'.$params['liste']['message'].'</p>';
			$html.=creation_table($params['liste']['sql'],$params['liste']['champs'],$params['liste']['post_sql'],$mode);
		}
		switch($action) {
			case 'voir':
			case 'modifier':
			// Création de l'entête avec le select sur l'entité considérée
				$html.='<h2>
						<form id="switch" method="post" action="index.php">
						<input type="hidden" name="page" value="'.$_POST['page'].'"/>
						<input type="hidden" name="section" value="'.$_POST['section'].'"/>
						<input type="hidden" name="action" value="'.$_POST['action'].'"/>
						<img src="public/images/icons/'.$params['common']['icone_titre'].'.png"/> '
						.select_entites($params['element']['sql_select']).
						'</form></h2>';
			// Génération des onglets cliquables sous le select
				$html.='<ul id="tabs">
					<li id="retour_liste" onclick="affElement(\'0\',\'entites\',\''.$_POST['section'].'\',\'\',\'content\');">
					<img height="16px" src="public/images/icons/retour_liste.png" alt="Retour" title="Retourner à la liste de mes unités d\'enseignement"/></li>';
				if ($mode=='rw') {
					$html.='<li onclick="submitForm(\'update\');" id="bouton_sauver" style="padding:0px;margin:0px;">
						<input type="submit" value="'.$texte_bouton.'" style="font-size:9px;height:20px;padding:0px;position: relative; top: -2px;"/>';	
				} else {
			// pour compabitibilité avec le code javascript
					$html.='<li style="display:none;">&nbsp;</li>';
				}
			// 
				foreach ($params['element']['tabs'] as $id_tab => $data) {
					$html.='<li id="'.$id_tab.'" class="subtabs"><img height="13px" src="public/images/icons/'.$data['icon'].'.png"/> '.$data['text'].'</li>';
				}
				$html.='<li id="Outils" class="subtabs"><img height="13px" src="public/images/icons/outils.png"/> Outils</li>
					</ul>';
			
				$html .='<form id="update" method="post" action="index.php">
					<input type="hidden" name="modification_soumise" value="oui">
					<input type="hidden" name="page" value="entites">
					<input type="hidden" name="section" value="'.$_POST['section'].'">
					<input type="hidden" name="id" value="'.$id.'">
					<input type="hidden" name="action" value="'.$action.'">
					<input type="hidden" name="div_target" value="content">
					<input type="hidden" name="active_tab" value="">';

				foreach ($params['element']['tabs'] as $id_tab => $data) {
						$html.='<div id="content_'.$id_tab.'" class="content_tab hidden">';
						require_once('app/includes/entites/tabs/'.$section.'_'.$id_tab.'.php');	
						$html.='</div>';
				}
				$html.='<div id="content_Outils" class="content_tab hidden"><ul>';
				foreach($params['element']['outils'] as $outil => $data) {
					$html.='<li onClick="genererFichier(\''.$_POST['section'].'_'. $outil.'_'.$_POST['id'].'\',\'csv\');"><img class="link" src="public/images/icons/'.$data['icon'].'.png" alt="'.$data['icon'].'" />
						'.$data['text'].'</li>';
				}
				$html.='</ul></div>';

				$html.='</form>';			
			break;
			case 'supprimer':
			case 'ajouter':
				
				$html.='<h2><img src="public/images/icons/'.$params['common']['icone_ajout'].'_ajouter.png"/> '.$action.' '.$params['common']['element'].'</h2>
					<ul id="tabs">
					<li id="retour_liste" onclick="affElement(\'0\',\'entites\',\''.$_POST['section'].'\',\'\',\'content\');">
					<img height="16px" src="public/images/icons/retour_liste.png" alt="Retour" title="Retourner à la liste de mes unités d\'enseignement"/></li>';
				if ($mode=='rw') {
					$html.='<li onclick="submitForm(\''.$action.'\');" id="bouton_sauver" style="padding:0px;margin:0px;">
						<input type="submit" value="'.$texte_bouton.'" style="font-size:9px;height:20px;padding:0px;position: relative;top:-2px;"/>';	
				} else {
					$html.='<li style="display:none;">&nbsp;</li>';
				}
				$html.='<li id="infos" class="subtabs"><img height="13px" src="public/images/icons/infos.png"/> Infos</li>
				</ul>';
				$html .='<form id="'.$action.'" method="post" action="index.php">
					<input type="hidden" name="modification_soumise" value="oui">
					<input type="hidden" name="page" value="entites">
					<input type="hidden" name="section" value="'.$_POST['section'].'">
					<input type="hidden" name="id" value="'.$id.'">
					<input type="hidden" name="action" value="'.$action.'">
					<input type="hidden" name="div_target" value="content">';
				if ($_POST['action']=='supprimer') {
					$mode='r';
					$ro=' readonly ';
				}
				require_once('app/includes/entites/tabs/'.$section.'_infos.php');
				$html.='</form>';	
			break;
			
		}
		if (empty($_POST['filtrage_soumis'])) {
			$html.'</div>';
		}
	break;
	case 'annexes':
		require_once('app/includes/entites/annexes.php');
	break;
	default:
		$html='<div class="content_tab">Cette section n\'est pas implémentée pour l\'instant ...</div>';
}

echo $html;
?>

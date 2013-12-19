<?php 
// initialisation du menu
$html_menu='';

// login + deconnexion
$html_menu.='<h2>
			<a style="font-size:11px" title="Accéder à mon espace" href="#" onclick="affElement(\'0\',\'mon_espace\',\'\',\'\',\'content\');">
			<img border="0" width="12px" src="public/images/icons/mon_espace.png"/> '.$_SESSION['login'].'</a>
			&nbsp;<a style="font-size:10px; text-decoration:underline;" title="Me déconnecter du site" href="index.php?page=deconnexion">déconnexion</a>';

// si admin ou gestionnaire, on affiche un select pour pouvoir se faire passer pour n'importe quel enseignant 
if ($_SESSION['group']=='admin' or $_SESSION['group']=='gestionnaire') {
	$html_menu.=select_enseignant('select_menu','');
}
$html_menu.='</h2>';
// formulaire de recherche
$html_menu.='<div style="height:18px; padding-left:7px;padding-top:3px;" title="Cliquez sur la loupe pour une recherche avancée">
		<form id="recherche_menu" method="post" action="index.php" onsubmit="soumetRecherche(\'recherche_menu\');"> 
		<img style="cursor:pointer;" onclick="affElement(\'0\',\'rechercher\',\'\',\'\',\'content\');" 
			title="Vous pouvez rechercher n\'importe quel élément par son nom ou sa description" width="12px" src="public/images/icons/rechercher.png"/>
		<input type="text" name="recherche" class="menu_input_text"'; 
if (isset($_POST['recherche'])) { 
	$html_menu.= 'value="'.htmlentities($_POST['recherche']).'" ';
}
$html_menu.='/>
		<input type="hidden" name="page" value="rechercher"/>
		<input type="hidden" name="force_template" value="yes"/>
		<input type="submit" value="OK" class="menu_bouton_ok"/>
		</form>
	</div>';

// Si enseignant choisi ou si utilisateur==enseignant, on affiche le menu de l'enseignant
if ($_SESSION['id_link']!=0) {
	$html_menu.='<ul>';
	foreach ($menu['enseignant'] as $element => $data) {
		$html_menu.='<li onclick="affElement(\'0\',\'mon_espace\',\''.$element.'\',\'\',\'content\');" >
			<img width="13px" src="public/images/icons/'.$data['icon'].'.png"/> 
			<a title="'.$data['title'].'" href="#">'.$data['text'].'</a></li>';
	}	
	$html_menu.='</ul>';	
}
// Si l'utilisateur est un gestionnaire ou un administrateur on lui affiche les éléments de gestion ainsi que les différentes entités
if ($_SESSION['group']=='admin' OR $_SESSION['group']=='gestionnaire') {
	$html_menu.='<h2 onclick="affElement(\'0\',\'gestion\',\'\',\'\',\'content\');"><img width="12px" src="public/images/icons/gestion.png"/> 
		<a title="Gestion pédagogique de l\'UFR et du Master" href="#"> Gestion</a></h2>';	
	$html_menu.='<ul>';
	foreach ($menu['gestion'] as $element => $data) {
		$html_menu.='<li onclick="affElement(\'0\',\'gestion\',\''.$element.'\',\'\',\'content\');" >
			<img width="13px" src="public/images/icons/'.$data['icon'].'.png"/> 
			<a title="'.$data['title'].'" href="#">'.$data['text'].'</a></li>';
	}	
}
$html_menu.='</ul><h2 onclick="affElement(\'0\',\'entites\',\'\',\'\',\'content\');"><img width="12px" src="public/images/icons/entites.png"/> 
<a title="Gérer les différentes entités" href="#"> Entités</a></h2>';

$html_menu.='<ul>';
foreach ($menu['entites'] as $element => $data) {
	$html_menu.='<li onclick="affElement(\'0\',\'entites\',\''.$element.'\',\'\',\'content\');" >
		<img width="13px" src="public/images/icons/'.$data['icon'].'.png"/> 
		<a title="'.$data['title'].'" href="#">'.$data['text'].'</a></li>';
}	
$html_menu.='</ul>';

// Inclusion du menu d'administration réservé à la responsable administrative
if ($_SESSION['group']=='admin') {
	$html_menu.='<h2 onclick="affElement(\'0\',\'admin\',\'\',\'\',\'content\');"><img width="12px" src="public/images/icons/admin.png"/> 
	<a title="Administrer la base" href="#">Administration</a></h2>';
	
	$html_menu.='<ul>';
	foreach ($menu['admin'] as $element => $data) {
		$html_menu.='<li onclick="affElement(\'0\',\'admin\',\''.$element.'\',\'\',\'content\');" >
			<img width="13px" src="public/images/icons/'.$data['icon'].'.png"/> 
			<a title="'.$data['title'].'" href="#">'.$data['text'].'</a></li>';
	}	
	$html_menu.='</ul>';
	
}

// Lien pour contacter les webmasters
$html_menu.='<h2  onclick="affElement(\'0\',\'contact\',\'\',\'\',\'content\');"><img width="12px" src="public/images/icons/contact.png"/> 
	<a title="Contactez les webmasters pour signaler un problème ou suggérer une amélioration"  href="#"> Assistance</a></h2>';

echo $html_menu;






?>


	

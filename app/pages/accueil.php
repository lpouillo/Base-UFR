<?php
/*
 * Created on 29 déc. 2008
 *
 */

?>
<div class="content_tab">
<p>Vous êtes bien arrivé sur le site de gestion des informations de l'UFR STEP et de l'IPGP. 
Les différentes rubriques auquel vous avez le droit d'accéder sont listées dans le menu sur votre gauche.
En cas de soucis, pensez à regarder la <a onClick="affElement('0','documentation','','','content');"  href="#">documentation</a> <img src="public/images/icons/aide.png"/> ou 
<a onClick="affElement('0','contact','','','content');"  href="#">contacter</a> <img src="public/images/icons/contact.png"/> 
directement les administrateurs. Vous pouvez également soumettre un <a onclick="popupForm('bug_report','accueil');" href="#">bug</a><img src="public/images/icons/bug.png"/> si vous en rencontrez (ce qui est probable).</p>
<div style="text-align:center;">Bonne visite !</div>
  

<?php
$n_menu=0;
$n_lignes=0;
$is_enseignant=0;
$is_gestion=0;
$is_admin=0;
$id_photo=recuperation_donnees("SELECT P.nom_md5 FROM s_photos P
					INNER JOIN Enseignants E
						ON E.id_photo=P.id_photo 
						AND E.id_enseignant=".$_SESSION['id_link']);
if (empty($id_photo)) {
	$photo='inconnu.jpg';
} else {
	$photo=$id_photo[0]['nom_md5'];
}

if ($_SESSION['id_link']!=0) {
	$n_lignes=max(sizeof($menu['enseignant']),$n_lignes);
	$lignes_enseignant=array();
	foreach ($menu['enseignant'] as $element => $data) {
		$lignes_enseignant[]=array_merge(array('element'=>$element),$data);
	}
	$is_enseignant=1;
}
if ($_SESSION['group']=='gestionnaire' or $_SESSION['group']=='admin') {
	$n_lignes=max(sizeof($menu['gestion']),$n_lignes);
	$lignes_gestion=array();
	foreach ($menu['gestion'] as $element => $data) {
		$lignes_gestion[]=array_merge(array('element'=>$element),$data);
	}
	$is_gestion=1;
}
if ($_SESSION['group']=='admin') {
	$n_lignes=max(sizeof($menu['admin']),$n_lignes);
	$lignes_admin=array();
	foreach ($menu['admin'] as $element => $data) {
		$lignes_admin[]=array_merge(array('element'=>$element),$data);
	}
	$is_admin=1;
}
$table_accueil='<table border="1" id="table_accueil">
	<tr>
		<th>'.$_SESSION['login'].'</th>';
if ($is_enseignant) {
	$table_accueil.='<th colspan="'.(3-$is_gestion-$is_admin).'" onclick="affElement(\'0\',\'mon_espace\',\'\',\'\',\'content\');">
		<img src="public/images/icons/mon_espace.png" /> Mon espace</th>';
}
if ($is_gestion) {
	$table_accueil.='<th colspan="'.(3-$is_enseignant-$is_admin).'" onclick="affElement(\'0\',\'gestion\',\'\',\'\',\'content\');">
		<img src="public/images/icons/gestion.png" />Gestion pédagogique</th>';
}
if ($is_admin) {
	$table_accueil.='<th colspan="'.(3-$is_enseignant-$is_gestion).'" onclick="affElement(\'0\',\'admin\',\'\',\'\',\'content\');">
		<img src="public/images/icons/admin.png" />Administration</th>';
}
	

$table_accueil.='<tr>
		<td onclick="affElement(\'0\',\'mon_espace\',\'mes_infos\',\'\',\'content\');" style="text-align:center;" 
		rowspan="'.($n_lignes+1).'"><img width="120px" src="public/images/photos/'.$photo.'" alt="ma photo"/>
		<br/>Changer ma photo</td>';

$lignes=array();
for ($i=0;$i<=$n_lignes-1;$i++) {
	$lignes[$i].='<tr>';
	if (!empty($lignes_enseignant[$i])) {
		$lignes[$i].='<td colspan="'.(3-$is_gestion-$is_admin).'" onclick="affElement(\'0\',\'mon_espace\',\''.$lignes_enseignant[$i]['element'].'\',\'\',\'content\');" >
		<img width="13px" src="public/images/icons/'.$lignes_enseignant[$i]['icon'].'.png"/> 
		<a title="'.$lignes_enseignant[$i]['title'].'" href="#">'.$lignes_enseignant[$i]['text'].'</a></td>';
	} elseif ($is_enseignant)  {
		$lignes[$i].='<td colspan="'.(3-$is_gestion-$is_admin).'">&nbsp; </td>';
	}
	if (!empty($lignes_gestion[$i])) {	
		$lignes[$i].='<td colspan="'.(3-$is_enseignant-$is_admin).'" onclick="affElement(\'0\',\'gestion\',\''.$lignes_gestion[$i]['element'].'\',\'\',\'content\');" >
		<img width="13px" src="public/images/icons/'.$lignes_gestion[$i]['icon'].'.png"/> 
		<a title="'.$lignes_gestion[$i]['title'].'" href="#">'.$lignes_gestion[$i]['text'].'</a></td>';
	} elseif ($is_gestion)  {
		$lignes[$i].='<td colspan="'.(3-$is_enseignant-$is_admin).'" >&nbsp;</td>';
	}
	if (!empty($lignes_admin[$i])) {
		$lignes[$i].='<td colspan="'.(3-$is_enseignant-$is_gestion).'" onclick="affElement(\'0\',\'admin\',\''.$lignes_admin[$i]['element'].'\',\'\',\'content\');" >
		<img width="13px" src="public/images/icons/'.$lignes_admin[$i]['icon'].'.png"/> 
		<a title="'.$lignes_admin[$i]['title'].'" href="#">'.$lignes_admin[$i]['text'].'</a></td>';
	} elseif ($is_admin)  {
		$lignes[$i].='<td colspan="'.(3-$is_enseignant-$is_gestion).'">&nbsp;</td>';
	}

	$lignes[$i].='</tr>';
}
foreach($lignes as $ligne) {
	$table_accueil.=$ligne;
}
$table_accueil.='<tr>';
$rechercher='<form id="recherche_accueil" method="post" action="index.php" onsubmit="soumetRecherche(\'recherche_accueil\');"> 
		<img title="Vous pouvez rechercher n\'importe quel élément par son nom ou sa description" src="public/images/icons/rechercher.png"/>
		Rechercher dans la base <input type="text" name="recherche" onkeyup="lancetimer2(\'recherche_accueil\');"/>		 
		<input type="hidden" name="page" value="rechercher"/></form>';
if ($is_gestion or $is_admin) {
	$table_accueil.='<th colspan="2">'.$rechercher.'</td>
		<th colspan="2">'.select_enseignant('select_accueil','Se faire passer pour :').'</td>';
} else {
	$table_accueil.='<th colspan="4">'.$rechercher.'</td>';
}
$table_accueil.='</tr><tr>';
$i_entites=0;
foreach ($menu['entites'] as $element => $data) {
	$i_entites++;
	if ($element!='annexes') {
		$table_accueil.='<td onclick="affElement(\'0\',\'entites\',\''.$element.'\',\'\',\'content\');" >
			<img width="13px" src="public/images/icons/'.$data['icon'].'.png"/> 
			<a title="'.$data['title'].'" href="#">'.$data['text'].'</a></td>
			';
		if ($i_entites>(sizeof($menu['entites'])/3)-1) {
			$table_accueil.= '</tr><tr>';
			$i_entites=0;
		}
	}
}
$table_accueil.='<tr>';
if ($is_gestion) {
	$table_accueil.='<th colspan="2" onClick="affElement(\'0\',\'entites\',\'annexes\',\'\',\'content\');">
		<img src="public/images/icons/annexe.png"/> Données Annexes</th>';
} else {
	$table_accueil.='<th colspan="2" onClick="affElement(\'0\',\'documentation\',\'\',\'\',\'content\');">
		<img src="public/images/icons/aide.png"/> Documentation</th>';
}	
$table_accueil.='<th colspan="2" onclick="affElement(\'0\',\'contact\',\'\',\'\',\'content\');"
			title="Contactez les webmasters pour signaler un problème ou suggérer une amélioration">
		<img src="public/images/icons/contact.png"> Assistance</th>
	</tr>	
</table>';

echo  $table_accueil;
?>
</div>

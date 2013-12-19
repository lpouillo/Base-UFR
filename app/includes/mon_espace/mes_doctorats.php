<?php
$html='';
$html='<h2><img src="public/images/icons/doctorat.png"/> Mes doctorats</h2>
	<div class="content_tab">';


if (empty($_POST['id_doctorat'])) { 
	
	$s_doctorats="SELECT D.id_doctorat,D.sujet, CONCAT(ET.nom,' ',ET.prenom) AS nom_prenom, D.id_etudiant 
			FROM Doctorats D 
			LEFT JOIN Etudiants ET ON ET.id_etudiant=D.id_etudiant
			INNER JOIN l_encadrant_doctorat ED
				ON ED.id_doctorat=D.id_doctorat
				AND ED.id_encadrant=".$_SESSION['id_link']; 

	$r_doctorats=mysql_query($s_doctorats);
	$n_doctorats=mysql_num_rows($r_doctorats);
	$add=($mode='rw')?'<a href="#" onClick="popupForm(\'ajout_doctorat\')">Ajouter un doctorat</a>':'';
	if ($n_doctorats==0) {
		$html .='<p>Vous n\'encadrez aucun doctorat.</p>
			<p>'.$add.'</p>';
	} else {
		$html .='<p>Vous trouverez sur cette page la liste des doctorats auquel vous participez en tant que 
				directeur ou co-directeur. '.$add.' 
				
				<form method="POST" action="index.php?page=mon_espace&section=mes_doctorats">
				<table width="100%"  class="table_sel">
					<tr>
						<th>Id</th><th>Titre</th><th>Etudiant</th>
					</tr>';
		while ($d_doctorat=mysql_fetch_array($r_doctorat)) {
			$html.='<tr><td><input type="submit" name="id_doctorat" value="'.$d_doctorat['id_doctorat_laboratoire'].'"></td>
					<td>'.$d_doctorat['sujet'].'</td>
					<td>';
			if($d_doctorat['nom_prenom']!='') {
				$html.='<a href="index.php?page=mon_espace&section=tous_les_etudiants&action=voir&id_choisie='.$d_doctorat['id_etudiant'].'"/>'.$d_doctorat['nom_prenom'].'<a/>';	
			} else {
				$html.='Non attribué';
			}
			$html.='</td>' .
					'</tr>';
		}
		
		$html .='</table></form></div>';
	}		
	
	
	
} else {
	$html.='<a href="index.php?page=mon_espace&section=mes_doctorats">Retour à la liste de mes doctorats</a><br/>';
	$mon_doctorat = new formulaire();


	$mon_doctorat->table='Doctorats';
	$mon_doctorat->action='modifier';
	$mon_doctorat->champs=array('tous');
	
	if ($_GET['section']=='mes_doctorats' AND isset($_POST['modification_soumise'])) {
		$mon_doctorat->execution_requete($_POST['modification_soumise']);
	} 
	
	
	$mon_doctorat->destination='index.php?page=mon_espace&section=mes_doctorats&id_doctorat='.$_POST['id_doctorat'];
	$mon_doctorat->definir_champs();

	$mon_doctorat->id_choisie=$_POST['id_doctorat'];
	$mon_doctorat->affiche_formulaire();
	
	$html.= $mon_doctorat->html;
}

?>

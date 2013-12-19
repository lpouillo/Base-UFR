<?php
/*
 * Created on 20 oct. 2008
 *
 */
$html='';
$mode='rw';
if (empty($_POST['id'])) {
	$html.='<h2><img src="public/images/icons/stage_laboratoire.png"/> Mes stages</h2>
	<div id="content_tab" class="content_tab">'; 
	$html .='<p>Vous trouverez sur cette page la liste des stages auquel vous participez en tant qu\'encadrant ou de tuteur. </p>';
	
	// STAGES EN LABORATOIRES
	$html.='<h3><img src="public/images/icons/stage_laboratoire.png"> Stages en laboratoire</h3>';
	$s_stage_laboratoire="SELECT S.id_stage_laboratoire, S.sujet, 
			CONCAT(ET.nom,' ',ET.prenom) AS etudiant, 
			CONCAT(EN.nom,' ',EN.prenom) AS directeur, L.nom AS labo, S.id_etudiant, A.libelle AS annee
			
			FROM Stages_Laboratoires S 
			LEFT JOIN Etudiants ET 
				ON ET.id_etudiant=S.id_etudiant 
			INNER JOIN l_encadrant_stage ES
				ON ES.id_stage=S.id_stage_laboratoire
			LEFT JOIN Enseignants EN
				ON ES.id_encadrant=EN.id_enseignant				
			LEFT JOIN Laboratoires L 
				ON L.id_laboratoire=EN.id_laboratoire
			INNER JOIN a_annee_scolaire A
				ON ES.id_annee_scolaire=".$id_annee_scolaire."
			WHERE ES.id_encadrant=".$_SESSION['id_link']." 
				AND ES.id_type_encadrant IN (1,2,3)
				AND A.id_annee_scolaire=10";
	
	$r_stage_laboratoire=mysql_query($s_stage_laboratoire)
		or die('Erreur lors de la récupération de la liste de vos stages en laboratoires : <br/>'.mysql_error());
	$n_stages_laboratoire=mysql_num_rows($r_stage_laboratoire);
	if ($n_stages_laboratoire==0) {
		$html .='<p>Vous n\'avez proposé aucun stage. 
		<a class="ajout_entree" onclick="popupForm(\'ajout_stage_laboratoire\')" href="#">Ajouter un stage</a></p>';		
	} elseif ($n_stages_laboratoire==1) { 
		$html .='<p>Vous avez proposé 1 stage. <a class="ajout_entree" onclick="popupForm(\'ajout_stage_laboratoire\')" href="#">Ajouter un stage</a></p>';
	} else {
		$html .='<p>Vous avez proposé '.$n_stages_laboratoire.' stages. <a class="ajout_entree" onclick="popupForm(\'ajout_stage_laboratoire\')" href="#">Ajouter un stage</a></p>';
	}
	if ($n_stages_laboratoire !=0) {
		$html.='<table class="table_sel">
					<tr>
						<th width="25px">Détails</th><th>Année</th><th>Sujet</th><th>Étudiant</th>
					</tr>';
				
		while ($stage=mysql_fetch_array($r_stage_laboratoire)) {
			$html.='<tr>
					<td class="td_selection">
					<img src="public/images/icons/modifier.png" title="Modifier les détails du stage" 
					 onclick="affElement(\'R_'.$stage['id_stage_laboratoire'].'\',\'mon_espace\',\'mes_stages\',\'voir\',\'content\');" 
					/></td>
					<td>'.$stage['annee'].'</td>
					<td>'.$stage['sujet'].'</td>
					<td>'.$stage['etudiant'].'</td>
				</tr>';
		}
		$html.='</table>';
	}
		
		
		
	
	// STAGES EN ENTREPRISES	
	$html.='<h3><img src="public/images/icons/stage_entreprise.png"> Stages en entreprise</h3>';
	$s_stage_entreprise="SELECT S.id_stage_entreprise, S.sujet,CONCAT(ET.nom,' ',ET.prenom) AS etudiant, 
					ENT.nom, A.libelle AS annee
					FROM Stages_Entreprises S 
					LEFT JOIN Etudiants ET 
						ON ET.id_etudiant=S.id_etudiant 
					LEFT JOIN Entreprises ENT 
						ON ENT.id_entreprise=S.id_entreprise
					LEFT JOIN l_encadrant_stage ES
						ON ES.id_stage=S.id_stage_entreprise
						AND ES.id_encadrant=".$_SESSION['id_link']." 
						AND ES.id_type_encadrant IN (7,8) 
						AND ES.id_annee_scolaire=".$id_annee_scolaire."
					INNER JOIN a_annee_scolaire A
						ON ES.id_annee_scolaire=A.id_annee_scolaire";
	$r_stage_entreprise=mysql_query($s_stage_entreprise)
		or die('Impossible de récupérer les stages en Entreprises pour lesquels vous êtes tuteur');
	$n_stages_entreprise=mysql_num_rows($r_stage_entreprise);
	if ($n_stages_entreprise==0) {
		$html .='<p>Vous n\'êtes tuteur d\'aucun stage.</p>';		
	} elseif ($n_stages_entreprise==1) { 
		$html .='<p>Vous êtes tuteur d\'1 stage.</p>';
	} else {
		$html .='<p>Vous êtes tuteur de '.$n_stages_entreprise.' stages.</p>';
	}
	if ($n_stages_entreprise !=0) {
		$html.='<table class="table_sel">
					<tr>
						<th width="25px">Détails</th><th>Année</th><th>Sujet</th><th>Étudiant</th>
					</tr>';
				
		while ($stage=mysql_fetch_array($r_stage_entreprise)) {
			$html.='<tr>
					<td class="td_selection">
					<img src="public/images/icons/voir.png" title="Voir les détails du stage" 
					onclick="affElement(\'P_'.$stage['id_stage_entreprise'].'\',\'mon_espace\',\'mes_stages\',\'voir\',\'content\');" 
					/></td>
					<td>'.$stage['annee'].'</td>
					<td>'.$stage['sujet'].'</td>
					<td>'.$stage['etudiant'].'</td>
				</tr>';
		}
		$html.='</table>';
	}
	
	// CAS D'ÉTUDES	
	$html.='<h3><img src="public/images/icons/cas_etudes.png"> Cas d\'études</h3>';
	$s_cas_etudes="SELECT CE.id_cas_etude, CE.sujet, CONCAT(ET.nom,' ',ET.prenom) AS etudiant, 
					ENT.nom, A.libelle AS annee
					FROM Cas_Etudes CE
					LEFT JOIN Etudiants ET 
						ON ET.id_etudiant=CE.id_etudiant 
					LEFT JOIN Entreprises ENT 
						ON ENT.id_entreprise=CE.id_entreprise
					LEFT JOIN l_encadrant_stage ES
						ON ES.id_stage=CE.id_cas_etude
						AND ES.id_encadrant=".$_SESSION['id_link']." 
						AND ES.id_type_encadrant IN (9,10) 
						AND ES.id_annee_scolaire=".$id_annee_scolaire."
					INNER JOIN a_annee_scolaire A
						ON ES.id_annee_scolaire=A.id_annee_scolaire";
	$r_cas_etudes=mysql_query($s_cas_etudes)
		or die('Impossible de récupérer les cas d\'études pour lesquels vous êtes tuteur<br/>'.mysql_error());
	$n_cas_etudes=mysql_num_rows($r_cas_etudes);
	if ($n_cas_etudes==0) {
		$html .='<p>Vous n\'êtes tuteur d\'aucun cas d\'étude.</p>';		
	} elseif ($n_cas_etudes==1) { 
		$html .='<p>Vous êtes tuteur d\'1 cas d\'étude.</p>';
	} else {
		$html .='<p>Vous êtes tuteur de '.$n_cas_etudes.' cas d\'étude.</p>';
	}
	if ($n_cas_etudes !=0) {
		$html.='<table class="table_sel">
					<tr>
						<th width="25px">Détails</th><th>Année</th><th>Sujet</th><th>Étudiant</th>
					</tr>';
				
		while ($stage=mysql_fetch_array($r_cas_etudes)) {
			$html.='<tr>
					<td class="td_selection">
					<img src="public/images/icons/voir.png" title="Voir les détails du stage" 
					onclick="affElement(\'C_'.$stage['id_cas_etude'].'\',\'mon_espace\',\'mes_stages\',\'voir\',\'content\');" 
					/></td>
					<td>'.$stage['annee'].'</td>
					<td>'.$stage['sujet'].'</td>
					<td>'.$stage['etudiant'].'</td>
				</tr>';
		}
		$html.='</table>';
	}
	
	$html .='</div>';
	
} else {
	$type_id=explode('_',$_POST['id']);
	$type=$type_id[0];
	$id=$type_id[1];
	switch ($type) {
		case 'R':
			$s_stage="SELECT sujet FROM Stages_Laboratoires WHERE id_stage_laboratoire=".$id;
			$inc='stages_laboratoires';
			$icon='stage_laboratoire';
		break; 
		case 'P':
			$mode='r';
			$s_stage="SELECT sujet FROM Stages_Entreprises WHERE id_stage_entreprise=".$id;
			$inc='stages_entreprises';
			$icon='stage_entreprise';
		break;
		case 'C':
			$mode='r';
			$s_stage="SELECT sujet FROM Cas_Etudes WHERE id_cas_etude=".$id;
			$inc='cas_etudes';
			$icon='cas_etude';
		break;
	}
	$tabs=array(
		'infos' => array ('icon' => 'infos', 'text' => 'Infos'),
		'ouvertures' => array ('icon' => 'ouverture', 'text' => 'Ouverture'),
		'encadrants' => array ('icon' => 'encadrement', 'text' => 'Encadrants'),
		'etudiant' => array ('icon' => 'etudiant', 'text' => 'Étudiant'),
		'outils' => array ('icon' => 'outils', 'text' => 'Outils')
	);
	$r_stage=mysql_query($s_stage);
	$d_stage=mysql_fetch_array($r_stage);
	$html='<h2><img src="public/images/icons/'.$icon.'.png"/> '.$d_stage['sujet'].'</h2
		<ul id="tabs">
		<li width="20%" id="retour_liste" onclick="affElement(\'0\',\'mon_espace\',\'mes_stages\',\'\',\'content\');">
		<img src="public/images/icons/retour_liste.png" alt="Retour" title="Retourner à la liste de mes stages"/></li>';
	if ($mode=='rw') {
		$html.='<li onclick="submitForm(\'update_stage\');"id="bouton_sauver" style="padding:0px;margin:0px;">
			<input type="submit" value="SAUVER" style="font-size:9px;height:20px;padding:0px;position: relative; top: -2px;"/>';
	} else {
		$html.='<li style="display:none;">&nbps;</li>';
	}	
	
	foreach ($tabs as $id_tab => $data) {
		$html.='<li id="'.$id_tab.'" class="subtabs"><img height="13px" src="public/images/icons/'.$data['icon'].'.png"/> '.$data['text'].'</li>';
	}
	$html.='</ul>';
	

	$html .='<form id="update_stage" method="post" action="index.php">
		<input type="hidden" name="modification_soumise" value="update_stage">
		<input type="hidden" name="page" value="mon_espace">
		<input type="hidden" name="section" value="mes_stages">
		<input type="hidden" name="id" value="'.$type.'_'.$id.'">
		<input type="hidden" name="action" value="modifier">
		<input type="hidden" name="div_target" value="content">';
	
	foreach ($tabs as $id_tab => $data) {
		$html.='<div id="content_'.$id_tab.'" class="content_tab hidden">';
		require_once('app/includes/entites/tabs/'.$inc.'_'.$id_tab.'.php');	
		$html.='</div>';
	}

	
	$html.='</form>';
}


?>

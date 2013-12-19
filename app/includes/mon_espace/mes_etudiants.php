<?php
/*
 * Created on 30 sept. 2008
 *
 */
$html='';
$mode='r';

if (empty($_POST['id'])) {
	$s_etudiants="SELECT E.id_etudiant, E.prenom, E.nom, E.email_ipgp, E.id_photo, SNOW.libelle AS libelle_spec_now, NNOW.abbreviation AS abbrv_niv_now,  
				SPAST.libelle AS libelle_spec_past, NPAST.abbreviation AS abbrv_niv_past, EPAST.nom AS nom_etab_past,
				UE.intitule
				FROM Etudiants E
				LEFT JOIN l_parcours_etudiant PNOW 
					ON PNOW.id_etudiant = E.id_etudiant
					AND PNOW.id_annee_scolaire=".$id_annee_scolaire."
				LEFT JOIN a_specialite SNOW 
					ON SNOW.id_specialite = PNOW.id_specialite
				LEFT JOIN a_niveau NNOW 
					ON NNOW.id_niveau = PNOW.id_niveau
				LEFT JOIN l_parcours_etudiant PPAST 
					ON PPAST.id_etudiant = E.id_etudiant
					AND PPAST.id_annee_scolaire=".($id_annee_scolaire-1)."
				LEFT JOIN a_specialite SPAST 
					ON SPAST.id_specialite = PPAST.id_specialite
				LEFT JOIN a_niveau NPAST 
					ON NPAST.id_niveau = PPAST.id_niveau
				LEFT JOIN Etablissements EPAST 
					ON EPAST.id_etablissement = PPAST.id_etablissement
				INNER JOIN l_enseignant_ue L2
					ON L2.id_enseignant=".$_SESSION['id_link']."
					AND L2.id_annee_scolaire=".$id_annee_scolaire."
				INNER JOIN l_etudiant_ue L
					ON L.id_etudiant=E.id_etudiant
					AND L.id_annee_scolaire=".$id_annee_scolaire."
					AND L.id_ue=L2.id_ue
				INNER JOIN Unites_Enseignement UE
					ON L2.id_ue=UE.id_ue
				GROUP BY E.id_etudiant
				ORDER BY E.nom";

	$r_etudiant=mysql_query($s_etudiants)
		or die('Erreur lors de la récupération de la liste des étudiants : <br/>'.mysql_error());
	$n_etudiant=mysql_num_rows($r_etudiant);
	$id_etudiant_prev=0;
	$prev_n_parcours=2;
	$html='<h2><img src="public/images/icons/etudiant.png"/> Mes étudiants</h2>';
	$html .='<div class="content_tab">';
	if ($n_etudiant>0) {
		$html.= '<p>Voici la liste des '.$n_etudiant.' étudiants qui suivent vos différentes unités d\'enseignement.</p>
				<form action="index.php?page=mon_espace&section=mes_etudiants" method="post">
				<table class="table_sel">
				<tr><th>Détails</th><th>Écrire</th><th>Photo</th><th>Étudiant</th><th>Parcours</th><th>Unités d\'enseignement suivies</th></tr>';
	} else {
		$html.= '<p>Vous ne dispensez pas de cours ou vos étudiants n\'ont pas été définis</p>';
	}
	while ($d_etudiants=mysql_fetch_array($r_etudiant)) {
		$html .='<tr>
					<td style="width:30px;text-align:center;">
					<img style="cursor:pointer;" onclick="affElement(\''.$d_etudiants['id_etudiant'].'\',\'mon_espace\',\'mes_etudiants\',\'voir\',\'content\')" width="20" border="0" src="public/images/icons/voir.png"/></td>
					<td style="width:30px;text-align:center;">
					<a href="mailto:'.$d_etudiants['email_ipgp'].'"><img border=0 width="20px" src="public/images/icons/contact.png"/></a></td>
					<td>';
		if ($d_etudiants['id_photo']!=0) {
			$s_photo="SELECT nom_md5 FROM s_photos WHERE id_photo=".$d_etudiants['id_photo'];
			$r_photo=mysql_query($s_photo) or die(mysql_error());
			$d_photo=mysql_fetch_array($r_photo);
			$html .='<img width="50px" src="public/images/photos/'.$d_photo['nom_md5'].'"/>';
		} else {
			$html .='<img width="50px" src="public/images/photos/inconnu.jpg"/>';
		}
		$html.='</td><td>'.$d_etudiants['nom'].' '.$d_etudiants['prenom'].'</td>
			<td>'.$d_etudiants['abbrv_niv_now'].' '.$d_etudiants['libelle_spec_now'].'<br/><em>Année précédente : ';
		
		if ($d_etudiants['abbrv_niv_past']!='') {
			$html .=$d_etudiants['abbrv_niv_past'].' '.$d_etudiants['libelle_spec_past'].' ('.$d_etudiants['nom_etab_past'].')</em>';
		} else {
			$html .='Inconnu</em>';
		}
		$html.='</td><td><strong>'.$d_etudiants['intitule'].'</strong></td>
			</tr>';	
	}		
	if ($n_etudiant>0) {
		$html .= '</table></form>';
	}
	$htmk.='</div>';
	
	

} else {
	$id=$_POST['id'];
	$sql_select="SELECT E.id_etudiant, CONCAT(E.nom,' ',E.prenom) AS nom_prenom,
				CONCAT (NI.abbreviation,' ',SP.libelle) AS spec
				FROM Etudiants E 
				LEFT JOIN l_parcours_etudiant P 
					ON P.id_etudiant=E.id_etudiant 
					AND P.id_annee_scolaire=".$id_annee_scolaire."
				LEFT JOIN a_niveau NI 
					ON P.id_niveau=NI.id_niveau
				LEFT JOIN a_specialite SP 
					ON P.id_specialite=SP.id_specialite
				INNER JOIN l_enseignant_ue L2
					ON L2.id_enseignant=".$_SESSION['id_link']."
					AND L2.id_annee_scolaire=".$id_annee_scolaire."
				INNER JOIN l_etudiant_ue L
					ON L.id_etudiant=E.id_etudiant
					AND L.id_annee_scolaire=".$id_annee_scolaire."
					AND L.id_ue=L2.id_ue
				INNER JOIN Unites_Enseignement UE
					ON L2.id_ue=UE.id_ue
				GROUP BY E.id_etudiant
				ORDER BY E.nom";
	
	$html.='<h2>
			<form id="switch" method="post" action="index.php">
			<input type="hidden" name="page" value="mon_espace"/>
			<input type="hidden" name="section" value="etudiants"/>
			<input type="hidden" name="action" value="voir"/>
			<img src="public/images/icons/etudiants.png"/> '
			.select_entites($sql_select).
			'</form></h2>';

	
	
	$html.='<ul id="tabs">
			<li id="retour_liste" onclick="affElement(\'0\',\'mon_espace\',\'mes_etudiants\',\'\',\'content\');">
			<img height="16px" src="public/images/icons/retour_liste.png" alt="Retour" title="Retourner à la liste de mes unités d\'enseignement"/></li>
			<li style="display:none;">&nbsp;</li>';
	
	$tabs=array(
		'infos' => array ('icon' => 'infos', 'text' => 'Infos'),
		'ues' => array ('icon' => 'module', 'text' => 'Unites d\'enseignement'),
		'scolarite' => array ('icon' => 'parcours', 'text' => 'Scolarité'),
		'stages' => array ('icon' => 'stage_laboratoire', 'text' => 'Stages'),
		'doctorat' => array ('icon' => 'doctorat', 'text' => 'Doctorat'),
		'emplois' => array ('icon' => 'professionnel', 'text' => 'Emplois')
//		'outils' => array ('icon' => 'outils', 'text' => 'Outils')
	);
	foreach ($tabs as $id_tab => $data) {
		$html.='<li id="'.$id_tab.'" class="subtabs"><img height="13px" src="public/images/icons/'.$data['icon'].'.png"/> '.$data['text'].'</li>';
	}
	$html.='</ul>';

	foreach ($tabs as $id_tab => $data) {
		$html.='<div id="content_'.$id_tab.'" class="content_tab hidden">';
		require_once('app/includes/entites/tabs/etudiants_'.$id_tab.'.php');	
		$html.='</div>';
	}
	
	
    
}
?>

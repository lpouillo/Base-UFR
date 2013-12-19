<?php
/*
 * Created on 3 oct. 2008
 *
 * Script permettant de générer la section mes cours de l'espace enseignant
 *
 * Tout réécrire en deux requètes avec un tableau
 */
$html='';

require_once('app/includes/entites/unites_enseignement.php');
// Si aucune UE n'a été choisie en visualisation, afficher la liste des ue de l'enseignant
if (empty($_POST['id'])) {
	// Récupération des niveaux et spécialités
	$s_niveau="SELECT id_niveau, abbreviation FROM a_niveau WHERE gestion=1";
	$r_niveau=mysql_query($s_niveau);
	$niveaux=array();
	while ($d_niveau=mysql_fetch_array($r_niveau)) {
		$niveaux[$d_niveau['id_niveau']]=$d_niveau['abbreviation'];
	}
	mysql_free_result($r_niveau);
	$s_specialite="SELECT id_specialite, abbreviation FROM a_specialite WHERE gestion=1";
	$r_specialite=mysql_query($s_specialite);
	$specialites=array();
	while ($d_specialite=mysql_fetch_array($r_specialite)) {
		$specialites[$d_specialite['id_specialite']]=$d_specialite['abbreviation'];
	}
	mysql_free_result($r_specialite);
	
	// Récupération de la liste des UE de STEP
	$s_liste_UE = "SELECT UE.id_ue, UE.intitule, EUE.id_situation
				FROM Unites_Enseignement UE 
				INNER JOIN l_enseignant_ue EUE
					ON EUE.id_enseignant=".$_SESSION['id_link']." 
					AND EUE.id_annee_scolaire=".$id_annee_scolaire." 
					AND EUE.id_ue=UE.id_ue 
					AND EUE.id_situation IN (20,21)
				WHERE UE.id_ufr=3
				GROUP BY UE.id_ue, UE.intitule";
	$r_liste_UE=mysql_query($s_liste_UE)
		or die('Erreur lors de la récupération des UE :<br/>'.mysql_error());
	$ues=array();
	while ($d_liste_UE=mysql_fetch_array($r_liste_UE)) {
		$s_etudiants="SELECT L.id_etudiant, P.id_niveau, P.id_specialite   
				FROM l_etudiant_ue L 
				INNER JOIN l_parcours_etudiant P
					ON P.id_etudiant=L.id_etudiant
					AND P.id_annee_scolaire=".$id_annee_scolaire."
				WHERE L.id_ue=".$d_liste_UE['id_ue']." AND L.id_annee_scolaire=".$id_annee_scolaire;
		$r_etudiants=mysql_query($s_etudiants);
		$nombres=array();
		$n_etudiants=mysql_num_rows($r_etudiants);
		$nombres['TOTAL']=$n_etudiants;
		while ($d_etudiants=mysql_fetch_array($r_etudiants)) {			
			$nombres[$d_etudiants['id_niveau']][$d_etudiants['id_specialite']]++;
		}
		$ues[]=array('id_ue' => $d_liste_UE['id_ue'] ,'intitule'=> $d_liste_UE['intitule'],
				'id_situation' => $d_liste_UE['id_situation'],'nombres' => $nombres);
	}
	mysql_free_result($r_liste_UE);
	
	
	
	$html='<h2><img src="public/images/icons/cours.png"/> Mes cours</h2>';
	$html.='<div class="content_tab">
		<p>Voici la liste des Unités d\'enseignements auxquelles vous participez.</p>';	
	
	$html.='<h3>UE STEP</h3>';
		
	$structure=array(
					'Licence' => array(
									'niveaux' => array('2','5'),
									'specialites' => array('121','116','141')	
										),
					'Master' => array(
									'niveaux' => array('6','7'),
									'specialites' => array('19','20','22','24','26','49')	
										),
					'Doctorat' => array(
									'niveaux' => array('19'),
									'specialites' => array('141')	
										)
	);	
	
	foreach ($structure AS $nom_grade => $grade) {
		$html .='<h4><img src="public/images/icons/bullet_'.$nom_grade.'.png"/> '.$nom_grade.'</h4>';
		$n_ue_grade=0;
		$html_ue='';
		foreach($ues as $ue) {
			$affiche_ue=0;
			foreach ($grade['niveaux'] as $niveau) {
				if (array_key_exists($niveau,$ue['nombres'])) {
					$affiche_ue=1;
				}
			}
			if ($affiche_ue) {
				$n_ue_grade++;
				if ($ue['id_situation']==20) {
					$icon='modifier';
				} else {
					$icon='voir';
				}
				$html_ue.='<tr>
						<td align="center"  style="padding: 0px;">
							<img onclick="affElement(\''.$ue['id_ue'].'\',\'mon_espace\',\'mes_cours\',\'modifier\',\'content\')" style="cursor:pointer;" width="20" border="0" src="public/images/icons/'.$icon.'.png"/>
						</td>
						<td width="=300px"><strong>'.$ue['intitule'].'</strong></td>';
				$autres=$ue['nombres']['TOTAL'];
				foreach ($grade['niveaux'] as $id_niveau) {
					foreach ($grade['specialites'] as $id_specialite) {
						$html_ue.='<td style="text-align:center">'.$ue['nombres'][$id_niveau][$id_specialite].'</td>';
						$autres-=$ue['nombres'][$id_niveau][$id_specialite];			
					}
				}
				$html_ue.='<td style="text-align:center">'.$autres.'</td><td style="text-align:center;"><strong>'.$ue['nombres']['TOTAL'].'</strong></tr>';
			}
		}
		if ($n_ue_grade!=0) {
			$html .='<table width="98%" class="table_sel">
				<tr><th width="40px">Détails</th><th>Intitule</th>';
			$n_parcours=0;
			foreach ($grade['niveaux'] as $id_niveau) {
				foreach ($grade['specialites'] as $id_specialite) {
					$html.='<th style="text-align:center">'.$niveaux[$id_niveau].' '.$specialites[$id_specialite].'</td>';
					$n_parcours++;
				}
			}
			$html.='<th style="text-align:center;width:40px">Autres</th><th style="text-align:center;width:40px">TOTAL</th>';
			$html.=$html_ue;
		} else {
			$html.='<p>Vous n\'enseignez pas à ce niveau ou votre responsable d\'UE ne vous a pas ajouté</p>';
		}
		
		$html.='</table>';
		
	}
	
	// Récupération des UE hors STEP
	$s_UE_hors = "SELECT UE.id_ue, UE.intitule, EUE.id_situation, U.libelle
				FROM Unites_Enseignement UE 
				INNER JOIN l_enseignant_ue EUE
					ON EUE.id_enseignant=".$_SESSION['id_link']." 
					AND EUE.id_annee_scolaire=".$id_annee_scolaire." 
					AND EUE.id_ue=UE.id_ue 
					AND EUE.id_situation IN (20,21)
				INNER JOIN a_ufr U
					ON UE.id_ufr=U.id_ufr
				WHERE UE.id_ufr<>3
				GROUP BY UE.id_ue, UE.intitule";
	$r_UE_hors=mysql_query($s_UE_hors);
	$n_UE_hors=mysql_num_rows($r_UE_hors);
	$html.='<h3>UE hors STEP</h3>
		<p><a href="#" onClick="popupForm(\'ajout_ue_externe\')">Déclarer une nouvelle UE</a></p>';
	if ($n_UE_hors) {
		$html.='<table  width="98%" class="table_sel">
				<tr><th width="50px">Modifier</th><th>Intitule</th><th>UFR de rattachement</th></tr>';
		while ($ue=mysql_fetch_array($r_UE_hors)) {
			if ($ue['id_situation']==20) {
				$icon='modifier';
			} else {
				$icon='voir';
			}
			$html.='<tr>
					<td align="center"  style="padding: 0px;">
						<img onclick="affElement(\''.$ue['id_ue'].'\',\'mon_espace\',\'mes_cours\',\'modifier\',\'content\')" style="cursor:pointer;" width="20" border="0" src="public/images/icons/'.$icon.'.png"/>
					</td>
					<td>'.$ue['intitule'].'</td>
					<td>'.$ue['libelle'].'</td>
					</tr>';
		}
		$html.='</table>';
	}
	
	// Récupération des activités hors maquette
	$html.='<h3>Activité hors maquette</h3>
		<p><a href="#" onClick="popupForm(\'ajout_hors_maquette\')">Déclarer une nouvelle activité hors maquette</a>
			<ul style="margin-left:30px;">';
	$s_hm="SELECT HM.libelle, LEHM.decharge, LEHM.n_etudiant FROM a_hors_maquette HM 
			INNER JOIN l_enseignant_hors_maquette LEHM
				ON LEHM.id_hors_maquette=HM.id_hors_maquette
				AND LEHM.id_enseignant=".$_SESSION['id_link']."
				AND LEHM.id_annee_scolaire=".$id_annee_scolaire;
	$r_hm=mysql_query($s_hm);
	while ($d_hm=mysql_fetch_array($r_hm)) {
		$html.='<li style="list-style-type:square">'.$d_hm['libelle'].' ('.$d_hm['n_etudiant'].'x'.$d_hm['decharge'].'h)</li>';
	}
	$html.='</ul></div>';
	
} else {
	$id=$_POST['id'];
	$id_ue=$id;
	// Récupération de l'intitulé de l'UE
	$s_nom_ue="SELECT intitule FROM Unites_Enseignement WHERE id_ue=".$id_ue;
	$r_nom_ue=mysql_query($s_nom_ue);
	$d_nom_ue=mysql_fetch_array($r_nom_ue);
	$html.='<h2><img src="public/images/icons/cours.png"/> '.$d_nom_ue['intitule'].'</h2>';
	// Récupération de la situation de l'enseignant
	$s_situation="SELECT id_situation FROM l_enseignant_ue WHERE id_ue=".$id_ue.' AND id_enseignant='.$_SESSION['id_link'];
	$r_situation=mysql_query($s_situation);
	$d_situation=mysql_fetch_array($r_situation);
	$mode=($d_situation['id_situation']==20)?'rw':'r';
	
	$html.='<ul id="tabs">
			<li id="retour_liste" onclick="affElement(\'0\',\'mon_espace\',\'mes_cours\',\'\',\'content\');">
			<img height="16px" src="public/images/icons/retour_liste.png" alt="Retour" title="Retourner à la liste de mes unités d\'enseignement"/></li>';
	if ($mode=='rw') {
		$html.='<li id="bouton_sauver">
			<input onclick="submitForm(\'update_cours\');" type="submit" value="SAUVER" />';	
	} else {
		$html.='<li style="display:none;">&nbsp;</li>';
	}
	foreach ($params['element']['tabs'] as $id_tab => $data) {
		$html.='<li id="'.$id_tab.'" class="subtabs"><img height="13px" src="public/images/icons/'.$data['icon'].'.png"/> '.$data['text'].'</li>';
	}
	$html.='<li id="Outils" class="subtabs"><img height="13px" src="public/images/icons/outils.png"/> Outils</li>
		</ul>';
	
	// FORMULAIRE UNIQUE POUR TOUS LES ONGLETS
	$html .='<form id="update_cours" method="post" action="index.php"  method="post" enctype="multipart/form-data">
		<input type="hidden" name="modification_soumise" value="update_ue">
		<input type="hidden" name="page" value="mon_espace">
		<input type="hidden" name="section" value="mes_cours">
		<input type="hidden" name="id" value="'.$id_ue.'">
		<input type="hidden" name="action" value="modifier">
		<input type="hidden" name="div_target" value="content">';

	foreach ($params['element']['tabs'] as $id_tab => $data) {
			$html.='<div id="content_'.$id_tab.'" class="content_tab hidden">';
			require_once('app/includes/entites/tabs/unites_enseignement_'.$id_tab.'.php');	
			$html.='</div>';
	}
	$html.='<div id="content_Outils" class="content_tab hidden"><ul>';
	foreach($params['element']['outils'] as $outil => $data) {
		$html.='<li onClick="genererFichier(\'unites_enseignement_'. $outil.'_'.$_POST['id'].'\',\'csv\');"><img class="link" src="public/images/icons/'.$data['icon'].'.png" alt="'.$data['icon'].'" />
			'.$data['text'].'</li>';
	}
	$html.='</ul></div>';
	$html.='</form>';
}

?>

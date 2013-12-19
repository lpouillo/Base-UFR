<?php
$id_etudiant=$_POST['id'];

// Récupération des infos de l'étudiant
$s_etudiant="SELECT E.nom AS nom, E.prenom AS prenom, 
		P.id_niveau, P.id_specialite, 
		NI.abbreviation AS niv, SP.abbreviation AS spec, 
		P.id_annee_scolaire, A.annee_debut AS annee
		FROM Etudiants E 
		INNER JOIN l_parcours_etudiant P 
			ON P.id_etudiant=E.id_etudiant 
		INNER JOIN a_niveau NI 
			ON P.id_niveau=NI.id_niveau 
		INNER JOIN a_specialite SP 
			ON P.id_specialite=SP.id_specialite
		INNER JOIN a_annee_scolaire A
			ON P.id_annee_scolaire=A.id_annee_scolaire		 
		WHERE E.id_etudiant=".$id_etudiant." 
		ORDER BY A.annee_debut DESC";

$r_etudiant=mysql_query($s_etudiant)
	or die('Impossible de récupérer les infos de l\'étudiant :<br/>'.mysql_error());
$parcours_all=array();
while ($d_etudiant=mysql_fetch_array($r_etudiant)) {
if ($d_etudiant['niv'][0]=='D') {
		$d_etudiant['niv']='D';
	}
	$parcours_all[]=$d_etudiant;
}

/*echo '<pre>';
print_r($parcours);
echo '</pre>';*/

foreach($parcours_all as $parcours) {
	// Vérification que le parcours est IPGP
	switch($parcours['spec']) {
		case 'gd':
		case 'gf':
		case 'ge':
		case 'gs':
		case 'gp':
		case 'gc':
		case 'gl':
		case 'gm':
		case 'mpt':
		case 'ssng':
		case 'step':
		case 'stgp':
		case 'stgc':
		case 'FORM_CONT':
		$hors_ipgp=0;
		break;
		default:
		$hors_ipgp=1;
	}
	$html.='<h3>'.$parcours['annee'].'-'.($parcours['annee']+1).' : '.$parcours['niv'].' '.$parcours['spec'].'</h3>';
	if ($hors_ipgp) {
		$html .='<p>Ce parcours n\'est pas géré par l\'UFR, il n\'a donc pas d\'UE</p>';
	} else {
		// Récupération des ues
		$s_ues="SELECT id_ue, intitule FROM Unites_Enseignement ORDER BY intitule";
		$r_ues=mysql_query($s_ues);
		$ues=array();
		while ($d_ues=mysql_fetch_array($r_ues)) {
			$ues[$d_ues['id_ue']]=$d_ues['intitule'];
		}
		$ues[0]='Aucune ue choisie';
	
		// Récupération des choix de modules de l'étudiant
		$s_ue_etudiant="SELECT P.id_etudiant, EUE.id_type_ue, EUE.numero_option, EUE.id_ue, EUE.moyenne, EUE.id_annee_scolaire
				FROM l_parcours_etudiant P
				INNER JOIN l_etudiant_ue EUE
					ON P.id_etudiant=EUE.id_etudiant 
					AND P.id_annee_scolaire=EUE.id_annee_scolaire 
				WHERE P.id_annee_scolaire=".$parcours['id_annee_scolaire']."
				AND P.id_etudiant=".$id_etudiant;

		$r_ue_etudiant=mysql_query($s_ue_etudiant)
			or die('Impossible de récupérer les ues de l\'étudiant : <br/>'.mysql_error());
		$n_ue_etudiant=mysql_num_rows($r_ue_etudiant);
		$ues_etudiant=array();
		while ($d_ue_etudiant=mysql_fetch_array($r_ue_etudiant)){
			$ues_etudiant[$d_ue_etudiant['id_type_ue']][$d_ue_etudiant['numero_option']]=array(
							'id_ue' => $d_ue_etudiant['id_ue'],
							'id_annee_scolaire' => $d_ue_etudiant['id_annee_scolaire'],
							'moyenne' => $d_ue_etudiant['moyenne']);
		}
		
		if ($n_ue_etudiant==0) {
			$html.='<p style="font-weight:bold;">Les ues de l\'étudiant n\'ont pas été définies</p>';
		} else {
			$html .= '<p>Voici la liste des unités d\'enseignement que suit l\'étudiant en '.$parcours['annee'].'-'.($parcours['annee']+1).'</p>';	
					
			$ordre_ue=array('uc','us','uo','de','dx','di','ur','u2','HMaq');
			$s_type_ue="SELECT id_type_ue, libelle, abbreviation FROM a_type_ue WHERE abbreviation<>'F'";
			$r_type_ue=mysql_query($s_type_ue);
			$type_ue=array();
			while ($d_type_ue=mysql_fetch_array($r_type_ue)) {
				$type_ue[$d_type_ue['abbreviation']]=array('id_type_ue' => $d_type_ue['id_type_ue'], 
															'libelle' => $d_type_ue['libelle']);
			}
		
			foreach ($ordre_ue AS  $abbreviation) {
				if (!empty($ues_etudiant[$type_ue[$abbreviation]['id_type_ue']])) {
					$html .='<h2>'.$type_ue[$abbreviation]['libelle'].'</h2>';
					$html .='<ul style="margin-left:40px;">';
					foreach($ues_etudiant[$type_ue[$abbreviation]['id_type_ue']] AS $numero_option => $ue_etudiant) {
						$id_ue_etudiant=$ue_etudiant['id_ue'];
						$id_annee_scolaire=$ue_etudiant['id_annee_scolaire'];
						$moyenne=($ue_etudiant['moyenne']>=0)?$ue_etudiant['moyenne']:$codes_notes[$ue_etudiant['moyenne']];
						if ($mode=='r') {
							$html.='<li><strong>'.$numero_option.'</strong> '.$ues[$id_ue_etudiant].' - '.$moyenne.'</li>';
						} else {
							$html.='<li style="list-style-type:none;">'.$numero_option.' 
							<select name="ues['.$id_annee_scolaire.']['.$type_ue[$abbreviation]['id_type_ue'].']['.$numero_option.']">';
							$sel=($id_ue_etudiant==0)?'selected="selected"':'';
							$html.='<option value="0" '.$sel.'>'.$ue[0].'</option>';
	                                 
							foreach($ues as $id_ue => $libelle) {
	                        	$sel=($id_ue_etudiant==$id_ue)?'selected="selected"':'';
	                            $html.='<option value="'.$id_ue.'" '.$sel.'>'.$libelle.'</option>';
	                        }
	                        
	                        $html.='</select> Moyenne : '.$moyenne.'</li>';
		
						}
					}
					$html .='</ul>';
				}
			}
			$html.='<h2>TOTAL : '.$n_u_etudiant.'</h2>';
			
		}
	}
}


?>

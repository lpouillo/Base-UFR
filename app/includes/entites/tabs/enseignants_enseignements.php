<?php
$id_enseignant=$_POST['id'];
// Récupération des libellés pour l'évolution n+1
$s_evolution="SELECT id_evolution_cours, libelle FROM a_evolution_cours";
$r_evolution=mysql_query($s_evolution);
$evolutions=array();
while ($d_evolution=mysql_fetch_array($r_evolution)) {
	$evolutions[$d_evolution['id_evolution_cours']]=$d_evolution['libelle'];
}
if ($mode=='rw') {
	$html.='<ul>
			<li><a href="#" onClick="popupForm(\'ajout_enseignant_ue\')">Ajouter une nouvelle unité d\'enseignement</a></li> 	
			<li><a href="#" onClick="popupForm(\'ajout_enseignant_ueexterne\')">Ajouter une unité d\'enseignement externe</a></li>
			<li><a href="#" onClick="popupForm(\'ajout_hors_maquette\')">Ajouter une activité hors maquette</a></li>
			</ul>';
}
$s_ue="SELECT LEU.id_ue, UE.intitule, UE.id_ufr,
			LEU.heures_cours, LEU.heures_TD, LEU.heures_TP, LEU.heures_colle, LEU.heures_terrain,
			LEU.ng_cours, LEU.ng_TD, LEU.ng_TP, LEU.ng_colles, LEU.njours_terrain, LEU.`evolution_n+1`,
			S.libelle AS situation 
			FROM l_enseignant_ue LEU
			LEFT JOIN Unites_Enseignement UE 
				ON LEU.id_ue=UE.id_ue
			INNER JOIN a_situation S
				ON LEU.id_situation=S.id_situation
				AND LEU.id_enseignant=".$id_enseignant."
				AND LEU.id_annee_scolaire=".$id_annee_scolaire;

$r_ue=mysql_query($s_ue);
$data=array();
while ($d_ue=mysql_fetch_array($r_ue)) {
	$data[trim($d_ue['situation'])][$d_ue['id_ue']]=$d_ue;
}

foreach($data as $situation => $ue) {
	$html.='<h3>'.$situation.'</h3>
	<ul>';
	foreach ($ue as $id_ue => $heures) {
		$html.= '<li style="padding-bottom:4px;"><strong>'.$heures['intitule'].'</strong> 
		<img src="public/images/icons/personne_supprimer.png" height="16px"/>';
		if ($mode=='rw') {
				$html.='
				Cours : <input size="3" type="text" name="heures['.$id_ue.'][cours]" value="'.$heures['heures_cours'].'"/>h x
				<input size="1" type="text" name="heures['.$id_ue.'][ng_cours]" value="'.$heures['ng_cours'].'"/> groupes,
				TD : <input size="3" type="text" name="heures['.$id_ue.'][TD]" value="'.$heures['heures_TD'].'"/>h x
				<input size="1" type="text" name="heures['.$id_ue.'][ng_TD]" value="'.$heures['ng_TD'].'"/> groupes,
				TP : <input size="3" type="text" name="heures['.$id_ue.'][TP]" value="'.$heures['heures_TP'].'"/>h x
				<input size="1" type="text" name="heures['.$id_ue.'][ng_TP]" value="'.$heures['ng_TP'].'"/> groupes,
				Colles : <input size="3" type="text" name="heures['.$id_ue.'][colle]" value="'.$heures['heures_colle'].'"/>h x
				<input size="1" type="text" name="heures['.$id_ue.'][ng_colles]" value="'.$heures['ng_colles'].'"/> groupes,
				Terrain : <input size="3" type="text" name="heures['.$id_ue.'][terrain]" value="'.$heures['heures_terrain'].'"/>h x
				<input size="1" type="text" name="heures['.$id_ue.'][njours_terrain]" value="'.$heures['njours_terrain'].'"/> jours =>
				TOTAL : <span class="total_heure">'.(1.5*$heures['heures_cours']*$heures['ng_cours']+$heures['heures_TD']*$heures['ng_TD']
					+$heures['heures_TP']*$heures['ng_TP']+$heures['heures_colle']*$heures['ng_colles']+$heures['heures_terrain']*$heures['njours_terrain']).'</span>
				</li>
				<li style="padding-bottom:10px;">
				<select name="heures['.$id_ue.'][evolution]">';
				foreach($evolutions as $id_evolution => $libelle) {
					$sel=($id_evolution==$heures['evolution_n+1'])?'selected="selected"':'';
					$html.='<option value="'.$id_evolution.'" '.$sel.'>'.$libelle.'</option>';
				}
				$html.='</select>
				</li>';
		} elseif ($id_enseignant==$_SESSION['id_link']) {
			$html.= '<li style="padding-bottom:4px;"><strong>'.$heures['intitule'].'</strong><em> '.$heures['fonction'].'</em></li>
				<li style="padding-bottom:4px;" id="heures_'.$id_ue.'">
				Cours : <input size="3" type="text" name="heures['.$id_ue.'][cours]" value="'.$heures['heures_cours'].'"/>h x
				<input size="1" type="text" name="heures['.$id_ue.'][ng_cours]" value="'.$heures['ng_cours'].'"/> groupes,
				TD : <input size="3" type="text" name="heures['.$id_ue.'][TD]" value="'.$heures['heures_TD'].'"/>h x
				<input size="1" type="text" name="heures['.$id_ue.'][ng_TD]" value="'.$heures['ng_TD'].'"/> groupes,
				TP : <input size="3" type="text" name="heures['.$id_ue.'][TP]" value="'.$heures['heures_TP'].'"/>h x
				<input size="1" type="text" name="heures['.$id_ue.'][ng_TP]" value="'.$heures['ng_TP'].'"/> groupes,
				Colles : <input size="3" type="text" name="heures['.$id_ue.'][colle]" value="'.$heures['heures_colle'].'"/>h x
				<input size="1" type="text" name="heures['.$id_ue.'][ng_colles]" value="'.$heures['ng_colles'].'"/> groupes,
				Terrain : <input size="3" type="text" name="heures['.$id_ue.'][terrain]" value="'.$heures['heures_terrain'].'"/>h x
				<input size="1" type="text" name="heures['.$id_ue.'][njours_terrain]" value="'.$heures['njours_terrain'].'"/> jours =>
				TOTAL : <span id="total_heure">'.(1.5*$heures['heures_cours']*$heures['ng_cours']+$heures['heures_TD']*$heures['ng_TD']
					+$heures['heures_TP']*$heures['ng_TP']+$heures['heures_colle']*$heures['ng_colles']+$heures['heures_terrain']*$heures['njours_terrain']).'</span>
				<br/>
					<select name="heures['.$id_ue.'][evolution]">';
				foreach($evolutions as $id_evolution => $libelle) {
					$sel=($id_evolution==$heures['evolution_n+1'])?'selected="selected"':'';
					$html.='<option value="'.$id_evolution.'" '.$sel.'>'.$libelle.'</option>';
				}
				$html.='</select><br/><input type="button" value="Mettre à jour mes heures" onClick="updateHoraires('.$id_enseignant.','.$id_ue.')"/></li>';
		} else {
			$html.='<li style="padding-bottom:4px;"><strong>'.$heures['intitule'].'</strong> <em>'.$heures['fonction'].'</em> </li>
				<li style="padding-bottom:4px;">Cours : '.$heures['heures_cours'].'h x '.$heures['ng_cours'].' groupes, TD : '.$heures['heures_TD'].'h x '.$heures['ng_TD'].' groupes, 
				TP : '.$heures['heures_TP'].'h x '.$heures['ng_TP'].' groupes, Colles/soutien : '.$heures['heures_colle'].'h x '.$heures['ng_colles'].' groupes, 
				Terrain : '.$heures['heures_terrain'].'h x '.$heures['ng_terrains'].' terrain
				TOTAL : <span class="total_heure">'.(1.5*$heures['heures_cours']+$heures['heures_TD']+$heures['heures_TP']+$heures['heures_colle']+$heures['heures_terrain']).'</span>
				
				</li>';
		}
	}
	$html.='</ul>';
}


if ($mode=='rw') {
	$s_hm="SELECT HM.id_hors_maquette, HM.libelle, LEHM.decharge, LEHM.n_etudiant FROM a_hors_maquette HM 
			INNER JOIN l_enseignant_hors_maquette LEHM
				ON LEHM.id_hors_maquette=HM.id_hors_maquette
				AND LEHM.id_enseignant=".$id_enseignant."
				AND LEHM.id_annee_scolaire=".$id_annee_scolaire;
	$r_hm=mysql_query($s_hm);
	$n_hm=mysql_num_rows($r_hm);
	
	$html.='<h3>Activité hors maquette</h3>';
	if ($n_hm >0) {
		
		$html.='<ul style="margin-left:40px;">';
		while ($d_hm=mysql_fetch_array($r_hm)) {
			$html.='<li style="list-style-type:square">'.$d_hm['libelle'].' : 
				<input name="lehm['.$id_enseignant.'-'.$id_annee_scolaire.'-'.$d_hm['id_hors_maquette'].'][decharge]" value="'.$d_hm['decharge'].'" size="2"/>h x
				<input name="lehm['.$id_enseignant.'-'.$id_annee_scolaire.'-'.$d_hm['id_hors_maquette'].'][n_etudiant]" value="'.$d_hm['n_etudiant'].'" size="1"/>
				TOTAL : '.($d_hm['n_etudiant']*$d_hm['decharge']).'</li>';
		}
		$html.='</ul>';	
	} else {
		$html.='<p>Aucune activité hors maquette déclarée dans la base.</p>';
	}
}


?>

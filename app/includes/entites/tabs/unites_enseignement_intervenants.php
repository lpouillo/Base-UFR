<?php
$id_ue=$_POST['id'];
// Récupération des libellés pour l'évolution n+1
$s_evolution="SELECT id_evolution_cours, libelle FROM a_evolution_cours";
$r_evolution=mysql_query($s_evolution);
$evolutions=array();
while ($d_evolution=mysql_fetch_array($r_evolution)) {
	$evolutions[$d_evolution['id_evolution_cours']]=$d_evolution['libelle'];
}

// Récupération du responsable et des intervenants de l'UE
$s_intervenants="SELECT E.id_enseignant, E.nom, E.prenom, DS.libelle AS fonction, 
		LINK.heures_cours, LINK.heures_TD, LINK.heures_TP, LINK.heures_colle, LINK.heures_terrain,
		LINK.ng_cours, LINK.ng_TD, LINK.ng_TP, LINK.ng_colles, LINK.njours_terrain, LINK.`evolution_n+1`
		FROM Enseignants E 
		INNER JOIN l_enseignant_ue LINK 
			ON E.id_enseignant=LINK.id_enseignant 
			AND LINK.id_ue=".$id_ue." 
			AND LINK.id_situation IN (20,21) 
		INNER JOIN a_situation DS 
			ON LINK.id_situation=DS.id_situation
		WHERE LINK.id_annee_scolaire=10 
		ORDER BY LINK.id_situation";
$r_intervenants=mysql_query($s_intervenants);
$intervenants=array();
while ($d_intervenants=mysql_fetch_array($r_intervenants)) {
	foreach($d_intervenants as $champ => $valeur) {
		$intervenants[$d_intervenants['id_enseignant']][$champ]=$valeur;
	}
}
if (trim($intervenants[$_SESSION['id_link']]['fonction'])=='Responsable') {
	$mode='rw';	
} 
$aff=($mode=='rw')?'<a href="#" onClick="popupForm(\'ajout_intervenant\',\''.$id_ue.'\')">Ajouter un intervenant</a>':'';
// On fait un grosse liste 
$html.='<p>Voici la liste des personnes qui enseignent dans cet UE. '.$aff.'</p>
	<ul>';
foreach($intervenants as $id_intervenant => $d_intervenant) {
	if ($mode=='rw') {
		$html.= '<li style="padding-bottom:4px;"><h3>'.$d_intervenant['nom'].' '.$d_intervenant['prenom'].'</h3> <em>'.$d_intervenant['fonction'].'</em> 
			<img style="cursor:pointer" src="public/images/icons/personne_supprimer.png" height="16px" title="Cliquez pour supprimer l\'intervenant" 
				onclick="popupForm(\'supprimer_intervenant\',\''.$_POST['id'].'%'.$d_intervenant['id_enseignant'].'\')"/></li>
			<li style="padding-bottom:4px;">
			Cours : <input size="3" type="text" name="heures['.$d_intervenant['id_enseignant'].'][cours]" value="'.$d_intervenant['heures_cours'].'"/>h x
			<input size="1" type="text" name="heures['.$d_intervenant['id_enseignant'].'][ng_cours]" value="'.$d_intervenant['ng_cours'].'"/> groupes,
			TD : <input size="3" type="text" name="heures['.$d_intervenant['id_enseignant'].'][TD]" value="'.$d_intervenant['heures_TD'].'"/>h x
			<input size="1" type="text" name="heures['.$d_intervenant['id_enseignant'].'][ng_TD]" value="'.$d_intervenant['ng_TD'].'"/> groupes,
			TP : <input size="3" type="text" name="heures['.$d_intervenant['id_enseignant'].'][TP]" value="'.$d_intervenant['heures_TP'].'"/>h x
			<input size="1" type="text" name="heures['.$d_intervenant['id_enseignant'].'][ng_TP]" value="'.$d_intervenant['ng_TP'].'"/> groupes,
			Colles : <input size="3" type="text" name="heures['.$d_intervenant['id_enseignant'].'][colle]" value="'.$d_intervenant['heures_colle'].'"/>h x
			<input size="1" type="text" name="heures['.$d_intervenant['id_enseignant'].'][ng_colles]" value="'.$d_intervenant['ng_colles'].'"/> groupes,
			Terrain : <input size="3" type="text" name="heures['.$d_intervenant['id_enseignant'].'][terrain]" value="'.$d_intervenant['heures_terrain'].'"/>h x
			<input size="1" type="text" name="heures['.$d_intervenant['id_enseignant'].'][njours_terrain]" value="'.$d_intervenant['njours_terrain'].'"/> jours =>
			TOTAL : <span class="total_heure">'.(1.5*$d_intervenant['heures_cours']*$d_intervenant['ng_cours']+$d_intervenant['heures_TD']*$d_intervenant['ng_TD']
				+$d_intervenant['heures_TP']*$d_intervenant['ng_TP']+$d_intervenant['heures_colle']*$d_intervenant['ng_colles']+$d_intervenant['heures_terrain']*$d_intervenant['njours_terrain']).'</span>
			</li>
			<li style="padding-bottom:10px;">
			<select name="heures['.$d_intervenant['id_enseignant'].'][evolution]">';
			foreach($evolutions as $id_evolution => $libelle) {
				$sel=($id_evolution==$d_intervenant['evolution_n+1'])?'selected="selected"':'';
				$html.='<option value="'.$id_evolution.'" '.$sel.'>'.$libelle.'</option>';
			}
			$html.='</select></li>';
	} elseif ($d_intervenant['id_enseignant']==$_SESSION['id_link']) {
		$html.= '<li style="padding-bottom:4px;"><h3>'.$d_intervenant['nom'].' '.$d_intervenant['prenom'].'</h3><em> '.$d_intervenant['fonction'].'</em></li>
			<li style="padding-bottom:4px;" id="heures_'.$d_intervenant['id_enseignant'].'">
			Cours : <input size="3" type="text" name="heures['.$d_intervenant['id_enseignant'].'][cours]" value="'.$d_intervenant['heures_cours'].'"/>h x
			<input size="1" type="text" name="heures['.$d_intervenant['id_enseignant'].'][ng_cours]" value="'.$d_intervenant['ng_cours'].'"/> groupes,
			TD : <input size="3" type="text" name="heures['.$d_intervenant['id_enseignant'].'][TD]" value="'.$d_intervenant['heures_TD'].'"/>h x
			<input size="1" type="text" name="heures['.$d_intervenant['id_enseignant'].'][ng_TD]" value="'.$d_intervenant['ng_TD'].'"/> groupes,
			TP : <input size="3" type="text" name="heures['.$d_intervenant['id_enseignant'].'][TP]" value="'.$d_intervenant['heures_TP'].'"/>h x
			<input size="1" type="text" name="heures['.$d_intervenant['id_enseignant'].'][ng_TP]" value="'.$d_intervenant['ng_TP'].'"/> groupes,
			Colles : <input size="3" type="text" name="heures['.$d_intervenant['id_enseignant'].'][colle]" value="'.$d_intervenant['heures_colle'].'"/>h x
			<input size="1" type="text" name="heures['.$d_intervenant['id_enseignant'].'][ng_colles]" value="'.$d_intervenant['ng_colles'].'"/> groupes,
			Terrain : <input size="3" type="text" name="heures['.$d_intervenant['id_enseignant'].'][terrain]" value="'.$d_intervenant['heures_terrain'].'"/>h x
			<input size="1" type="text" name="heures['.$d_intervenant['id_enseignant'].'][njours_terrain]" value="'.$d_intervenant['njours_terrain'].'"/> jours =>
			TOTAL : <span id="total_heure">'.(1.5*$d_intervenant['heures_cours']*$d_intervenant['ng_cours']+$d_intervenant['heures_TD']*$d_intervenant['ng_TD']
				+$d_intervenant['heures_TP']*$d_intervenant['ng_TP']+$d_intervenant['heures_colle']*$d_intervenant['ng_colles']+$d_intervenant['heures_terrain']*$d_intervenant['njours_terrain']).'</span>
			<br/>
				<select name="heures['.$d_intervenant['id_enseignant'].'][evolution]">';
			foreach($evolutions as $id_evolution => $libelle) {
				$sel=($id_evolution==$d_intervenant['evolution_n+1'])?'selected="selected"':'';
				$html.='<option value="'.$id_evolution.'" '.$sel.'>'.$libelle.'</option>';
			}
			$html.='</select><br/><input type="button" value="Mettre à jour mes heures" onClick="updateHoraires('.$d_intervenant['id_enseignant'].','.$id_ue.')"/></li>';
	} else {
		$html.='<li style="padding-bottom:4px;"><h3>'.$d_intervenant['nom'].' '.$d_intervenant['prenom'].'</h3> <em>'.$d_intervenant['fonction'].'</em> </li>
			<li style="padding-bottom:4px;">Cours : '.$d_intervenant['heures_cours'].'h x '.$d_intervenant['ng_cours'].' groupes, TD : '.$d_intervenant['heures_TD'].'h x '.$d_intervenant['ng_TD'].' groupes, 
			TP : '.$d_intervenant['heures_TP'].'h x '.$d_intervenant['ng_TP'].' groupes, Colles/soutien : '.$d_intervenant['heures_colle'].'h x '.$d_intervenant['ng_colles'].' groupes, 
			Terrain : '.$d_intervenant['heures_terrain'].'h x '.$d_intervenant['ng_terrains'].' terrain
			TOTAL : <span class="total_heure">'.(1.5*$d_intervenant['heures_cours']+$d_intervenant['heures_TD']+$d_intervenant['heures_TP']+$d_intervenant['heures_colle']+$d_intervenant['heures_terrain']).'</span>
			
			</li>';
	}
}
$html.='</ul></p>';
?>

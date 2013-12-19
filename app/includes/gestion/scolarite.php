<?php
$html='<div class="content_tab"><h2><img src="public/images/icons/parametres.png"/> Scolarité</h2>
	<h3><img src="public/images/icons/definition.png"/> Définition des unités d\'enseignement par parcours</h3>';
// Formulaire de sélection du couple niveau/spec pour l'année scolaire voulue
$html.='<p>Veuillez choisir l\'année scolaire, le niveau et la spécialité afin de définir les unités d\'enseignement</p>
		<form id="choix_scolarite" method="post" action="#">
		<table style="margin-left:20px;">';

$s_annee_scolaire="SELECT id_annee_scolaire, libelle FROM a_annee_scolaire ORDER BY annee_debut DESC";
$r_annee_scolaire=mysql_query($s_annee_scolaire)
	or die('Impossible de récupérer les années scolaires :<br/>'.mysql_error());
$html.='</td></tr><tr><th>Année scolaire</th>';
while ($d_annee_scolaire=mysql_fetch_array($r_annee_scolaire)) {
	if (!empty($d_annee_scolaire['id_annee_scolaire'])) {
		$html.='<td><input type="radio" name="id_annee_scolaire" value="'.$d_annee_scolaire['id_annee_scolaire'].'"/>'.$d_annee_scolaire['libelle'].'</td>';
	} else {
		$html.='<td>&nbsp;</td>';
	}
}

$html.='</tr><tr><th>Niveau</th>';
$s_niveau="SELECT id_niveau, abbreviation FROM a_niveau WHERE gestion=1 ORDER BY abbreviation";
$r_niveau=mysql_query($s_niveau)
	or die('Impossible de récupérer les niveaux :<br/>'.mysql_error());
while ($d_niveau=mysql_fetch_array($r_niveau)) {
	if (!empty($d_niveau['id_niveau'])) {
		$html.='<td><input type="radio" name="id_niveau" value="'.$d_niveau['id_niveau'].'"/>'.$d_niveau['abbreviation'].'</td>';
	} else {
		$html.='<td>&nbsp;</td>';
	}
}
$html.='</tr><tr><th>Spécialité</th>';
$s_specialite="SELECT id_specialite, abbreviation FROM a_specialite WHERE gestion=1 ORDER BY abbreviation";
$r_specialite=mysql_query($s_specialite)
	or die('Impossible de récupérer les spécialités:<br/>'.mysql_error());
while ($d_specialite=mysql_fetch_array($r_specialite)) {
	if (!empty($d_specialite['id_specialite'])) {
		$html.='<td><input type="radio" name="id_specialite" value="'.$d_specialite['id_specialite'].'"/>'.$d_specialite['abbreviation'].'</td>';
	} else {
		$html.='<td>&nbsp;</td>';
	}
	
}
$html.='</tr></table>
		<input type="hidden" name="page" value="gestion"/>
		<input type="hidden" name="section" value="scolarite"/>
		<input type="hidden" name="choix_scolarite" value="nombre_ue"/>
		</form>
		<p><input type="submit" value="Définir les ues" onclick="submitForm(\'choix_scolarite\');"/></p>';

$html.='<h3><img src="public/images/icons/importation.png"/> Attribution des UE par défaut</h3> 
		<p><input type="submit" value="Lancer l\'attribution" onclick="submitForm(\'update_interne\')"/></p></form>';


$html.'</div>';
/*
if (empty($_POST['choix_scolarite'])) {
	// Formulaire de sélection du couple niveau/spec pour l'année scolaire voulue
	$html.='<p>Veuillez choisir l\'année scolaire, le niveau et la spécialité afin de définir les unités d\'enseignement</p>
			<form id="choix_scolarite" method="post" action="#">
			<table>';
	
	$s_annee_scolaire="SELECT id_annee_scolaire, libelle FROM a_annee_scolaire ORDER BY annee_debut DESC";
	$r_annee_scolaire=mysql_query($s_annee_scolaire)
		or die('Impossible de récupérer les années scolaires :<br/>'.mysql_error());
	$html.='</td></tr><tr><th>Année scolaire</th><td>';
	while ($d_annee_scolaire=mysql_fetch_array($r_annee_scolaire)) {
		$html.='<input type="radio" name="id_annee_scolaire" value="'.$d_annee_scolaire['id_annee_scolaire'].'"/>'.$d_annee_scolaire['libelle'];
	}
	
	$html.='</td></tr><tr><th>Niveau</th><td>';
	$s_niveau="SELECT id_niveau, abbreviation FROM a_niveau WHERE gestion=1 ORDER BY abbreviation";
	$r_niveau=mysql_query($s_niveau)
		or die('Impossible de récupérer les niveaux :<br/>'.mysql_error());
	while ($d_niveau=mysql_fetch_array($r_niveau)) {
		$html.='<input type="radio" name="id_niveau" value="'.$d_niveau['id_niveau'].'"/>'.$d_niveau['abbreviation'];
	}
	$html.='</td></tr><tr><th>Spécialité</th><td>';
	$s_specialite="SELECT id_specialite, abbreviation FROM a_specialite WHERE gestion=1 ORDER BY abbreviation";
	$r_specialite=mysql_query($s_specialite)
		or die('Impossible de récupérer les spécialités:<br/>'.mysql_error());
	while ($d_specialite=mysql_fetch_array($r_specialite)) {
		$html.='<input type="radio" name="id_specialite" value="'.$d_specialite['id_specialite'].'"/>'.$d_specialite['abbreviation'];
	}
	$html.='</td></tr></table>
			<input type="hidden" name="page" value="gestion"/>
			<input type="hidden" name="section" value="scolarite"/>
			<input type="hidden" name="choix_scolarite" value="nombre_ue"/>
			</form>
			<p><input type="submit" value="Définir les ues" onclick="submitForm(\'choix_scolarite\');"/></p>';
} elseif ($_POST['choix_scolarite']=='nombre_ue') {
	// Récupération du libellé du parcours
	$s_parcours="SELECT NI.libelle AS niveau, SP.libelle AS specialite 
				FROM a_niveau NI, a_specialite SP
				WHERE id_niveau=".$_POST['id_niveau']." 
				AND id_specialite=".$_POST['id_specialite'];
	$r_parcours=mysql_query($s_parcours);
	$d_parcours=mysql_fetch_array($r_parcours);
	$html .='<p><strong>'.$d_parcours['niveau'].' - '.$d_parcours['specialite'].'</strong></p>';
	
	$s_n_ue="SELECT id_type_ue, COUNT(id_ue) AS nb  FROM l_parcours_ue 	
					WHERE id_niveau=".$_POST['id_niveau']." 
				AND id_specialite=".$_POST['id_specialite']."
				AND id_annee_scolaire=".$_POST['id_annee_scolaire']."
				GROUP BY id_type_ue";
	$r_n_ue=mysql_query($s_n_ue)
		or die('Impossible de récupérer le nombre d\'ue par type');
	while ($d_n_ue=mysql_fetch_array($r_n_ue)) {
		$n_ue[$d_n_ue['id_type_ue']]=$d_n_ue['nb'];
	}
	
	$html.='<form id="definition_n_ues">
			<input type="hidden" name="page" value="gestion"/>
			<input type="hidden" name="section" value="scolarite"/>
			<input type="hidden" name="choix_scolarite" value="choix_des_ues"/>
			<input type="hidden" name="id_annee_scolaire" value="'.$_POST['id_annee_scolaire'].'"/>
			<input type="hidden" name="id_niveau" value="'.$_POST['id_niveau'].'"/>
			<input type="hidden" name="id_specialite" value="'.$_POST['id_specialite'].'"/>
			';
	// Combien d'ue de chaque type pour le trièdre sélectionné
	$html.='<p>Définissez le nombre d\'unité d\'enseignement de chaque type</p>';
	$s_type_ue="SELECT id_type_ue, libelle FROM a_type_ue WHERE abbreviation<>'F'";
	$r_type_ue=mysql_query($s_type_ue)
		or die('Impossible de récupérer les types d\'ue :<br/>'.mysql_error());
	while ($d_type_ue=mysql_fetch_array($r_type_ue)) {
		$html .='<h3>'.$d_type_ue['libelle'].'</h3><p><select name="nombre_ue_'.$d_type_ue['id_type_ue'].'">';
		for ($i=0;$i<=20;$i++) {
			$sel=($i==$n_ue[$d_type_ue['id_type_ue']])?'selected="selected"':'';
			$html.='<option value="'.$i.'" '.$sel.'>'.$i.'</option>'; 
		}
		$html.='</select></p>';
	}
	$html.='<input type="submit" value="Etape suivante" onclick="submitForm(\'definition_n_ues\');"/>';
	$html.='</form>';
} elseif ($_POST['choix_scolarite']=='choix_des_ues') {
	$s_parcours="SELECT NI.libelle AS niveau, SP.libelle AS specialite 
				FROM a_niveau NI, a_specialite SP
				WHERE id_niveau=".$_POST['id_niveau']." 
				AND id_specialite=".$_POST['id_specialite'];
	$r_parcours=mysql_query($s_parcours);
	$d_parcours=mysql_fetch_array($r_parcours);
	$html .='<p><strong>'.$d_parcours['niveau'].' - '.$d_parcours['specialite'].'</strong></p>';
	
	$s_old_parcours_ue="SELECT numero_option, id_type_ue, id_ue  
						FROM l_parcours_ue
						WHERE id_niveau=".$_POST['id_niveau']." 
						AND id_specialite=".$_POST['id_specialite']." 
						AND id_annee_scolaire=".$_POST['id_annee_scolaire'];
	$r_old_parcours_ue=mysql_query($s_old_parcours_ue);
	$old_ue=array();
	while ($d_old_parcours_ue=mysql_fetch_array($r_old_parcours_ue)) {
		$old_ue[$d_old_parcours_ue['id_type_ue']][$d_old_parcours_ue['numero_option']]=$d_old_parcours_ue['id_ue'];
	}
	
	$s_type_ue="SELECT id_type_ue, libelle FROM a_type_ue WHERE abbreviation<>'F'";
	$r_type_ue=mysql_query($s_type_ue)
		or die('Impossible de récupérer les types d\'ue :<br/>'.mysql_error());
	$html.='<form id="affectation_ue_defaut">
			<input type="hidden" name="page" value="gestion"/>
			<input type="hidden" name="section" value="scolarite"/>
			<input type="hidden" name="choix_scolarite" value="resume"/>
			<input type="hidden" name="id_annee_scolaire" value="'.$_POST['id_annee_scolaire'].'"/>
			<input type="hidden" name="id_niveau" value="'.$_POST['id_niveau'].'"/>
			<input type="hidden" name="id_specialite" value="'.$_POST['id_specialite'].'"/>';
	
	while ($d_type_ue=mysql_fetch_array($r_type_ue)) {
		if ($_POST['nombre_ue_'.$d_type_ue['id_type_ue']]>0) {
			$html .='<h3>'.$d_type_ue['libelle'].'</h3><ol style="margin-left:20px;">';
			$s_ue="SELECT UE.id_ue, UE.intitule 
						FROM Unites_Enseignement UE ORDER BY UE.intitule";
			$r_ue=mysql_query($s_ue);
			$ue=array();
			while ($d_ue=mysql_fetch_array($r_ue)) {
				$ue[$d_ue['id_ue']]=$d_ue['intitule'];
			}
			
			for ($i=1;$i<=$_POST['nombre_ue_'.$d_type_ue['id_type_ue']];$i++) {
				$d_ue=mysql_fetch_array($r_ue);
				$html.='<li><select name="ue_'.$_POST['id_niveau'].'_'.$_POST['id_specialite'].'_'.
					$_POST['id_annee_scolaire'].'_'.$i.'_'.$d_type_ue['id_type_ue'].'">';
				$html .='<option value="0">Aucune</option>';	
				foreach ($ue AS $id_ue=>$intitule) {
					
					$sel=($id_ue==$old_ue[$d_type_ue['id_type_ue']][$i])?'selected="selected"':'';
					$html.='<option value="'.$id_ue.'" '.$sel.'>'.$intitule.'</option>';
				}
				
				
				$html.='</li>';
			}
			$html.='</ol>';
		}
		
	}
	$html.='<input type="submit" value="Etape suivante" onclick="submitForm(\'affectation_ue_defaut\');"/>';
	$html.='</form>';
	
	
	
} else {
	$s_delete="DELETE FROM l_parcours_ue 
				WHERE id_niveau=".$_POST['id_niveau'].'
				AND id_specialite='.$_POST['id_specialite'].'
				AND id_annee_scolaire='.$_POST['id_annee_scolaire'];
	$r_delete=mysql_query($s_delete)
		or die ('Impossible de supprimer les ues par défaut');
	
				echo '<pre>';
				print_r($_POST);				
				echo '</pre>';
		
	$s_insert_parcours_ue="INSERT INTO l_parcours_ue (`date_in`,`id_niveau`,`id_specialite`,`id_annee_scolaire`,`numero_option`,`id_type_ue`,`id_ue`) 
							VALUES ";	
	
	foreach($_POST as $key => $element) {
		
		switch (substr($key,0,3)) {
			case 'ue_':
				$split_key=split('_',$key);
				$s_insert_parcours_ue .="(CURDATE(),'".$split_key[1]."','".$split_key[2]."','".$split_key[3]."','".$split_key[4]."','".$split_key[5]."','".$element."'), ";				
			break;
		}
	}
	
	echo substr($s_insert_parcours_ue,0,-2);
	$r_insert_parcours_ue=mysql_query(substr($s_insert_parcours_ue,0,-2))
		or die ('Impossible de soummettre les ues par défaut ...<br/>'.mysql_error());
	$html .='<p>Les ues par défaut ont été définies</p>';	
		
}*/
?>
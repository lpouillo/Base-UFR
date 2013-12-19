<?php
if (empty($_POST['id_ue'])) {
	$html.='<form action="#" method="POST" enctype="multipart/form-data">
			<p style="margin-left:20px"><select name="id_ue">';
		
	// Choix de l'UE
	$s_ue="SELECT id_ue, intitule FROM Unites_Enseignement ORDER BY intitule";
	$r_ue=mysql_query($s_ue);
	while ($d_ue=mysql_fetch_array($r_ue)) {
		$html.='<option value="'.$d_ue['id_ue'].'">'.$d_ue['intitule'].'</option>';
	}
	$html.='</select><br/><select name="id_annee_scolaire">';
	// Choix de l'année scolaire
	$s_annee_scolaire="SELECT id_annee_scolaire, annee_debut FROM a_annee_scolaire ORDER BY annee_debut DESC";
	$r_annee_scolaire=mysql_query($s_annee_scolaire);
	while ($d_annee_scolaire=mysql_fetch_array($r_annee_scolaire)) {
		$html.='<option value="'.$d_annee_scolaire['id_annee_scolaire'].'">'.$d_annee_scolaire['annee_debut'].'-'.($d_annee_scolaire['annee_debut']+1).'</option>';
	}
	$html.='</select><br/>
			<input type="submit" value="Étape suivante"/>
			<input type="hidden" name="force_template" value="yes"/>
			<input type="hidden" name="page" value="gestion"/>
			<input type="hidden" name="section" value="notes"/>
			<input type="hidden" name="action_notes" value="notes_ue"/>
			</p></form>';
} elseif (empty($_FILES['notes_ue'])) {
	/*echo '<pre>';
	print_r($_POST);
	echo '</pre>';*/
	$html.='<form action="#" method="POST" enctype="multipart/form-data">
		<p><select name="id_evaluation"><option value="0">Nouvelle évaluation</option>';
	// Est-ce une nouvelle évaluation ?
	
	$s_eval="SELECT id_evaluation,libelle FROM Evaluations WHERE id_ue=".$_POST['id_ue'].' AND id_annee_scolaire='.$_POST['id_annee_scolaire'];
	$r_eval=mysql_query($s_eval);
	while ($d_eval=mysql_fetch_array($r_eval)) {
		$html.='<option value="'.$d_eval['id_evaluation'].'">'.$d_eval['libelle'].'</option>';
	}
	$html.='</select>';
	
	
	$html.= '<br/>Sélectionner le fichier à importer : id_etudiant ; nom ; prenom ; note
			<input type="file" name="notes_ue"/> <br/>
			<input type="submit" value="Lancer l\'importation des notes"/>
			<input type="hidden" name="force_template" value="yes"/>
			<input type="hidden" name="page" value="gestion"/>
			<input type="hidden" name="section" value="notes"/>
			<input type="hidden" name="action_notes" value="notes_ue"/>
			<input type="hidden" name="id_ue" value="'.$_POST['id_ue'].'"/>
			<input type="hidden" name="id_annee_scolaire" value="'.$_POST['id_annee_scolaire'].'"/>
			</p></form>';
} else {
	if ($_FILES['notes_ue']['size']==0) {
		echo 'FICHIER VIDE.';
	} else {
		// Création de l'évaluation
		if ($_POST['id_evaluation']==0) {
			$s_ins_eval="INSERT INTO Evaluations (`id_evaluation`,`id_ue`,`coefficient`,`id_annee_scolaire`,`libelle`) 
				VALUES ('','".$_POST['id_ue']."','1','".$_POST['id_annee_scolaire']."','non précisé')";
			$r_ins_eval=mysql_query($s_ins_eval);
			$id_evaluation=mysql_insert_id();
			$html.= 'Une nouvelle évaluation a été ajoutée.<br/><br/>';
		} else {
			$id_evaluation=$_POST['id_evaluation'];
		}
		
		$s_replace_notes="REPLACE INTO Notes (`date_in`,`valeur`,`id_etudiant`,`id_evaluation`,`validante`) VALUES ";
		
		$fp = fopen ($_FILES['notes_ue']['tmp_name'], 'r');
		$n_lignes=0;
		while (!feof($fp)) {	
			$ligne = fgets($fp,8192);
			$ligne = explode (';',$ligne);
			for ($i=0;$i<sizeof($ligne);$i++) {
				$ligne[$i]=trim(str_replace('"','',$ligne[$i]));
			}
						
			if ($ligne[0] != 'E.id_etudiant' and $ligne[0]!='') {
				$s_replace_notes.="(CURDATE(),'".str_replace(",",".",$ligne[3])."','".$ligne[0]."','".$id_evaluation."','1'), ";
			}	
			
		}
		$s_replace_notes=substr($s_replace_notes,0,-2);
		$r_replace_notes=mysql_query($s_replace_notes);		
		if ($r_replace_notes) {
			$html.='Les notes ont bien été prises en compte. 
					Cliquez <a href="#" onclick="affElement(\''.$_POST['id_ue'].'\',\'entites\',\'unites_enseignement\',\'\',\'content\')">ici</a> 
					pour vérifier que tout s\'est bien passé.';
		} else {
			echo mysql_error();
		}
	}
}
?>

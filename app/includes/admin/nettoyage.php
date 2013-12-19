<h2><img src="public/images/icons/nettoyage.png"/> Nettoyage des données</h2>
<div class="content_tab">
<?php
if (!isset($_POST['type_nettoyage'])) {
	echo '<form id="nettoyage" method="post" action="#">
		<input type="hidden" name="page" value="admin"/>
		<input type="hidden" name="section" value="nettoyage"/>
		<p><input type="radio" name="type_nettoyage" value="doublons_ue"/> Fusionner des UE<br/>
		<input type="radio" name="type_nettoyage" value="correction_ue_M1_2009_2010"/> Corriger les UE optionnelles en UE hors maquette pour les M1<br/>
		<input type="radio" name="type_nettoyage" value="uid_etudiants" checked="checked"/> Déterminer les uid des étudiants<br/>
		<input type="radio" name="type_nettoyage" value="encadrants_stages" checked="checked"/> Vérifications des encadrants de stages<br/>
		<input type="submit" value="Choisir l\'élément à nettoyer" onclick="submitForm(\'nettoyage\')"/></p></form>';	
} else {
	switch($_POST['type_nettoyage']) {
		case 'encadrants_stages';
			$s_labo="SELECT ES1.id_stage, S.sujet, ES1.id_type_encadrant 
				FROM l_encadrant_stage ES1
				INNER JOIN Stages_Laboratoires S
					ON ES1.id_stage=S.id_stage_laboratoire
				WHERE ES1.id_type_encadrant IN (1,2,3)
				UNION
				SELECT ES2.id_stage, S.sujet, ES2.id_type_encadrant
				FROM l_encadrant_stage ES2
				INNER JOIN Stages_Entreprises S
					ON ES2.id_stage=S.id_stage_entreprise
				WHERE ES2.id_type_encadrant IN (7,8)";
			$r_labo=mysql_query($s_labo);
			$count=0;
			while ($d_labo==mysql_fetch_array($r_labo)) {
				if ($d_labo['sujet']==''){
					$count++;
				}
			}
			echo $count.' entrées sont incorrectes';
		case 'doublons_ue':
			if (!isset($_POST['ue_a_garder'])) {
				$s_ues="SELECT id_ue,intitule,date_in FROM Unites_Enseignement ORDER BY intitule";
				$r_ues=mysql_query($s_ues);
				$ues=array();
				while ($d_ues=mysql_fetch_array($r_ues)) {
					$ues[$d_ues['id_ue']]=array('intitule' => $d_ues['intitule'], 'date_in' => $d_ues['date_in']);
				}
				$html ='<h4>Fusion des UE</h4>';
				$html .='<p>Choisissez les ues à fusionner :</p>
						<form id="fusion_ue" method="post" action="#">
						<input type="hidden" name="page" value="admin"/>
						<input type="hidden" name="section" value="nettoyage"/>
						<input type="hidden" name="type_nettoyage" value="doublons_ue" />';
				$html .= 'UE à garder : <select name="ue_a_garder">';
				foreach ($ues as $id_ue => $ue) {
					$html.='<option value="'.$id_ue.'">'.$ue['intitule'].' '.$ue['date_in'].'</option>';
				}
				$html.='</select><br/>';
				$html .= 'UE à supprimer <select name="ue_a_supprimer">';
				foreach ($ues as $id_ue => $ue) {
					$html.='<option value="'.$id_ue.'">'.$ue['intitule'].' '.$ue['date_in'].'</option>';
				}
				$html.='</select>
				<p><input type="submit" value="Fusionner les ue" onclick="submitForm(\'fusion_ue\')"/></p></form>';
			} else {
				if ($_POST['ue_a_garder']==$_POST['ue_a_supprimer']) {
					die ('Vous avez choisi la même UE ... tête d\'ananas !! ');
				} else {
					$s_old_intitule="SELECT intitule FROM Unites_Enseignement WHERE id_ue=".$_POST['ue_a_supprimer'];
					$r_old_intitule=mysql_query($s_old_intitule);
					$d_old_intitule=mysql_fetch_array($r_old_intitule);
					$s_replace_old_intitule="REPLACE INTO l_intitule_ue (`id_ue`,`id_annee_scolaire`,`intitule`) 
								VALUES ('".$_POST['ue_a_garder']."','9','".addslashes($d_old_intitule['intitule'])."')";
					mysql_query($s_replace_old_intitule) 
						or die('impossible de rentrer l\'ancien intitule.');
					$s_update_etudiant_ue="UPDATE l_etudiant_ue SET id_ue=".$_POST['ue_a_garder']." 
											WHERE id_ue=".$_POST['ue_a_supprimer'];
					mysql_query($s_update_etudiant_ue)
						or die('impossible de mettre à jour les choix des etudiants');
					$s_update_ouverture_ue="UPDATE l_ouverture_ue SET id_ue=".$_POST['ue_a_garder']." 
											WHERE id_ue=".$_POST['ue_a_supprimer']." AND id_annee_scolaire=9";
					mysql_query($s_update_ouverture_ue)
						or die ('impossible de mettre à jour les ouvertures de l\'année dernière');
					$s_update_encadrant_ue="UPDATE l_enseignant_ue SET id_ue=".$_POST['ue_a_garder']." 
											WHERE id_ue=".$_POST['ue_a_supprimer']." AND id_annee_scolaire=9";
					mysql_query($s_update_encadrant_ue) 
						or die ('impossible de mettre à jour les encadrants pour l\'année dernière<br/>'.mysql_error());
					$s_delete_ue="DELETE FROM Unites_Enseignement WHERE id_ue=".$_POST['ue_a_supprimer'];
					mysql_query($s_delete_ue) 
						or die ('impossible de supprimer la vieille UE');
					
				}
			}
			
		break;
		case 'uid_etudiants' :
			$s_etudiants="SELECT id_etudiant, email_ipgp, uid FROM Etudiants";
			$r_etudiants=mysql_query($s_etudiants);
			while ($d_etudiants=mysql_fetch_array($r_etudiants)) {
				if ($d_etudiants['uid']=='') {
					$pos_ipgp=strpos($d_etudiants['email_ipgp'],'ipgp');
					$pos_at=strpos($d_etudiants['email_ipgp'],'@');
					
					if ($pos_ipgp!==false) {
					//	echo $d_etudiants['email_ipgp'].'=>'.$pos_at.' '.$pos_ipgp.'<br/>';
						$new_uid=substr($d_etudiants['email_ipgp'],0,$pos_at);
						$s_update="UPDATE Etudiants SET uid='".$new_uid."' WHERE id_etudiant=".$d_etudiants['id_etudiant'];
						echo $s_update.'<br/>';
						mysql_query($s_update)
							or die (mysql_error());
					}
				}
			}
		break;
		case 'correction_ue_M1_2009_2010':
			$s_etudiant_M1="SELECT id_etudiant FROM l_parcours_etudiant WHERE id_annee_scolaire=10 AND id_niveau=6";
			$r_etudiant_M1=mysql_query($s_etudiant_M1);
			$n_etudiant_M1=mysql_num_rows($r_etudiant_M1);
			while ($d_etudiant_M1=mysql_fetch_array($r_etudiant_M1)) {
				$s_update="UPDATE l_etudiant_ue SET id_type_ue=18 WHERE id_etudiant=".$d_etudiant_M1['id_etudiant']." AND id_annee_scolaire=10 AND id_type_ue=3";
				mysql_query($s_update);
			}
		break;
	}
	
}
$html.='</div>';

?>

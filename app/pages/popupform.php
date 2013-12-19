<?php
if (empty($_POST['form_submitted'])) {
	if ($_POST['section']!='horaires') {
		$html.='<h3>'.$_POST['section'].'</h3><form id="frm_'.$_POST['section'].'" target="if_'.$_POST['section'].'" action="index.php?page=popupform" method="post">
			<input type="hidden" name="form_submitted" value="oui"/> 
			<input type="hidden" name="id" value="'.$_POST['id'].'"/>
			<input type="hidden" name="section" value="'.$_POST['section'].'"/>
			<input type="hidden" name="page" value="popupform"/>
			<input type="hidden" name="form_submitted" value="yes"/>';
	}
	$id=0;
	switch($_POST['section']) {
		case 'ajout_enseignant':
		case 'ajout_dir_stage_labo':	
		case 'ajout_codir_stage_labo_1':
		case 'ajout_codir_stage_labo_2':
		case 'ajout_tuteur_stage_entreprise_1':
		case 'ajout_tuteur_stage_entreprise_2':
			require_once('app/includes/entites/tabs/enseignants_infos.php');
		break;
		case 'ajout_stage_laboratoire':
			$tmp=explode('_',$_POST['section']);
			require_once('app/includes/entites/tabs/stages_laboratoires_infos.php');
		break;
		
			
		case 'upload':
			$html.='Choisir un fichier :
			<input type="file" name="tmp_photo"/>';
		break;
		case 'ajout_hors_maquette':
		case 'ajout_parcours':
		case 'ajout_responsabilite':
		case 'ajout_ue_externe':
		case 'ajout_evaluation':
		case 'ajout_intervenant':
		case 'supprimer_intervenant':
		case 'supprimer_evaluation':
			if (file_exists('app/includes/popupforms/'.$_POST['section'].'.php')) {
				require_once('app/includes/popupforms/'.$_POST['section'].'.php');
			} else {
				$html.='<p>Ce formulaire n\'a pas été implémenté.</p>';
			}
		break;
		case 'ajout_contact':
		case 'ajout_maitre_stage_entreprise_1':
		case 'ajout_maitre_stage_entreprise_2':
			require_once('app/includes/entites/tabs/professionnels_infos.php');
		break;
		case 'ajout_tuteur_stage_entreprise_1':
		case 'ajout_tuteur_stage_entreprise_2':
			require_once('app/includes/entites/tabs/enseignants_infos.php');
		break;		
		case 'horaires':
			require_once('app/includes/popupforms/horaires.php');
		break;
		case 'bug_report':
			require_once('app/includes/popupforms/bugreport.php');
		break;
		default:
			require_once('app/includes/popupforms/form_liste.php');
	}
	if ($_POST['section']!='horaires') {
		$html.='<p style="text-align:center;"><input type="submit" value="VALIDER"/> <a href="#" onclick="cancelPopupForm();">Annuler</a></p>
			</form>	
			<iframe name="if_'.$_POST['section'].'" style="display:none;" src="app/pages/blank.html">';
	}
	
} else {
	switch($_POST['section']) {
		case 'bug_report':
			$s_bug="INSERT INTO s_bug (`id_bug`,`date_in`,`login`,`type_bug`,`descriptif`) 
				VALUES ('',CURDATE(),'".$_SESSION['login']."','".secure_mysql($_POST['type_bug'])."','".secure_mysql($_POST['descriptif'])."')";	
			$r_bug=mysql_query($s_bug)
				or die(mysql_error());
			$to      = 'pouillou@ipgp.fr';
			$subject = '[Base UFR] BUG : '.$_SESSION['login'].' a soumis un bug';
			$message = $_POST['descriptif'];
			$headers = 'From: base-ufr@ipgp.fr' . "\r\n" .
						'X-Mailer: PHP/' . phpversion();
			$headers .= 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
			
			mail($to, $subject, $message, $headers);
		break;
		case 'ajout_contact':
		case 'ajout_maitre_stage_entreprise_1':
		case 'ajout_maitre_stage_entreprise_2':
			$id_new=insert_table('Professionnels');
			$html.='<script>
					var select_a_maj=parent.document.getElementById(\''.$_POST['id'].'\');
					select_a_maj.add(new Option("'.$_POST['nom'].' '.$_POST['prenom'].'", "'.$id_new.'",false,true),null);
					parent.cancelPopupForm();
				</script>';
		break;
		case 'ajout_parcours':
			$s_insert="REPLACE INTO l_parcours_etudiant (`date_in`,`date_modif`,`id_etudiant`,`id_annee_scolaire`,`id_niveau`,`id_specialite`,`id_etablissement`)
				VALUES (CURDATE(),CURDATE(),'".$_POST['id']."','".$_POST['id_annee_scolaire']."','".$_POST['id_niveau']."',
				'".$_POST['id_specialite']."','".$_POST['id_etablissement']."')";
			mysql_query($s_insert)
				or die($s_insert.'<br/>'.mysql_error()); 	
			$html.='<script>
				parent.cancelPopupForm();
				parent.affElement(\''.$_POST['id'].'\',\'entites\',\'etudiants\',\'modifier\',\'content\')
				</script>';
		break;
		case 'ajout_evaluation':
			$s_insert_evaluation="INSERT INTO Evaluations (`id_evaluation`,`id_ue`,`id_annee_scolaire`,`libelle`,`id_type_evaluation`,`coefficient`,
			`note_maximale`,`bonus`) VALUES ('','".$_POST['id']."','".$_POST['id_annee_scolaire']."','".$_POST['libelle']."',
			'".$_POST['id_type_evaluation']."','".$_POST['coefficient']."','".$_POST['note_maximale']."','".$_POST['bonus']."')";			
			mysql_query($s_insert_evaluation)
				or die(mysql_error());
			$id_eval=mysql_insert_id();
			$s_etudiants="SELECT id_etudiant FROM l_etudiant_ue WHERE id_ue='".$_POST['id']."' AND id_annee_scolaire='".$_POST['id_annee_scolaire']."'";
			$r_etudiants=mysql_query($s_etudiants);
			while ($d_etudiants=mysql_fetch_array($r_etudiants)) {
				$s_note="INSERT INTO Notes (`date_in`,`valeur`,`id_etudiant`,`id_evaluation`) VALUES (
					CURDATE(),'-3','".$d_etudiants['id_etudiant']."','".$id_eval."')";
				mysql_query($s_note) 
				 or die($s_note.'<br/>'.mysql_error());
			}
			$html.='<script>
				parent.cancelPopupForm();
				parent.affElement(\''.$_POST['id'].'\',\'entites\',\'unites_enseignement\',\'modifier\',\'content\');
				</script>';
		break;
		case 'supprimer_evaluation':
			$s_delete="DELETE FROM Evaluations WHERE id_evaluation=".$_POST['id_evaluation'];
			mysql_query($s_delete)
				or die(mysql_error()); 
			$s_del_notes="DELETE FROM Notes WHERE id_evaluation=".$_POST['id_evaluation'];
			mysql_query($s_del_notes)
				or die(mysql_error());
			$html.='<script>
				parent.cancelPopupForm();
				parent.affElement(\''.$_POST['id_ue'].'\',\'entites\',\'unites_enseignement\',\'modifier\',\'content\');
				</script>';
		break;
		case 'ajout_intervenant':
			$s_insert="INSERT INTO l_enseignant_ue (`id_ue`,`id_enseignant`,`id_situation`,`id_annee_scolaire`) VALUES
				('".$_POST['id']."','".$_POST['id_new_intervenant']."','".$_POST['id_situation']."','".$id_annee_scolaire."')";
			
			mysql_query($s_insert)
				or die($s_insert.'<br/>'.mysql_error());
			$html.='<script>
				parent.cancelPopupForm();
				parent.affElement(\''.$_POST['id'].'\',\'entites\',\'unites_enseignement\',\'modifier\',\'content\');
				</script>';
		break;
		case 'supprimer_intervenant':
			$s_delete="DELETE FROM l_enseignant_ue WHERE id_enseignant='".$_POST['id_intervenant']."' AND id_ue='".$_POST['id_ue']."' 
				AND id_annee_scolaire='".$id_annee_scolaire."'";
			mysql_query($s_delete) 
				or die($s_delete.'<br/>'.mysql_error());
			$html.='<script>
				parent.cancelPopupForm();
				parent.affElement(\''.$_POST['id_ue'].'\',\'entites\',\'unites_enseignement\',\'modifier\',\'content\');
				</script>';
		break;
		default:
			switch($_POST['section']) {	
				case 'id_pays':
					$table='a_pays';
				break;
				case 'id_niveau':
					$table='a_niveau';
				break;
				case 'id_ville_naissance':
				case 'id_ville_scol':
				case 'id_ville_perm':
				case 'id_ville_pro':
				case 'id_ville_perso':
					$table='a_ville';
					$champ='libelle';
				break;
				case 'id_entreprise':
					$table='Entreprises';
					$champ='nom';
				break;
				case 'id_etablissement':
					$table='Etablissements';
					$champ='nom';
				break;
				case 'id_laboratoire':
					$table='Laboratoires';
					$champ='nom';	
				break;
				default:
					$table='a_'.substr($_POST['section'],3);
					$champ='libelle';
			}
			$s_insert_element="INSERT INTO ".$table." (`date_in`,`".$champ."`) VALUES (CURDATE(),'".addslashes($_POST['libelle'])."')";
			mysql_query($s_insert_element) or die(mysql_error());
						
			$html.='<script>
					var select_a_maj=parent.document.getElementById(\''.$_POST['section'].'\');
					select_a_maj.add(new Option("'.$_POST['libelle'].'", "'.mysql_insert_id().'",false,true),null);
					parent.cancelPopupForm();
				</script>';
	}
}

echo $html;
?>

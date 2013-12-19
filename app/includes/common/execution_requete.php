<?php
/* 
 * 
 * Script centralisant les requètes effectuées dans la base de données
 * 
*/
$test=array();
if (isset($_POST['modification_soumise']) and isset($_SESSION['id_user'])) {
	// Définition de la table à mettre à jour
	switch($_POST['section']) {
		case 'mes_infos':
			$table='Enseignants';
		break;
		case 'etudiants':
			$table='Etudiants';
			$autres=array('scolarite','unites_enseignement'); 
		break;
		case 'enseignants':
			$table='Enseignants';
			$autres=array('enseignements'); 
		break;
		case 'mes_cours':
		case 'unites_enseignement':
			$table='Unites_Enseignement';
			$autres=array('ouvertures','intervenants','notes');
		break;
		case 'mes_stages':
		case 'stages_laboratoires':
			$table='Stages_Laboratoires';
			$autres=array('ouvertures','encadrants');
		break;
		case 'stages_entreprises':
			$table='Stages_Entreprises';
			$autres=array('ouvertures','encadrants');
		break;
		case 'cas_etudes':
			$table='Cas_Etudes';
			$autres=array('ouvertures','encadrants');
		break;
		case 'mes_doctorats':
		case 'doctorats':
			$table='Doctorats';
			$autres=array('encadrants','financements','soutenance');
		break;
		case 'emplois':
			$table='Emplois';
		break;
		case 'entreprises':
			$table='Entreprises';
		break;
		case 'laboratoires':
			$table='Laboratoires';
		break;
		case 'professionnels':
			$table='Professionnels';
		break;
		case 'annexes':
			$table=$_POST['table'];
		break;
		case 'responsabilites':
			foreach($_POST['decharge'] as $id_responsabilite => $heures) {
				$s_update="UPDATE Responsabilites SET decharge_horaire='".$heures."', id_enseignant='".$_POST['resp'][$id_responsabilite]."'
					WHERE id_responsabilite='".$id_responsabilite."'";
				mysql_query($s_update)
					or die($s_update.'<br/>'.mysql_error());
			}
		break;
	}
	switch($_POST['action']) {
		case 'ajouter':
			$_POST['id']=insert_table($table);
			$action='modifier';
			$_POST['action']='modifier';
		break;
		case 'modifier':
			$test[]=update_table($table);
			if (sizeof($autres)>0) {
				foreach($autres as $update) {
					//echo $id_annee_scolaire;
					autres_update($update,$_POST['section']);
				}
			}
		break;
		case 'supprimer':
			$test[]=delete_table($table);
			$action='';
		/*	if (sizeof($autres)>0) {
				foreach($autres as $delete) {
					autres_delete($delete,$_POST['section']);
				}
			}*/
		break;
	}
}
/*
 * 
 * 		foreach ($_POST AS $k_ouv => $ouv) {
								$split_ouv=explode('_',$k_ouv);
								if ($split_ouv[0]=='ouv') {
									$id_ue=$split_ouv[1];
									$id_niveau=$split_ouv[2];
									$id_specialite=$split_ouv[3];
									$sql_replace="REPLACE INTO l_ouverture_stage (`id_stage`,`id_specialite`,`id_niveau`,`id_type_stage`,`id_annee_scolaire`,`ouvert`)" .
											" VALUES (".$_POST['id'].",".$id_specialite.",".$id_niveau.",1,".$id_annee_scolaire.",".$ouv.")";
									mysql_query($sql_replace) 
										or die($sql_replace.'<br/>'.mysql_error());
								}
							}
						// mise à jour des encadrants
							$s_encadrants="REPLACE INTO l_encadrant_stage (`id_stage`,`id_encadrant`,`date_modif`,`id_type_encadrant`,`id_annee_scolaire`) VALUES 
									(".$_POST['id'].",".$_POST['contact'].",CURDATE(),4,".$id_annee_scolaire."),
									(".$_POST['id'].",".$_POST['maitre_stage_entreprise_1'].",CURDATE(),5,".$id_annee_scolaire."),
									(".$_POST['id'].",".$_POST['maitre_stage_entreprise_2'].",CURDATE(),6,".$id_annee_scolaire."),
									(".$_POST['id'].",".$_POST['tuteur_stage_entreprise_1'].",CURDATE(),7,".$id_annee_scolaire."),
									(".$_POST['id'].",".$_POST['tuteur_stage_entreprise_2'].",CURDATE(),8,".$id_annee_scolaire.")";
							//echo $s_encadrants;
							$r_encadrants=mysql_query($s_encadrants);
					
 * switch($_POST['action']) {
						case 'modifier':
							$id_ue=$_POST['id'];
							// données de l'UE
							$test[]=update_table('Unites_Enseignement');
							// intervenants
							if (sizeof($_POST['heures'])!=0) {
								foreach($_POST['heures'] as $id_enseignant => $enseignant) {
									$s_update="UPDATE l_enseignant_ue SET heures_cours='".$enseignant['cours']."', heures_TD='".$enseignant['TD']."',
												heures_TP='".$enseignant['TP']."', heures_colle='".$enseignant['colle']."', heures_terrain='".$enseignant['terrain']."',
												ng_cours='".$enseignant['ng_cours']."', ng_TD='".$enseignant['ng_TD']."', ng_TP='".$enseignant['ng_TP']."', 
												ng_colles='".$enseignant['ng_colles']."', njours_terrain='".$enseignant['njours_terrain']."', `evolution_n+1`='".$enseignant['evolution']."'
												WHERE id_enseignant=".$id_enseignant." AND id_ue=".$id_ue." AND id_annee_scolaire=".$id_annee_scolaire;
							
									$test[]=mysql_query($s_update);;
								}
							}
							// evaluations et notes
							if (sizeof($_POST['evaluations'])!=0) {
								foreach($_POST['evaluations'] as $id_evaluation => $evaluation) {
									$s_replace_evaluation="REPLACE INTO Evaluations (`id_evaluation`,`id_ue`,`libelle`,`id_type_evaluation`,`coefficient`,`note_maximale`,`bonus`,`id_annee_scolaire`)
											VALUES ('".$id_evaluation."','".$id_ue."','".$evaluation['libelle']."','".$evaluation['id_type_evaluation']."',
											'".$evaluation['coefficient']."','".$evaluation['note_maximale']."','".$evaluation['bonus']."','".$evaluation['id_annee_scolaire']."') ";
									$test[]=mysql_query($s_replace_evaluation);;				
								}
								$s_update_notes="";
								
							if (sizeof($_POST['notes'])!=0) {
									foreach($_POST['notes'] as $id_evaluation => $notes) {
										foreach ($notes as $id_etudiant => $note) {
											$s_update_note="REPLACE INTO Notes (`id_etudiant`,`id_evaluation`,`valeur`)
											VALUES ('".$id_etudiant."','".$id_evaluation."','".$note."')";
											$test[]=mysql_query($s_update_note);
										}
									} 
								}
							}
						break;
					}
				break;
 
						break;
						case 'supprimer':
							delete_table('Etudiants');
							$action='';
						break;
	switch($_POST['modification_soumise']) {
		/* AJOUT D'OBJETS /
		case 'add_annonce':
			$add_annonce=new data('INSERT','Annonces','','','','','');
			$add_annonce->execution_requete('ajouter');
			$id_new_annonce=mysql_insert_id();
		break;
		case 'add_etudiant':
		// ajout d'un étudiant
			$add_etudiant=new data('INSERT','Etudiants','','','','','');
			$add_etudiant->execution_requete('ajouter');
			$id_new_etudiant=mysql_insert_id();
		break;
		case 'add_enseignant':
		// ajout d'un enseignant
			$add_enseignant=new data('INSERT','Enseignants','','','','','');
			$add_enseignant->execution_requete('ajouter');
			$id_new_enseignant=mysql_insert_id();
		break;
		case 'add_etablissement':
		// ajout d'un établissement
			$add_etablissement=new data('INSERT','Etablissements','','','','','');
			$add_etablissement->execution_requete('ajouter');
			$id_new_etablissement=mysql_insert_id();
		case 'add_entreprise':
		// ajout d'une entreprise
			$add_entreprise=new data('INSERT','Entreprises','','','','','');
			$add_entreprise->execution_requete('ajouter');
			$id_new_entreprise=mysql_insert_id();
		break;
		case 'add_laboratoire';
			$add_laboratoire=new data('INSERT','Laboratoires','','','','','');
			$add_laboratoire->execution_requete('ajouter');
			$id_new_laboratoire=mysql_insert_id();	
		break;
		case 'add_professionnel':
		// ajout d'un professionnel		
			$add_professionnel=new data('INSERT','Professionnels','','','','','');
			$add_professionnel->execution_requete('ajouter');
			$id_new_professionnel=mysql_insert_id();	
			
		case 'add_ue':
		// ajout d'une unités d'enseignement
			$add_ue=new data('INSERT','Unites_Enseignement','','','','','');
			$add_ue->execution_requete('ajouter');
			$id_new_ue=mysql_insert_id();	
			// creation d'une ouverture pour que l'UE soit affiché dans la liste des UE
			$s_create_ouv="INSERT INTO ouverture_ue (`id_ue`,`id_specialite`,`id_niveau`,`id_type_ue`) VALUES ('".$id_new_ue."','121','2','5')";
			$r_create_ouv=mysql_query($s_create_ouv);
		break;
		case 'add_utilisateur':
			$_POST['password']=md5($_POST['password']);
			$add_utilisateur=new data('INSERT','s_users','','','','','');
			$add_utilisateur->execution_requete('ajouter');
			$id_new_ue=mysql_insert_id();	
		break;
		case 'add_stage_labo':
		// ajout d'un stage en laboratoire
			$add_stage_labo = new data('INSERT','Stages_Laboratoires','','','','','');
			$add_stage_labo->execution_requete('ajouter');
			$id_new_stage_labo=mysql_insert_id();
		break;
		case 'add_stage_entreprise':
		// ajout d'un stage en entreprise
			$add_stage_labo = new data('INSERT','Stages_Entreprises','','','','','');
			$add_stage_labo->execution_requete('ajouter');
			$id_new_stage_entreprise=mysql_insert_id();
		break;
		case 'add_annexes':
			$update_annexes=new data('INSERT',$_POST['table'],'','','','','');			
			$update_annexes->execution_requete('ajouter');
			$id_new_annexes=mysql_insert_id();
		break;
		// MODIFICATIONS D'OBJETS //
		case 'update_infos':
		// mise à jour des infos de l'enseignant
			$update_infos=new data('UPDATE','Enseignants','',array($_POST['id_enseignant']),'','','');
			$update_infos->execution_requete('modifier');
		break;
		case 'update_annonce':
		// mise à jour des infos de l'étudiant
			$update_annonce=new data('UPDATE','Annonces','',array($_POST['id_annonce']),'','','');
			$update_annonce->execution_requete('modifier');
		break;
		case 'update_etudiant':
		// mise à jour des infos de l'étudiant
			$update_etudiant=new data('UPDATE','Etudiants','',array($_POST['id_etudiant']),'','','');
			$update_etudiant->execution_requete('modifier');
		// Mise à jour du parcours de l'étudiant
			$s_annee_scolaire="SELECT id_annee_scolaire FROM a_annee_scolaire ORDER BY annee_debut DESC";
			$r_annee_scolaire=mysql_query($s_annee_scolaire);
			while ($d_annee_scolaire=mysql_fetch_array($r_annee_scolaire)) {
				$year=$d_annee_scolaire['id_annee_scolaire'];
				$sql_replace="REPLACE INTO l_parcours_etudiant (`date_in`,`date_modif`,`id_etudiant`,`id_annee_scolaire`,`id_niveau`,`id_specialite`,`id_etablissement`," .
						"`note_moyenne`,`classement`,`id_mention`,`avis_jury`) " .
						"VALUES (NOW(),NOW(),".$_POST['id_etudiant'].",".$year.",".$_POST['id_niveau_'.$year].",".$_POST['id_specialite_'.$year].", ".
								$_POST['id_etablissement_'.$year].",'".$_POST['note_moyenne_'.$year]."','".$_POST['classement_'.$year]."'," .
										"'".$_POST['id_mention_'.$year]."','".addslashes($_POST['avis_jury_'.$year])."')";
				
				mysql_query($sql_replace) or die(mysql_error());
				
				// Vérification que le parcours de l'année est un parcours STEP
				if ($_POST['id_etablissement_'.$year]!=70 and $_POST['id_etablissement_'.$year]!=83) {
					// plus dans l'établissement pour l'année $year => on vire ses ues pour cette année
					$s_delete_ues="DELETE FROM l_etudiant_ue WHERE id_etudiant=".$_POST['id_etudiant']." AND id_annee_scolaire=".$year;
					$r_delete_ues=mysql_query($s_delete_ues)
						or die(mysql_error());
				} else {
					// on vérifie que la spécialité et le niveau sont bien en STEP
					$s_niv="SELECT gestion FROM a_niveau WHERE id_niveau=".$_POST['id_niveau_'.$year];
					$r_niv=mysql_query($s_niv);
					$d_niv=mysql_fetch_array($r_niv);
					$s_spec="SELECT gestion FROM a_specialite WHERE id_specialite=".$_POST['id_specialite_'.$year];
					$r_spec=mysql_query($s_spec);
					$d_spec=mysql_fetch_array($r_spec);
					if (!$d_niv['gestion'] or !$d_spec['gestion']) {
						$s_delete_ues="DELETE FROM l_etudiant_ue WHERE id_etudiant=".$_POST['id_etudiant']." AND id_annee_scolaire=".$year;
						$r_delete_ues=mysql_query($s_delete_ues);
					}
				}
			} 
		
			
		break;
		case 'update_enseignant':
		// mise à jour de l'enseignant
			$update_enseignant=new data('UPDATE','Enseignants','',array($_POST['id_enseignant']),'','','');
			$update_enseignant->execution_requete('modifier');
						
		// mise à jour de ses enseignements
			
			
		}
		// mise à jour de ses encadrements
		break;
		case 'update_etablissement':
		// mise à jour des infos de l'enseignant
			$update_etablissement=new data('UPDATE','Etablissements','',array($_POST['id_etablissement']),'','','');
			$update_etablissement->execution_requete('modifier');
		break;
		case 'update_laboratoire':
		// mise à jour des infos de l'enseignant
			$update_labo=new data('UPDATE','Laboratoires','',array($_POST['id_laboratoire']),'','','');
			$update_labo->execution_requete('modifier');
		break;
		case 'update_ue':
		// mise à jour des infos de l'unité d'enseignement
			$update_ue=new data('UPDATE','Unites_Enseignement','',array($_POST['id_ue']),'','','');
			$update_ue->execution_requete('modifier');
		// mise à jour des ouvertures de l'unité d'enseignement
		foreach ($_POST AS $k_ouv => $ouv) {
			$split_ouv=split('_',$k_ouv);
			if ($split_ouv[0]=='ouv') {
				$id_ue=$split_ouv[1];
				$id_niveau=$split_ouv[2];
				$id_specialite=$split_ouv[3];
				$sql_replace="REPLACE INTO l_ouverture_ue (`id_ue`,`id_specialite`,`id_niveau`,`id_type_ue`,`id_annee_scolaire`)" .
						" VALUES (".$id_ue.",".$id_specialite.",".$id_niveau.",".$ouv.",".$id_annee_scolaire.")";
				
				mysql_query($sql_replace) or die(mysql_error());
			}
		}
		// Mise à jour des responsables et des intervenants
		if ($_POST['new_enseignant_ue']!=0) {
			$s_insert_enseignant="INSERT INTO l_enseignant_ue (`id_ue`,`id_enseignant`,`id_situation`,`id_annee_scolaire`) 
							VALUES (".$_POST['id_ue'].",".$_POST['new_enseignant_ue'].",".$_POST['new_enseignant_ue_type'].",".$id_annee_scolaire.")";
			mysql_query($s_insert_enseignant)
				or die('Impossible d\'insérer le nouvel enseignant :<br/>'.mysql_error());
		}
		foreach($_POST as $libelle => $valeur) {
			if (substr($libelle,0,18)=='del_enseignant_ue_') {
				$s_delete_enseignant="DELETE FROM l_enseignant_ue WHERE id_ue=".$_POST['id_ue']." AND id_enseignant=".$valeur." AND id_annee_scolaire=".$id_annee_scolaire;
				mysql_query($s_delete_enseignant) 
					or die('Impossible de supprimer l\'enseignant :<br/>'.mysql_error());
			}
		}
		// Mise à jour des heures enseignants
		
		// Mise à jour des évaluations
		
	
		
		break;
		case 'update_stage_labo':
		// mise à jour des infos pour le stage en laboratoire
			$update_stage_labo=new data('UPDATE','Stages_Laboratoires','',array($_POST['id_stage_laboratoire']),'','','');
			$update_stage_labo->execution_requete('modifier');
		// mise à jour des ouvertures
			foreach ($_POST AS $k_ouv => $ouv) {
				$split_ouv=split('_',$k_ouv);
				if ($split_ouv[0]=='ouv') {
					$id_ue=$split_ouv[1];
					$id_niveau=$split_ouv[2];
					$id_specialite=$split_ouv[3];
					$sql_replace="REPLACE INTO l_ouverture_stage (`id_stage`,`id_specialite`,`id_niveau`,`id_type_stage`,`id_annee_scolaire`,`ouvert`)" .
							" VALUES (".$_POST['id_stage_laboratoire'].",".$id_specialite.",".$id_niveau.",0,".$id_annee_scolaire.",".$ouv.")";
					mysql_query($sql_replace) or die($sql_replace .'<br/>'.mysql_error());
				}
			}
		// mise à jour des encadrants
			$s_encadrants="REPLACE INTO l_encadrant_stage (`id_stage`,`id_encadrant`,`date_modif`,`id_type_encadrant`,`id_annee_scolaire`) VALUES 
					(".$_POST['id_stage_laboratoire'].",".$_POST['dir_stage_labo'].",CURDATE(),1,".$id_annee_scolaire."),
					(".$_POST['id_stage_laboratoire'].",".$_POST['codir_stage_labo_1'].",CURDATE(),2,".$id_annee_scolaire."),
					(".$_POST['id_stage_laboratoire'].",".$_POST['codir_stage_labo_2'].",CURDATE(),3,".$id_annee_scolaire.")";
//			echo $s_encadrants;
			$r_encadrants=mysql_query($s_encadrants);
		break;
		case 'update_stage_entreprise':
		// mise à jour des infos pour le stage en entreprise
			$update_stage_entreprise=new data('UPDATE','Stages_Entreprises','',array($_POST['id_stage_entreprise']),'','','');
			$update_stage_entreprise->execution_requete('modifier');
		// mise à jour des ouvertures
			foreach ($_POST AS $k_ouv => $ouv) {
				$split_ouv=split('_',$k_ouv);
				if ($split_ouv[0]=='ouv') {
					$id_ue=$split_ouv[1];
					$id_niveau=$split_ouv[2];
					$id_specialite=$split_ouv[3];
					$sql_replace="REPLACE INTO l_ouverture_stage (`id_stage`,`id_specialite`,`id_niveau`,`id_type_stage`,`id_annee_scolaire`,`ouvert`)" .
							" VALUES (".$_POST['id_stage_entreprise'].",".$id_specialite.",".$id_niveau.",1,".$id_annee_scolaire.",".$ouv.")";
					mysql_query($sql_replace) or die(mysql_error());
				}
			}
		// mise à jour des encadrants
			$s_encadrants="REPLACE INTO l_encadrant_stage (`id_stage`,`id_encadrant`,`date_modif`,`id_type_encadrant`,`id_annee_scolaire`) VALUES 
					(".$_POST['id_stage_entreprise'].",".$_POST['contact'].",CURDATE(),4,".$id_annee_scolaire."),
					(".$_POST['id_stage_entreprise'].",".$_POST['maitre_stage_entreprise_1'].",CURDATE(),5,".$id_annee_scolaire."),
					(".$_POST['id_stage_entreprise'].",".$_POST['maitre_stage_entreprise_2'].",CURDATE(),6,".$id_annee_scolaire."),
					(".$_POST['id_stage_entreprise'].",".$_POST['tuteur_stage_entreprise_1'].",CURDATE(),7,".$id_annee_scolaire."),
					(".$_POST['id_stage_entreprise'].",".$_POST['tuteur_stage_entreprise_2'].",CURDATE(),8,".$id_annee_scolaire.")";
	//		echo $s_encadrants;
			$r_encadrants=mysql_query($s_encadrants);
			
		break;
		case 'update_entreprise':
		// mise à jour des infos pour l'entreprise			
			$update_entreprise=new data('UPDATE','Entreprises','',array($_POST['id_entreprise']),'','','');
			$update_entreprise->execution_requete('modifier');
		break;
		case 'update_professionnel':
		// mise à jour des infos pour le professionnel
			$update_professionnel=new data('UPDATE','Professionnels','',array($_POST['id_professionnel']),'','','');
			$update_professionnel->execution_requete('modifier');
		break;
		case 'update_annexes':
			$s_id="SHOW FIELDS FROM ".$_POST['table'];
			$r_id=mysql_query($s_id);
			$d_id=mysql_fetch_array($r_id);
			$update_annexes=new data('UPDATE',$_POST['table'],'',array($_POST[$d_id[0]]),'','','');
			$update_annexes->execution_requete('modifier');
			$id_new_annexes=$_POST[$d_id[0]];
		break;

		/* SUPPRESSION D'OBJETS /
		case 'delete_annonce':
		// suppression de l'étudiant
			$delete_annonce=new data('DELETE','Annonces','','','','','');
			$delete_annonce->execution_requete('supprimer');
		break;
		case 'delete_etudiant':
		// suppression de l'étudiant
			$delete_etudiant=new data('DELETE','Etudiants','','','','','');
			$delete_etudiant->execution_requete('supprimer');
		// suppression de TOUTES ses données
			$s_delete_ue="DELETE FROM l_etudiant_ue WHERE id_etudiant=".$_POST['id_etudiant'];
			$r_delete_ue=mysql_query($s_delete_ue);
			$s_delete_parcours="DELETE FROM l_parcours_etudiant WHERE id_etudiant=".$_POST['id_etudiant'];
			$r_delete_parcours=mysql_query($s_delete_parcours);
		break;
		case 'delete_enseignant':
		// suppression de l'enseignant
			$delete_enseignant=new data('DELETE','Enseignants','','','','','');
			$delete_enseignant->execution_requete('supprimer');
		break;
		case 'delete_laboratoire':
		// suppression du laboratoire
			$delete_enseignant=new data('DELETE','Laboratoires','','','','','');
			$delete_enseignant->execution_requete('supprimer');
		break;
		case 'delete_ue':
		// suprresion de l'UE
			$delete_ue=new data('DELETE','Unites_Enseignement','','','','','');
			$delete_ue->execution_requete('supprimer');
			// Suppression des données annexes
			$delete_ue_ouverture="DELETE FROM ouverture_ue WHERE id_ue=".$_POST['id_ue'];
			mysql_query($delete_ue_ouverture);
		break;
		case 'delete_stage_labo':
			$delete_stage_labo=new data('DELETE','Stages_Laboratoires','','','','','');
			$delete_stage_labo->execution_requete('supprimer');
		break;
		case 'delete_stage_entreprise':
			$delete_stage_labo=new data('DELETE','Stages_Entreprises','','','','','');
			$delete_stage_labo->execution_requete('supprimer');
			// Suppression des encadrants et des ouvertures
			$s_del_encadrant="DELETE FROM l_encadrant_stage WHERE id_stage=".$_POST['id_stage_entreprise']." 
				AND id_type_encadrant IN (4,5,6,7,8)";
			mysql_query($s_del_encadrant);
			$s_del_ouverture="DELETE FROM l_ouverture_stage WHERE id_stage=".$_POST['id_stage_entreprise']." 
				AND id_type_stage=1";
			mysql_query($s_del_encadrant);
		break;
		case 'delete_entreprise':
			$delete_entreprise=new data('DELETE','Entreprises','','','','','');
			$delete_entreprise->execution_requete('supprimer');
		break;
		case 'delete_professionnel':
			$delete_professionnel=new data('DELETE','Professionnels','','','','','');
			$delete_professionnel->execution_requete('supprimer');
		break;
		case 'delete_utilisateur':
			$delete_user=new data('DELETE','s_users','','','','','');
			$delete_user->execution_requete('supprimer');
		break;
		case 'delete_annexes':
			$s_id="SHOW FIELDS FROM ".$_POST['table'];
			$r_id=mysql_query($s_id);
			$d_id=mysql_fetch_array($r_id);
			$_POST['id']=$_POST[$d_id[0]];
			$update_annexes=new data('DELETE',$_POST['table'],'','','','','');
			$update_annexes->execution_requete('supprimer');
			$_POST['action']='voir';
		break;
		case 'responsabilites':
			foreach($_POST as $resp => $id_enseignant) {
				$tmp=split('_',$resp);
			
				if ($tmp[0]=='resp') {	
					$id_responsabilite=$tmp[1];
					$s_update_resp="UPDATE a_responsabilite SET id_enseignant=".$id_enseignant." WHERE id_responsabilite=".$id_responsabilite; 
					$r_update_resp=mysql_query($s_update_resp)
						or die(mysql_error());
				}
			}
		break;
		default:
		$log_request=$autresication.' n\'est pas encore implémenté, ';
	}
	
	if($error_requete) {
		$log_request.='Erreur à l\'execution de l\'une des requètes';
		$log_request_class='error';
	} else {
		$log_request.=$autresication.' executée avec succès';
		$log_request_class='succes';
		
		if ($_SESSION['group']=='enseignant') {
			$to      = 'base-ufr@ipgp.fr';
			$subject = '[Base UFR] MODIFICATION : '.$_SESSION['login'].' a fait : '.$autresication;
			$message='';
			foreach ($_POST AS $key => $element) {
				$message .= $key.' : '.$element.'<br/>';	
			}
			$headers = 'From: base-ufr@ipgp.fr' . "\r\n" .
			    	    'X-Mailer: PHP/' . phpversion();
			$headers .= 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
			
			
			mail($to, $subject, $message, $headers);
		}
	}	
	
} else {
	$log_request='';
}
*/

?>

<?php
if (empty($_FILES['importation_stages'])) {
	$html.='<form action="#" method="POST" enctype="multipart/form-data">' .
			'Sélectionner le fichier à importer : ' .
			'<input type="file" name="importation_stages"/> '.
			'<input type="checkbox" name="TRUNCATE" value="oui"/> Supprimer les anciennes donnees '.
			'<input type="submit" value="Lancer l\'importation"/>' .
			'<input type="hidden" name="type_importation" value="stages_entreprises"/>' .
			'<input type="hidden" name="page" value="admin"/>' .
			'<input type="hidden" name="section" value="importation"/>' .
			'<input type="hidden" name="force_template" value="yes"/>'.
			'</form>';
	
	
} else {
	$fp = fopen ($_FILES['importation_stages']['tmp_name'], 'r');
	// élimination de la première ligne
	$ligne = fgetcsv($fp,8096,';','"');
	
	while (($ligne = fgetcsv($fp,8096,',','"'))!== false) {
		if ($ligne[1]=='Y') {
			/*echo '<pre>';
			print_r($ligne);
			echo '</pre>';*/
			// ENTREPRISE
			$nom_entreprise=$ligne[4];
			$pres_entreprise=$ligne[5];
			$www_entreprise=$ligne[6];
			// CONTACT
			$nom_contact=$ligne[7];
			$prenom_contact=$ligne[8];
			$email_contact=$ligne[9];
			$tel_contact=$ligne[10];
			// SERVICE
			$nom_service=$ligne[11];
			$adresse_service=$ligne[12];
			$cp_service=$ligne[13];
			$ville_service=$ligne[14];
			if ($ligne[15]!='') {			
				$pays_service=$ligne[15];
			} else {
				$pays_service=$ligne[16];
			}
			// OUVERTURES
			$ouv_L2=($ligne[17]=='Oui')?1:0;
			$ouv_L3=($ligne[18]=='Oui')?1:0;
			$ouv_M1=($ligne[19]=='Oui')?1:0;
			$ouv_M2=($ligne[20]=='Oui')?1:0;
			$ouv_gei=($ligne[21]=='Oui')?1:0;
			$ouv_g2s=($ligne[22]=='Oui')?1:0;
			$ouv_gl=($ligne[23]=='Oui')?1:0;
			$ouv_ssng=($ligne[24]=='Oui')?1:0;
			// DETAIL DU STAGE
			$sujet=$ligne[25];
			$description=$ligne[26];
			$remuneration=$ligne[27];
			$embauche=$ligne[28];
			$infos_complementaires=$ligne[29];
			
			$html.='Traitement du stage <strong>'.$sujet.'</strong><ul>';
			
			// Création de la ville, du pays si besoin
			$s_pays="SELECT id_pays FROM a_pays WHERE libelle='".addslashes($pays_service)."'";
			$r_pays=mysql_query($s_pays);
			$test_pays=mysql_num_rows($r_pays);
			if ($test_pays) {
				$d_pays=mysql_fetch_array($r_pays);
				$id_pays=$d_pays['id_pays'];
			} else {
				$s_insert_pays="INSERT INTO a_pays (`id_pays`,`date_in`,`libelle`) VALUES ('',NOW(),'".addslashes($pays_service)."')";
				$test_insert_pays=mysql_query($s_insert_pays);
				if ($test_insert_pays) {
					$html.='<li>Le nouveau pays '.$pays_service.' a été ajouté.</li>';
					$id_pays=mysql_insert_id();
				} else {
					die('<p>Le nouveau pays '.$pays_service.' n\'a pas pu être ajouté.</p>');
				}
			}
			$s_ville="SELECT id_ville FROM a_ville WHERE libelle='".addslashes($ville_service)."'";
			$r_ville=mysql_query($s_ville);
			$test_ville=mysql_num_rows($r_ville);
			if ($test_ville) {
				$d_ville=mysql_fetch_array($r_ville);
				$id_ville=$d_ville['id_ville'];
			} else {
				$s_insert_ville="INSERT INTO a_ville (`id_ville`,`date_in`,`libelle`) VALUES ('',NOW(),'".addslashes($ville_service)."')";
				$test_insert_ville=mysql_query($s_insert_ville);
				if ($test_insert_ville) {
					$html.='<li>La nouvelle ville '.$ville_service.' a été ajoutée.</li>';
					$id_ville=mysql_insert_id();
				} else {
					die('La nouvelle ville '.$ville_service.' n\'a pu être ajoutée.');
				}
			}
			// Création de l'entreprise si besoin
			$s_entreprise_existe="SELECT id_entreprise FROM Entreprises WHERE nom='".addslashes($nom_entreprise)."'";
			$r_entreprise_existe=mysql_query($s_entreprise_existe);
			$test_entreprise_existe=mysql_num_rows($r_entreprise_existe);

			if ($test_entreprise_existe) {
				$d_entreprise_existe=mysql_fetch_array($r_entreprise_existe);
				$id_entreprise_mere=$d_entreprise_existe['id_entreprise'];
				$html.='<li>L\'entreprise '.$nom_entreprise.' existe déjà dans la base.</li> ';
			} else {
				$s_insert_entreprise="INSERT INTO Entreprises (`date_in`,`nom`,`www`,`descriptif`) " .
						"VALUES ('".$date_in."','".addslashes($nom_entreprise)."','".addslashes($www_entreprise)."','".addslashes($pres_entreprise)."')";
				$test_insert_entreprise=mysql_query($s_insert_entreprise);
				if ($test_insert_entreprise) {
					$html.='<li>L\'entreprise '.$nom_entreprise.' a été entrée dans la base.</li>';
					$id_entreprise_mere=mysql_insert_id();
				} else {
					die('<p>Echec de l\'insertion de l\'entreprise '.$nom_entreprise.'</p>');
				}
			}
			// Création du service si besoin
			$s_service_existe="SELECT id_entreprise FROM Entreprises WHERE nom='".addslashes($nom_service)."'";
			$r_service_existe=mysql_query($s_service_existe);
			$test_service_existe=mysql_num_rows($r_service_existe);

			if ($test_service_existe) {
				$d_service_existe=mysql_fetch_array($r_service_existe);
				$id_service=$d_service_existe['id_entreprise'];
				$html.='<li>Le service '.$nom_service.' existe déjà dans la base.</li> ';
			} else {
				$s_insert_service="INSERT INTO Entreprises (`date_in`,`nom`,`adresse`,`code_postal`,`id_ville`,`id_pays`,`id_entreprise_mere`) " .
						"VALUES ('".$date_in."','".addslashes($nom_service)."','".addslashes($adresse_service)."','".addslashes($cp_service)."',
						".$id_ville.",".$id_pays.",".$id_entreprise_mere.")";
				echo$s_insert_service; 
				$test_insert_service=mysql_query($s_insert_service);
				if ($test_insert_service) {
					$html.='<li>Le service '.$nom_service.' a été entrée dans la base.</li>';
					$id_service=mysql_insert_id();
				} else {
					die('<p>Echec de l\'insertion du service '.$nom_service.'</p>');
				}
			}
			// Création du contact si besoin
			$s_contact_existe="SELECT id_professionnel FROM Professionnels WHERE nom='".addslashes($nom_contact)."' AND prenom='".addslashes($prenom_contact)."'";
			
			$r_contact_existe=mysql_query($s_contact_existe);
			$test_contact_existe=mysql_num_rows($r_contact_existe);
			
			if ($test_contact_existe) {
				$d_contact_existe=mysql_fetch_array($r_contact_existe);
				$id_contact=$d_contact_existe['id_professionnel'];
				$html.='<li>Le contact '.$nom_contact.' existe déjà dans la base. </li>';
			} else {
				$s_insert_contact="INSERT INTO Professionnels (`date_in`,`nom`,`prenom`,`email`,`telephone`,`id_entreprise`) " .
						"VALUES ('".$date_in."','".addslashes($nom_contact)."','".addslashes($prenom_contact)."','".$email_contact."','".$tel_contact."'" .
								",".$id_service.")";
				$test_insert_contact=mysql_query($s_insert_contact);
				if ($test_insert_contact) {
					$html.='<li>Le contact '.$nom_contact.' a été entrée dans la base.</li>';
					$id_contact=mysql_insert_id();
				} else {
					die('<p>Echec de l\'insertion du contact '.$nom_contact.' '.$prenom_contact.'</p>');
				}
			}
			// Création du stage si besoin
			$s_stage_existe="SELECT id_stage_entreprise FROM Stages_Entreprises WHERE sujet='".addslashes($sujet)."'";
			$r_stage_existe=mysql_query($s_stage_existe);
			$test_stage_existe=mysql_num_rows($r_stage_existe);
			if ($test_stage_existe) {
				$d_stage_existe=mysql_fetch_array($r_stage_existe);
				$id_stage=$d_stage_existe['id_stage_entreprise'];
				$html.='<li>Le stage '.$sujet.' existe déjà dans la base. </li>';
			} else {
				$s_insert_stage="INSERT INTO Stages_Entreprises (`id_stage_entreprise`,`date_in`,`sujet`,`description`,`id_entreprise`,`gratification`,`informations_complementaires`,`emploi`) 
						VALUES ('',CURDATE(),'".addslashes($sujet)."','".addslashes($description)."','".$id_service."','".addslashes($remuneration)."','".addslashes($infos_complementaires)."',
						'".addslashes($embauche)."')";
				$test_insert_stage=mysql_query($s_insert_stage);
				if ($test_insert_stage) {
					$html.='<li>Le stage '.$sujet.' a été entrée dans la base.</li>';
					$id_stage=mysql_insert_id();
				} else {
					die('<p>Echec de l\'insertion du stage '.$sujet.'</p>'.mysql_error());
				}
			}
			// Encadrant
			$s_encadrant="REPLACE INTO l_encadrant_stage (`id_stage`,`id_encadrant`,`date_modif`,`id_type_encadrant`,`id_annee_scolaire`) VALUES 
					(".$id_stage.",".$id_contact.",CURDATE(),4,10) ";
			$r_encadrant=mysql_query($s_encadrant)
				or die('Impossible de mettre à jour l\'encadrant du stage :<br/>'.mysql_error());
			$html.='<li>Encadrant insérés</li>';
			// Ouvertures
			$s_ouvertures="REPLACE INTO l_ouverture_stage (`id_stage`,`id_specialite`,`id_niveau`,`id_type_stage`,`id_annee_scolaire`,`ouvert`)" .
							" VALUES ";
			if ($ouv_L2) {
			 	$s_ouvertures.="(".$id_stage.",121,2,1,".$id_annee_scolaire.",1), ";
			}
			if ($ouv_L3) {
				$s_ouvertures.="(".$id_stage.",121,5,1,".$id_annee_scolaire.",1), ";
			}
			if ($ouv_M1) {
				$s_ouvertures.="(".$id_stage.",19,6,1,".$id_annee_scolaire.",".$ouv_gei."),
								(".$id_stage.",26,6,1,".$id_annee_scolaire.",".$ouv_g2s."),
								(".$id_stage.",22,6,1,".$id_annee_scolaire.",".$ouv_gl."),
								(".$id_stage.",561,6,1,".$id_annee_scolaire.",".$ouv_ssng."), ";
			}
			if ($ouv_M2) {
				$s_ouvertures.="(".$id_stage.",19,7,1,".$id_annee_scolaire.",".$ouv_gei."),
								(".$id_stage.",26,7,1,".$id_annee_scolaire.",".$ouv_g2s."),
								(".$id_stage.",22,7,1,".$id_annee_scolaire.",".$ouv_gl."),
								(".$id_stage.",561,7,1,".$id_annee_scolaire.",".$ouv_ssng."), ";
			}
			$s_ouvertures=substr($s_ouvertures,0,-2);
			$r_ouverture=mysql_query($s_ouvertures)
				or die('Impossible de mettre à jour les ouvertures : <br/>'.mysql_error());
			$html.='<li>ouverture insérées</li>';
			 
		}
	}
}

?>

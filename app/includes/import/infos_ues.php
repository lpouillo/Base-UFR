<?php
if (empty($_FILES['infos_ue'])) {
	$html .='<p><strong>Format de fichier :</strong> Année ; UE ; GradeUE ;	Type UE ; Niveau UE	; Spécialité ; ouverture option M1 ; ouverture option M2 ; ects ;	
			coef ; Semestre ; Responsable ; Email ; Prerequis ; Resume ; Competences ; Evaluation ; Eval&nbsp;CC ; Eval&nbsp; EF </p>';
	$html .='<form method="post" action="#" id="lancer_importation" enctype="multipart/form-data" >
			<p style="margin-left:20px;"><input type="hidden" name="page" value="admin"/>
			<input type="hidden" name="section" value="importation"/>
			<input type="hidden" name="type_importation" value="infos_ue"/>
			<input type="file" name="infos_ue" />
			<input type="submit" value="Lancer l\'importation"/>
			<input type="hidden" name="force_template" value="yes"/>
			</p></form>';
} else {
	$s_niv="SELECT id_niveau, abbreviation FROM a_niveau";
	$r_niv=mysql_query($s_niv);
	while ($d_niv=mysql_fetch_array($r_niv)) {
		$g_niv[$d_niv['abbreviation']]=$d_niv['id_niveau'];
	}
	$s_spec="SELECT id_specialite, abbreviation FROM a_specialite";
	$r_spec=mysql_query($s_spec);
	while ($d_spec=mysql_fetch_array($r_spec)) {
		$g_spec[$d_spec['abbreviation']]=$d_spec['id_specialite'];
	}
	$s_type="SELECT id_type_ue, abbreviation FROM a_type_ue";
	$r_type=mysql_query($s_type);
	while ($d_type=mysql_fetch_array($r_type)) {
		$g_type[$d_type['abbreviation']]=$d_type['id_type_ue'];
	}
	if ($_FILES['infos_ue']['size']==0) {
		$html.='FICHIER VIDE.';
	} else {
		$i_ue=0;
		$html='<strong>Début de traitement de '.$_FILES['infos_ue']['name'].'</strong><br/>';
		$fp = fopen ($_FILES['infos_ue']['tmp_name'], 'r');
		$html.='<ul>';
		
		while (($ligne = fgetcsv($fp,8096,';','"'))!== false) {
			
			// Conversion en UTF-8
			foreach($ligne as &$element) {
				$element=trim(utf8_encode($element));
			}
			if ($i_ue>0) {
				$annee_debut=$ligne[0];
				$intitule=$ligne[1];
				$grade=$ligne[2];
				$type=$ligne[3];
				$niveaux=array();
				$niveaux=split(',',$ligne[4]);
				/*echo '<pre>'.$i_ue.' ';
				print_r($niveaux);
				echo '<pre>';*/
				$specialites=array();
				$specialites=split(',',$ligne[5]);
				$ouv_M1=split(',',$ligne[6]);
				$ouv_M2=split(',',$ligne[7]);
				$ects=$ligne[8];
				$coefficient=$ligne[9];
				$semestre=$ligne[10];
				$responsable=$ligne[11];
				$email_responsable=$ligne[12];
				$prerequis=$ligne[13];
				$resume=$ligne[14];
				$competences=$ligne[15];
				$evaluation=$ligne[16];
				// Récupération de l'id_ue dans la base
				$s_ue="SELECT id_ue FROM Unites_Enseignement WHERE intitule='".addslashes($intitule)."'";
				$r_ue=mysql_query($s_ue);
				$d_ue=mysql_fetch_array($r_ue);
				if ($d_ue['id_ue']!=0) {
					$html.='<li style="margin-bottom:5px;">'.$i_ue.' : '.$intitule.' a pour id : '.$d_ue['id_ue'].'<br/>';
					// Mise à jour des informations de la table Unites_Enseignement
					$s_update_ue="UPDATE Unites_Enseignement SET
								`date_modif`=CURDATE(),
								`prerequis`='".addslashes($pre_requis)."',
								`resume`='".addslashes($resume)."',
								`competences`='".addslashes($competences)."',
								`evaluation`='".addslashes($evaluation)."'
								 WHERE id_ue=".$d_ue['id_ue'];
					
					$r_update=mysql_query($s_update_ue);
					if ($r_update) {
						$html.=' Données mise à jour.<br/>';
					} else {
						$html.=' Error !!';
					}	
				} else {
					$s_new_ue="INSERT INTO Unites_Enseignement (`id_ue`,`date_in`,`date_modif`,`intitule`,`ects`,`prerequis`,
								`resume`,`competences`,`evaluation`) VALUES
								('',CURDATE(),CURDATE(),'".addslashes($intitule)."','".addslashes($ects)."','".addslashes($prerequis)."','".addslashes($resume)."',
								'".addslashes($competences)."','".addslashes($evaluation)."')";
					$r_new_ue=mysql_query($s_new_ue) or die('Erreur à l\'insertion de la nouvelle ue :<br/>'.$s_new_ue.'<br/>'.mysql_error());
					$html .='<li style="margin-bottom:5px;">'.$i_ue.' : - '.$intitule.'  a été ajoutée.<br/>'.$s_new_ue.'<br/>';
					$d_ue['id_ue']=mysql_insert_id();
				}
				// Définition du responsable
				$s_responsable="SELECT id_enseignant FROM Enseignants WHERE CONCAT(nom,' ',prenom)='".addslashes($responsable)."'";
				$r_responsable=mysql_query($s_responsable);
				$d_responsable=mysql_fetch_array($r_responsable);
				if ($d_responsable['id_enseignant']!=0) {
					$html .= 'Responsable = '.$d_responsable['id_enseignant'].'<br/>';
					$s_update_responsable="REPLACE INTO l_enseignant_ue (`id_enseignant`,`id_ue`,`id_situation`,`id_annee_scolaire`) VALUES
					(".$d_responsable['id_enseignant'].",".$d_ue['id_ue'].",20,".$id_annee_scolaire.")";
					$r_update_responsable=mysql_query($s_update_responsable);
					$s_update_mail="UPDATE Enseignants SET email_pro=".$email_responsable." WHERE id_enseignant=".$d_responsable['id_enseignant'];
					$r_update_mail=mysql_query($s_update_mail);
				} else {
					$html .= 'Responsable non trouvé.<br/>';
				}
				// Définition des ouvertures
				$s_update_ouverture="REPLACE INTO l_ouverture_ue (`id_ue`,`id_niveau`,`id_specialite`,`id_annee_scolaire`,`id_type_ue`,`semestre`) VALUES ";
				foreach($niveaux as $niv) {
					if($niv=='M') {
						$niv='M2';
						$specialites=$ouv_M2;
					}
					foreach ($specialites as $spec) {
						$s_update_ouverture.="(".$d_ue['id_ue'].",".$g_niv[$niv].",".$g_spec[$spec].",".$id_annee_scolaire.",
							".$g_type[$type].",".$semestre."), ";	
					}	
					if ($ouv_M2[0]!='0') {
						foreach($ouv_M2 as $spec) {
							$s_update_ouverture.="(".$d_ue['id_ue'].",".$g_niv['M2'].",".$g_spec[$spec].",".$id_annee_scolaire.",
								".$g_type[$type].",".$semestre."), ";
						}
					}
				}
				
				$r_update_ouverture= mysql_query(substr($s_update_ouverture,0,-2))
				or die ('Impossible de foutre les ouvertures pour '.substr($s_update_ouverture,0,-2));
				$html.='Ouvertures mises à jour</li>';				
			}
			$i_ue++;
		}
		$html.='</ul>';
	}
}
?>

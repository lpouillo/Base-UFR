<?php
if (empty($_FILES['scolarite_etudiant'])) {
	$html .='<p><strong>Format de fichier :</strong> 
			Année ; Civilité ; NOM ; Prénom ; Niveau ; Grade ; Spécialité ; Semestre ; Etab ; n° INE ; N° Etudiant ; Partenariat ; 
			Formation ; N-1 Etat ; N-1 Sitiuation ; N-1 Spécialité ; N-1Niveau ; N-1Etab ; Moyenne N-1 ; Résultat N-1 ; Rang N-1 ; 
			Mention N-1 ; Commentaires N-1 ; S1 ; N-1 ; S2 N-1 ; Session ; Demande compensation ; Reste à valider																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																				
	
			Tél perso ; Tél portable ; Email perso ; Email IPG ; Email P7 ; Email pro ; Commentaires 																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																
	</p>';
	$html .='<form method="post" action="#" id="lancer_importation" enctype="multipart/form-data" >
			<p style="margin-left:20px;"><input type="hidden" name="page" value="admin"/>
			<input type="hidden" name="section" value="importation"/>
			<input type="hidden" name="type_importation" value="scolarite_etudiant"/>
			<input type="file" name="scolarite_etudiant" />
			<input type="submit" value="Lancer l\'importation"/>
			<input type="hidden" name="force_template" value="yes"/>
			</p></form>';
} else {
	
	// TRAITEMENT
	if ($_FILES['scolarite_etudiant']['size']==0) {
		$html.='FICHIER VIDE.';
	} else {
		$html='<strong>Début de traitement de '.$_FILES['scolarite_etudiant']['name'].'</strong><br/>';
		$fp = fopen ($_FILES['scolarite_etudiant']['tmp_name'], 'r');
		$html.='<ul>';
		$i_ligne=0;
		
		while (($ligne = fgetcsv($fp,8096,',','"'))!== false) {
			if ($i_ligne>0) {
				foreach($ligne as &$element) {
					$element=trim(utf8_encode($element));
				}
				
			
				$annee=$ligne[0];
				// On recherche le id_civilite
				$civilite=$ligne[1];
				$s_civilite="SELECT id_civilite FROM a_civilite WHERE abbreviation='".$civilite."'";
				$r_civilite=mysql_query($s_civilite);
				$d_civilite=mysql_fetch_array($r_civilite);
				if ($d_civilite['id_civilite']!='') {
					$id_civilite=$d_civilite['id_civilite'];
				} else {
					die('Civilite non trouvée');
				}
				// On recherche l'étudiant et on le créé s'il n'existe pas
				$nom=$ligne[2];
				$prenom=$ligne[3];
				$s_etudiant="SELECT id_etudiant FROM Etudiants WHERE nom='".addslashes($nom)."' AND prenom='".addslashes($prenom)."'";
				$r_etudiant=mysql_query($s_etudiant);
				$d_etudiant=mysql_fetch_array($r_etudiant);
				if ($d_etudiant['id_etudiant']!='') {
					$html.= '<li>'.$nom.' '.$prenom.' a pour id='.$d_etudiant['id_etudiant'].'<br/>';
					$id_etudiant=$d_etudiant['id_etudiant'];
				} else {
					$s_insert_etudiant="INSERT INTO Etudiants (`id_etudiant`,`date_in`,`nom`,`prenom`) VALUES ('',CURDATE(),'".addslashes($nom)."','".addslashes($prenom)."')";
					echo $s_insert_etudiant;
					$r_insert_etudiant=mysql_query($s_insert_etudiant)
						or die('Impossible d\'insérer l\'étudiant');
					$html.='<li>'.$nom.' '.$prenom.' a été ajouté à la base avec l\'id '.mysql_insert_id().'<br/>';
				}
				// On recherche son niveau
				$niveau=$ligne[4];
				$s_niveau="SELECT id_niveau FROM a_niveau WHERE abbreviation='".addslashes($niveau)."'";
				$r_niveau=mysql_query($s_niveau);
				$d_niveau=mysql_fetch_array($r_niveau);
				$id_niveau=$d_niveau['id_niveau'];
				// On recherche sa specialite
				$specialite=$ligne[6];
				$s_specialite="SELECT id_specialite FROM a_specialite WHERE abbreviation='".addslashes($specialite)."'";
				$r_specialite=mysql_query($s_specialite);
				$d_specialite=mysql_fetch_array($r_specialite);
				$id_specialite=$d_specialite['id_specialite'];
				// ETABLISSEMENT
				$etablissement=$ligne[8];
				if ($etablissement=='Paris Diderot-P7') {
					$id_etablissement=83;
				} elseif ($etablissement=='IPGP - Paris') {
					$id_etablissement=70;
				}
				$numero_etudiant=$ligne[10];
				// PARCOURS N-1
				$specialite1=$ligne[15];
				$s_specialite="SELECT id_specialite FROM a_specialite WHERE abbreviation='".addslashes($specialite1)."'";
				$r_specialite=mysql_query($s_specialite);
				$d_specialite=mysql_fetch_array($r_specialite);
				if ($d_specialite['id_specialite'] !='') {
					$id_specialite1=$d_specialite['id_specialite'];
				} else {
					$s_insert_specialite="INSERT INTO a_specialite (`libelle`) VALUES ('".addslashes($specialite1)."')";
					$r_insert_specialite=mysql_query($s_insert_specialite);
					$id_specialite1=mysql_insert_id();
				}
				$niveau1=$ligne[16];
				$s_niveau="SELECT id_niveau FROM a_niveau WHERE abbreviation='".addslashes($niveau1)."'";
				$r_niveau=mysql_query($s_niveau);
				$d_niveau=mysql_fetch_array($r_niveau);
				if ($d_niveau['id_niveau']!='') {
					$id_niveau1=$d_niveau['id_niveau'];	
				} else {
					$s_insert_niveau="INSERT INTO a_niveau (`libelle`) VALUES ('".addslashes($niveau1)."')";
					$r_insert_niveau=mysql_query($s_insert_niveau);
					$id_niveau1=mysql_insert_id();
				}
				$etablissement1=$ligne[17];
				if ($etablissement1=='Paris Diderot-P7') {
					$id_etablissement1=83;
				} elseif ($etablissement1=='IPGP - Paris') {
					$id_etablissement1=70;
				} else {
					$s_etablissement="SELECT id_etablissement FROM Etablissements WHERE nom='".addslashes($etablissement1)."'";
					$r_etablissement=mysql_query($s_etablissement);
					$d_etablissement=mysql_fetch_array($r_etablissement);
					if ($d_etablissement['id_etablissement']!='') {
						$id_etablissement1=$d_etablissement['id_etablissement'];	
					} else {
						$s_insert_etablissement="INSERT INTO Etablissements (`nom`) VALUES ('".addslashes($etablissement1)."')";
						$r_insert_etablissement=mysql_query($s_insert_etablissement);
						$id_etablissement1=mysql_insert_id();
					}
				}
				// MOYENNE N-1
				$moyenne1=$ligne[18];
				
				// Mise à jour du Parcours
				$s_parcours1="SELECT id_niveau, id_specialite, id_etablissement 
								FROM l_parcours_etudiant 
								WHERE id_etudiant=".$id_etudiant." AND id_annee_scolaire=9";
				$r_parcours1=mysql_query($s_parcours1);
				$n_parcours1=mysql_num_rows($r_parcours1);
				if ($n_parcours1==0) {
					$s_p1=",(CURDATE(),CURDATE(),".$id_etudiant.",9,".$id_niveau1.",".$id_specialite1.",".$id_etablissement1.",'".$moyenne1."')";
				} else {
					$s_p1="";
				}
				$s_update_parcours="REPLACE INTO l_parcours_etudiant (`date_in`,`date_modif`,`id_etudiant`,`id_annee_scolaire`,
							`id_niveau`,`id_specialite`,`id_etablissement`,`note_moyenne`) VALUES 
							(CURDATE(),CURDATE(),".$id_etudiant.",10,'".$id_niveau."','".$id_specialite."','".$id_etablissement."','-1')".
							$s_p1;
				
				
				$r_update_parcours=mysql_query($s_update_parcours) or die('Erreur lors de la mise à jour du parcours <br/>'.$s_update_parcours);//
				
			}
				
			$i_ligne++;
		}
	}
}
?>
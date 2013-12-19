<?php
if (empty($_FILES['infos_etudiant'])) {
	$html .='<p><strong>Format de fichier :</strong> 
			Année ; Civilité ; NOM ; Prénom ; Jour anniversaire ; Mois anniversaire ; Année anniversaire ;
			Adresse 1 ; CP 1 ; Ville 1 ; Pays 1 ;  Adresse 2 ; CP 2 ; Ville 2 ; Pays 2 ; Parents ;
			Tél perso ; Tél parents ; Tél portable ; Email perso  																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																
	</p>';
	$html .='<form method="post" action="#" id="lancer_importation" enctype="multipart/form-data" >
			<p style="margin-left:20px;">
			<input type="hidden" name="page" value="gestion"/>
			<input type="hidden" name="section" value="importation"/>
			<input type="hidden" name="type_importation" value="infos_etudiant"/>
			<input type="file" name="infos_etudiant" />
			<input type="submit" value="Lancer l\'importation"/>
			<input type="hidden" name="force_template" value="yes"/>
			</p></form>';
} else {
	if ($_FILES['infos_etudiant']['size']==0) {
		$html.='FICHIER VIDE.';
	} else {
		// récupération des civilités
		$civilites=array();
		$s_civilites="SELECT id_civilite, abbreviation FROM a_civilite";
		$r_civilites=mysql_query($s_civilites);
		while ($d_civilites=mysql_fetch_array($r_civilites)){
			$civilites[$d_civilites['abbreviation']]=$d_civilites['id_civilite'];
		}
		// récupération des villes
		$villes=array();
		$s_villes="SELECT id_ville, libelle FROM a_ville";
		$r_villes=mysql_query($s_villes);
		while ($d_villes=mysql_fetch_array($r_villes)){
			$villes[secure_mysql($d_villes['libelle'])]=$d_villes['id_ville'];
		}
		// récupération des pays
		$pays=array();
		$s_pays="SELECT id_pays, libelle FROM a_pays";
		$r_pays=mysql_query($s_pays);
		while ($d_pays=mysql_fetch_array($r_pays)){
			$pays[secure_mysql($d_pays['libelle'])]=$d_pays['id_pays'];
		}
		
		
		$html='<p><strong>Début de traitement de '.$_FILES['infos_etudiant']['name'].'</strong><br/>
				Création des nouvelles villes et des nouveaux pays si besoin</p>';
		$fp = fopen ($_FILES['infos_etudiant']['tmp_name'], 'r');
		$html.='<ul>';
		$i_ligne=0;
		
		$infos=array();
		
		while (($ligne = fgetcsv($fp,8096,';','"'))!== false) {
			if ($i_ligne>0) {
				
				// Pré-traitement : encodage utf8, suppression des espaces, échappement des cotes
				foreach($ligne as &$element) {
					$element=secure_mysql(trim(utf8_encode($element)));
				}
				
				
				$infos[$i_ligne]['id_civilite']=$civilites[$ligne[1]];
				$infos[$i_ligne]['nom']=$ligne[2];
				$infos[$i_ligne]['prenom']=$ligne[3];
				$mois=($ligne[5]<10)?'0'.$ligne[5]:$ligne[5];
				$jour=($ligne[4]<10)?'0'.$ligne[4]:$ligne[4];
				$infos[$i_ligne]['date_naissance']=$ligne[6].'-'.$mois.'-'.$jour;
				$infos[$i_ligne]['adresse_scol']=$ligne[7];
				$infos[$i_ligne]['code_postal_scol']=$ligne[8];
				if ($villes[$ligne[9]]!='') {
					$infos[$i_ligne]['id_ville_scol']=$villes[$ligne[9]];
				} else {
					$s_insert_ville="INSERT INTO a_ville (`id_ville`,`libelle`) VALUES ('','".$ligne[9]."')";
					$r_insert_ville=mysql_query($s_insert_ville);
					$id_new_ville=mysql_insert_id();
					$infos[$i_ligne]['id_ville_scol']=$id_new_ville;
					$html.='<li>La ville '.$ligne[9].' a été ajoutée.</li>';
				}
				if ($pays[$ligne[10]]!='') {
					$infos[$i_ligne]['id_pays_scol']=$pays[$ligne[10]];
				} else {
					$s_insert_pays="INSERT INTO a_pays (`id_pays`,`libelle`) VALUES ('','".$ligne[10]."')";
					$r_insert_pays=mysql_query($s_insert_pays);
					$id_new_pays=mysql_insert_id();
					$infos[$i_ligne]['id_pays_scol']=$id_new_pays;
					$html.='<li>Le pays '.$ligne[10].' a été ajouté.</li>';
				}
				$infos[$i_ligne]['adresse_perm']=$ligne[11];
				$infos[$i_ligne]['code_postal_perm']=$ligne[12];
				if ($villes[$ligne[13]]!='') {
					$infos[$i_ligne]['id_ville_perm']=$villes[$ligne[13]];
				} else {
					$s_insert_ville="INSERT INTO a_ville (`id_ville`,`libelle`) VALUES ('','".$ligne[13]."')";
					$r_insert_ville=mysql_query($s_insert_ville);
					$id_new_ville=mysql_insert_id();
					$infos[$i_ligne]['id_ville_perm']=$id_new_ville;
					$html.='<li>La ville '.$ligne[13].' a été ajoutée.</li>';
				}
				if ($pays[$ligne[14]]!='') {
					$infos[$i_ligne]['id_pays_perm']=$pays[$ligne[14]];
				} else {
					$s_insert_pays="INSERT INTO a_pays (`id_pays`,`libelle`) VALUES ('','".$ligne[14]."')";
					$r_insert_pays=mysql_query($s_insert_pays);
					$id_new_pays=mysql_insert_id();
					$infos[$i_ligne]['id_pays_perm']=$id_new_pays;
					$html.='<li>Le pays '.$ligne[14].' a été ajouté.</li>';
				}
				$infos[$i_ligne]['telephone_scol']=$ligne[16];
				$infos[$i_ligne]['telephone_perm']=$ligne[17];
				$infos[$i_ligne]['telephone_mobile']=$ligne[18];
				$infos[$i_ligne]['email_perso']=$ligne[19];

				// Récupération de l'ID étudiant
				$s_etudiant="SELECT id_etudiant FROM Etudiants WHERE nom='".$infos[$i_ligne]['nom']."' AND prenom='".$infos[$i_ligne]['prenom']."'";
				$r_etudiant=mysql_query($s_etudiant)
					or die($s_etudiant.' '.mysql_error());
				if ($n_etudiant=mysql_num_rows($r_etudiant)) {
					$d_etudiant=mysql_fetch_array($r_etudiant);
					$infos[$i_ligne]['id_etudiant']=$d_etudiant['id_etudiant'];
				}
				
			}	
			$i_ligne++;
		}
		foreach ($infos as $i_ligne => $info) {
			if ($info['id_etudiant']=='') {
				$s_insert_etudiant="INSERT INTO Etudiants (`id_etudiant`,`id_civilite`,`nom`,`prenom`,`date_naissance`,
					`adresse_scol`,`code_postal_scol`,`id_ville_scol`,`id_pays_scol`,`telephone_scol`,
					`adresse_perm`,`code_postal_perm`,`id_ville_perm`,`id_pays_perm`,`telephone_perm`,
					`telephone_mobile`,`email_perso`) VALUES 
					('','".$info['id_civilite']."','".$info['nom']."','".$info['prenom']."','".$info['date_naissance']."',
					'".$info['adresse_scol']."','".$info['code_postal_scol']."',".$info['id_ville_scol'].",".$info['id_pays_scol'].",'".$info['telephone_scol']."',
					'".$info['adresse_perm']."','".$info['code_postal_perm']."',".$info['id_ville_perm'].",".$info['id_pays_perm'].",'".$info['telephone_perm']."',
					'".$info['telephone_mobile']."','".$info['email_perso']."')";
				mysql_query($s_insert_etudiant)
					or die($s_insert_etudiant.' : '.mysql_error());
			} else {
				$s_infos_etudiant="SELECT `id_civilite`,`nom`,`prenom`,`date_naissance`,
					`adresse_scol`,`code_postal_scol`,`id_ville_scol`,`id_pays_scol`,`telephone_scol`,
					`adresse_perm`,`code_postal_perm`,`id_ville_perm`,`id_pays_perm`,`telephone_perm`,
					`telephone_mobile`,`email_perso` FROM Etudiants WHERE id_etudiant=".$info['id_etudiant'];
				$r_infos_etudiant=mysql_query($s_infos_etudiant);
				$d_infos_etudiant=mysql_fetch_array($r_infos_etudiant);
				$diff1=array_diff($info,$d_infos_etudiant);
				$diff2=array_diff($d_infos_etudiant,$info);
				/*echo '<pre>';
				print_r($diff1);
				print_r($diff2);
				echo '</pre>';*/
				echo '<br/>'.$info['nom'].'<br/>';
				foreach ($info as $champ => $valeur) {
					if ($valeur!=$d_infos_etudiant[$champ]) {
						echo $valeur.' - '.$d_infos_etudiant[$champ].'<br/> ';
					}
				}
			}
		}
	}
}
?>
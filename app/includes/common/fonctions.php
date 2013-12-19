<?php
// Fonction d'échappement des quotes pour éviter les injections SQL
function secure_mysql($string) {
 	$string=str_replace("'","\'",$string);
 	$string=str_replace('"','\"',$string);
 	return $string;
}


// fonction permettant de récupérer l'instant unix
function getmicrotime(){
    list($usec, $sec) = explode(" ",microtime());
    return ((float)$usec + (float)$sec);
}



// Récupération du dn LDAP
function get_dn ($ds, $user) {

    // Bind anonymously to the directory.
    if (!($ldapbind = @ldap_bind($ds))) {
            die("Unable to bind anonymously to the directory.");
    }

    // Search for the user entry.
    if (!($sr = ldap_search($ds, "ou=people,dc=ipgp,dc=jussieu,dc=fr", "(uid=$user)", array("dn")))) {
            die("Unable to search directory.");
    }

    // Count the number of entries returned and check for errors.
    $entry_count = ldap_count_entries($ds, $sr);
    if ($entry_count == 0) {
        if (!ldap_free_result($sr)) {
        	die("Unable to free search results");
        }
        return;
    }
    if ($entry_count != 1) {
            die("Invalid number of DNs found ($entry_count).");
    }

    // Try to retrieve the DN.
    if (!($entry = ldap_first_entry($ds, $sr))) {
            die("Unable to retrieve search results.");
    }
    if (!($dn = ldap_get_dn($ds, $entry))) {
            die("Unable to retrieve DN.");
    }
    // Clean up memory.
    if (!ldap_free_result($sr)) {
            die("Unable to free search results");
    }

    return $dn;
}

// Fonctions pour les tables et les données
function generation_requete($table, $champs, $filtres, $ordre) {
	$sql="SELECT ";
	foreach ($champs as $champ) {
		$sql.="`".$champ."`, ";
	}
	$sql = substr($sql,0,-2)." FROM ".$table;
	if (!empty($filtres)) {
    	$sql .= " WHERE ";
    	foreach($filtres as $champ => $valeur) {
    		$sql.=$champ.'='.$valeur.' AND ';
    	}
    	$sql.="1=1";
    }
    
	return $sql;
}

function default_accueil ($tableau) {
	if ($_SESSION['group']=='enseignant') {
		unset($menu['entites']['annexes']);
	}
	$html_default='<div class="content_tab" style="width:600px;margin:auto;">';
	foreach ($tableau as $element => $data) {
		$html_default.='<h2 class="link" onclick="affElement(\'0\',\''.$_POST['page'].'\',\''.$element.'\',\'\',\'content\');">
			<img src="public/images/icons/'.$data['icon'].'.png"/> '.$data['text'].'</h2>
			<p>'.$data['title'].'</p>';
	}
	$html_default.='</div>';
	return $html_default;
}

function recuperation_champs ($table) {
	$champs=array();
	$result=mysql_query("SHOW FIELDS FROM ".$table)
		or die(mysql_error());
	while ($data=mysql_fetch_array($result)) {
		$champs[]=$data['Field'];
	}
	return $champs;
}


function recuperation_donnees ($sql) {
	$result=mysql_query($sql)
		or die($sql.'<br/><strong>'.mysql_error().'</strong>');
	$return_array=array();
	$i=0;
	while ($data=mysql_fetch_array($result,MYSQL_ASSOC)) {
		foreach ($data as $champ => $valeur) {
			$return_array[$i][$champ]=$valeur;	
		}		
		$i++;
	}
	mysql_free_result($result);
	
	return $return_array;
}

function select_enseignant($id,$message) {
	$html_select='<form id="'.$id.'" method="post" action="#">
			<input type="hidden" name="page" value="mon_espace"/>'.$message.' 
			<select id="small_select" name="id_link" onchange="submitForm(\''.$id.'\')" title="Choisir une enseignant">';
	$s_enseignants="SELECT id_enseignant, CONCAT(nom,' ',prenom) AS nom_prenom FROM Enseignants ORDER BY nom";
	$r_enseignants=mysql_query($s_enseignants) 
		or die('Impossible de récupérer la liste des enseignants');
	$html_select.='<option value="0">Choisir un enseignant</option>';
	while ($d_enseignants=mysql_fetch_array($r_enseignants)) {
		$sel=($_SESSION['id_link']==$d_enseignants['id_enseignant'])?'selected="selected"':'';
		$html_select.='<option value="'.$d_enseignants['id_enseignant'].'" '.$sel.'>'.$d_enseignants['nom_prenom'].'</option>';
	}
	$html_select.='</select></form>';	
	return $html_select;
}

function normal_input($champ,$valeur,$largeur) {
	global $ro;
	$html.='<input id="'.$champ.'" name="'.$champ.'" value="'.$valeur.'" size="'.$largeur.'"'.$ro.'/>';
	return $html;
}
function champ_date($champ,$date) {
	global $mode;
	$ro=($mode!='rw')?'READONLY':'';
	$html.='<input type="text" value="'.$date.'" tabindex="1" class="DatePicker" name="'.$champ.'" id="'.$champ.'" 
		'.$ro.'>';
	return $html;
}
function generation_oui_non($champ,$valeur) {
	global $mode;
	$ro=($mode=='rw')?'':'readonly';
	$sel_oui=($valeur==1)?'checked="checked"':'';
	$sel_non=($valeur==0)?'checked="checked"':'';
	$html_oui_non='<input type="radio" name="'.$champ.'" '.$sel_oui.' value="1" '.$ro.'/> OUI
				<input type="radio" name="'.$champ.'" '.$sel_non.' value="0" '.$ro.'/> NON';
	return $html_oui_non;
}
function generation_select($nom_champ,$table,$champs,$id) {
	global $mode;
	
	if ($mode!='rw') {
		$dis=' DISABLED ';
	}
	$sql="SELECT ".$champs[0].", ".$champs[1]." FROM ".$table." ORDER BY ".$champs[1];
	
	$result=mysql_query($sql);

	$html_select='<select id="'.$nom_champ.'" name="'.$nom_champ.'" 
		'.$dis.'">
		<option value="0">Non défini</option>';
	while ($data=mysql_fetch_array($result)) {
		$sel=($id==$data[0])?"selected=\"selected\"":"";
		$html_select .= '<option value="'.$data[0].'" '.$sel.'>'.$data[1].'</option>';
	}
	mysql_free_result($result);
	$html_select.='</select>';
	if ($mode=='rw') {
		$html_select.='<a title="ajouter un élément dans la liste" href="#" onClick="popupForm(\''.$nom_champ.'\')"> 
		<img height="12px" border="0" src="public/images/icons/ajouter.png" alt="ajouter un élément"/></a>';
	}
	return $html_select;
}

function get_photo($id_photo) {
	global $section;
	global $mode;
	
	if ($id_photo==0) {
		switch($section) {
			case 'mes_infos';
			case 'mes_etudiants';
			case 'enseignants':
			case 'etudiants':
			case 'professionnels':
				$filename='inconnu.jpg';
			break;
			case 'mes_cours';
			case 'mes_stages';
			case 'stages_laboratoires':
			case 'stages_entreprises':
			case 'cas_etudes':
			case 'unites_enseignement':
				$filename='ue_inconnue.jpg';
			break;	
			default:
			$filename='empty_photo.jpg';
		}
		$id_photo=0;
	} else {
		$s_photo="SELECT id_photo, nom_md5 FROM s_photos WHERE id_photo=".$id_photo;
		$r_photo=mysql_query($s_photo);
		$d_photo=mysql_fetch_array($r_photo);
		$id_photo=$d_photo[0];
		$filename=$d_photo[1];
	}
	$html=  '<div id="photo" >
		<img id="photo_img" height="120px" src="public/images/photos/'.$filename.'" alt="ma_photo"/>
		<input id="photo_src" type="hidden" name="photo_src" value=""/>';
	
	if ($mode=='rw') {
		$html.='<br/><a href="#" onClick="popupForm(\'upload\')">Changer la photo</a>';	
	}
	return $html;
}


function select_entites($sql) {
	$html_select='<select id="select_entites" onChange="submitForm(\'switch\');" name="id" class="select_element">';
	$result=mysql_query($sql)
		or die($sql.'<br/>'.mysql_error());	
	while($data=mysql_fetch_array($result)) {
		$sel=($data[0]==$_POST['id'])?'selected="selected"':'';
		$html_select.='<option class="select_element" value="'.$data[0].'" '.$sel.'>'.substr($data[1],0,100);
		$html_select.=($data[2]!='')?' ('.$data[2].')</option>':'</option>';
	}
	$html_select .='</select>';
	return $html_select;
}

function creation_table($sql,$champs,$post_sql,$mode) {
	global $params;
	$section=$_POST['section'];
	  	
	// Application des filtres 
	if (isset($_POST['filtres'])) {
		$filtres=$_POST['filtres'];
		if ($_POST['conserver_filtres']=='oui')	{
			$_SESSION['filtres'.$section]=$_POST['filtres'];
		}
	} elseif (isset($_SESSION['filtres'.$section])){
		$filtres=$_SESSION['filtres'.$section];
	} 
	if (isset($filtres)) {
		$sql.=" WHERE ";
		$i_champ=0;
		foreach	($filtres as $champ => $value) {
			if (!empty($value)) {
				if ($i_champ==0 and is_int($value)) {
					$sql.=$champ."=".secure_mysql($value)." AND ";
				} else {
					$sql.=$champ." LIKE '%".secure_mysql($value)."%' AND ";
				} 
			}
			$i_champ++;
		}
		$sql.="1=1";
	}
	
	
	$sql.=$post_sql;
	
	if (isset($_POST['limite']) AND is_numeric($_POST['limite'])) {
		$sql.=" LIMIT ".round($_POST['limite']);
		$limite=round($_POST['limite']);
	}
	$_SESSION[$section.'_last']=$sql;
	$tableau=recuperation_donnees($sql);
	
	// Création du formulaire de filtrage
	$html.='<form id="form_filtrage"  method="POST">
			<input type="hidden" name="page" value="'.$_POST['page'].'"/>
			<input type="hidden" name="section" value="'.$_POST['section'].'"/>
			<input type="hidden" name="filtrage_soumis" value="oui"/>'.
		$input_table.'
		<table class="table_sel" id="table_liste" cellpadding="0" cellspacing="0" style="display:block;" width="'.$_POST['table_width'].'">
		<thead id="liste_header">
			<tr>';
	if($mode=='rw') {
		$html.='<td colspan="3" class="liste_container_bt">
					<input type="button" value="AJOUTER UNE ENTRÉE" 
						onclick="affElement(\'0\',\''.$_POST['page'].'\',\''.$_POST['section'].'\',\'ajouter\',\'content\');"> 
				</td>
				<td>';
	} else {
		$html.='<td colspan="3">';
	} 
	$html.='<span id="n_record">'.sizeof($tableau).' entrées</span> <input type="checkbox" name="conserver_filtres" value="oui" checked="checked"> Conserver les filtres</td>
		<td width="600px" colspan="'.(sizeof($champs)+3).'" style="border:none;"><h3>Outils</h3>';
	
	if ($mode=='rw') {
		$html.='<img class="link" src="public/images/icons/importation.png" alt="importation" title="Importer des données"
				onClick="popupForm(\'importation_'.$_POST['section'].'\')"/> Importer ';
	}
	$html.='<img class="link" src="public/images/icons/csv.png" alt="csv" title="Exporter la sélection vers un tableur"
				onClick="genererFichier(\''.$_POST['section'].'_vue_last\',\'csv\');"/> Exporter ';
	foreach($params['liste']['outils'] as $outil => $data) {
		$html.='<img class="link" src="public/images/icons/'.$data['icon'].'.png" alt="'.$data['icon'].'" 
				onClick="genererFichier(\''.$_POST['section'].'_'. $outil.'_last\',\'csv\');"/> '.$data['text'].'&nbsp;&nbsp;';
	}
	
	$colspan=($mode=='rw')?'2':'1';
	$html.='</td>
		</tr>
		<tr>
			<th colspan="'.$colspan.'"> Afficher 
				<input name="limite" onkeyup="lancetimer();" type="text" value="'.$limite.'" size="3"></th>';
	foreach($champs as $k_champ =>$champ) {
		$html.='<th>'.$champ[0].'</th>';
	}

	$html.='</tr><tr><th colspan="'.$colspan.'">Filtrer</th>';
	// Affichage des entêtes de champs
	foreach($champs as $k_champ =>$champ) {
		$html.='<th>
			<input style="width:100%;" onkeyup="lancetimer();" onsubmit="lancetimer();" type="text" 
				name="filtres['.$champ[1].']." value="'.$filtres[$champ[1]].'">
			</th>';
	}
	$html.='</tr></thead><tbody id="table_body">';
	
	// Affichage des lignes 
  	if (empty($tableau)) {
  		$html.='<tr><td colspan="'.(sizeof($champs)+$colspan).'">Aucune données dans la base.</td></tr>';
  	} else {
  		foreach ($tableau as $ligne) {
  			$html.='<tr>';
  			$i_champ=0;
  			foreach($champs as $k_champ => $champ) {
  				if($i_champ<1)  {			
  					if ($mode=='rw') {
			  			$html.='<td class="td_selection">
			  						<img src="public/images/icons/modifier.png"
			  						onclick="affElement(\''.$ligne[$k_champ].'\',\''.$_POST['page'].'\',\''.$_POST['section'].'\',\'modifier\',\'content\')"/>
			  					</td><td class="td_selection">
			  						<img src="public/images/icons/supprimer.png"
			  						onclick="affElement(\''.$ligne[$k_champ].'\',\''.$_POST['page'].'\',\''.$_POST['section'].'\',\'supprimer\',\'content\')"/>
			  					</td>';
		  			} else {
		  				$html.='<td class="td_selection">
			  						<img src="public/images/icons/voir.png"
			  						onclick="affElement(\''.$ligne[$k_champ].'\',\''.$_POST['page'].'\',\''.$_POST['section'].'\',\'voir\',\'content\')"/>
			  					</td>';
		  			}
  					//
  					$html.='<td class="td_id">'.$ligne[$k_champ].'</td>';
  				} elseif ($k_champ=='id_photo') { 
  					$f_name='inconnu.jpg';
  					if ($ligne[$k_champ]!=0) {
  						$s_photo="SELECT nom_md5 FROM photos WHERE id_photo=".$ligne['id_photo'];
  						$r_photo=mysql_query($s_photo);
  						$d_photo=mysql_fetch_array($r_photo);
  						$f_name=$d_photo['nom_md5'];
  					}
  					
  					$html.='<td>
  								<img height="50px" src="public/images/photos/'.$f_name.'" />
  							</td>';
  				} else {
					$html.='<td>'.$ligne[$k_champ].'</td>';
  				}
				
				$i_champ++;
  			}	
  			$html.='</tr>';	
  		}
  	}
		
	$html.='</tbody></table></form>';  
	return $html;
}

function creation_table_annexe($table) {
	$s_champs="SHOW FIELDS FROM ".$table;
	$r_champs=mysql_query($s_champs);
	$champs=array();
	$sql="SELECT ";
	while($d_champs=mysql_fetch_array($r_champs)) {

		$champs[$d_champs['Field']]=array($d_champs[0],$d_champs[0]);
		$sql.=$d_champs[0].", ";
	}
	$sql=substr($sql,0,-2)." FROM ".$_POST['table'];
	
	// Application des filtres 
	if (isset($_POST['filtres'])) {
		$filtres=$_POST['filtres'];
		if ($_POST['conserver_filtres']=='oui')	{
			$_SESSION['filtres'.$table]=$_POST['filtres'];
		}
	} elseif (isset($_SESSION['filtres'.$table])){
		$filtres=$_SESSION['filtres'.$table];
	} 
	if (isset($filtres)) {
		$sql.=" WHERE ";
		$i_champ=0;
		foreach	($filtres as $champ => $value) {
			if (!empty($value)) {
				if ($i_champ==0) {
					$sql.=$champ."=".secure_mysql($value)." AND ";
				} else {
					$sql.=$champ." LIKE '%".secure_mysql($value)."%' AND ";
				} 
			}
			$i_champ++;
		}
		$sql.="1=1";
	}
	
	$sql.=" ORDER BY libelle";
	if (isset($_POST['limite']) AND is_numeric($_POST['limite'])) {
		$sql.=" LIMIT ".round($_POST['limite']);
		$limite=round($_POST['limite']);
	}

	$tableau=recuperation_donnees($sql);
	
	// Création du formulaire de filtrage
	$html.='<form id="form_filtrage"  method="POST">
			<input type="hidden" name="page" value="entites"/>
			<input type="hidden" name="section" value="annexes"/>
			<input type="hidden" name="filtrage_soumis" value="oui"/>
			<input type="hidden" name="table" value="'.$table.'"/>
		<table class="table_sel" id="table_liste" cellpadding="0" cellspacing="0" style="display:block;" width="'.$_POST['table_width'].'">
		<thead id="liste_header">
			<tr>';
	$html.='<td colspan="3" class="liste_container_bt">
					<input type="button" value="AJOUTER UNE ENTRÉE" 
						onclick="navAnnexes(\''.$_POST['table'].'\',\'0\',\'ajouter\',\'content\');"> 
				</td>
				<td><span id="n_record">'.sizeof($tableau).' entrées</span> <input type="checkbox" name="conserver_filtres" value="oui" checked="checked"> Conserver les filtres</td>
			<td width="600px" colspan="'.(sizeof($champs)+3).'" style="border:none;"><img src="public/images/icons/csv.png" title="Sélection en CSV"
				onClick="genererFichier(\'last_'.$_POST['page'].'_'.$_POST['section'].'\',\'csv\');"/>
				<strong>Exporter la sélection</strong> <img src="public/images/icons/importation.png" title="Importer des données">
				<strong>Importer des données</strong>';


	$html.='</td>
		</tr>
		<tr>
			<th colspan="2"> Afficher 
				<input name="limite" onkeyup="lancetimer();" type="text" value="'.$limite.'" size="3"></th>';
	foreach($champs as $k_champ =>$champ) {
		$html.='<th>'.$champ[0].'</th>';
	}

	$html.='</tr><tr><th colspan="2">Filtrer</th>';
	// Affichage des entêtes de champs
	foreach($champs as $k_champ =>$champ) {
		$html.='<th>
			<input style="width:100%;" onkeyup="lancetimer();" onsubmit="lancetimer();" type="text" 
				name="filtres['.$champ[1].']." value="'.$filtres[$champ[1]].'">
			</th>';
	}
	$html.='</tr></thead><tbody id="table_body">';
	
	
	// Affichage des lignes 
  	if (empty($tableau)) {
  		$html.='<tr><td colspan="'.(sizeof($champs)+2).'">Aucune données dans la base.</td></tr>';
  	} else {
  		foreach ($tableau as $ligne) {
  			$html.='<tr>';
  			$i_champ=0;
  			foreach($champs as $k_champ => $champ) {
  				if($i_champ<1)  {			
  				
		  			$html.='<td class="td_selection">
		  						<img src="public/images/icons/modifier.png"
		  						onclick="navAnnexes(\''.$_POST['table'].'\',\''.$ligne[$k_champ].'\',\'modifier\')"/>
		  					</td><td class="td_selection">
		  						<img src="public/images/icons/supprimer.png"
		  						onclick="navAnnexes(\''.$_POST['table'].'\',\''.$ligne[$k_champ].'\',\'supprimer\')"/>
		  					</td>';
		  			
  					$html.='<td class="td_id">'.$ligne[$k_champ].'</td>';
  				} elseif ($k_champ=='id_photo') { 
  					$f_name='inconnu.jpg';
  					if ($ligne[$k_champ]!=0) {
  						$s_photo="SELECT nom_md5 FROM photos WHERE id_photo=".$ligne['id_photo'];
  						$r_photo=mysql_query($s_photo);
  						$d_photo=mysql_fetch_array($r_photo);
  						$f_name=$d_photo['nom_md5'];
  					}
  					
  					$html.='<td>
  								<img height="50px" src="public/images/photos/'.$f_name.'" />
  							</td>';
  				} else {
					$html.='<td>'.$ligne[$k_champ].'</td>';
  				}
				
				$i_champ++;
  			}	
  			$html.='</tr>';	
  		}
  	}
		
	$html.='</tbody></table></form>';  
	
	return $html;
}

// Mise à jour de la base de données
function insert_table($table) {
	$champs=recuperation_champs($table);
	$s_insert="INSERT INTO ".$table." (";
	foreach ($_POST as $champ => $valeur) {
		if (in_array($champ, $champs)) {
			$s_insert.= " `".$champ."`, ";
			$s_insert2.="'".secure_mysql($valeur)."', ";
		}
	}
	$s_insert=substr($s_insert,0,-2).") VALUES (".substr($s_insert2,0,-2).")";
	$r_insert=mysql_query($s_insert)
		or die('impossible d\'ajouter l\'entité ..');
	return mysql_insert_id();
}

function update_table($table) {
	$s_update="UPDATE ".$table." SET ";
	$champs=recuperation_champs($table);
	$key=$champs[0];
	foreach ($_POST as $champ => $valeur) {
		if (in_array($champ, $champs)) {
			$s_update.=$champ."='".secure_mysql($valeur)."', ";
		}
	}
	$s_update=substr($s_update,0,-2)." WHERE ".$key."='".secure_mysql($_POST['id'])."'";
	$update=mysql_query($s_update);
	if ($update) {
		return 1;	
	} else {
		return 0;
	}	
}

function delete_table($table) {
	$champs=recuperation_champs($table);
	$key=$champs[0];
	$s_delete="DELETE FROM ".$table." WHERE ".$key."='".secure_mysql($_POST['id'])."'";
	$r_delete=mysql_query($s_delete)
		or die('impossible de supprrimer l\'entité ..');
}

function autres_update($type,$section) {
	global $id_annee_scolaire;
	switch($type) {
		case 'scolarite':
			foreach($_POST['scolarite'] as $id_annee => $scol_annee) {
				$s_replace="REPLACE INTO l_parcours_etudiant (`date_modif`,`id_etudiant`,`id_annee_scolaire`,`id_niveau`,`id_specialite`,`id_etablissement`,
						`note_moyenne`,`classement`,`id_mention`,`avis_jury`) 
						VALUES (NOW(),'".$_POST['id']."','".$id_annee."','".$scol_annee['id_niveau']."','".$scol_annee['id_specialite']."','".
								$scol_annee['id_etablissement']."','".secure_mysql($scol_annee['note_moyenne'])."','".$scol_annee['classement']."','".
										$scol_annee['id_mention']."','".secure_mysql($scol_annee['avis_jury'])."')";
				mysql_query($s_replace)
					or die($s_replace.'<br/>'.mysql_error());
			} 
		break;
		case 'unites_enseignement':
			foreach($_POST['ues'] AS $id_annee => $ue_par_type) {
				foreach($ue_par_type as $id_type_ue => $ues) {
        			foreach($ues as $numero_option => $id_ue) {
			        	$s_update_ue="UPDATE l_etudiant_ue SET id_ue=".$id_ue."
			                                        WHERE id_annee_scolaire=".$id_annee."
			                                        AND id_etudiant=".$_POST['id']."
			                                        AND id_type_ue=".$id_type_ue."
			                                        AND numero_option=".$numero_option;
			        	//echo $s_update_ue.'<br/>';
			        	mysql_query($s_update_ue)
		               		or die('Impossible de mettre à jour les choix d\'ues de l\'étudiant');
        			}
				}
			}
		break;
		case 'enseignements':
			// Unités d'enseignement
			if (sizeof($_POST['heures'])!=0) {
				foreach($_POST['heures'] as $id_ue => $enseignant) {
					$s_update="UPDATE l_enseignant_ue SET heures_cours='".$enseignant['cours']."', heures_TD='".$enseignant['TD']."',
								heures_TP='".$enseignant['TP']."', heures_colle='".$enseignant['colle']."', heures_terrain='".$enseignant['terrain']."',
								ng_cours='".$enseignant['ng_cours']."', ng_TD='".$enseignant['ng_TD']."', ng_TP='".$enseignant['ng_TP']."', 
								ng_colles='".$enseignant['ng_colles']."', njours_terrain='".$enseignant['njours_terrain']."', `evolution_n+1`='".$enseignant['evolution']."'
								WHERE id_enseignant=".$_POST['id']." AND id_ue=".$id_ue." AND id_annee_scolaire=".$id_annee_scolaire;
					mysql_query($s_update)
						or die($s_update.'<br/>'.mysql_error());
					
				}
			}
			// activité hors maquette
			if (sizeof($_POST['lehm'])!=0) { 
				foreach($_POST['lehm'] as $k_lehm => $d_lehm) {
					$tmp=explode('-',$k_lehm);
					$s_update="UPDATE l_enseignant_hors_maquette SET decharge='".$d_lehm['decharge']."', n_etudiant='".$d_lehm['n_etudiant']."'
						WHERE id_enseignant='".$tmp[0]."' AND id_annee_scolaire='".$tmp[1]."' AND id_hors_maquette='".$tmp[2]."'";
					//
					$r_update=mysql_query($s_update)
						or die (mysql_error());
				}
			}
		break;
		case 'intervenants':
			if (sizeof($_POST['heures'])!=0) {
				foreach($_POST['heures'] as $id_enseignant => $enseignant) {
					$s_update="UPDATE l_enseignant_ue SET heures_cours='".$enseignant['cours']."', heures_TD='".$enseignant['TD']."',
								heures_TP='".$enseignant['TP']."', heures_colle='".$enseignant['colle']."', heures_terrain='".$enseignant['terrain']."',
								ng_cours='".$enseignant['ng_cours']."', ng_TD='".$enseignant['ng_TD']."', ng_TP='".$enseignant['ng_TP']."', 
								ng_colles='".$enseignant['ng_colles']."', njours_terrain='".$enseignant['njours_terrain']."', `evolution_n+1`='".$enseignant['evolution']."'
								WHERE id_enseignant=".$id_enseignant." AND id_ue=".$_POST['id']." AND id_annee_scolaire=".$id_annee_scolaire;
			
					mysql_query($s_update)
						or die($s_update.'<br/>'.mysql_error());
				}
			}
		break;
		case 'financements':
			
		break;
		case 'notes':
			if (sizeof($_POST['evaluations'])!=0) {
				foreach($_POST['evaluations'] as $id_evaluation => $evaluation) {
					$s_replace_evaluation="REPLACE INTO Evaluations (`id_evaluation`,`id_ue`,`libelle`,`id_type_evaluation`,`coefficient`,`note_maximale`,`bonus`,`id_annee_scolaire`)
							VALUES ('".$id_evaluation."','".$_POST['id']."','".$evaluation['libelle']."','".$evaluation['id_type_evaluation']."',
							'".$evaluation['coefficient']."','".$evaluation['note_maximale']."','".$evaluation['bonus']."','".$evaluation['id_annee_scolaire']."') ";
					mysql_query($s_replace_evaluation)
						or die(mysql_error());				
				}
			}
			// Mise à jour des notes de l'UE
			$s_update_notes="";
			if (sizeof($_POST['notes'])!=0) {
				foreach($_POST['notes'] as $id_evaluation => $notes) {
					foreach ($notes as $id_etudiant => $note) {
						$s_update_note="REPLACE INTO Notes (`id_etudiant`,`id_evaluation`,`valeur`)
						VALUES ('".$id_etudiant."','".$id_evaluation."','".$note."')";
						mysql_query($s_update_note)
							or die(mysql_error());
					}
				} 
			}
		break;
		case 'soutenance':
			
		break;	
		case 'encadrants':
			switch($section) {
				case 'stages_laboratoires':
					$s_encadrants="REPLACE INTO l_encadrant_stage (`id_stage`,`id_encadrant`,`date_modif`,`id_type_encadrant`,`id_annee_scolaire`) VALUES 
						(".$_POST['id'].",".$_POST['dir_stage_labo'].",CURDATE(),1,".$id_annee_scolaire."),
						(".$_POST['id'].",".$_POST['codir_stage_labo_1'].",CURDATE(),2,".$id_annee_scolaire."),
						(".$_POST['id'].",".$_POST['codir_stage_labo_2'].",CURDATE(),3,".$id_annee_scolaire.")";
					$r_encadrants=mysql_query($s_encadrants)
						or die($s_encadrants.'<br/>'.mysql_error());
				break;
				case 'stages_entreprises':
				
					$s_encadrants="REPLACE INTO l_encadrant_stage (`id_stage`,`id_encadrant`,`date_modif`,`id_type_encadrant`,`id_annee_scolaire`) VALUES 
						(".$_POST['id'].",".$_POST['contact'].",CURDATE(),4,".$id_annee_scolaire."),
						(".$_POST['id'].",".$_POST['maitre_stage_entreprise_1'].",CURDATE(),5,".$id_annee_scolaire."),
						(".$_POST['id'].",".$_POST['maitre_stage_entreprise_2'].",CURDATE(),6,".$id_annee_scolaire."),
						(".$_POST['id'].",".$_POST['tuteur_stage_entreprise_1'].",CURDATE(),7,".$id_annee_scolaire."),
						(".$_POST['id'].",".$_POST['tuteur_stage_entreprise_2'].",CURDATE(),8,".$id_annee_scolaire.")";
					$r_encadrants=mysql_query($s_encadrants)
						or die($s_encadrants.'<br/>'.mysql_error());
					
				break;
				case 'cas_etudes':
					
				break;
				case 'doctorats':
					
				break;
			}
		break;
		case 'ouvertures':
			switch($section) {
				case 'unites_enseignement':
					foreach ($_POST['ouvertures'][$_POST['id']] AS $id_niveau => $ouv_spec) {
						foreach($ouv_spec as $id_specialite => $ouv) {
							$sql_replace="REPLACE INTO l_ouverture_ue (`id_ue`,`id_specialite`,`id_niveau`,`id_annee_scolaire`,`id_type_ue`)" .
									" VALUES (".$_POST['id'].",".$id_specialite.",".$id_niveau.",".$id_annee_scolaire.",".$ouv.")";
							mysql_query($sql_replace) 
								or die($sql_replace.'<br/>'.mysql_error());
						}
					}
				
				break;
				case 'stages_laboratoires':
					foreach ($_POST['ouvertures'] AS $id_niveau => $ouv_spec) {
						foreach($ouv_spec as $id_specialite => $ouv) {
							$sql_replace="REPLACE INTO l_ouverture_stage (`id_stage`,`id_specialite`,`id_niveau`,`id_annee_scolaire`,`id_type_stage`,`ouvert`)" .
									" VALUES (".$_POST['id'].",".$id_specialite.",".$id_niveau.",".$id_annee_scolaire.",0,'".$ouv."')";
							mysql_query($sql_replace) 
								or die($sql_replace.'<br/>'.mysql_error());
								echo $sql_replace.'<br/>';
						}
					}
				break;
				case 'stages_entreprises':
				
					foreach ($_POST['ouvertures'] AS $id_niveau => $ouv_spec) {
						foreach($ouv_spec as $id_specialite => $ouv) {
							$sql_replace="REPLACE INTO l_ouverture_stage (`id_stage`,`id_specialite`,`id_niveau`,`id_annee_scolaire`,`id_type_stage`,`ouvert`)" .
									" VALUES (".$_POST['id'].",".$id_specialite.",".$id_niveau.",".$id_annee_scolaire.",1,'".$ouv."')";
							mysql_query($sql_replace) 
								or die($sql_replace.'<br/>'.mysql_error());
						}
					}
				break;
				case 'cas_etudes':
					
				break;
			}
				
		break;
	}
}



?>

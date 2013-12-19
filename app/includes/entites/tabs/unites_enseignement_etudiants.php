<?
// Récupération des niveaux et spécialités
$s_niveau="SELECT id_niveau, abbreviation FROM a_niveau WHERE gestion=1";
$r_niveau=mysql_query($s_niveau);
$niveaux=array();
while ($d_niveau=mysql_fetch_array($r_niveau)) {
	$niveaux[$d_niveau['id_niveau']]=$d_niveau['abbreviation'];
}
mysql_free_result($r_niveau);
$s_specialite="SELECT id_specialite, abbreviation FROM a_specialite WHERE gestion=1";
$r_specialite=mysql_query($s_specialite);
$specialites=array();
while ($d_specialite=mysql_fetch_array($r_specialite)) {
	$specialites[$d_specialite['id_specialite']]=$d_specialite['abbreviation'];
}
mysql_free_result($r_specialite);

$s_etudiants="SELECT L.id_etudiant, P.id_niveau, P.id_specialite   
		FROM l_etudiant_ue L 
		INNER JOIN l_parcours_etudiant P
			ON P.id_etudiant=L.id_etudiant
			AND P.id_annee_scolaire=".$id_annee_scolaire."
		WHERE L.id_ue=".$_POST['id']." AND L.id_annee_scolaire=".$id_annee_scolaire;
$r_etudiants=mysql_query($s_etudiants);
$nombres=array();
$n_etudiants=mysql_num_rows($r_etudiants);
$nombres['TOTAL']=$n_etudiants;
while ($d_etudiants=mysql_fetch_array($r_etudiants)) {			
	$nombres[$d_etudiants['id_niveau']][$d_etudiants['id_specialite']]++;
}
$html.='<table border="0" style="margin-left:20px;">
		<tr>
			<td style="text-align:center;padding:5px;"><strong>TOTAL :</strong> '.$nombres['TOTAL'].'</td>';
foreach ($nombres as $id_niveau => $specs) {
	if (is_int($id_niveau)) {
		foreach ($specs as $id_specialite => $nombre) {
			$html.='<td style="text-align:center;padding:5px;"><strong>'.$niveaux[$id_niveau].' '.$specialites[$id_specialite].' :</strong> '.$nombre.'</td>';	
		}
	}
}
$html.='</tr>
	</table>';
			

$s_etudiants="SELECT E.id_etudiant, E.nom, E.prenom, E.email_ipgp, E.id_photo, NNOW.abbreviation AS abbrv_niv_now, SNOW.abbreviation AS abbrv_spec_now,  
				NPAST.abbreviation AS abbrv_niv_past, SPAST.abbreviation AS abbrv_spec_past, EPAST.nom AS nom_etab_past
				FROM Etudiants E
				LEFT JOIN l_parcours_etudiant PNOW ON PNOW.id_etudiant = E.id_etudiant
					AND PNOW.id_annee_scolaire=".$id_annee_scolaire."
				LEFT JOIN a_specialite SNOW ON SNOW.id_specialite = PNOW.id_specialite
				LEFT JOIN a_niveau NNOW ON NNOW.id_niveau = PNOW.id_niveau
				LEFT JOIN l_parcours_etudiant PPAST ON PPAST.id_etudiant = E.id_etudiant
					AND PPAST.id_annee_scolaire=".($id_annee_scolaire-1)."
				LEFT JOIN a_specialite SPAST ON SPAST.id_specialite = PPAST.id_specialite
				LEFT JOIN a_niveau NPAST ON NPAST.id_niveau = PPAST.id_niveau
				LEFT JOIN Etablissements EPAST ON EPAST.id_etablissement = PPAST.id_etablissement
				WHERE E.id_etudiant
				IN (
					SELECT id_etudiant
					FROM l_etudiant_ue
					WHERE id_ue=".$_POST['id']."
					AND id_annee_scolaire=".$id_annee_scolaire."
					)
				ORDER BY E.nom";
//echo $s_etudiants;
$r_etudiants=mysql_query($s_etudiants)
	or die('Erreur lors de la récupération de la liste des étudiants :<br/>'.mysql_error());
$n_etudiants=mysql_num_rows($r_etudiants);



$html .= '<table class="table_sel" >
		<tr><th width="40px">Détail</th><th width="30px">Email</th><th width="50px">Photo</th><th>Nom Prénom</th><th>Parcours actuel</th><th>Parcours précédent</th></tr>';


while ($d_etudiants=mysql_fetch_array($r_etudiants)) {

	$html .='<tr><td style="width:30px;text-align:center;" onclick="affElement(\''.$d_etudiants['id_etudiant'].'\',\'entites\',\'etudiants\',\'voir\',\'content\')">
					<img style="cursor:pointer;" width="20" border="0" src="public/images/icons/voir.png"/></td>
				<td style="width:30px;text-align:center;"><a href="mailto:'.$d_etudiants['email_ipgp'].'">
					<img border=0 width="20px" src="public/images/icons/contact.png"/></a></td>
				<td>';
	if ($d_etudiants['id_photo']!=0) {
			$s_photo="SELECT nom_md5 FROM s_photos WHERE id_photo=".$d_etudiants['id_photo'];
			$r_photo=mysql_query($s_photo) or die(mysql_error());
			$d_photo=mysql_fetch_array($r_photo);
			$html .='<img width="50px" src="public/images/photos/'.$d_photo['nom_md5'].'"/>';
		} else {
			$html .='<img width="50px" src="public/images/photos/inconnu.jpg"/>';
		}
	$html.=		'</td>'.
		'<td>'.$d_etudiants['nom'].' '.$d_etudiants['prenom'].'</td>' .
		'<td>'.$d_etudiants['abbrv_niv_now'].' '.$d_etudiants['abbrv_spec_now'].'</td>';
	
	if ($d_etudiants['abbrv_niv_past']!='') {
		$html .='<td>'.$d_etudiants['abbrv_niv_past'].' '.$d_etudiants['abbrv_spec_past'].' ('.$d_etudiants['nom_etab_past'].')</td>';
	} else {
		$html .='<td ><em>Inconnu</em></td>';
	}
	$html.='</tr>';	
}
	
$html .= '</table>';
?>

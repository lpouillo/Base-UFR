<?php

$sql="SELECT E.email_ipgp FROM Etudiants E
				WHERE E.id_etudiant
				IN (
					SELECT id_etudiant
					FROM l_etudiant_ue
					WHERE id_ue=".$id."
					AND id_annee_scolaire=".$id_annee_scolaire."
				)	ORDER BY E.nom";


$result=mysql_query($sql)
	or die(mysql_error());

while ($data=mysql_fetch_array($result)) {
	$destinataires.=$data['email_ipgp'].', ';	
	$html.=$data['email_ipgp'].', ';
}
$html.='<br/>Voici la liste a importer dans votre carnet d\'adresses. Pour leur ecrire, cliquez simplement <a href="mailto:'.$destinataires.'">ici</a>.';
/*
$n_personnes=sizeof($noms);


$html .= '<div>' .
		'<img src="public/images/logo-step.png" height="50px"/>' .
		'<img src="public/images/logo-ipgp.jpg" height="60px"/>' .
		'&nbsp; Master 2008-2009 - <strong>' .$_SESSION['nom_ue'].
		'</strong></div>';

$html .= '<table width="500px">' .
		'<tr>';
for ($i=0;$i<$n_personnes;$i++) {
	$html .= '<td style="vertical-align:top;">';
	if ($id_photos[$i]==0) {
		$html .= '<img width="100px" src="public/images/inconnu.jpg" />';	
	} else {
		$html .= '<img width="100px" src="image.php?img='.$id_photos[$i].'" />';	
	}
	$html .= '<br/><div style="font-size:10px;"> '.$noms[$i].' '.$prenoms[$i].'</div></td>';
	
	if (($i+1) % 6 ==0 and $i!=0) {
		$html .='</tr><tr>';
	}
	 
}
$html .= '</tr></table>';


*/
echo $html;
die ();
/*
$sql="SELECT E.nom, E.prenom, E.email_ipgp, E.telephone_mobile, E.date_naissance FROM Etudiants E
	WHERE E.id_etudiant IN (
			SELECT id_etudiant
			FROM l_etudiant_ue
			WHERE id_ue=".$id."
			AND id_annee_scolaire=".$id_annee_scolaire."
			)	
		ORDER BY E.nom";
$result=mysql_query($sql);

while ($data=mysql_fetch_array($result)) {
	$tmp_date=explode('-',$data['date_naissance']);

	$string_all.="dn: cn=".$data['prenom']." ".$data['nom'].",mail=".$data['email_ipgp']." \nobjectclass: top \nobjectclass: person \nobjectclass: organizationalPerson \nobjectclass: inetOrgPerson \ngivenName: ".$data['prenom']." \nsn: ".$data['nom']." \ncn: ".$data['prenom']." ".$data['nom']." \nmail: ".$data['email_ipgp']." \nbirthyear: ".$tmp_date[0]." \nbirthmonth: ".$tmp_date[1]." \nbirthday: ".$tmp_date[2]."  \n \n";
}

$fname='liste_ue_'.$id.'.ldif';

header('Content-disposition: filename="'.$fname.'"');
header("Content-Type: text/ldif");

echo utf8_decode($string_all);*/
?>
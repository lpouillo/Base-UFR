<?php
/*
echo '<pre>';
print_r($nom_requete);
print_r($_SESSION);
echo '</pre>';
*/

switch ($nom_requete[2]) {
	case 'last':
		$s_etudiants=$_SESSION['etudiants_last'];
		foreach($_SESSION['filtresetudiants'] as $filtre) {
			$titre.=$filtre.' ';
		}
	break;
	default:
	$s_etudiants="SELECT E.id_etudiant FROM Etudiants E
					INNER JOIN l_parcours_etudiant P
					ON P.id_etudiant=E.id_etudiant
					AND P.id_annee_scolaire=".$id_annee_scolaire."
					INNER JOIN a_niveau NI
						ON P.id_niveau=NI.id_niveau
						AND NI.gestion=1
					INNER JOIN a_specialite SP
						ON P.id_specialite=SP.id_specialite
						AND SP.gestion=1";
}
	

$r_etudiant=mysql_query($s_etudiants)
	or die(mysql_error());
$n_etudiants=mysql_num_rows($r_etudiant);
$donnees=array();
while ($d_etudiant=mysql_fetch_array($r_etudiant)) {
	
	$donnees[]=$d_etudiant;
}


// creation du PDF
$pdf=new PDF();
$pdf->Proprietes();

$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetAuthor('http://enseignant.ipgp.fr');
$pdf->SetTitle($titre);
// Entête
$pdf->Image('public/images/logo-ipgp.jpg',5,6,15);
$pdf->Image('public/images/logo-p7.jpg',20,6,10);
$pdf->Image('public/images/logo-step.jpg',30,10,15);
$pdf->Ln();
$pdf->SetFont('Arial','B',18);
$pdf->SetXY(50,10);
$pdf->Cell(120,5,utf8_decode($titre),0,0,'C');
$pdf->Ln();
$pdf->SetFont('Arial','',12);
$pdf->SetXY(100,20);
$pdf->Cell(10,5,utf8_decode(date('d/m/Y')),0,0,'C');

$pdf->SetXY(10,30);
// Champs du tableau

$x_pos=0;
$y_pos=0;
for($i_donnee=0;$i_donnee<sizeof($donnees);$i_donnee++) {
	$s_photo="SELECT P.nom_md5 
			FROM s_photos P
			INNER JOIN Etudiants E
				ON P.id_photo=E.id_photo
				AND E.id_etudiant=".$donnees[$i_donnee]['id_etudiant'];
	$r_photo=mysql_query($s_photo);
	$d_photo=mysql_fetch_array($r_photo);
	if ($d_photo['nom_md5']=='') {
		$d_photo['nom_md5']='inconnu.jpg';
	}
	
	$pdf->SetFont('Arial','',9);
	$pdf->Image('public/images/photos/'.$d_photo['nom_md5'],10+($x_pos*35),(30+($y_pos*50)),30);
	$pdf->SetXY(10+($x_pos*35),(30+($y_pos*50)+45));
	$pdf->Cell(0,0,utf8_decode($donnees[$i_donnee]['nom'].' '.$donnees[$i_donnee]['prenom']));
	//$pdf->Cell(0,0,$x_pos.' '.$y_pos);
	$x_pos++;
	
	if ($x_pos>=5) {
		$x_pos=0;
		$y_pos++;
		if ($y_pos==5) {
			$pdf->AddPage();
			// Entête
			$pdf->Image('public/images/logo-ipgp.jpg',5,6,15);
			$pdf->Image('public/images/logo-p7.jpg',20,6,10);
			$pdf->Image('public/images/logo-step.jpg',30,10,15);
			$pdf->Ln();
			$pdf->SetFont('Arial','B',18);
			$pdf->SetXY(50,10);
			$pdf->Cell(120,5,utf8_decode($titre),0,0,'C');
			$pdf->Ln();
			$pdf->SetFont('Arial','',12);
			$pdf->SetXY(100,20);
			$pdf->Cell(10,5,utf8_decode(date('d/m/Y')),0,0,'C');
			$y_pos=0;
		}
	}
}	
$pdf->Output('export.pdf','I');
?>

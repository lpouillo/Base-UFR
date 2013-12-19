<?
// trombi d'une ue
$s_ue="SELECT UE.intitule FROM Unites_Enseignement UE WHERE UE.id_ue=".$id;
$r_ue=mysql_query($s_ue)
	or die(mysql_error());
$d_ue=mysql_fetch_array($r_ue);
$titre=$d_ue[0];
$s_etudiants="SELECT E.id_etudiant, CONCAT(E.nom,' ',E.prenom) AS nom_prenom, E.id_photo
				FROM Etudiants E
				LEFT JOIN l_parcours_etudiant PNOW ON PNOW.id_etudiant = E.id_etudiant
					AND PNOW.id_annee_scolaire=".$id_annee_scolaire."
				LEFT JOIN a_specialite SNOW ON SNOW.id_specialite = PNOW.id_specialite
				LEFT JOIN a_niveau NNOW ON NNOW.id_niveau = PNOW.id_niveau
				WHERE E.id_etudiant
				IN (
					SELECT id_etudiant
					FROM l_etudiant_ue
					WHERE id_ue=".$id."
					AND id_annee_scolaire=".$id_annee_scolaire."
				)	
				GROUP BY E.nom
				ORDER BY E.nom";

$r_etudiant=mysql_query($s_etudiants)
	or die(mysql_error());
$n_etudiants=mysql_num_rows($r_etudiant);
$donnees=array();
while ($d_etudiant=mysql_fetch_array($r_etudiant)) {
	$donnees[]=$d_etudiant;
}


// creation du PDF
if ($complet) {
	$pdf=new PDF();
	$pdf->Proprietes();
	
	$pdf->AliasNbPages();
	$pdf->AddPage();

	$pdf->SetTitle($titre);
}
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
	$r_photo=mysql_query($s_photo)
		or die(mysql_error());
	$d_photo=mysql_fetch_array($r_photo);
	if ($d_photo['nom_md5']=='') {
		$d_photo['nom_md5']='inconnu.jpg';
	}
	
	$pdf->SetFont('Arial','',9);
	$pdf->Image('public/images/photos/'.$d_photo['nom_md5'],10+($x_pos*35),(30+($y_pos*50)),30);
	$pdf->SetXY(10+($x_pos*35),(30+($y_pos*50)+45));
	$pdf->Cell(0,0,utf8_decode($donnees[$i_donnee]['nom_prenom']));
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

if ($complet) {
	$pdf->Output('trombi_'.str_replace(' ','_',substr($titre,0,100)).'.pdf','I');
}

?>

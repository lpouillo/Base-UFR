<?php
/*
 * Created on 4 déc. 2009
 *
 * Ce script génére la feuille d'émargement de l'UE
 */
 
$sql_ue="SELECT UE.intitule, E.nom, E.prenom, NI.abbreviation FROM Unites_Enseignement UE 
		INNER JOIN l_enseignant_ue LUEE 
			ON LUEE.id_ue=UE.id_ue 
		INNER JOIN Enseignants E 
			ON LUEE.id_enseignant=E.id_enseignant 
		INNER JOIN l_ouverture_ue OUE 
			ON OUE.id_ue=UE.id_ue 
			AND OUE.id_annee_scolaire=".$id_annee_scolaire."
			AND OUE.id_type_ue<>8 
		INNER JOIN a_niveau NI ON 
			OUE.id_niveau=NI.id_niveau 
		WHERE LUEE.id_situation=20  
			AND UE.id_ue=".$id;
$result_ue=mysql_query($sql_ue)
	or die($sql_ue.'<br/>'.mysql_error());
$n_ouv=mysql_num_rows($result_ue);
$data_ue=mysql_fetch_array($result_ue);
$intitule=html_entity_decode($data_ue['intitule'],ENT_QUOTES,'UTF-8');
$longueur_intitule=strlen($intitule);
$max_longueur_intitule=65;
$nom=$data_ue['nom'];
$prenom=$data_ue['prenom'];
$niveau1=$data_ue['abbreviation'];
if ($n_ouv>1) {
	while($data_ue=mysql_fetch_array($result_ue)) {
		if ($data_ue['abbreviation']!=$niveau1) {
			$niveau2=$data_ue['abbreviation'];
		}	
	}
}
$s_etudiants="SELECT E.id_etudiant, E.nom, E.prenom, E.email_ipgp, E.id_photo, 
				NNOW.abbreviation AS abbrv_niv_now, SNOW.abbreviation AS abbrv_spec_now
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
				)	ORDER BY E.nom";

$r_etudiant=mysql_query($s_etudiants);
$n_etudiants=mysql_num_rows($r_etudiant);

while ($d_etudiant=mysql_fetch_array($r_etudiant)) {
	$donnees[]=$d_etudiant;
}

if ($complet) {	
	$pdf=new PDF();
	$pdf->AliasNbPages();

	//Propriétés du document
	$pdf->SetAuthor('Gestion de l\'enseignement - UFR STEP / IPGP');
	$pdf->SetTitle('Feuille d\'émargement 2009-2010');
} 
$pdf->AddPage();
// Entête
$pdf->Image('public/images/logo-ipgp.jpg',5,6,15);
$pdf->Image('public/images/logo-p7.jpg',20,6,10);
$pdf->Ln();
$pdf->SetFont('Arial','',12);
$pdf->SetXY(50,10);
$pdf->Cell(120,5,utf8_decode('Feuille d\'émargement 2009-2010'),0,0,'C');
$pdf->Ln();
$pdf->SetFont('Arial','B',14);
$pdf->SetXY(50,15);	
if ($longueur_intitule<$max_longueur_intitule) {
	$pdf->Cell(120,5,utf8_decode($intitule),0,0,'C');
	
} else {
	
	$pdf->Cell(120,5,utf8_decode(substr($intitule,0,$longueur_intitule/2)),0,0,'C');
	$pdf->SetXY(50,20);	
	$pdf->Cell(120,5,utf8_decode('-'.substr($intitule,$longueur_intitule/2)),0,0,'C');	
}
$pdf->SetXY(50,25);	
$pdf->SetFont('Arial','I',9);
$pdf->Cell(120,5,utf8_decode('L\'anonymat des copies est obligatoire (EXAMEN ECRIT)'),0,0,'C');


// Informations complémentaires
$pdf->SetXY(10,30);
$pdf->SetFont('Arial','',10);
$pdf->Cell(10,5,utf8_decode('Responsable : '.$prenom.' '.$nom));
$pdf->SetXY(100,30);
$pdf->Cell(10,5,utf8_decode('Niveau UE : '.$niveau1.' '.$niveau2));
$pdf->SetXY(10,35);
$pdf->Cell(10,5,utf8_decode('Date examen : '));
$pdf->SetXY(100,35);
$pdf->Cell(10,5,utf8_decode('Lieu examen : '));
$pdf->SetXY(10,40);

$pdf->Cell(10,5,utf8_decode('Nom et signature des surveillants : '));
$pdf->SetXY(10,50);
$pdf->SetFont('Arial','',8);
$pdf->Cell(10,5,utf8_decode('ge: Génie de l\'Environnement et Industrie ; gs=Géophysique de Surface et de Subsurface ; gp=Géophysique ; gc=Géochimie '));
$pdf->SetXY(10,53);
$pdf->Cell(10,5,utf8_decode('gm=Géomatériaux ; gl: Géologie et risques naturels ; gd=Génie de l\'environnement ; gf=Géosciences fondamentales'));
$pdf->SetXY(80,60);
$pdf->Cell(10,5,utf8_decode('Nombre d\'étudiants : '.sizeof($donnees)));

// Liste des étudiants
$pdf->SetTextColor(0,0,0);	
$pdf->Ln();
$pdf->SetFont('Arial','B',9);
$pdf->Cell(80,5,utf8_decode('Nom Prénom'),1);
$pdf->Cell(30,5,utf8_decode('Niveau Spécialité'),1);
$pdf->Cell(80,5,'Signature',1);
$pdf->Ln();
$pdf->SetFont('Arial','',10);
for($i_donnee=0;$i_donnee<sizeof($donnees);$i_donnee++) {
	// nom et prénom
	$pdf->Cell(80,10,utf8_decode($donnees[$i_donnee]['nom'].' '.$donnees[$i_donnee]['prenom']),1);
	// niveau
	$pdf->Cell(30,10,utf8_decode($donnees[$i_donnee]['abbrv_niv_now'].' '.$donnees[$i_donnee]['abbrv_spec_now']),1);
	// Zone pour émarger
	$pdf->Cell(80,10,'',1);
	$pdf->Ln();
	if ($i_donnee==19 OR $i_donnee==43) {
		$pdf->AddPage();
		// Entête
		$pdf->Image('public/images/logo-ipgp.jpg',5,6,15);
		$pdf->Image('public/images/logo-p7.jpg',20,6,10);
		$pdf->Ln();
		$pdf->SetFont('Arial','',12);
		$pdf->SetXY(50,10);
		$pdf->Cell(120,5,utf8_decode('Feuille d\'émargement 2009-2010'),0,0,'C');
		$pdf->Ln();
		$pdf->SetFont('Arial','B',14);
		$pdf->SetXY(50,15);	
		if ($longueur_intitule<$max_longueur_intitule) {
			$pdf->Cell(120,5,utf8_decode($intitule),0,0,'C');
			$pdf->SetXY(50,20);		
		} else {
			$pdf->Cell(120,5,utf8_decode(substr($intitule,0,$longueur_intitule/2)),0,0,'C');
			$pdf->SetXY(50,20);	
			$pdf->Cell(120,5,utf8_decode('-'.substr($intitule,$longueur_intitule/2)),0,0,'C');
			$pdf->SetXY(50,20);	
		}
		$pdf->SetXY(50,25);	
		$pdf->SetFont('Arial','I',9);
		$pdf->Cell(120,5,utf8_decode('L\'anonymat des copies est obligatoire (EXAMEN ECRIT)'),0,0,'C');

		$pdf->SetFont('Arial','',10);
		$pdf->SetXY(10,30);
	}
}	

if ($complet) {
	$pdf->Output('emargement_ue_'.$id_ue.'.pdf','I');
}

?>

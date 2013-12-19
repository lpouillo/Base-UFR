<?php

$s_ues="SELECT UE.intitule, UE.code, GROUP_CONCAT(NI.abbreviation) AS niveau, GROUP_CONCAT(SP.abbreviation) AS specialite, UE.semestre,
		UE.prerequis, UE.resume, UE.organisation, UE.competences, UE.evaluation, UE.id_photo, UE.id_ufr
		FROM Unites_Enseignement UE
		INNER JOIN l_ouverture_ue OUE
			ON OUE.id_ue=".$id_ue."
			AND OUE.id_annee_scolaire=".$id_annee_scolaire."
			AND OUE.id_type_ue<>8
		INNER JOIN a_niveau NI
			ON OUE.id_niveau=NI.id_niveau
		INNER JOIN a_specialite SP
			ON OUE.id_specialite=SP.id_specialite
		WHERE UE.id_ue=".$id_ue."
		GROUP BY UE.intitule, UE.code, UE.semestre,
		UE.prerequis, UE.resume, UE.organisation, UE.competences, UE.evaluation, UE.id_photo, UE.id_ufr";
 		
$ues=recuperation_donnees($s_ues);
/*
echo '<pre>';
print_r($ues);
echo '</pre>';
*/
require_once('app/classes/fpdf/fpdf.php');
require_once('app/classes/fpdf/fonctions.php');


$pdf=new PDF('L');
$pdf->AliasNbPages();
$pdf->AddPage();

//Propriétés du document
$pdf->SetAuthor('Base de gestion UFR STEP - IPGP');
$pdf->SetTitle(utf8_decode('Fiche identité UE : '.$ues[0]['intitule'].' ('.$annee.' - '.($annee+1).')'));

// Entête
$pdf->Image('public/images/logo-ipgp.jpg',5,6,15);
$pdf->Image('public/images/logo-p7.jpg',20,6,10);	

$pdf->SetXY(50,10);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(100,7,utf8_decode('FICHE D\'IDENTITÉ UE'));
$pdf->SetFont('Arial','BI',14);
$pdf->Cell(15,7,utf8_decode($ues[0]['intitule']));
$pdf->Ln();	
$pdf->Ln();	
$pdf->Ln();	
$pdf->SetFont('Arial','B',11);
$pdf->Cell(60,5,utf8_decode('Intitulé de l\'UE'));
$pdf->SetFont('Arial','',11);
$pdf->Cell(15,5,utf8_decode($ues[0]['intitule']));
$pdf->Ln();	
$pdf->SetFont('Arial','B',11);
$pdf->Cell(60,5,utf8_decode('Code UE'));
$pdf->SetFont('Arial','',11);
$pdf->Cell(15,5,utf8_decode($ues[0]['code']));
$pdf->Ln();	
$pdf->SetFont('Arial','B',11);
$pdf->Cell(60,5,utf8_decode('Année universitaire'));
$pdf->SetFont('Arial','',11);
$pdf->Cell(15,5,utf8_decode($annee.'-'.($annee+1)));
 
if (strpos($ues[0]['niveau'],'L') !== false) {
	$cycle='Licence';
} elseif (strpos($ues[0]['niveau'],'M') !== false) {
	$cycle='Master';
} elseif (strpos($ues[0]['niveau'],'D') === false) {
	$cycle='Doctorat';
} 
$niveau=substr($ues[0]['niveau'],0,2);
$specialite=substr($ues[0]['specialite'],0,2);

$pdf->Ln();	
$pdf->SetFont('Arial','B',11);
$pdf->Cell(60,5,utf8_decode('Cycle'));
$pdf->SetFont('Arial','',11);
$pdf->Cell(15,5,utf8_decode($cycle));
$pdf->Ln();	
$pdf->SetFont('Arial','B',11);
$pdf->Cell(60,5,utf8_decode('Niveau'));
$pdf->SetFont('Arial','',11);
$pdf->Cell(15,5,utf8_decode($niveau));
$pdf->Ln();	
$pdf->SetFont('Arial','B',11);
$pdf->Cell(60,5,utf8_decode('Parcours/Spécialité'));
$pdf->SetFont('Arial','',11);
$pdf->Cell(15,5,utf8_decode($specialite));
$pdf->Ln();	
$pdf->SetFont('Arial','B',11);
$pdf->Cell(60,5,utf8_decode('Semestre'));
$pdf->SetFont('Arial','',11);
$pdf->Cell(15,5,utf8_decode($ues[0]['semestre']));
$pdf->Ln();	
$pdf->Ln();	
$pdf->SetFont('Arial','I',10);
$pdf->Cell(60,5,utf8_decode('Prérequis'));
$pdf->SetFont('Arial','',10);
$pdf->MultiCell(0, 5, utf8_decode($ues[0]['prerequis']));
$pdf->Ln();	
$pdf->SetFont('Arial','I',10);
$pdf->Cell(60,5,utf8_decode('Résumé'));
$pdf->SetFont('Arial','',10);
$pdf->MultiCell(0, 5, utf8_decode($ues[0]['resume']));
$pdf->Ln();	
$pdf->SetFont('Arial','I',10);
$pdf->Cell(60,5,utf8_decode('Compétences visées'));
$pdf->SetFont('Arial','',10);
$pdf->MultiCell(0, 5, utf8_decode($ues[0]['competences']));
$pdf->Ln();	
$pdf->SetFont('Arial','I',10);
$pdf->Cell(60,5,utf8_decode('Modalités de contrôle des connaissances'));
$pdf->SetFont('Arial','',10);
$pdf->MultiCell(0, 5, utf8_decode($ues[0]['evaluation']));
$pdf->Ln();	
$pdf->SetFont('Arial','I',10);
$pdf->Cell(60,5,utf8_decode('Organisation pédagogique'));
$pdf->SetFont('Arial','',10);
$pdf->MultiCell(0, 5, utf8_decode($ues[0]['organisation']));
$pdf->Ln();	
$pdf->Ln();	

$pdf->SetFont('Arial','B',10);
$pdf->Cell(40,5,utf8_decode('Équipe pédagogique'));
$pdf->Cell(40,5,utf8_decode('Nom de l\'enseignant'));
$pdf->Cell(60,5,utf8_decode('Email'));
$pdf->Cell(20,5,utf8_decode('Statut'));
$pdf->Cell(10,5,utf8_decode('CM'));
$pdf->Cell(10,5,utf8_decode('TD'));
$pdf->Cell(10,5,utf8_decode('TP'));
$pdf->Cell(20,5,utf8_decode('H éq. TD'));
$pdf->Cell(40,5,utf8_decode('Évolution année n+1'));
$pdf->Ln();	
$pdf->SetFont('Arial','',10);
$s_enseignants="SELECT SI.libelle AS situation, CONCAT(E.prenom,' ',E.nom) AS enseignant, E.email_pro, ST.libelle AS statut, 
				EUE.heures_cours, EUE.heures_TD, EUE.heures_TP, (1.5*EUE.heures_cours+EUE.heures_TD+EUE.heures_TP) AS heures_eq,
				EUE.`evolution_N+1` AS evolution
				FROM l_enseignant_ue EUE
				INNER JOIN Enseignants E
					ON E.id_enseignant=EUE.id_enseignant
					AND EUE.id_annee_scolaire=".$id_annee_scolaire."
				LEFT JOIN a_situation SI
					ON SI.id_situation=EUE.id_situation
				LEFT JOIN a_statut ST
					ON ST.id_statut=E.id_statut
				WHERE EUE.id_ue=".$id_ue."
				ORDER BY SI.libelle DESC, ST.libelle";
$enseignants=recuperation_donnees($s_enseignants);

foreach($enseignants as $enseignant) {
	$pdf->Cell(40,5,utf8_decode($enseignant['situation']));
	$pdf->Cell(40,5,utf8_decode($enseignant['enseignant']));
	$pdf->Cell(60,5,utf8_decode($enseignant['email_pro']));
	$pdf->Cell(20,5,utf8_decode($enseignant['statut']));
	$pdf->Cell(10,5,utf8_decode($enseignant['heures_cours']));
	$pdf->Cell(10,5,utf8_decode($enseignant['heures_TD']));
	$pdf->Cell(10,5,utf8_decode($enseignant['heures_TP']));
	$pdf->Cell(20,5,utf8_decode($enseignant['heures_eq']));
	$pdf->Cell(40,5,utf8_decode($enseignant['evolution']));
	$pdf->Ln();
}


$pdf->Output('fiche_'.$ues[0]['intitule'].'_'.$annee.'-'.($annee+1).'.pdf','I');
exit;




?>

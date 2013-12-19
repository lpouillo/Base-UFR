<?php
$s_enseignant="SELECT E.nom, E.prenom, ST.libelle AS statut, ET.nom AS etablissement, UFR.libelle AS ufr, L.nom AS laboratoire
				FROM Enseignants E
				LEFT JOIN a_statut ST
					ON E.id_statut=ST.id_statut
				LEFT JOIN Etablissements ET
					ON E.id_etablissement=ET.id_etablissement
				LEFT JOIN a_ufr UFR
					ON E.id_ufr=UFR.id_ufr
				LEFT JOIN Laboratoires L
					ON E.id_laboratoire=L.id_laboratoire
				WHERE E.id_enseignant=".$id_enseignant;
$enseignant=recuperation_donnees($s_enseignant);
if ($n==1) {
	$pdf=new PDF();
	$pdf->AliasNbPages();
	$pdf->AddPage();
	//Propriétés du document
	$pdf->SetAuthor('Base de gestion UFR STEP - IPGP');
	$pdf->SetTitle(utf8_decode('Service enseignant : '.$enseignant[0]['nom'].' '.$enseignant[0]['prenom'].' ('.$annee.' - '.($annee+1).')'));
} else {
	$pdf->AddPage();
}
/* Entetes */
$pdf->SetFont('Arial','',12);
$pdf->Cell(35,5,utf8_decode('Le : '.date('d/m/Y')));
$pdf->SetX(100);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(50,5,utf8_decode('Service effectué  ('.$annee.'-'.($annee+1).')'));
$pdf->Ln();
$pdf->Ln();
$pdf->SetFont('Arial','',10);
$pdf->Cell(35,5,utf8_decode('NOM Prénom'));

/* Infos enseignant */
$pdf->SetFont('Arial','B',10);
$pdf->Cell(90,5,utf8_decode($enseignant[0]['nom'].' '.$enseignant[0]['prenom']));
$TOTAL_X=$pdf->GetX();
$TOTAL_Y=$pdf->GetY();
$pdf->Ln();
$pdf->SetFont('Arial','',10);
$pdf->Cell(35,5,utf8_decode('Corps'));
$pdf->SetFont('Arial','B',10);
$pdf->Cell(90,5,utf8_decode($enseignant[0]['statut']));
$pdf->Ln();
$pdf->SetFont('Arial','',10);
$pdf->Cell(35,5,utf8_decode('Grade'));
$pdf->SetFont('Arial','B',10);
$pdf->Cell(90,5,utf8_decode($enseignant[0]['grade']));
$pdf->SetFont('Arial','',10);
$pdf->Ln();
$pdf->Ln();
$pdf->Cell(35,5,utf8_decode('Établissement'));
$pdf->SetFont('Arial','B',10);
$pdf->Cell(90,5,utf8_decode($enseignant[0]['etablissement']));
$pdf->SetFont('Arial','',10);
$pdf->Ln();
$pdf->Cell(35,5,utf8_decode('UFR'));
$pdf->SetFont('Arial','B',10);
$pdf->Cell(90,5,utf8_decode($enseignant[0]['ufr']));
$pdf->SetFont('Arial','',10);
$pdf->Ln();
$pdf->Cell(35,5,utf8_decode('Laboratoire'));
$pdf->SetFont('Arial','B',10);
$pdf->Cell(90,5,utf8_decode($enseignant[0]['laboratoire']));
$pdf->SetFont('Arial','',10);
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();

/* Initialisation des totaux */
$total_heures_presentiel=0;
$total_heures_non_pres=0;
$total_heures_activite=0;
$total_heures_STEP=0;
$total_heures_hors=0;

/* Unités d'enseignement */
$pdf->SetFillColor(204,204,255);
$pdf->SetDrawColor(204,204,255);
$pdf->SetTextColor(0,0,255);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(70,5,utf8_decode('Activités maquettées en présentiel'),1,0,'',1);
$pdf->SetTextColor(0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(120,5,utf8_decode('(UE STEP et hors STEP)'),1,0,'',1);
$pdf->Ln();
$pdf->SetFont('Arial','B',10);
$pdf->SetFillColor(255);
$pdf->Cell(58,5,utf8_decode('Unité d\'enseignement'));
$pdf->Cell(15,5,utf8_decode('UFR'));
$pdf->Cell(8,5,utf8_decode('CM'));
$pdf->Cell(9,5,utf8_decode('grpe'));
$pdf->Cell(8,5,utf8_decode('TD'));
$pdf->Cell(9,5,utf8_decode('grpe'));
$pdf->Cell(8,5,utf8_decode('TP'));
$pdf->Cell(9,5,utf8_decode('grpe'));
$pdf->Cell(13,5,utf8_decode('Colles'));
$pdf->Cell(9,5,utf8_decode('grpe'));
$pdf->Cell(13,5,utf8_decode('Terrain'));
$pdf->Cell(14,5,utf8_decode('jours'));
$pdf->Cell(20,5,utf8_decode('Éq. TD'));
$pdf->Ln();
$pdf->SetFont('Arial','',10);
$s_ues="SELECT LEU.id_ue, UE.intitule, UFR.libelle AS ufr, 
		LEU.heures_cours, LEU.ng_cours, LEU.heures_TD, LEU.ng_TD, LEU.heures_TP, LEU.ng_TP,
		LEU.heures_colle, LEU.ng_colles, LEU.heures_terrain, LEU.njours_terrain, 
		(1.5*LEU.heures_cours*LEU.ng_cours+LEU.heures_TD*LEU.ng_TD+LEU.heures_TP*LEU.ng_TP+
		LEU.heures_colle*LEU.ng_colles+LEU.heures_terrain*LEU.njours_terrain) AS heures_eq_TD
		FROM l_enseignant_ue LEU
		INNER JOIN Unites_Enseignement UE 
			ON LEU.id_ue=UE.id_ue
		INNER JOIN a_ufr UFR
			ON UE.id_ufr=UFR.id_ufr
		WHERE LEU.id_enseignant=".$id_enseignant."
			AND LEU.id_annee_scolaire=".$id_annee_scolaire."
		ORDER BY UE.intitule";
$ues=recuperation_donnees($s_ues);
foreach($ues as $ue) {
	$pdf->Cell(58,5,utf8_decode(substr($ue['intitule'],0,35)));
	$pdf->Cell(15,5,utf8_decode($ue['ufr']));
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(8,5,utf8_decode($ue['heures_cours']),0,0,'R');
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(9,5,utf8_decode($ue['ng_cours']),0,0,'R');
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(8,5,utf8_decode($ue['heures_TD']),0,0,'R');
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(9,5,utf8_decode($ue['ng_TD']),0,0,'R');
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(8,5,utf8_decode($ue['heures_TP']),0,0,'R');
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(9,5,utf8_decode($ue['ng_TP']),0,0,'R');
	$pdf->Cell(13,5,utf8_decode($ue['heures_colle']),0,0,'R');
	$pdf->Cell(9,5,utf8_decode($ue['ng_colles']),0,0,'R');
	$pdf->Cell(13,5,utf8_decode($ue['heures_terrain']),0,0,'R');
	$pdf->Cell(9,5,utf8_decode($ue['njours_terrain']),0,0,'R');
	$pdf->Cell(21,5,utf8_decode($ue['heures_eq_TD']),0,0,'R');		
	$pdf->Ln();
	$total_heures_presentiel+=$ue['heures_eq_TD'];
	if ($ue['ufr']=='STEP') {
		$total_heures_STEP+=$ue['heures_eq_TD'];
	} else {
		$total_heures_hors+=$ue['heures_eq_TD'];
	}
}

$pdf->SetFont('Arial','B',10);
$pdf->Cell(169,5,utf8_decode('TOTAL HEURES PRÉSENTIEL'));		
$pdf->Cell(20,5,utf8_decode($total_heures_presentiel),0,0,'R');		
$pdf->Ln();
$pdf->Ln();


/* Stages et cas d'études */
$pdf->SetFillColor(204,204,255);
$pdf->SetDrawColor(204,204,255);
$pdf->SetTextColor(0,0,255);
$pdf->Cell(70,5,utf8_decode('Activités maquettées hors présentiel'),1,0,'',1);
$pdf->SetTextColor(0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(120,5,utf8_decode('(encadrement de stages et cas d\'étude)'),1,0,'',1);
$pdf->Ln();
$pdf->SetFillColor(255);

/* Récapitulatif des encadrements */

$s_encadrements="SELECT ES.id_type_encadrant, TE.libelle, ES.id_stage
				FROM l_encadrant_stage ES
				INNER JOIN Enseignants E
					ON E.id_enseignant=ES.id_encadrant
				INNER JOIN a_type_encadrant TE
					ON TE.id_type_encadrant=ES.id_type_encadrant
				WHERE ES.id_encadrant=".$id_enseignant."
					AND ES.id_annee_scolaire=".$id_annee_scolaire."
				ORDER BY ES.id_type_encadrant";
//echo $s_encadrements:

$r_encadrements=mysql_query($s_encadrements);
$encadrements=array();
while ($d_encadrements=mysql_fetch_array($r_encadrements)) {
	$encadrements[$d_encadrements['id_type_encadrant']]['libelle']=$d_encadrements['libelle'];
	$encadrements[$d_encadrements['id_type_encadrant']]['stages'][]=$d_encadrements['id_stage'];
}

/*echo '<pre>';
print_r($encadrements);
echo '</pre>';*/
foreach($encadrements AS $id_type_encadrant => $data) {
	if ($id_type_encadrant==1 OR $id_type_encadrant==2 OR $id_type_encadrant==3) {
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(70,5,utf8_decode($data['libelle']));
		$pdf->Cell(20,5,utf8_decode('Niveau'));
		$pdf->Cell(15,5,utf8_decode('%'));
		$pdf->Cell(24,5,utf8_decode('Directeur'));
		$pdf->Cell(24,5,utf8_decode('Codirecteur 1'));
		$pdf->Cell(24,5,utf8_decode('Codirecteur 2'));
		$pdf->Cell(25,5,utf8_decode('Éq. TD'));
		$pdf->Ln();
		$pdf->SetFont('Arial','',10);
		foreach ($data['stages'] as $id_stage_laboratoire) {
			$s_stage_L="SELECT CONCAT(E.nom,' ',E.prenom) AS etudiant, NI.abbreviation AS niveau,
				ENS1.nom AS directeur, ENS2.nom AS codir1, ENS3.nom AS codir2
				FROM Stages_Laboratoires SL
				INNER JOIN Etudiants E
					ON SL.id_etudiant=E.id_etudiant
				INNER JOIN l_parcours_etudiant P
					ON E.id_etudiant=P.id_etudiant
					AND P.id_annee_scolaire=".$id_annee_scolaire."
				INNER JOIN a_niveau NI
					ON NI.id_niveau=P.id_niveau
				INNER JOIN l_encadrant_stage ES1
					ON SL.id_stage_laboratoire=ES1.id_stage
					AND ES1.id_type_encadrant=1
				LEFT JOIN Enseignants ENS1 
					ON ES1.id_encadrant=ENS1.id_enseignant
				LEFT JOIN l_encadrant_stage ES2
					ON SL.id_stage_laboratoire=ES2.id_stage
					AND ES2.id_type_encadrant=2
				LEFT JOIN Enseignants ENS2 
					ON ES2.id_encadrant=ENS2.id_enseignant
				LEFT JOIN l_encadrant_stage ES3
					ON SL.id_stage_laboratoire=ES3.id_stage
					AND ES3.id_type_encadrant=3
				LEFT JOIN Enseignants ENS3 
					ON ES3.id_encadrant=ENS3.id_enseignant
				WHERE SL.id_stage_laboratoire=".$id_stage_laboratoire."
				ORDER BY NI.abbreviation";
			$r_stage_L=mysql_query($s_stage_L);
			while ($d_stage_L=mysql_fetch_array($r_stage_L)) {
				$pdf->Cell(70,5,utf8_decode($d_stage_L['etudiant']));
				$n_directeur=0;
				if ($d_stage_L['directeur']!='') {
					$n_directeur++;
				}
				if ($d_stage_L['codir1']!='') {
					$n_directeur++;
				}
				if ($d_stage_L['codir2']!='') {
					$n_directeur++;
				}
				$pdf->Cell(20,5,utf8_decode($d_stage_L['niveau']));
				$pdf->Cell(15,5,utf8_decode(round(100/$n_directeur).'%'));
				$pdf->Cell(24,5,utf8_decode($d_stage_L['directeur']));
				$pdf->Cell(24,5,utf8_decode($d_stage_L['codir1']));
				$pdf->Cell(24,5,utf8_decode($d_stage_L['codir2']));
				$pdf->Cell(10,5,utf8_decode(round(12/$n_directeur)),0,0,'R');
				$pdf->Ln();
				$total_heures_non_pres+=round(12/$n_directeur);
			}
		}
		
	} elseif ($id_type_encadrant==7 OR $id_type_encadrant==8) {		
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(70,5,utf8_decode($data['libelle']));
		$pdf->Cell(20,5,utf8_decode('Niveau'));
		$pdf->Cell(15,5,utf8_decode('%'));
		$pdf->Cell(30,5,utf8_decode('Tuteur 1'));
		$pdf->Cell(30,5,utf8_decode('Tuteur 2'));
		$pdf->SetX(185);
		$pdf->Cell(25,5,utf8_decode('Éq. TD'));
		$pdf->Ln();

		foreach ($data['stages'] as $id_stage_entreprise) {
			$pdf->SetFont('Arial','',10);
			$s_stage_R="SELECT CONCAT(E.nom,' ',E.prenom) AS etudiant, NI.abbreviation AS niveau,
				TUT1.nom AS tuteur1, TUT2.nom AS tuteur2
				FROM Stages_Entreprises SE
				INNER JOIN Etudiants E
					ON SE.id_etudiant=E.id_etudiant
				INNER JOIN l_parcours_etudiant P
					ON E.id_etudiant=P.id_etudiant
					AND P.id_annee_scolaire=".$id_annee_scolaire."
				INNER JOIN a_niveau NI
					ON NI.id_niveau=P.id_niveau
				LEFT JOIN l_encadrant_stage ES1
					ON SE.id_stage_entreprise=ES1.id_stage
					AND ES1.id_type_encadrant=7
				LEFT JOIN Enseignants TUT1 
					ON ES1.id_encadrant=TUT1.id_enseignant
				LEFT JOIN l_encadrant_stage ES2
					ON SE.id_stage_entreprise=ES2.id_stage
					AND ES2.id_type_encadrant=8
				LEFT JOIN Enseignants TUT2 
					ON ES2.id_encadrant=TUT2.id_enseignant
				WHERE SE.id_stage_entreprise=".$id_stage_entreprise."
				ORDER BY NI.abbreviation";
				
			$r_stage_R=mysql_query($s_stage_R);
			while ($d_stage_R=mysql_fetch_array($r_stage_R)) {
				$pdf->Cell(70,5,utf8_decode($d_stage_R['etudiant']));
				$n_tuteur=0;
				if ($d_stage_R['tuteur1']!='') {
					$n_tuteur++;
				}
				if ($d_stage_R['tuteur2']!='') {
					$n_tuteur++;
				}
				$pdf->Cell(20,5,utf8_decode($d_stage_R['niveau']));
				$pdf->Cell(15,5,utf8_decode(round(100/$n_tuteur).'%'));
				$pdf->Cell(30,5,utf8_decode($d_stage_R['tuteur1']));
				$pdf->Cell(30,5,utf8_decode($d_stage_R['tuteur2']));				
				$coeff=array('L2' => 12, 'L3' => 12, 'M1' => 12, 'M2' => 12);
				$pdf->SetX(174);
				$pdf->Cell(25,5,utf8_decode($coeff[$d_stage_R['niveau']]/$n_tuteur),0,0,'R');
				$total_heures_non_pres+=$coeff[$d_stage_R['niveau']]/$n_tuteur;
				$pdf->Ln();
			}
			
		}
	}
}
$pdf->SetFont('Arial','B',10);
$pdf->Cell(169,5,utf8_decode('TOTAL HEURES NON PRÉSENTIEL'));		
$pdf->Cell(20,5,utf8_decode($total_heures_non_pres),0,0,'R');
$total_heures_STEP+=$total_heures_non_pres;		
$pdf->Ln();
$pdf->Ln();

/* Activités d'intérêt général */

$pdf->SetFillColor(204,204,255);
$pdf->SetDrawColor(204,204,255);
$pdf->SetTextColor(0,0,255);
$pdf->Cell(70,5,utf8_decode('Activités d\'intérêt général'),1,0,'',1);
$pdf->SetTextColor(0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(120,5,utf8_decode('(responsabilités + UE hors maquette)'),1,0,'',1);
$pdf->Ln();
$pdf->SetFillColor(255);

$s_activites="SELECT R.libelle, R.decharge_horaire AS decharge
			FROM Responsabilites R
			WHERE R.id_enseignant=".$id_enseignant."
			UNION
			SELECT HM.libelle, EHM.decharge
			FROM l_enseignant_hors_maquette EHM
			INNER JOIN a_hors_maquette HM
				ON EHM.id_hors_maquette=HM.id_hors_maquette
			WHERE EHM.id_enseignant=".$id_enseignant." 
				AND EHM.id_annee_scolaire=".$id_annee_scolaire;
$activites=recuperation_donnees($s_activites);

foreach ($activites as $activite) {
	$pdf->Cell(169,5,utf8_decode($activite['libelle']));
	$pdf->Cell(20,5,utf8_decode($activite['decharge']),0,0,'R');		
	$pdf->Ln();
	$total_heures_activite+=$activite['decharge'];
}			
$total_heures_STEP+=$total_heures_activite;
$pdf->SetFont('Arial','B',10);
$pdf->Cell(169,5,utf8_decode('TOTAL HEURES RESPONSABILITÉS ET HORS MAQUETTE'));		
$pdf->Cell(20,5,utf8_decode($total_heures_activite),0,0,'R');		
$pdf->Ln();
$pdf->Ln();


/* Totaux en haut à gauche */
$pdf->SetXY($TOTAL_X,$TOTAL_Y);
$pdf->SetFont('Arial','',10);
$pdf->Cell(35,5,utf8_decode('Horaire statutaire'));
$pdf->SetFont('Arial','B',10);
$pdf->Cell(10,5,utf8_decode('192'),0,0,'R');
$pdf->Ln();
$pdf->SetX(135);
$pdf->SetFont('Arial','',10);
$pdf->Cell(35,5,utf8_decode('Total présentiel'));
$pdf->Cell(10,5,utf8_decode($total_heures_presentiel),0,0,'R');
$pdf->Ln();
$pdf->SetX(135);
$pdf->Cell(35,5,utf8_decode('Total hors présentiel'));
$pdf->Cell(10,5,utf8_decode($total_heures_non_pres),0,0,'R');
$pdf->Ln();
$pdf->SetX(135);
$pdf->Cell(35,5,utf8_decode('Total responsabilités'));
$pdf->Cell(10,5,utf8_decode($total_heures_activite),0,0,'R');
$pdf->Ln();
$pdf->Ln();
$pdf->SetX(135);
$pdf->Cell(35,5,utf8_decode('Total UFR STEP'));
$pdf->Cell(10,5,utf8_decode($total_heures_STEP),0,0,'R');
$pdf->Ln();
$pdf->SetX(135);
$pdf->Cell(35,5,utf8_decode('Total autres UFR'));
$pdf->Cell(10,5,utf8_decode($total_heures_hors),0,0,'R');
$pdf->Ln();
$pdf->Ln();
$pdf->SetX(135);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(35,5,utf8_decode('Total des services'));
$pdf->SetFillColor(255,255,0);
$pdf->Cell(10,5,utf8_decode($total_heures_presentiel+$total_heures_non_pres+$total_heures_activite),0,0,'R',1);
$pdf->Ln();
$pdf->Ln();

if ($n==1) {	
	$pdf->Output('service_'.$enseignant[0]['nom'].'_'.$enseignant[0]['prenom'].'_'.$annee.'-'.($annee+1).'.pdf','I');
	exit;
}


?>

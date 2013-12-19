<?php
$s_annee_scolaire="SELECT * FROM a_annee_scolaire WHERE id_annee_scolaire=".$id_annee_scolaire;
$r_annee_scolaire=mysql_query($s_annee_scolaire);
$annee=mysql_fetch_array($r_annee_scolaire);

// Récupération des infos de l'étudiant
$s_etudiant="SELECT E.nom, E.prenom, E.date_naissance, VI.libelle AS ville, E.numero_ine, E.numero_etudiant, 
			NI.libelle AS niveau, NI.id_niveau, SP.libelle AS specialite, SP.abbreviation AS spec, SP.id_specialite,
			P.note_moyenne, P.classement, M.libelle AS mention, P.avis_jury 
			FROM Etudiants E 
			LEFT JOIN l_parcours_etudiant P 
				ON P.id_etudiant=E.id_etudiant
			INNER JOIN a_niveau NI 
				ON NI.id_niveau=P.id_niveau
			INNER JOIN a_specialite SP 
				ON SP.id_specialite=P.id_specialite
			LEFT JOIN a_mention M 
				ON M.id_mention=P.id_mention
			LEFT JOIN a_ville VI 
				ON VI.id_ville=E.id_ville_naissance
			WHERE E.id_etudiant=".$id." AND P.id_annee_scolaire=".$id_annee_scolaire;

$r_etudiant=mysql_query($s_etudiant)
	or die(mysql_error());
$d_etudiant=mysql_fetch_array($r_etudiant);
$nom=$d_etudiant['nom'];
$prenom=$d_etudiant['prenom'];
$date_naissance=$d_etudiant['date_naissance'];
$ville_naissance=$d_etudiant['ville'];
$ine=$d_etudiant['numero_ine'];
$numero=$d_etudiant['numero_etudiant'];
$niveau=$d_etudiant['niveau'];
$specialite=$d_etudiant['specialite'];
$moyenne_generale=$d_etudiant['note_moyenne'];
$classement=$d_etudiant['classement'];
$mention=$d_etudiant['mention'];
$session2=0;
$avis_jury=$d_etudiant['avis_jury'];

$total_ects=0;
// Décompte du nombre d'étudiants
switch($d_etudiant['spec']) {
	case 'ge':
	case 'gs':
	case 'gf':
	case 'gd':
		$id_specialite='='.$d_etudiant['id_specialite'];
	break;
	case 'gc':
	case 'gp':
	case 'gl':
	case 'gm':
		$id_specialite=' IN (20,22,24,49)';
	break;
}
$s_n_etudiants="SELECT id_etudiant FROM l_parcours_etudiant 
				WHERE id_niveau=".$d_etudiant['id_niveau']." AND id_specialite".$id_specialite." AND id_annee_scolaire=".$id_annee_scolaire;

$r_n_etudiants=mysql_query($s_n_etudiants);
$n_etudiants=mysql_num_rows($r_n_etudiants);

// Récupération des ses unités d'enseignements 
$ues=array();
$s_ues="SELECT UE.intitule, UE.ects, S.libelle AS semestre, TUE.abbreviation AS type_ue, EM.session, EM.moyenne, EM.validante, A.annee_debut AS annee
		FROM Unites_Enseignement UE
		LEFT JOIN l_etudiant_ue EM
			ON UE.id_ue=EM.id_ue
		LEFT JOIN a_type_ue TUE 
			ON EM.id_type_ue=TUE.id_type_ue
		LEFT JOIN a_semestre S
			ON UE.id_semestre=S.id_semestre
		INNER JOIN a_annee_scolaire A 
			ON EM.id_annee_scolaire=A.id_annee_scolaire
		WHERE EM.id_etudiant=".$id." 
		AND EM.id_annee_scolaire=".$id_annee_scolaire."
		ORDER BY  S.libelle, UE.intitule";
//echo $s_ues;
$r_ues=mysql_query($s_ues)
	or die(mysql_error());
while ($d_ues=mysql_fetch_array($r_ues)) {
	if (substr($niveau,0,2)=='Ma') {
		switch($d_ues['type_ue']) {
			case 'uo':
				if ($d_ues['validante']) {
					$ues['uov'][]=array('intitule' => $d_ues['intitule'], 'ects' => $d_ues['ects'], 'moyenne' => $d_ues['moyenne'],
						'session' => $d_ues['session'], 'annee' => $d_ues['annee']);
				} else {
					$ues['uonv'][]=array('intitule' => $d_ues['intitule'], 'ects' => $d_ues['ects'], 'moyenne' => $d_ues['moyenne'],
						'session' => $d_ues['session'], 'annee' => $d_ues['annee']);
				}
			break;
			default:
				$ues[$d_ues['type_ue']][]=array('intitule' => $d_ues['intitule'], 'ects' => $d_ues['ects'], 'moyenne' => $d_ues['moyenne'],
						'session' => $d_ues['session'], 'annee' => $d_ues['annee']);
			
		}
	} else {
		$ues[$d_ues['semestre']][]=array('intitule' => $d_ues['intitule'], 'ects' => $d_ues['ects'], 'moyenne' => $d_ues['moyenne'],
						'session' => $d_ues['session'], 'annee' => $d_ues['annee']);
		
	}
}
/*echo '<pre>';
print_r($ues);
echo '</pre>';
die();*/

// Création du PDF

if ($pdf_complet) {
	$pdf=new PDF();
	$pdf->AliasNbPages();
	$pdf->AddPage();

	//Propriétés du document
	$pdf->SetAuthor('Base de gestion UFR STEP - IPGP');
	$pdf->SetTitle(utf8_decode('Relevé de notes '.$annee['annee_debut'].'-'.($annee['annee_debut']+1).' - '.$nom .' '.$prenom));
}
$pdf->SetLineWidth(0.2);
// Entête
$pdf->Image('public/images/logo-ipgp.jpg',5,6,15);
$pdf->Image('public/images/logo-p7.jpg',20,6,10);
if ($specialite=='Génie de l\'Environnement et Industrie') {
	$pdf->Image('public/images/IUP.jpg',180,6,15);
}
$pdf->Ln();

$pdf->SetFont('Times','I',10);
$pdf->SetXY(50,10);
$pdf->Cell(120,0,utf8_decode('Département d\'enseignement IPGP - Université Paris Diderot'),0,0,'C');
$pdf->SetXY(50,14);
$pdf->Cell(120,0,utf8_decode('UFR STEP - IUP Génie de l\'Environnement'),0,0,'C');
$pdf->SetXY(50,20);
$pdf->SetFont('Times','B',14);
$pdf->Cell(120,0,utf8_decode('RELEVÉ DE NOTES '.$annee['annee_debut'].'-'.($annee['annee_debut']+1)),0,0,'C');
$pdf->SetXY(50,25);
$pdf->SetFont('Times','',10);


switch($niveau) {
	case 'Master 1':
		$grade='MASTER';
		$niv='1ère année';	
	break;
	case 'Master 2':
		$grade='MASTER';
		$niv='2ème année';
	break;
	case 'Licence 2':
		$grade='LICENCE';
		$niv='2ème année';	
	break;
	case 'Licence 3':
		$grade='LICENCE';
		$niv='3ème année';
	break;
}

switch ($specialite) {
	case 'Géophysique de Surface et de Subsurface':
	case 'Génie de l\'Environnement et Industrie':
	case 'Systèmes spatiaux de navigation et géolocalisation':
		$type='PROFESSIONNEL';
	break;
	case 'Géophysique':
	case 'Géochimie':
	case 'Géologie et risques naturels':
	case 'Méthodes physique en télédétection':
		$type='RECHERCHE';
	break;
}
$pdf->Cell(120,0,utf8_decode($grade.' '.$type.' '.$niv),0,0,'C');


$pdf->SetXY(20,32);
$pdf->SetFont('Times','I',10);
$pdf->Cell(20,0,utf8_decode('Domaine :'));
$pdf->SetFont('Times','',10);
$pdf->Cell(60,0,utf8_decode('Sciences, Technologie, Santé'));
$pdf->SetXY(20,37);
$pdf->SetFont('Times','I',10);
$pdf->Cell(20,0,utf8_decode('Mention :'));
$pdf->SetFont('Times','',10);
$pdf->Cell(60,0,utf8_decode('Sciences de la Terre de l\'Environnement et des Planètes'));
$pdf->SetXY(20,42);
$pdf->SetFont('Times','I',10);
$pdf->Cell(20,0,utf8_decode('Spécialité :'));
$pdf->SetFont('Times','',10);
$pdf->Cell(60,0,utf8_decode($specialite));	
//$pdf->Line(10,46,190,46);
// INFOS ETUDIANT
$pdf->SetXY(20,50);
$pdf->SetFont('Times','I',10);
$pdf->Cell(30,0,utf8_decode('Nom : '));
$pdf->SetFont('Times','',10);
$pdf->Cell(50,0,utf8_decode($nom));
$pdf->SetFont('Times','I',10);
$pdf->Cell(30,0,utf8_decode('Prénom : '));
$pdf->SetFont('Times','',10);
$pdf->Cell(50,0,utf8_decode($prenom));
$pdf->SetXY(20,55);
$tmp_date=explode('-',$date_naissance);
$pdf->SetFont('Times','I',10);
$pdf->Cell(30,0,utf8_decode('Né(e) le : '));
$pdf->SetFont('Times','',10);
$pdf->Cell(50,0,utf8_decode($tmp_date[2].'/'.$tmp_date[1].'/'.$tmp_date[0]));
$pdf->SetFont('Times','I',10);
$pdf->Cell(30,0,utf8_decode('à : '));
$pdf->SetFont('Times','',10);
$pdf->Cell(50,0,utf8_decode($ville_naissance));
$pdf->SetXY(20,60);
$pdf->SetFont('Times','I',10);
$pdf->Cell(30,0,utf8_decode('N° étudiant : '));
$pdf->SetFont('Times','',10);
$pdf->Cell(50,0,utf8_decode($numero));
$pdf->SetFont('Times','I',10);
$pdf->Cell(30,0,utf8_decode('N° INE : '));
$pdf->SetFont('Times','',10);
$pdf->Cell(50,0,utf8_decode($ine));


if (substr($niveau,0,2)=='Ma') {
	// UC
	if (sizeof($ues['uc'])!=0) {
		$pdf->SetXY(10,70);
		$pdf->SetFont('Times','B',10);
		$pdf->Cell(130,7,utf8_decode('Unités d\'enseignement de tronc commun'),1);
		$pdf->Cell(15,7,utf8_decode('Année'),1,0,'C');
		$pdf->Cell(15,7,utf8_decode('Session'),1,0,'C');
		$pdf->Cell(15,7,utf8_decode('ECTS'),1,0,'C');
		$pdf->Cell(15,7,utf8_decode('Note /20'),1,0, 'C');
		$pdf->Ln();
		$pdf->SetFont('Times','',10);
		foreach($ues['uc'] as $u) {
			$star='';
			if (sizeof($ues['u2'])>0 ) {
				foreach($ues['u2'] as $u_2) {
					if ($u_2['intitule']==$u['intitule']) {
						$session2=1;
						$star='*';
					}
				}
			}
			$pdf->Cell(130,5,utf8_decode($u['intitule'].' '.$star),1);
			$pdf->Cell(15,5,utf8_decode($u['annee']),1,0,'C');
			$pdf->Cell(15,5,utf8_decode($u['session']),1,0,'C');
			$pdf->Cell(15,5,utf8_decode($u['ects']),1,0,'C');
			switch($u['moyenne']) {
				case -4:
					$pdf->Cell(15,5,utf8_decode('dispensé'),1,0,'C');
				break;
				case -3:
					$pdf->Cell(15,5,utf8_decode('en attente'),1,0,'C');
				break;
				case -2:
					$pdf->Cell(15,5,utf8_decode('validé'),1,0,'C');
					$total_ects+=$u['ects'];
				break;
				case -1:
					$pdf->Cell(15,5,utf8_decode('absent'),1,0,'C');
				break;
				case '':
					$pdf->Cell(15,5,utf8_decode('--'),1,0,'C');	
				break;
				default:
					$pdf->Cell(15,5,utf8_decode(number_format($u['moyenne'],2)),1,0,'C');
					$total_ects+=$u['ects'];
			} 
			$pdf->Ln();
		}
	}
	$pdf->Ln(3);
	// US 
	if (sizeof($ues['us'])!=0) {
		$pdf->SetFont('Times','B',10);
		$pdf->Cell(130,7,utf8_decode('Unités d\'enseignement de spécialité'),1);
		$pdf->Cell(15,7,utf8_decode('Année'),1,0,'C');
		$pdf->Cell(15,7,utf8_decode('Session'),1,0,'C');
		$pdf->Cell(15,7,utf8_decode('ECTS'),1,0,'C');
		$pdf->Cell(15,7,utf8_decode('Note /20'),1,0, 'C');
		$pdf->Ln();
		$pdf->SetFont('Times','',10);
		foreach($ues['us'] as $u) {
			$star='';
			if (sizeof($ues['u2'])>0 ) {
				foreach($ues['u2'] as $u_2) {
					if ($u_2['intitule']==$u['intitule']) {
						$session2=1;
						$star='*';
					}
				}
			}
			$pdf->Cell(130,5,utf8_decode($u['intitule'].' '.$star),1);
			$pdf->Cell(15,5,utf8_decode($u['annee']),1,0,'C');
			$pdf->Cell(15,5,utf8_decode($u['session']),1,0,'C');
			$pdf->Cell(15,5,utf8_decode($u['ects']),1,0,'C');
			switch($u['moyenne']) {
				case -4:
					$pdf->Cell(15,5,utf8_decode('dispensé'),1,0,'C');
				break;
				case -3:
					$pdf->Cell(15,5,utf8_decode('en attente'),1,0,'C');
				break;
				case -2:
					$pdf->Cell(15,5,utf8_decode('validé'),1,0,'C');
					$total_ects+=$u['ects'];
				break;
				case -1:
					$pdf->Cell(15,5,utf8_decode('absent'),1,0,'C');
				break;
				case '':
					$pdf->Cell(15,5,utf8_decode('--'),1,0,'C');	
				break;
				default:
					$pdf->Cell(15,5,utf8_decode(number_format($u['moyenne'],2)),1,0,'C');
					$total_ects+=$u['ects'];
			} 
			$pdf->Ln();
		}
		$pdf->Ln(3);
	}
	// UOV
	if (sizeof($ues['uov'])!=0) {
		$pdf->SetFont('Times','B',10);
		$pdf->Cell(130,7,utf8_decode('Unités optionnelles validantes'),1);
		$pdf->Cell(15,7,utf8_decode('Année'),1,0,'C');
		$pdf->Cell(15,7,utf8_decode('Session'),1,0,'C');
		$pdf->Cell(15,7,utf8_decode('ECTS'),1,0,'C');
		$pdf->Cell(15,7,utf8_decode('Note /20'),1,0, 'C');
		$pdf->Ln();
		$pdf->SetFont('Times','',10);
		foreach($ues['uov'] as $u) {
			$star='';
			if (sizeof($ues['u2'])>0 ) {
				foreach($ues['u2'] as $u_2) {
					if ($u_2['intitule']==$u['intitule']) {
						$session2=1;
						$star='*';
					}
				}
			}
			$pdf->Cell(130,5,utf8_decode($u['intitule'].' '.$star),1);
			$pdf->Cell(15,5,utf8_decode($u['annee']),1,0,'C');
			$pdf->Cell(15,5,utf8_decode($u['session']),1,0,'C');
			$pdf->Cell(15,5,utf8_decode($u['ects']),1,0,'C');
			switch($u['moyenne']) {
				case -4:
					$pdf->Cell(15,5,utf8_decode('dispensé'),1,0,'C');
				break;
				case -3:
					$pdf->Cell(15,5,utf8_decode('en attente'),1,0,'C');
				break;
				case -2:
					$pdf->Cell(15,5,utf8_decode('validé'),1,0,'C');
					$total_ects+=$u['ects'];
				break;
				case -1:
					$pdf->Cell(15,5,utf8_decode('absent'),1,0,'C');
				break;
				case '':
					$pdf->Cell(15,5,utf8_decode('--'),1,0,'C');	
				break;
				default:
					$pdf->Cell(15,5,utf8_decode(number_format($u['moyenne'],2)),1,0,'C');
					$total_ects+=$u['ects'];
			} 
			$pdf->Ln();
		}
		$pdf->Ln(3);
	}
	// UONV
	if (sizeof($ues['uonv'])!=0) {
		$pdf->SetFont('Times','BI',10);
		$pdf->Cell(130,7,utf8_decode('Unités optionnelles non validantes'),1);
		$pdf->Cell(15,7,utf8_decode('Année'),1,0,'C');
		$pdf->Cell(15,7,utf8_decode('Session'),1,0,'C');
		$pdf->Cell(15,7,utf8_decode('ECTS'),1,0,'C');
		$pdf->Cell(15,7,utf8_decode('Note /20'),1,0, 'C');
		$pdf->Ln();
		$pdf->SetFont('Times','I',10);
		foreach($ues['uonv'] as $u) {
			if ($u['moyenne']>=10) {
				$pdf->Cell(130,5,utf8_decode($u['intitule']),1);
				$pdf->Cell(15,5,utf8_decode($u['annee']),1,0,'C');
				$pdf->Cell(15,5,utf8_decode($u['session']),1,0,'C');
				$pdf->Cell(15,5,utf8_decode($u['ects']),1,0,'C');
				switch($u['moyenne']) {
					case -4:
						$pdf->Cell(15,5,utf8_decode('dispensé'),1,0,'C');
					break;
					case -3:
						$pdf->Cell(15,5,utf8_decode('en attente'),1,0,'C');
					break;
					case -2:
						$pdf->Cell(15,5,utf8_decode('validé'),1,0,'C');
					break;
					case -1:
						$pdf->Cell(15,5,utf8_decode('absent'),1,0,'C');
					break;
					case '':
						$pdf->Cell(15,5,utf8_decode('--'),1,0,'C');	
					break;
					default:
						$pdf->Cell(15,5,utf8_decode(number_format($u['moyenne'],2)),1,0,'C');
				} 
				$pdf->Ln();
			}
		}
		$pdf->Ln(3);
	}
} else {
	$pdf->SetXY(10,70);
	foreach ($ues as $semestre => $ue) {
		$pdf->SetFont('Times','BI',10);
		if ($semestre!='') {
			$pdf->Cell(130,7,utf8_decode('Unités d\'enseignement du '.$semestre),1);
		} else {
			$pdf->Cell(130,7,utf8_decode('Unités d\'enseignement dont le semestre n\'a pas été défini'),1);
		}
		$pdf->Cell(15,7,utf8_decode('Année'),1,0,'C');
		$pdf->Cell(15,7,utf8_decode('Session'),1,0,'C');
		$pdf->Cell(15,7,utf8_decode('ECTS'),1,0,'C');
		$pdf->Cell(15,7,utf8_decode('Note /20'),1,0, 'C');
		$pdf->Ln();
		$pdf->SetFont('Times','I',10);
		foreach($ue as $u) {
			$star='';
			if (sizeof($ues['u2'])>0 ) {
				foreach($ues['u2'] as $u_2) {
					if ($u_2['intitule']==$u['intitule']) {
						$session2=1;
						$star='*';
					}
				}
			}
			$pdf->Cell(130,5,utf8_decode($u['intitule'].' '.$star),1);
			$pdf->Cell(15,5,utf8_decode($u['annee']),1,0,'C');
			$pdf->Cell(15,5,utf8_decode($u['session']),1,0,'C');
			$pdf->Cell(15,5,utf8_decode($u['ects']),1,0,'C');
			switch($u['moyenne']) {
				case -4:
					$pdf->Cell(15,5,utf8_decode('dispensé'),1,0,'C');
				break;
				case -3:
					$pdf->Cell(15,5,utf8_decode('en attente'),1,0,'C');
				break;
				case -2:
					$pdf->Cell(15,5,utf8_decode('validé'),1,0,'C');
					$total_ects+=$u['ects'];
				break;
				case -1:
					$pdf->Cell(15,5,utf8_decode('absent'),1,0,'C');
				break;
				case '':
					$pdf->Cell(15,5,utf8_decode('--'),1,0,'C');	
				break;
				default:
					$pdf->Cell(15,5,utf8_decode(number_format($u['moyenne'],2)),1,0,'C');
					$total_ects+=$u['ects'];
			} 
			$pdf->Ln();
		}
		$pdf->Ln(3);
	}
}

if ($moyenne_generale==-1) {
	$moyenne_generale='Absent';
	$classement='Absent';
} else {
	$moyenne_generale=number_format($moyenne_generale,2);
	$classement=$classement.'/'.$n_etudiants;
}
$pdf->SetLineWidth(0.5);
$pdf->Rect(20,205,170,30);
$pdf->SetXY(25,210);
$pdf->SetFont('Times','I',12);
$pdf->Cell(50,0,utf8_decode('Moyenne Générale : '));
$pdf->SetFont('Times','',12);
$moyenne_generale=($moyenne_generale==0)?'En attente':$moyenne_generale;
$pdf->Cell(30,0,utf8_decode($moyenne_generale));
$pdf->SetFont('Times','I',12);
$pdf->Cell(50,0,utf8_decode('ECTS validé :'));
$pdf->SetFont('Times','',12);
$pdf->Cell(30,0,utf8_decode($total_ects));
$pdf->Ln(7);
$pdf->SetX(25);
if ($avecrang) {
	$pdf->SetFont('Times','I',12);
	$pdf->Cell(50,0,utf8_decode('Classement : '));
	$pdf->SetFont('Times','',12);
	$pdf->Cell(30,0,utf8_decode($classement));
}

if ($moyenne_generale=='En attente') {
	$decision='En attente';
	$mention='En attente';
} else {
	if ($avis_jury=='') {
		if ($session2) {
			$decision='Est convoqué en session 2 pour les UE marquées d\'une *';
		} else {
			$decision='A validé son année';
		}
	} else {
		$decision=$avis_jury;
	}
}
if ($avecmention) {
	$pdf->SetFont('Times','I',12);
	$pdf->Cell(50,0,utf8_decode('Mention : '));
	$pdf->SetFont('Times','',12);
	$pdf->Cell(30,0,utf8_decode($mention));
}
$pdf->Ln(10);
$pdf->SetX(30);
$pdf->SetFont('Times','B',12);

$pdf->Cell(10,0,utf8_decode('Décision du jury : '.$decision));


$pdf->SetFont('Times','',12);
$pdf->SetXY(120,245);
$pdf->Cell(1,0,utf8_decode('Paris, le '.date('d/m/Y')));
$pdf->SetXY(120,250);

if (substr($niveau,0,2)=='Ma') {
	if ($d_etudiant['specialite']=='Géophysique de Surface et de Subsurface') {
		$intitule_signature='Le responsable de la spécialité G2S,';
		$nom_signature='François Métivier';
	} elseif ($d_etudiant['specialite']=='Génie de l\'Environnement et Industrie') {
		$intitule_signature='Le directeur de l\'IUP,';
		$nom_signature='Jean-Pierre Frangi';
		
	} else {
		$intitule_signature='La responsable du Master,';
		$nom_signature='Laure Meynadier';
	}
} else {
	$intitule_signature='Le directeur de l\'UFR STEP,';
	$nom_signature='Édouard Kaminski';
}
$pdf->Cell(1,0,utf8_decode($intitule_signature));
$pdf->SetXY(120,255);
$pdf->Cell(1,0,utf8_decode($nom_signature));

if ($pdf_complet) {
	$pdf->Output('releve_'.$nom.'_'.$prenom.'.pdf','I');
	exit;
}

?>

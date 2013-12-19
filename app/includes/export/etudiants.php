<?


switch ($nom_requete[1]) {
	case 'releves':
		switch ($nom_requete[2]) {
			case 'complet':
				$avecrang=1;
				$avecmention=1;
			break;
			case 'provisoire':
				$avecrang=0;
				$avecmention=0;
			break;
			case 'mention':
				$avecrang=0;
				$avecmention=1;
			break;
		}
		if (isset($nom_requete[3])) {
			$id=$nom_requete[3];
			if ($id!='last') {
				$pdf_complet=1;
				include('app/includes/export/etudiants/releves.php');
			} else {
				$r_etudiants=mysql_query($_SESSION['etudiants_last']);
				$pdf=new PDF();
				$pdf->AliasNbPages();
			
				//Propriétés du document
				$pdf->SetAuthor('Base de gestion UFR STEP - IPGP');
				$pdf->SetTitle(utf8_decode('Relevé de notes'));
			
				while ($d_etudiants=mysql_fetch_array($r_etudiants)) {
					$id=$d_etudiants['id_etudiant'];
					$pdf_complet=0;
					$pdf->AddPage();
					include('app/includes/export/etudiants/releves.php');
				}
				$pdf->Output('releve_last.pdf','I');
			}
		} else {
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
			
			$r_etudiants=mysql_query($s_etudiants);
		
			$pdf=new PDF();
			$pdf->AliasNbPages();
		
			//Propriétés du document
			$pdf->SetAuthor('Base de gestion UFR STEP - IPGP');
			$pdf->SetTitle(utf8_decode('Relevé de notes'));
		
			while ($d_etudiants=mysql_fetch_array($r_etudiants)) {
				$id=$d_etudiants['id_etudiant'];
				$pdf_complet=0;
				$pdf->AddPage();
				include('app/includes/export/etudiants/releves.php');
			}
			$pdf->Output('releve_all.pdf','I');
		}
	break;
	case 'doctorants':
		include('app/includes/export/etudiants/doctorants.php');
	break;
	case 'sansstage':
		include('app/includes/export/etudiants/sans_stage.php');
	break;
	case 'sous8':
		include('app/includes/export/etudiants/notes_sous_8.php');
	break;
	case 'trombi':
		include('app/includes/export/etudiants/trombi.php');
	break;
	case 'vue':
		echo $_SESSION['etudiants_last'];
}
?>

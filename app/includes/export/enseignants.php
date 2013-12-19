<?php
switch($nom_requete[1]) {
	case 'servicecsv':
		require_once('app/includes/export/enseignants/servicecsv.php');
	break;
	case 'service':
		if ($nom_requete[2]!='') {
			$id_enseignant=$nom_requete[2];
			$n=1;
			require_once('app/includes/export/enseignants/service.php');
		} else {
			$pdf=new PDF();
			$pdf->AliasNbPages();
			$pdf->SetAuthor('Base de gestion UFR STEP - IPGP');
			$pdf->SetTitle(utf8_decode('Service des enseignants ('.$annee.' - '.($annee+1).')'));					
			
			$s_enseignant="SELECT id_enseignant FROM Enseignants ORDER BY nom";
			$enseignants=recuperation_donnees($s_enseignant);
			$n=sizeof($enseignants);
			foreach ($enseignants as $data) {
				$id_enseignant=$data['id_enseignant'];
				include('app/includes/export/enseignants/service.php');
			}
			$pdf->Output('service_enseignants_'.$annee.'-'.($annee+1).'.pdf','I');
			exit;
		}
		
	break;
	case 'fiche':
		require_once('app/includes/export/enseignants/fiche.php');
	break;
	default :
		echo $nom_requete[1].' non implémenté !';
}
?>

<?php
/*echo '<pre>';
print_r($nom_requete);
echo '</pre>';*/
$id=$nom_requete[3];
$outil=$nom_requete[2];

if ($id!='last' and $id!='') {
	$complet=1;
	require_once('app/includes/export/unites_enseignement/'.$outil.'.php');
} else {	
	switch ($outil) {
		case 'emargement':
		case 'trombi':
			$pdf=new PDF();
			$pdf->AliasNbPages();
		
			//Propriétés du document
			$pdf->SetAuthor('Base de gestion UFR STEP - IPGP');
		break;
		case 'liste':
			$fname='ue_etudiants_'.date('Ymd').'.csv';
			header("Content-Type: text/csv");
			header('Content-disposition: filename="'.$fname.'"');
		break;
	}
	$r_ues=mysql_query($_SESSION['unites_enseignement_last']);
	
	while ($d_ues=mysql_fetch_array($r_ues)) {
		$id=$d_ues['id_ue'];
		$complet=0;
		include('app/includes/export/unites_enseignement/'.$outil.'.php');
	}
	switch ($outil) {
		case 'emargement':
		case 'trombi':
			$pdf->Output($outil.'_last.pdf','I');
		break;
		case 'liste':
			echo utf8_decode($string_all);
		break;
	}
}
?>
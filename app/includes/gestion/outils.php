<?php
if (isset($_POST['id_annee_scolaire'])) {
	$_SESSION['id_annee_scolaire']=$_POST['id_annee_scolaire'];
} elseif (empty($_SESSION['id_annee_scolaire'])) {
	$_SESSION['id_annee_scolaire']=$id_annee_scolaire;
}

$html='<div class="content_tab"><h2><img src="public/images/icons/outils.png"/> Outils</h2>
	<form id="switch_annee" method="post">
	<input type="hidden" name="page" value="gestion"/>
	<input type="hidden" name="section" value="outils"/>
	<p><select name="id_annee_scolaire">';
$s_annee="SELECT id_annee_scolaire, libelle FROM a_annee_scolaire ORDER BY annee_debut DESC";
$r_annee=mysql_query($s_annee);
while ($d_annee=mysql_fetch_array($r_annee)) {
	$sel=($_SESSION['id_annee_scolaire']==$d_annee['id_annee_scolaire'])?'selected="selected"':'';
	$html.='<option value="'.$d_annee['id_annee_scolaire'].'" '.$sel.'>'.$d_annee['libelle'].'</option>';
}
$html.='</select> <input type="submit" value="Changez d\'année scolaire" onclick="submitForm(\'switch_annee\');"/></p></form>';


$outils=array(
	'notes' => array(
		'licence' => array('icon' => 'csv', 'text' => 'Licence'),
		'M1_GEI' => array('icon' => 'csv', 'text' => 'Master 1 GEI'),
		'M1_G2S' => array('icon' => 'csv', 'text' => 'Master 1 G2S'),
		'M1_R' => array('icon' => 'csv', 'text' => 'Master 1 Recherche'),
		'M2_GEI' => array('icon' => 'csv', 'text' => 'Master 2 GEI'),
		'M2_G2S' => array('icon' => 'csv', 'text' => 'Master 2 G2S'),
		'M2_R' => array('icon' => 'csv', 'text' => 'Master 2 Recherche')),
	'etudiants' => array(
		'releves_complet' => array('icon' => 'pdf', 'text' => 'Relevés complets'),
		'releves_provisoire' => array('icon' => 'pdf', 'text' => 'Relevés provisoires'),
		'doctorants' => array('icon' => 'tous_les_doctorats', 'text' => 'Doctorants'),
		'sansstage' => array('icon' => 'csv', 'text' => 'Sans stage'),
		'sous8' => array('icon' => 'csv', 'text' => 'Avec notes en dessous de 10')),
	'enseignants' => array(
		'servicecsv' => array( 'icon' => 'csv', 'text' => ''),
		'service' => array( 'icon' => 'pdf', 'text' => 'Service des enseignants'),
		'trombi' => array('icon' => 'trombi', 'text' => 'Trombinoscope')),
	'unites_enseignement' => array(
		'fiches' => array('icon' => 'pdf', 'text' => 'Fiches'),
		'etudiants' => array('icon' => 'csv', 'text' => 'Liste des étudiants par UE'),
		'trombi' => array('icon' => 'trombi', 'text' => 'Trombinoscope'),
		'emargement' => array('icon' => 'pdf', 'text' => 'Émargement')),	
	'stages_laboratoires' => array(
		'fiches' => array('icon' => 'pdf', 'text' => 'Fiches'),
		'bilans' => array('icon' => 'csv', 'text' => 'Bilan')),
	'stages_entreprises' => array(
		'fiches' => array('icon' => 'pdf', 'text' => 'Fiches'),
		'bilans' => array('icon' => 'csv', 'text' => 'Bilan')),
	);

$html.='<h3><img src="public/images/icons/notes.png"/> Notes</h3><ul>';
foreach($outils['notes'] as $type_export => $outil) {
	$html.='<li class="link" style="display:inline;margin-right:10px;" onclick="genererFichier(\'notes_'.$type_export.'\',\'pdf\');"/>
		<img src="public/images/icons/'.$outil['icon'].'.png"/> '.$outil['text'].'</li>';
}
$html.='</ul>';
	
foreach($menu['entites'] as $entite => $data) {
	if (isset($outils[$entite])) {
		$html.='<h3><img src="public/images/icons/'.$data['icon'].'.png"/> '.$data['text'].'</h3><ul>';
		foreach($outils[$entite] as $type_export => $outil) {
			$html.='<li class="link" style="display:inline;margin-right:10px;" onclick="genererFichier(\''.$entite.'_'.$type_export.'\',\'pdf\');"/>
			<img src="public/images/icons/'.$outil['icon'].'.png"/> '.$outil['text'].'</li>';
		}
		$html.='</ul>';
	}	
}




$html.='</div>';
?>
 

<!-- 
<ul style="padding-left:20px;list-style-type: square;">
	<li><img src="public/images/icons/pdf.png" onclick="genererFichier('choixmodules','pdf');"/>
		Générer les fiches de choix modules</li>
	<li><img src="public/images/icons/csv.png" onclick="genererFichier('listeparue','csv');"/>
		Liste des etudiants par module</li>
	<li><img src="public/images/icons/csv.png" onclick="genererFichier('ueparspec','csv');"/>
		Liste des UE par spécialités en M2</li>
</ul>
<h3>Doctorants</h3>
<ul style="padding-left:20px;list-style-type: square;">
	<li><img onClick="genererFichier('doctorants','csv');" src="public/images/icons/csv.png" title="Générer un fichier excel avec les notes"/> 
		Listes de doctorants</li>
</ul>
<h3>Bilans des notes des étudiants</h3>
<ul style="padding-left:20px;list-style-type: square;">
	<li><img onClick="genererFichier('bilan_notes_Licence','pdf');" src="public/images/icons/pdf.png" title="Générer tous les relevés au format PDF"/> 
		<img onClick="genererFichier('bilan_notes_Licence','csv');" src="public/images/icons/csv.png" title="Générer un fichier excel avec les notes"/> 
		Licence</li>
	<li><img onClick="genererFichier('bilan_notes_M1_GEI','pdf');" src="public/images/icons/pdf.png" title="Générer tous les relevés au format PDF"/> 
		<img onClick="genererFichier('bilan_notes_M1_GEI','csv');" src="public/images/icons/csv.png" title="Générer un fichier excel avec les notes"/> 
		Master 1 GEI</li>
	<li><img onClick="genererFichier('bilan_notes_M1_G2S','pdf');" src="public/images/icons/pdf.png" title="Générer tous les relevés au format PDF"/>
		<img onClick="genererFichier('bilan_notes_M1_G2S','csv');" src="public/images/icons/csv.png" title="Générer un fichier excel avec les notes"/> 
		Master 1 G2S</li>
	<li><img onClick="genererFichier('bilan_notes_M1_R','pdf');" src="public/images/icons/pdf.png" title="Générer tous les relevés au format PDF"/> 
		<img onClick="genererFichier('bilan_notes_M1_R','csv');" src="public/images/icons/csv.png" title="Générer un fichier excel avec les notes"/> 
		Master 1 Recherche</li>
	<li><img onClick="genererFichier('bilan_notes_M2_GEI','pdf');" src="public/images/icons/pdf.png" title="Générer tous les relevés au format PDF"/> 
		<img onClick="genererFichier('bilan_notes_M2_GEI','csv');"src="public/images/icons/csv.png" title="Générer un fichier excel avec les notes"/> 
		Master 2 GEI</li>
	<li><img onClick="genererFichier('bilan_notes_M2_G2S','pdf');" src="public/images/icons/pdf.png" title="Générer tous les relevés au format PDF"/> 
		<img onClick="genererFichier('bilan_notes_M2_G2S','csv');" src="public/images/icons/csv.png" title="Générer un fichier excel avec les notes"/> 
		Master 2 G2S</li>
	<li><img onClick="genererFichier('bilan_notes_M2_R','pdf');" src="public/images/icons/pdf.png" title="Générer tous les relevés au format PDF"/> 
		<img onClick="genererFichier('bilan_notes_M2_R','csv');" src="public/images/icons/csv.png" title="Générer un fichier excel avec les notes"/> 
		Master 2 Recherche</li>
</ul>
<h3>Bilans des stages</h3>
<ul style="padding-left:20px;list-style-type: square;">
	<li><img onClick="genererFichier('bilan_stagelabo_2009','csv');" src="public/images/icons/csv.png" title="Générer un fichier excel avec les stages de l'année 2009"/>
		 Bilan des stages en laboratoires 2009-2010</li>
	<li><img onClick="genererFichier('bilan_stagepro_2009','csv');" src="public/images/icons/csv.png" title="Générer un fichier excel avec les stages de l'année 2009"/>
		 Bilan des stages en entreprises 2009-2010</li>
	<li><img onClick="genererFichier('etudiants_sansstage_2009','csv');" src="public/images/icons/csv.png" title="Générer un fichier excel avec les étudiants de M qui n'ont pas de stages"/>
		 Étudiants sans stage 2009-2010</li>
</ul>
<h3>Autres bilans</h3>
<ul style="padding-left:20px;list-style-type: square;">
	<li>
		<img onClick="genererFichier('enseignants_service','pdf');" src="public/images/icons/pdf.png"/> 
		<img onClick="genererFichier('enseignants_service','csv');" src="public/images/icons/csv.png"/> Service des enseignants</li>
	<li><img src="public/images/icons/pdf.png"/> <img onClick="genererFichier('bilan_ues','csv');" src="public/images/icons/csv.png"/> Bilan des notes par unité d'enseignement</li>
	<li><img src="public/images/icons/pdf.png"/> <img onClick="genererFichier('notes_en_dessous_de_8','csv');" src="public/images/icons/csv.png"/> Listes des élèves ayant eu une note inférieure à 8</li>
</ul>
 -->

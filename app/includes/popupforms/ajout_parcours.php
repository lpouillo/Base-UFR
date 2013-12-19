<?php
$annees=recuperation_donnees("SELECT id_annee_scolaire, libelle 
		FROM a_annee_scolaire 
		WHERE id_annee_scolaire NOT IN (
			SELECT id_annee_scolaire FROM l_parcours_etudiant 
			WHERE id_etudiant=".$_POST['id']." 
				AND id_etablissement<>0
				AND id_niveau<>0
				AND id_specialite<>0 
		)
		ORDER BY annee_debut DESC");
$niveaux=recuperation_donnees("SELECT id_niveau, libelle FROM a_niveau ORDER BY gestion DESC, libelle");
$specialites=recuperation_donnees("SELECT id_specialite, libelle FROM a_specialite ORDER BY gestion DESC, libelle");
$etablissements=recuperation_donnees("SELECT id_etablissement, nom FROM Etablissements ORDER BY nom");

$html.='<p>Année scolaire <select name="id_annee_scolaire">';
foreach($annees as $annee) {
	$html.='<option value="'.$annee['id_annee_scolaire'].'">'.$annee['libelle'].'</option>';
}
$html.='</select></p>';
$html.='<p>Niveau <select name="id_niveau">';
foreach($niveaux as $niveau) {
	$html.='<option value="'.$niveau['id_niveau'].'">'.$niveau['libelle'].'</option>';
}
$html.='</select></p>';
$html.='<p>Spécialité <select name="id_specialite">';
foreach($specialites as $specialite) {
	$html.='<option value="'.$specialite['id_specialite'].'">'.$specialite['libelle'].'</option>';
}
$html.='</select></p>';
$html.='<p>Etablissment <select name="id_etablissement">';
foreach($etablissements as $etablissement) {
	$html.='<option value="'.$etablissement['id_etablissement'].'">'.$etablissement['nom'].'</option>';
}
$html.='</select></p>';
?>
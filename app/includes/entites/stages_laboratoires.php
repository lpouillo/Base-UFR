<?php
/*
 * Created on 20 oct. 2008
 *
 */
$params=array(
		'common' => array(
			'titre' => 'Stages en laboratoire',
			'element' => 'un stage en laboratoire',
			'icone_titre' => 'stage_laboratoire', 
			'icone_ajout' => 'stage_laboratoire'),
		'liste' => array(
			'message' => 'Voici la liste des stages de l\'UFR STEP et du Master STEP-IPGP.',
			'sql' => "SELECT SL.id_stage_laboratoire, SL.sujet AS sujet, GROUP_CONCAT( DISTINCT NI.abbreviation ) AS niveaux, 
				GROUP_CONCAT( DISTINCT SP.abbreviation ) AS specialites, MAX( A.libelle ) AS annee, CONCAT( P.nom, ' ', P.prenom ) AS directeur,
				CONCAT(E.nom,' ',E.prenom) AS etudiant
				FROM Stages_Laboratoires SL
				INNER JOIN l_ouverture_stage OS 
					ON SL.id_stage_laboratoire = OS.id_stage
					AND OS.id_type_stage =0
					AND OS.ouvert =1
				LEFT JOIN a_niveau NI 
					ON OS.id_niveau = NI.id_niveau
				LEFT JOIN a_specialite SP
					ON OS.id_specialite = SP.id_specialite
				LEFT JOIN a_annee_scolaire A 
					ON OS.id_annee_scolaire = A.id_annee_scolaire
				LEFT JOIN l_encadrant_stage ES 
					ON ES.id_stage = SL.id_stage_laboratoire
					AND OS.id_annee_scolaire = ES.id_annee_scolaire
					AND ES.id_type_encadrant =1
				LEFT JOIN Enseignants P 
					ON P.id_enseignant = ES.id_encadrant
				LEFT JOIN Etudiants E 
					ON E.id_etudiant=SL.id_etudiant",
			'post_sql' => " GROUP BY SL.id_stage_laboratoire ORDER BY SL.sujet",
			'champs' => array(
				'id_stage_laboratoire' => array('id_stage_laboratoire','SL.id_stage_laboratoire'), 
				'sujet' => array('Sujet','SL.sujet'), 
				'niveaux' => array('Niveaux','NI.libelle'), 
				'specialites' => array('Spécialités','SP.libelle'),
				'annee' => array('Année','A.libelle'),
				'directeur' => array('Directeur','P.nom'),
				'etudiant' => array('Étudiant','E.nom')),
			'outils' => array(
				'fiche' => array('icon' => 'pdf','text' => 'Fiches'))),
		'element' => array(
			'sql_select' => "SELECT SL.id_stage_laboratoire, SL.sujet  
				FROM Stages_Laboratoires SL ORDER BY SL.sujet",
			'tabs' => array(
				'infos' => array ('icon' => 'infos', 'text' => 'Infos'),
				'ouvertures' => array ('icon' => 'ouverture', 'text' => 'Ouvertures'),
				'encadrants' => array ('icon' => 'enseignant', 'text' => 'Encadrants')),
			'outils' => array(
				'fiche' => array('icon' => 'pdf','text' => 'Fiche')))
		);
?>

<?php
/*
 * Created on 20 oct. 2008
 *
 */
$params=array(
		'common' => array(
			'titre' => 'Stages en entreprise',
			'element' => 'un stage en entreprise',
			'icone_titre' => 'stage_entreprise', 
			'icone_ajout' => 'stage_entreprise'),
		'liste' => array(
			'message' => 'Voici la liste des stages de l\'UFR STEP et du Master STEP-IPGP.',
			'sql' => "SELECT SE.id_stage_entreprise, SE.sujet AS sujet, 
				GROUP_CONCAT( DISTINCT NI.abbreviation ) AS niveaux, GROUP_CONCAT( DISTINCT SP.abbreviation ) AS specialites, 
				MAX( A.libelle ) AS annee, CONCAT( P.nom, ' ', P.prenom ) AS contact, CONCAT( E.nom,' ',E.prenom) AS etudiant
				FROM Stages_Entreprises SE
				LEFT JOIN l_ouverture_stage OS 
					ON SE.id_stage_entreprise = OS.id_stage
					AND OS.id_type_stage =1
					AND OS.ouvert =1
				LEFT JOIN a_niveau NI 
					ON OS.id_niveau = NI.id_niveau
				LEFT JOIN a_specialite SP 
					ON OS.id_specialite = SP.id_specialite
				LEFT JOIN a_annee_scolaire A 
					ON OS.id_annee_scolaire = A.id_annee_scolaire
				LEFT JOIN l_encadrant_stage ES 
					ON ES.id_stage = SE.id_stage_entreprise
					AND OS.id_annee_scolaire = ES.id_annee_scolaire
					AND ES.id_type_encadrant=4
				LEFT JOIN Professionnels P 
					ON P.id_professionnel = ES.id_encadrant
				LEFT JOIN Etudiants E 
					ON E.id_etudiant = SE.id_etudiant",
			'post_sql' => " GROUP BY SE.id_stage_entreprise ORDER BY SE.sujet",
			'champs' => array(
				'id_stage_entreprise' => array('id_stage_entreprise','SE.id_stage_entreprise'), 
				'sujet' => array('Sujet','SE.sujet'), 
				'niveaux' => array('Niveaux','NI.abbreviation'), 
				'specialites' => array('Spécialités','SP.abbreviation'),
				'annee' => array('Année','A.libelle'),
				'contact' => array('Contact','P.nom'),
				'etudiant' => array('Étudiant','E.nom')),
			'outils' => array(
				'fiche' => array('icon' => 'pdf','text' => 'Fiche'))),
		'element' => array(
			'sql_select' => "SELECT SE.id_stage_entreprise, SE.sujet  
				FROM Stages_Entreprises SE ORDER BY SE.sujet",
			'tabs' => array(
				'infos' => array ('icon' => 'infos', 'text' => 'Infos'),
				'ouvertures' => array ('icon' => 'ouverture', 'text' => 'Ouvertures'),
				'encadrants' => array ('icon' => 'professionnel', 'text' => 'Encadrants')),
			'outils' => array(
				'fiche' => array('icon' => 'pdf','text' => 'Fiche')))
	);
?>

<?php
/*
 * Created on 20 oct. 2008
 *
 */
$params=array(
		'common' => array(
			'titre' => 'Cas d\'études',
			'element' => 'un cas d\'étude',
			'icone_titre' => 'cas_etudes', 
			'icone_ajout' => 'cas_etudes'),
		'liste' => array(
			'message' => 'Voici la liste des cas d\'études de l\'UFR STEP et du Master STEP-IPGP.',
			'sql' => "SELECT CE.id_cas_etude, CE.sujet AS sujet, 
				GROUP_CONCAT( DISTINCT NI.abbreviation ) AS niveaux, GROUP_CONCAT( DISTINCT SP.abbreviation ) AS specialites, 
				MAX( A.libelle ) AS annee, CONCAT( P.nom, ' ', P.prenom ) AS contact, CONCAT( E.nom,' ',E.prenom) AS etudiant
				FROM Cas_Etudes CE
				LEFT JOIN l_ouverture_stage OS 
					ON CE.id_cas_etude = OS.id_stage
					AND OS.id_type_stage =1
					AND OS.ouvert =1
				LEFT JOIN a_niveau NI 
					ON OS.id_niveau = NI.id_niveau
				LEFT JOIN a_specialite SP 
					ON OS.id_specialite = SP.id_specialite
				LEFT JOIN a_annee_scolaire A 
					ON OS.id_annee_scolaire = A.id_annee_scolaire
				LEFT JOIN l_encadrant_stage ES 
					ON ES.id_stage = CE.id_cas_etude
					AND OS.id_annee_scolaire = ES.id_annee_scolaire
					AND ES.id_type_encadrant=4
				LEFT JOIN Professionnels P 
					ON P.id_professionnel = ES.id_encadrant
				LEFT JOIN Etudiants E 
					ON E.id_etudiant = CE.id_etudiant",
			'post_sql' => " GROUP BY CE.id_cas_etude ORDER BY CE.sujet",
			'champs' => array(
				'id_cas_etude' => array('id_cas_etude','CE.id_cas_etude',4), 
				'sujet' => array('Sujet','CE.sujet',15), 
				'niveaux' => array('Niveaux','NI.libelle',10), 
				'specialites' => array('Spécialités','SP.libelle',10),
				'annee' => array('Année','A.libelle',10),
				'contact' => array('Contact','P.nom',20),
				'etudiant' => array('Étudiant','E.nom',4)),
			'outils' => array(
				'fiche' => array('icon' => 'pdf','text' => 'Fiches'))),
		'element' => array(
			'sql_select' => "SELECT CE.id_cas_etude, CE.sujet  
				FROM Cas_Etudes CE ORDER BY CE.sujet",
			'tabs' => array(
				'infos' => array ('icon' => 'infos', 'text' => 'Infos'),
				'ouvertures' => array ('icon' => 'ouverture', 'text' => 'Ouvertures'),
				'encadrants' => array ('icon' => 'professionnel', 'text' => 'Encadrants')),
			'outils' => array(
				'fiche' => array('icon' => 'pdf','text' => 'Fiche'))
		)
	);

?>

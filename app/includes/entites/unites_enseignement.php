<?php
/*
 * Created on 21 oct. 2008
 *
 */
$params=array(
		'common' => array(
			'titre' => 'Unités d\'enseignement',
			'element' => 'une unité d\'enseignement',
			'icone_titre' => 'toutes_les_ues', 
			'icone_ajout' => 'cours'),
		'liste' => array(
			'message' => 'Voici la liste des unités d\'enseignement de l\'UFR STEP et du Master STEP-IPGP
				et l\'École doctorale de Sciences de la Terre.',
			'sql' => "SELECT UE.id_ue, UE.intitule, GROUP_CONCAT( DISTINCT NI.abbreviation ) AS niveaux, GROUP_CONCAT( DISTINCT SP.abbreviation ) AS specialites, T.libelle AS
				type_ue, MAX(A.libelle) AS annee, CONCAT( E.nom, ' ', E.prenom ) AS responsable
				FROM Unites_Enseignement UE
				LEFT JOIN l_ouverture_ue OUE 
					ON UE.id_ue = OUE.id_ue
					AND OUE.id_type_ue <>8
				LEFT JOIN a_niveau NI 
					ON OUE.id_niveau = NI.id_niveau
				LEFT JOIN a_specialite SP 
					ON OUE.id_specialite = SP.id_specialite
				LEFT JOIN a_type_ue T 
					ON OUE.id_type_ue = T.id_type_ue
				LEFT JOIN a_annee_scolaire A 
					ON OUE.id_annee_scolaire = A.id_annee_scolaire
				LEFT JOIN l_enseignant_ue EUE 
					ON EUE.id_ue = UE.id_ue
					AND OUE.id_annee_scolaire = EUE.id_annee_scolaire
					AND EUE.id_situation =20
				LEFT JOIN Enseignants E 
			ON EUE.id_enseignant = E.id_enseignant",
			'post_sql' => " GROUP BY UE.id_ue ORDER BY UE.intitule",
			'champs' => array(
				'id_ue' => array('id_ue','UE.id_ue'), 
				'intitule' => array('Intitulé','UE.intitule'), 
				'niveaux' => array('Niveaux','NI.abbreviation'), 
				'specialites' => array('Spécialités','SP.abbreviation'),
				'type_ue' => array('Type','T.libelle'),
				'annee' => array('Année','A.libelle'),
				'responsable' => array('Responsable','E.nom')),
			'outils' => array(
				'fiche' => array('icon' => 'pdf','text' => 'Fiches compléte'),
				'liste' => array('icon' => 'csv','text' => 'Listes'),
				'trombi' => array('icon' => 'trombi','text' => 'Trombinoscope'),
				'emargement' => array('icon' => 'emargement','text' => 'Émargement'))),
		'element' => array(
			'sql_select' => "SELECT UE.id_ue, UE.intitule
				FROM Unites_Enseignement UE 
				ORDER BY UE.intitule",
			'tabs' => array(
				'infos' => array ('icon' => 'infos', 'text' => 'Infos'),
				'ouvertures' => array ('icon' => 'ouverture', 'text' => 'Ouvertures'),
				'etudiants' => array ('icon' => 'tous_les_etudiants', 'text' => 'Etudiants'),
				'intervenants' => array ('icon' => 'enseignant', 'text' => 'Intervenants'),
				'notes' => array ('icon' => 'notes', 'text' => 'Notes')),
			'outils' => array(
				'fiche' => array('icon' => 'pdf','text' => 'Fiche compléte'),
				'liste' => array('icon' => 'csv','text' => 'Liste des étudiants'),
				'trombi' => array('icon' => 'trombi','text' => 'Trombinoscope'),
				'emargement' => array('icon' => 'emargement','text' => 'Feuille d\'émargement'),
				'ldif' => array('icon' => 'carnet_addresses','text' => 'Liste de diffusion'))));
?>


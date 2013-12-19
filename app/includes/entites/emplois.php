<?php
$params=array(
		'common' => array(
			'titre' => 'Les emplois',
			'element' => 'un emploi',
			'icone_titre' => 'emploi', 
			'icone_ajout' => 'emploi'),
		'liste' => array(
			'message' => 'Voici la liste des emplois occupés par des anciens étudiants ou des offres qui nous sont parvenus.',
			'sql' => "SELECT E.id_emploi, E.libelle, ENT.nom AS entreprise, E.date_debut, TC.libelle AS type_contrat,
				CONCAT(ET.nom,' ',ET.prenom) AS etudiant 
				FROM Emplois E
				LEFT JOIN Entreprises ENT
					ON ENT.id_entreprise=E.id_entreprise
				LEFT JOIN l_etudiant_emploi EE
					ON EE.id_emploi=E.id_emploi
				LEFT JOIN Etudiants ET
					ON ET.id_etudiant=EE.id_etudiant
				LEFT JOIN a_type_contrat TC
					ON TC.id_type_contrat=E.id_type_contrat",
			'post_sql' => " ORDER BY ENT.nom",
			'champs' =>	array(
				'id_emploi' => array('id_emploi','E.id_emploi',4), 
				'libelle' => array('libelle','E.libelle',4), 
				'entreprise' => array('Entreprise','ENT.nom',15),
				'type_contrat' => array('Contrat','TC.libelle',10),
				'date_debut' => array('Début','E.date_debut',10),
				'etudiant' => array('Étudiant','ET.nom',10)),
			'outils' => array(
				'fiche' => array('icon' => 'pdf','text' => 'Fiches'))),
		'element' => array(
			'sql_select' => "SELECT id_emploi, libelle FROM Emplois ORDER BY libelle",
			'tabs' => array(
				'infos' => array ('icon' => 'infos', 'text' => 'Infos'),
				'etudiant' => array ('icon' => 'etudiant', 'text' => 'Étudiant'),
				'entreprise' => array ('icon' => 'entreprise', 'text' => 'Entreprise')),
			'outils' => array(
				'fiche' => array('icon' => 'pdf','text' => 'Fiche'))
		)
	);

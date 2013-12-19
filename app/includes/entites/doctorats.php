<?php
$params=array(
		'common' => array(
			'titre' => 'Les Doctorats',
			'element' => 'un doctorat',
			'icone_titre' => 'tous_les_doctorats', 
			'icone_ajout' => 'doctorat'),
		'liste' => array(
			'message' => 'Voici la liste des cas d\'études de l\'UFR STEP et du Master STEP-IPGP.',
			'sql' => "SELECT D.id_doctorat, D.titre, CONCAT(E.nom,' ',E.prenom) AS etudiant, 
			CONCAT(DIR.nom,' ',DIR.prenom) AS directeur, D.date_debut, D.date_fin 
			FROM Doctorats D
			LEFT JOIN Etudiants E
				ON E.id_etudiant=D.id_etudiant
			LEFT JOIN l_encadrant_doctorat ED
				ON D.id_doctorat=ED.id_doctorat
				AND ED.id_type_encadrant=9
			LEFT JOIN Enseignants DIR
				ON ED.id_encadrant=DIR.id_enseignant",
			'post_sql' => " ORDER BY D.titre",
			'champs' =>	array('id_doctorat' => array('id_doctorat','D.id_doctorat'), 
				'titre' => array('Titre','D.titre'),
				'etudiant' => array('Doctorant','E.nom'),
				'directeur' => array('Directeur','DIR.nom'),	 
				'date_debut' => array('Début','D.date_debut'), 
				'date_fin' => array('Fin','D.date_fin')),
			'outils' => array(
				'fiche' => array('icon' => 'pdf','text' => 'Fiches'))),
		'element' => array(
			'sql_select' => "SELECT D.id_doctorat, D.titre
				FROM Doctorats D ORDER BY D.titre",
			'tabs' => array(
				'infos' => array ('icon' => 'infos', 'text' => 'Infos'),
				'etudiant' => array ('icon' => 'etudiant', 'text' => 'Étudiant'),
				'encadrement' => array ('icon' => 'encadrement', 'text' => 'Encadrants'),
				'financement' => array ('icon' => 'financement', 'text' => 'Financement'),
				'soutenance' => array ('icon' => 'soutenance', 'text' => 'Soutenance')),
			'outils' => array(
				'fiche' => array('icon' => 'pdf','text' => 'Fiche')))
	);
?>

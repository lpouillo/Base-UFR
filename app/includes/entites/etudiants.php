<?php
$params=array(
		'common' => array(
				'titre' => 'Étudiants',
				'element' => 'un étudiant',
				'icone_titre' => 'tous_les_etudiants', 
				'icone_ajout' => 'personne'),
		'liste' => array(
			'message' => 'Voici la liste des étudiants de l\'UFR STEP, du Master STEP-IPGP
				et l\'École doctorale de Sciences de la Terre.',
			'sql' => "SELECT E.id_etudiant AS id_etudiant, E.nom AS nom, E.prenom AS prenom, 
						NI.libelle AS niveau, SP.libelle AS specialite, ET.nom AS etablissement,
						E.telephone_mobile AS telephone , E.email_ipgp AS email,
						A.libelle AS annee_scolaire
						FROM Etudiants E
						LEFT JOIN (
							SELECT E.id_etudiant, max(P.id_annee_scolaire) AS id_annee_scolaire
							FROM Etudiants E
							JOIN l_parcours_etudiant P 
								ON E.id_etudiant=P.id_etudiant
								AND P.id_niveau<>0
								AND P.id_specialite<>0
								AND P.id_etablissement<>0
							GROUP BY E.id_etudiant 
							) TMP
							ON TMP.id_etudiant=E.id_etudiant
						LEFT JOIN l_parcours_etudiant P
							ON E.id_etudiant=P.id_etudiant
							AND TMP.id_annee_scolaire=P.id_annee_scolaire
						LEFT JOIN a_niveau NI
							ON P.id_niveau=NI.id_niveau
						LEFT JOIN a_specialite SP 
							ON P.id_specialite=SP.id_specialite
						LEFT JOIN Etablissements ET
							ON P.id_etablissement=ET.id_etablissement
						LEFT JOIN a_annee_scolaire A
							ON P.id_annee_scolaire=A.id_annee_scolaire",
			'post_sql' => " GROUP BY E.id_etudiant,E.nom ORDER BY E.nom",
			'champs' => array(
				'id_etudiant' => array('id_etudiant','E.id_etudiant'), 
				'nom' => array('Nom','E.nom'), 
				'prenom' => array('Prénom','E.prenom'), 
				'niveau' => array('Niveau','NI.libelle'),
				'specialite' => array('Spécialité','SP.libelle'),
				'etablissement' => array('Établissement','ET.nom'),
				'email' => array('Email','E.email_ipgp'),
				'telephone' => array('Téléphone','E.telephone_mobile'),
				'annee_scolaire' => array('Année scolaire','A.libelle')),
			'outils' => array(
				'fiche' => array('icon' => 'pdf', 'text' => 'Fiches'),
				'releves_complet' => array('icon' => 'pdf', 'text' => 'Relevés complets'),
				'releves_provisoire' => array('icon' => 'pdf', 'text' => 'Relevés provisoires'),
				'trombi' => array('icon' => 'trombi', 'text'=> 'Trombinoscope'))),
		'element' => array(
			'sql_select' => "SELECT E.id_etudiant, CONCAT(E.nom,' ',E.prenom) AS nom_prenom,
					CONCAT(NI.abbreviation,' ',SP.libelle) AS spec
					FROM Etudiants E 
					LEFT JOIN l_parcours_etudiant P 
						ON P.id_etudiant=E.id_etudiant 
						AND P.id_annee_scolaire=".$id_annee_scolaire."
					LEFT JOIN a_niveau NI 
						ON P.id_niveau=NI.id_niveau
					LEFT JOIN a_specialite SP 
						ON P.id_specialite=SP.id_specialite
					ORDER BY nom, prenom",
			'tabs' => array(
				'infos' => array ('icon' => 'infos', 'text' => 'Infos'),
				'scolarite' => array ('icon' => 'parcours', 'text' => 'Scolarité'),
				'ues' => array ('icon' => 'module', 'text' => 'Unites d\'enseignement'),
				'stages' => array ('icon' => 'stage_laboratoire', 'text' => 'Stages'),
				'doctorat' => array ('icon' => 'doctorat', 'text' => 'Doctorat'),
				'emplois' => array ('icon' => 'emploi', 'text' => 'Emplois')),
			'outils' => array(
				'fiche' => array('icon' => 'pdf','text' => 'Fiche'),
				'releves_complet' => array('icon' => 'pdf','text' => 'Relevé de notes'),
				'releves_mention' => array('icon' => 'pdf','text' => 'Relevé de notes (sans le rang)'),
				'releves_provisoire' => array('icon' => 'pdf','text' => 'Relevé de notes (sans la mention et sans le rang)'),
			)
		)
	);
?>

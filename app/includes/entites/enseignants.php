<?php
$params=array(
		'common' => array(
			'titre' => 'Enseignants',
			'element' => 'un enseignant',
			'icone_titre' => 'enseignant', 
			'icone_ajout' => 'personne'),
		'liste' => array(
			'message' => 'Voici la liste des enseignants de l\'UFR STEP et du Master STEP-IPGP
				et l\'École doctorale de Sciences de la Terre.',
			'sql' => "SELECT E.id_enseignant, E.nom, E.prenom, E.telephone_professionnel, E.email_pro, E.www, 
					L.nom AS laboratoire, ET.nom AS etablissement, U.libelle AS ufr
					FROM Enseignants E
					LEFT JOIN Laboratoires L
						ON L.id_laboratoire=E.id_laboratoire
					LEFT JOIN Etablissements ET
						ON ET.id_etablissement=E.id_etablissement
					LEFT JOIN a_ufr U
						ON U.id_ufr=E.id_ufr",
			'post_sql' => " GROUP BY E.nom ORDER BY E.nom",
			'champs' => array(
				'id_enseignant' => array('id_enseignant','E.id_enseignant'), 
				'nom' => array('Nom','E.nom'), 
				'prenom' => array('Prénom','E.prenom'), 
				'telephone_professionnel' => array('Téléphone','E.telephone_professionnel'),
				'email_pro' => array('Email','SP.libelle'),
				'laboratoire' => array('Laboratoire','L.nom'),
				'etablissement' => array('Établissement','ET.nom'),
				'ufr' => array('UFR','U.libelle')),
			'outils' => array(
				'fiche' => array('icon' => 'pdf','text' => 'Fiches'),
				'service' => array('icon' => 'pdf','text' => 'Services'))),
		'element' => array(
			'sql_select' => "SELECT E.id_enseignant, CONCAT(E.nom,' ',E.prenom) AS nom_prenom,
				L.nom AS labo 
				FROM Enseignants E 
				LEFT JOIN Laboratoires L 
					ON E.id_laboratoire=L.id_laboratoire
				ORDER BY E.nom, E.prenom",
			'tabs' => array(
					'infos' => array ('icon' => 'infos', 'text' => 'Infos'),
					'responsabilites' => array ('icon' => 'responsabilite', 'text' => 'Responsabilites'),
					'enseignements' => array ('icon' => 'cours', 'text' => 'Enseignements'),
					'encadrements' => array ('icon' => 'encadrement', 'text' => 'Encadrements')),
			'outils' => array(
				'fiche' => array('icon' => 'pdf','text' => 'Fiche de l\'enseignant'),
				'service' => array('icon' => 'pdf','text' => 'Service dispensé')))
		);
?>


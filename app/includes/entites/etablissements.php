<?php
$params=array(
		'common' => array(
			'titre' => ' Les Établissements',
			'element' => 'un laboratoire',
			'icone_titre' => 'etablissement', 
			'icone_ajout' => 'etablissement'),
		'liste' => array(
			'message' => 'Voici la liste des établissements.',
			'sql' => "SELECT E.id_etablissement, E.nom AS etablissement, VI.libelle AS ville, PA.libelle AS pays,
				TE.libelle AS type_etablissement, E.www 
				FROM Etablissements E
				LEFT JOIN a_ville VI
					ON VI.id_ville=E.id_ville
				LEFT JOIN a_pays PA
					ON PA.id_pays=E.id_pays
				LEFT JOIN a_type_etablissement TE
					ON TE.id_type_etablissement=E.id_type_etablissement",
			'post_sql' => " ORDER BY E.nom",
			'champs' => array(
				'id_etablissement' => array('id_etablissement','E.id_etablissement'), 
				'etablissement' => array('Nom','E.nom'),
				'ville' => array('Ville','VI.libelle'),	 
				'pays' => array('Pays','PA.libelle'), 
				'type_etablissement' => array('Type','TE.libelle'), 
				'www' => array('Site web','E.www')),
			'outils' => array(
				'fiche' => array('icon' => 'pdf','text' => 'Fiches'))),
		'element' => array(
			'sql_select' => "SELECT E.id_etablissement, E.nom FROM Etablissements E ORDER BY E.nom",
			'tabs' => array(
				'infos' => array ('icon' => 'infos', 'text' => 'Infos'),
				'personnel' => array ('icon' => 'utilisateur', 'text' => 'Personnel')),
			'outils' => array(
				'fiche' => array('icon' => 'pdf','text' => 'Fiche')))
	);


?>



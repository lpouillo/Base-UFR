<?
$params=array(
		'common' => array(
			'titre' => 'Les Professionnels',
			'element' => 'un professionnel',
			'icone_titre' => 'professionnel', 
			'icone_ajout' => 'personne'),
		'liste' => array(
			'message' => 'Voici la liste des professionnels en relation avec l\'UFR et l\'IPGP',
			'sql' => "SELECT P.id_professionnel, CONCAT(P.nom,' ',P.prenom) AS nom, F.libelle AS fonction, 
					E.nom AS entreprise, P.telephone
					FROM Professionnels P
					LEFT JOIN a_fonction F 
						ON F.id_fonction=P.id_fonction 
					LEFT JOIN Entreprises E 
						ON E.id_entreprise=P.id_entreprise",
			'post_sql' => " ORDER BY P.nom",
			'champs' =>	array(
				'id_professionnel' => array('id_professionnel','P.id_professionnel'), 
				'nom' => array('Nom','P.nom'), 
				'fonction' => array('Fonction','F.libelle'), 
				'entreprise' => array('Entreprise','E.nom'), 
				'telephone' => array('Téléphone','P.telephone')),
			'outils' => array(
				'fiche' => array('icon' => 'pdf','text' => 'Fiches'))),
		'element' => array(
			'sql_select' => "SELECT P.id_professionnel, CONCAT(nom,' ',prenom) AS nom_prenom FROM Professionnels P ORDER BY P.nom",
			'tabs' => array(
				'infos' => array ('icon' => 'infos', 'text' => 'Infos'),
				'encadrement' => array ('icon' => 'encadrement', 'text' => 'Encadrements')),
			'outils' => array(
				'fiche' => array('icon' => 'pdf','text' => 'Fiche')))
	);

?>

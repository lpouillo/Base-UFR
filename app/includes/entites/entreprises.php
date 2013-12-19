<?
$params=array(
		'common' => array(
			'titre' => 'Les Entreprises',
			'element' => 'une entreprise',
			'icone_titre' => 'entreprise', 
			'icone_ajout' => 'entreprise'),
		'liste' => array(
			'message' => 'Voici la liste des entreprises qui accueillent des étudiants, fournissent du matériel ..',
			'sql' => "SELECT E.id_entreprise, E.nom AS entreprise, VI.libelle AS ville, PA.libelle AS pays, E.telephone,E.www 
				FROM Entreprises E
				LEFT JOIN a_ville VI
					ON VI.id_ville=E.id_ville
				LEFT JOIN a_pays PA
					ON PA.id_pays=E.id_pays",
			'post_sql' => " ORDER BY E.nom",
			'champs' =>	array(
				'id_entreprise' => array('id_entreprise','E.id_entreprise'), 
				'entreprise' => array('Entreprise','E.nom'),
				'ville' => array('Ville','VI.libelle'),	 
				'pays' => array('Pays','PA.libelle'), 
				'telephone' => array('Téléphone','E.telephone'), 
				'www' => array('Site web','E.www')),
			'outils' => array(
				'fiche' => array('icon' => 'pdf','text' => 'Fiches'))),
		'element' => array(
			'sql_select' => "SELECT id_entreprise,nom FROM Entreprises ORDER BY nom",
			'tabs' => array(
				'infos' => array ('icon' => 'infos', 'text' => 'Infos'),
				'professionnels' => array ('icon' => 'professionnel', 'text' => 'Professionnels'),
				'stages' => array ('icon' => 'stage_entreprise', 'text' => 'Stages'),
				'emplois' => array ('icon' => 'emploi', 'text' => 'Emplois')),
			'outils' => array(
				'fiche' => array('icon' => 'pdf','text' => 'Fiches')))
	);
			
			
?>

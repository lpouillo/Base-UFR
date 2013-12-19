<?php
$params=array(
		'common' => array(
			'titre' => ' Les Laboratoires',
			'element' => 'un laboratoire',
			'icone_titre' => 'laboratoire', 
			'icone_ajout' => 'laboratoire'),
		'liste' => array(
			'message' => 'Voici la liste des cas d\'études de l\'UFR STEP et du Master STEP-IPGP.',
			'sql' => "SELECT L.id_laboratoire, L.nom, VI.libelle AS ville, PA.libelle AS pays, L.telephone, L.www, ET.nom AS etablissement 
					FROM Laboratoires L
					LEFT JOIN a_ville VI
						ON VI.id_ville=L.id_ville
					LEFT JOIN a_pays PA
						ON PA.id_pays=L.id_pays
					LEFT JOIN Etablissements ET
						ON ET.id_etablissement=L.id_etablissement",
			'post_sql' => " ORDER BY L.nom",
			'champs' => array(
				'id_laboratoire' => array('ID','L.id_laboratoire'),
				'nom' => array('Nom','L.nom'),
				'etablissement' => array('Établissement','ET.nom'),
				'ville' => array('Ville','VI.libelle'),	 
				'pays' => array('Pays','PA.libelle'), 
				'telephone' => array('Téléphone','L.telephone'), 
				'www' => array('Site web','L.www')),
			'outils' => array(
				'fiche' => array('icon' => 'pdf','text' => 'Fiches'))),
		'element' => array(
			'sql_select' => "SELECT L.id_laboratoire, L.nom FROM Laboratoires L ORDER BY nom",
			'tabs' => array(
				'infos' => array ('icon' => 'infos', 'text' => 'Infos'),
				'personnel' => array ('icon' => 'utilisateur', 'text' => 'Personnel')),
			'outils' => array(
				'fiche' => array('icon' => 'pdf','text' => 'Fiche')))
		);
?>

<?php
/*
 * Created on 29 déc. 2008
 * Paramètres de l'application de gestion
 * 
 */
// Serveur LDAP
$ldap_server="ldapwebslave.ipgp.fr";

// Base de données MySQL
$db_host="localhost";
$db_user="base_ufr";
$db_password="";
$db_name="base_ufr";

// Fuseau horaire
date_default_timezone_set('Europe/Paris');

// Wikis

// Année
$annee=2009;
$id_annee_scolaire=10;


// DEBUG
$beta=1;
$debug=0;

// Fonctionnalités
$menu['enseignant']=array(
	'mes_infos' => array('text' => 'Mes informations', 'icon'=> 'infos', 'title' => 'Voir mes infos personnelles'),
	'mon_planning' => array('text' => 'Mon planning', 'icon'=> 'planning', 'title' =>'Accéder à mon planning de cours'),
	'mes_responsabilites' => array ('text' => 'Mes responsabilites', 'icon' => 'responsabilite', 'title' => 'Voir la liste de mes responsabilites'),
	'mes_cours' => array('text' => 'Mes cours', 'icon'=> 'cours', 'title' =>'Accéder à la liste de mes enseignements'),
	'mes_etudiants' => array('text' => 'Mes étudiants', 'icon'=> 'etudiant', 'title' =>'Voir la liste de mes étudiants'),
	'mes_stages' => array('text' => 'Mes stages', 'icon'=> 'stage_laboratoire', 'title' =>'Les stages auquels je collabore en tant qu\'encadrant ou tuteur'),
	'mes_doctorats' => array('text' => 'Mes doctorats', 'icon'=> 'doctorat', 'title' =>'Les doctorats que j\'encadre'),
	'documentation' => array('text' => 'Documentation', 'icon'=> 'aide', 'title' =>'Documentation sur le fonctionnement de Educatix')
);
$menu['gestion']=array(
	'documentation' => array('text' => 'Documentation', 'icon'=> 'aide', 'title' => 'Modifier la documentation interne du site'),
	'outils' => array('text' => 'Outils', 'icon'=> 'outils', 'title' => 'Outils de gestion : compatibilité, effectifs, bilans'),
	'notes' => array('text' => 'Notes', 'icon'=> 'notes', 'title' => 'Importer des notes et mettre à jour les moyennes'),
	'annonces' => array('text' => 'Annonces', 'icon'=> 'annonce', 'title' => 'Effectuer des annonces sur le S.E.D. et dans le flux RSS'),
	'scolarite' => array('text' => 'Scolarité', 'icon'=> 'parametres', 'title' => 'UE par défaut pour chaque niveau/specialite'),
	'update' => array('text' => 'Mises à jour', 'icon'=> 'update', 'title' => 'Mise à jour automatique des sites de Educatix et de la base'),
	'importation' => array('text' => 'Importation', 'icon'=> 'importation', 'title' => 'Importation de données depuis un fichier csv')
);
$menu['entites']=array(
	'etudiants' => array('text' => 'Etudiants', 'icon' => 'tous_les_etudiants', 'title' => 'Fiche étudiants, scolarité pédagogique, parcours','table' => 'Etudiants',
		'recherche' => array('nom','prenom')),
	'enseignants' => array('text' => 'Enseignants', 'icon' => 'enseignant', 'title' => 'Infos enseignants, responsables U.E., intervenants ...','table' => 'Enseignants',
		'recherche' => array('nom','prenom')),
	'unites_enseignement' => array('text' => 'Unités enseignement', 'icon' => 'toutes_les_ues', 'title' => 'Infos U.E., compatibilité, étudiants inscrits ...','table' => 'Unites_Enseignement',
		'recherche' => array('intitule','code','resume')),
	'stages_laboratoires' => array('text' => 'Stages Laboratoires', 'icon' => 'stage_laboratoire', 'title' => 'Infos, encadrant, étudiant des stages en laboratoires','table' => 'Stages_Laboratoires',
		'recherche' => array('sujet','description')),
	'stages_entreprises' => array('text' => 'Stages Entreprises', 'icon' => 'stage_entreprise', 'title' => 'Infos, encadrant, étudiant des stages en entreprises','table' => 'Stages_Entreprises',
		'recherche' => array('sujet','description')),
	'cas_etudes' => array('text' => 'Cas d\'études', 'icon' => 'cas_etudes', 'title' => 'Gérer les cas d\'études','table' => 'Cas_Etudes',
		'recherche' => array('sujet','description')),
	'doctorats' => array('text' => 'Doctorats', 'icon' => 'tous_les_doctorats', 'title' => 'Infos doctorats, encadrant, étudiant','table' => 'Doctorats',
		'recherche' => array('titre','sujet')),
	'emplois' => array('text' => 'Emplois', 'icon' => 'emploi', 'title' => 'Infos emplois des anciens et nouvelles offres','table' => 'Emplois',
		'recherche' => array('libelle','description')),
	'laboratoires' => array('text' => 'Laboratoires', 'icon' => 'laboratoire', 'title' => 'Gérer les laboratoires associés à l\'UFR et au Master','table' => 'Laboratoires',
		'recherche' => array('nom')),
	'etablissements' => array('text' => 'Établissements', 'icon' => 'etablissement', 'title' => 'Gérer les établissements (universités, lycées, ...)','table' => 'Etablissements',
		'recherche' => array('nom')),
	'entreprises' => array('text' => 'Entreprises', 'icon' => 'entreprise', 'title' => 'Gérer les entreprises proposant des stages et des emplois','table' => 'Entreprises',
		'recherche' => array('nom')),
	'professionnels' => array('text' => 'Professionnels', 'icon' => 'professionnel', 'title' => 'Gérer les professionnels','table' => 'Professionnels',
		'recherche' => array('nom','prenom')),
	'annexes' => array('text' => 'Données annexes', 'icon' => 'annexe', 'title' => 'Modifier les données annexes (specialités, niveaux, villes, ...)')
);
$menu['admin']=array(
	'responsabilites' => array('text' => 'Responsabilités', 'icon'=> 'responsabilite', 'title' => 'Définir les responsabilités'),
	'fichiers_prives' => array('text' => 'Fichiers privés', 'icon'=> 'fichiers', 'title' => 'Accéder aux fichiers privés'),
	'utilisateurs' => array('text' => 'Utilisateurs', 'icon'=> 'utilisateur', 'title' => 'Gestion des utilisateurs non-IPGP ou à responsabilité spéciale'),
	'gropus' => array('text' => 'Groupes', 'icon'=> 'groupe', 'title' => 'Gestion des groupes'),
	'droits' => array('text' => 'Droits d\'accès', 'icon'=> 'droit', 'title' => 'Gérer les droits d\'accès aux pages'),
	'nettoyage' => array('text' => 'Nettoyage', 'icon'=> 'nettoyage', 'title' => 'Nettoyage de la base de donnée'),
	'connexions' => array('text' => 'Connexions', 'icon'=> 'connexion', 'title' => 'Vérifier l\'usage de la base'),
	'bugs' => array('text' => 'Bugs', 'icon'=> 'bug', 'title' => 'Voir les bugs soumis')
);

// Paramètres
$codes_notes=array(
	'-1' => 'Absent',
	'-2' => 'Validé',
	'-3' => 'En attente',
	'-4' => 'Dispensé');
?>

<?php
/*
 * Created on 2 sept. 2008
 *
 * Script permettant l'authentification et la récupération des données utilisateurs
 **************************************************************************************
 * 2 modes d'authentification :
 *  - table users dans la base de données (gestionnaire, admin, rôles spécifiques)
 *  - vérification sur le ldap de l'institut
 * 
 * Création des données de session
 * Log de la connexion sur le site
 */

$login_error=0;

if ($_POST['login']!='' AND isset($_POST['password'])) {
	// test sur la base locale
	$s_user = "SELECT `id_user`,`login`,`id_group`,`id_link` FROM s_users WHERE login='".secure_mysql($_POST['login'])."' AND password='".md5($_POST['password'])."'";
	$r_user = mysql_query($s_user) 
		or die ('La requète sur la base locale est mal formulée, tentative d\'injection SQL détectée.');
	
	// si on a trouvé l'user dans la base locale, on récupère son id
	if ($d_user = mysql_fetch_array($r_user)) {
		$_SESSION['id_user']=$d_user['id_user'];
		$_SESSION['login']=$d_user['login'];
		$_SESSION['id_link']=$d_user['id_link'];
		$id_group=$d_user['id_group'];
		$s_session="SELECT G.libelle AS user_group
					FROM Enseignants E
					INNER JOIN s_groups G
						ON G.id_group=".$id_group;
	} else {
		// sinon on test sur le ldap pour les enseignants de l'UFR
		$ds = ldap_connect($ldap_server);
		if (!($dn = get_dn($ds, $_POST['login']))) {
	     //   echo "erreur get dn $ds";
			$login_error=true;
		}
		// Check the current password.
		if (!($ldapbind = @ldap_bind($ds, $dn, $_POST['password']))) {
        	$login_error=true;
		} else {	
			// récupération des infos de l'enseignant
			$s_enseignant="SELECT `id_enseignant` FROM Enseignants WHERE uid='".$_POST['login']."'";			
			$r_enseignant=mysql_query($s_enseignant) 
				or die('Vous n\'êtes pas autorisé à vous connecter sur cette application. 
					Si vous êtes un étudiant, rendez vous sur sed.ipgp.fr. Contactez les adminitrateurs ... ');
			$n_res=mysql_num_rows($r_enseignant);
			if ($n_res) {
				$d_enseignant=mysql_fetch_array($r_enseignant);
				$_SESSION['id_user']=$d_enseignant['id_enseignant'];
				$_SESSION['login']=$_POST['login'];
				$_SESSION['id_link']=$d_enseignant['id_enseignant'];				
				// Si authentification LDAP, affectation du groupe enseignant (3) 
				$id_group=3;
				$s_session="SELECT E.nom, E.prenom, G.libelle AS user_group
					FROM Enseignants E
					INNER JOIN s_groups G
						ON G.id_group=".$id_group."
					WHERE E.id_enseignant=".$_SESSION['id_link'];			
			} else {
				$login_error=true;
			}					
		}				
	}	
	if ($login_error==0) {
		// récupération des données de sessions
		$r_session=mysql_query($s_session) 
			or die('Impossible de récupérer vos variables de sessions. Contactez les adminitrateurs ... ');
		$d_session=mysql_fetch_array($r_session);
		$_SESSION['nom']=$d_session['nom'];
		$_SESSION['prenom']=$d_session['prenom'];
		$_SESSION['group']=$d_session['user_group'];
				
		// on log la connexion sur le site 
		$date=date('Y-m-d');
		$heure=date('H:i'); 
		$s_connexion="INSERT INTO s_connexions (`date`,`heure`,`login`,`interne`) VALUES ('".$date."','".$heure."','".$_SESSION['login']."','".$_SERVER['REMOTE_ADDR']."')";
		mysql_query($s_connexion) 
			or die('Insertion de la connexion échouée. Contactez les adminitrateurs ... ');		
	}
}

// Changement de compte id_link si admin
if (($_SESSION['group']=='admin' or $_SESSION['group']=='gestionnaire') and isset($_POST['id_link'])) {
	$_SESSION['id_link']=$_POST['id_link'];
}
?>

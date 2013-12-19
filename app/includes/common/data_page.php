<?php
/*
 * Created on 29 déc. 2008
 *
 *  Récupération des différentes informations sur la page
 */
// Nettoyage des données postées

// Détermination du nom de la page

if (empty($_GET['page']) AND empty($_POST['page'])) {
    $page='accueil';
} else {
    // Vérification de l'existence de la page dans le répertoire page
    if (isset($_GET['page'])) {
        if ($_GET['page']!='' AND file_exists('app/pages/'.$_GET['page'].'.php')) {
            $page=$_GET['page'];
        }
    } else {
        if (isset($_POST['page'])) {
            if ($_POST['page']!='' AND file_exists('app/pages/'.$_POST['page'].'.php')) {
                $page=$_POST['page'];
            } else {
                $page='not_found';
            }
        }
    }
    // Des fichiers pouvant trainer (~ ... ), seconde vérification que la page est bien dans la table pages
    // et possède donc des droits définis pour les différentes groupes
    $dans_table_page=0;
    $s_page="SELECT nom_page FROM s_pages";
    $r_page=mysql_query($s_page)
        or die('La table s_pages n\'est pas disponible ou ses champs ne sont pas corrects. Contactez les adminitrateurs ... ');
    while ($d_page=mysql_fetch_array($r_page)) {
        if ($d_page['nom_page']==$page) {
            $dans_table_page=1;
        };
    }
    if (!$dans_table_page) {
        $page='not_found';
    }

}

// Récupération des droits de l'utilisateur
if ($page=='contact') {
    $mode='r';
} elseif (empty($_SESSION['id_user'])) {
    $page='connexion';
    
} else {
    $s_droits="SELECT droit FROM s_droits_group DG
        INNER JOIN s_groups G ON DG.id_group=G.id_group AND G.libelle='".$_SESSION['group']."'
        INNER JOIN s_pages P ON DG.id_page=P.id_page AND P.nom_page='".$page."'";
  
    $r_droits=mysql_query($s_droits)
        or die('Erreur lors de la récupération de vos droits d\'accès, vous ne pouvez continuer. Contactez les adminitrateurs ... ');
    while ($d_droits=mysql_fetch_array($r_droits)){
        $droits[]=$d_droits['droit'];
    }
    if (in_array('rw',$droits)) {
        $mode='rw';
    } elseif (in_array('r',$droits)) {
        $mode='r';
    } else {
        $page='non_autorise';
    }
}
$ro=($mode=='rw')?'':' READONLY ';
// Récupération des infos de la page
$s_page="SELECT titre_page,message_page,icone FROM s_pages WHERE nom_page='".$page."'";
$r_page=mysql_query($s_page)
    or die('Erreur lors de la récupération des infos de la page. Contactez les adminitrateurs ... ');
while ($d_page=mysql_fetch_array($r_page)) {
    $titre_page=$d_page['titre_page'];
    $message_page=$d_page['message_page'];
    $icone=$d_page['icone'];
}

// TEST DE LA SECTION 
if (empty($_POST['section'])) {
	if (empty($_GET['section'])) {
		$section='default';
	} else {
		$section=$_GET['section'];
	}
} else {
	$section=$_POST['section'];
}

// ACTION
if (empty($_POST['action'])) {
	$action='liste';
} else {
	$action=$_POST['action'];
}

// on cache les données annexes
if ($_SESSION['group']=='enseignant') {
	unset($menu['entites']['annexes']);
}
?>

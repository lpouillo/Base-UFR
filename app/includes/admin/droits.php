<?php
/*
 * Created on 27 sept. 2008
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
$html='<h2><img width="16px" src="public/images/icons/droit.png"/> Gestion des droits</h2>
<div class="content_tab">';

// si le formulaire de gestion des droits a été soumis, on met à jour les droits
if ($_POST['action']=="modifier_droits") {
	$groups=array();
	$droit=$_POST["droit"];
	$sql="SELECT id_group FROM s_groups"; 		
	$result=mysql_query($sql) 
		or die('Impossible de récupérer les groupes : <br/>'.mysql_error());
	while ($data=mysql_fetch_array($result)) {
		array_push($groups,$data["id_group"]);
	}
	$pages=array();
	$sql = "SELECT id_page FROM s_pages";
	$result=mysql_query($sql)
		or die('Impossible de récupérer les pages :<br/>'.mysql_error());
	while ($data=mysql_fetch_array($result)) {
		array_push($pages,$data["id_page"]);
	}
	$sql="TRUNCATE TABLE s_droits_group";
	mysql_query($sql)
		or die('Impossible de vider la table :'.mysql_error());

	foreach($groups as $id_group) {
		foreach($pages as $id_page) {
			if (isset($droit[$id_group][$id_page])) {
				$sql="INSERT INTO s_droits_group (`id_group`,`id_page`,`droit`) VALUES (".$id_group.",".$id_page.",'".secure_mysql($droit[$id_group][$id_page])."');";
				mysql_query($sql)
					or die('Erreur lors de l\'insertion des droits :<br/>'.mysql_error());
			}
		}
	}

}


$groups=array();
$html.= '<input onclick="submitForm(\'update_droits\');" type="submit" value="Valider les modifications"/>
		<form id="update_droits" action="index.php" method="post">
		<input type="hidden" name="page" value="admin">
		<input type="hidden" name="section" value="droits">
		<table class="table_admin">
		<tr><th>PAGES</th>';

$sql="SELECT id_group,libelle FROM s_groups"; 		
$result=mysql_query($sql)
	or die ('Erreur lors de la récupération des libellés des groupes :<br/>'.mysql_error());
$sql_select="";
$sql_join="";
while ($data=mysql_fetch_array($result)) {
	$sql_select.=",D".$data["id_group"].".droit AS droit_".$data["id_group"]."";
	$sql_join.="LEFT JOIN s_droits_group D".$data["id_group"]." ON D".$data["id_group"].".id_page=P.id_page AND D".$data["id_group"].".id_group=".$data["id_group"]." ";
	$html.= '<th>'.$data['libelle'].'</th>';
	array_push($groups,$data["id_group"]);
}
$html.= '</tr>';
$sql = "SELECT P.id_page,P.nom_page".$sql_select."
		FROM s_pages P
		".$sql_join."
		ORDER BY P.nom_page";
$result=mysql_query($sql)
	or die ('Erreur lors de la récupération des droits pour les couples page/groupe :<br/>'.mysql_error());
while ($data=mysql_fetch_array($result))  {
	$html.= '<tr>
			<th>'.$data['nom_page'].'</th>';
	foreach($groups as $id_group) {
		$html.= "<td><select name=\"droit[".$id_group."][".$data["id_page"]."]\">";
		$sel=($data["droit_".$id_group]=="aucun")?" selected=\"selected\"":"";
		$html.= "<option value=\"aucun\" ".$sel.">Aucun</option>";
		$sel=($data["droit_".$id_group]=="r")?" selected=\"selected\"":"";					
		$html.= "<option value=\"r\" ".$sel.">Lecture</option>";
		$sel=($data["droit_".$id_group]=="rw")?" selected=\"selected\"":"";
		$html.= "<option value=\"rw\" ".$sel.">Lecture/Ecriture</option>";
		$html.= "</td>";
	}
	$html.= '</tr>';
}
$html.=	'</table>
	<input type="hidden" name="action" value="modifier_droits"/>
	</form>
	<input onclick="submitForm(\'update_droits\');" type="submit" value="Valider les modifications"/></div>';
	
?>

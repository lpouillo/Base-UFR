<?php
/*echo '<pre>';
print_r($_POST);
echo '</pre>';*/

if (empty($_POST['filtrage_soumis'])) {
	$html='<div id="content_tab" class="content_tab">';
}
$html.='<h2><img src="public/images/icons/annexe.png"/> Gestion des données annexes</h2>
	<ul>';
$s_tables="SHOW TABLES LIKE 'a_%'";
$r_tables=mysql_query($s_tables)
or die(mysql_error());
$i_annexe=0;
while ($d_tables=mysql_fetch_array($r_tables)) {
	$i_annexe++;
		$sel=($_POST['table']==$d_tables[0])?' style="color:white;background-color:#005959;" ':'';
	
	$html.='<li '.$sel.' class="li_annexe" onClick="navAnnexes(\''.$d_tables[0].'\',\'0\',\'voir\')">'.ucfirst(substr($d_tables[0],2)).'</li>';
	if ($i_annexe>15) {
		$i_annexe=0;
		$html.='</ul><ul>';
	}
}
$html.='</ul>
	
	<form id="nav_annexes">
		<input type="hidden" name="page" value="entites"/>
		<input type="hidden" name="section" value="annexes"/>
		<input type="hidden" name="table" value=""/>
		<input type="hidden" name="id" value=""/>
		<input type="hidden" name="action" value=""/>
	</form>';
if (empty($_POST['table'])) {
	$html.='<p>Veuillez cliquer sur une table pour la modifier.</p>';
} else {
	$table=$_POST['table'];
	$s_champs="SHOW FIELDS FROM ".$table;
	$r_champs=mysql_query($s_champs);
	$champs=array();
	while($d_champs=mysql_fetch_array($r_champs)) {
		$champs[]=$d_champs[0];
	}
	$filtres=array('id_'.substr($table,2) => $_POST['id']);
	$infos=recuperation_donnees(generation_requete($table,$champs,$filtres,$ordre));
	switch($_POST['action']) {
		case 'ajouter':
			$html.='<input type="submit" value="AJOUTER" onclick="submitForm(\'ajouter_annexes\')"/>
				<form id="ajouter_annexes" method="post" action="index.php">
				<input type="hidden" name="modification_soumise" value="add_annexes"/>
				<input type="hidden" name="table" value="'.$_POST['table'].'"/>
				<input type="hidden" name="page" value="entites"/>
				<input type="hidden" name="section" value="annexes"/>
				<input type="hidden" name="action" value="'.$_POST['action'].'"/>
				<input type="hidden" name="date_in" value="'.date('Y-m-d').'"/>
				<input type="hidden" name="date_modif" value="'.date('Y-m-d').'"/>
				<table>';
			$i_champ=0;
			foreach ($champs as $champ) {
				if ($i_champ>2) {
					$html.='<tr>
							<th>'.$champ.'</th><td>'.normal_input($champ,$infos[0][$champ],60).'</td>
							</tr>';
				} 
				$i_champ++;
			}
			
			$html.='</table></form>';
		break;
		case 'modifier':
			$html.='<input type="submit" value="MODIFIER" onclick="submitForm(\'modifier_annexes\')"/>
				<form id="modifier_annexes" method="post" action="index.php">
				<input type="hidden" name="modification_soumise" value="update_annexes"/>
				<input type="hidden" name="table" value="'.$_POST['table'].'"/>
				<input type="hidden" name="page" value="entites"/>
				<input type="hidden" name="section" value="annexes"/>
				<input type="hidden" name="action" value="'.$_POST['action'].'"/>
				
				<table>';
			$i_champ=0;
			foreach ($champs as $champ) {
				$html.='<tr>
						<th>'.$champ.'</th><td>';
				if ($i_champ==0) {
					$html.='<input type="hidden" name="id" value="'.$infos[0][$champ].'"/>';
				} elseif ($champ=='date_in') {
					$html.=$infos[0][$champ].' <em>Modifié automatiquement</em>
					<input type="hidden" name="date_in" value="'.$infos[0][$champ].'" />';
				} elseif ($champ=='date_modif') {
					$html.=$infos[0][$champ].' <em>Modifié automatiquement</em>
					<input type="hidden" name="date_modif" value="'.date('Y-m-d').'" />';
				} else {
					$html.=normal_input($champ,$infos[0][$champ],60);
				}
				$html.='</td></tr>';
				$i_champ++;
			}
			
			$html.='</table></form>';
		break;
		case 'supprimer':
			$mode='r';
			$html.='<input type="submit" value="SUPPRIMER" onclick="submitForm(\'supprimer_annexes\')"/>
				<form id="supprimer_annexes" method="post" action="index.php">
				<input type="hidden" name="modification_soumise" value="delete_annexes"/>
				<input type="hidden" name="table" value="'.$_POST['table'].'"/>
				<input type="hidden" name="page" value="entites"/>
				<input type="hidden" name="section" value="annexes"/>
				<input type="hidden" name="action" value="'.$_POST['action'].'"/>
				<table>';
			foreach ($champs as $champ) {
				$html.='<tr>
						<th>'.$champ.'</th><td>'.$infos[0][$champ].'</td>
						</tr>';
			}
			
			$html.='</table></form>';
		break;
		default:
			$html.=creation_table_annexe($_POST['table']);
	}
	
}
if (empty($_POST['filtrage_soumis'])) {
	$html.='</div>';
}
?>
<?php
$html='<h2><img src="public/images/icons/connexion.png"/> Connexions</h2>
	<div class="content_tab">
	<table>
		<tr>
			<td><h3>Les dernières connexions</h3></td>
			<td><h3>Les utilisateurs</h3></td>
			<td><h3>Les 40 derniers jours</h3></td>
		</tr><tr>
			<td><table class="table_sel">
					<tr>
						<th width="40px">id_connexion</th><th>login</th><th>date</th><th>heure</th>
					</tr>';
$s_connexions="SELECT id_connexion, date, heure, login 
	FROM s_connexions 
	ORDER BY date DESC, heure DESC 
	LIMIT 40";
$r_connexions=mysql_query($s_connexions) 
	or die ('Impossible de récupérer la liste des connexions : <br/>'.mysql_error());
while ($d_connexions=mysql_fetch_array($r_connexions)) {
	$html.='<tr>
				<td>'.$d_connexions['id_connexion'].'</td>
				<td>'.$d_connexions['login'].'</td>
				<td>'.$d_connexions['date'].'</td>
				<td>'.$d_connexions['heure'].'</td>
			</tr>';
}
$html.='</table></td><td><table class="table_sel">
					<tr>
						<th>login</th><th>nombres</th>
					</tr>';
$s_connexions="SELECT COUNT(id_connexion) AS n_connexion, login 
	FROM s_connexions GROUP BY login ORDER BY n_connexion DESC LIMIT 40";
$r_connexions=mysql_query($s_connexions) 
	or die ('Impossible de récupérer la liste des connexions : <br/>'.mysql_error());
while ($d_connexions=mysql_fetch_array($r_connexions)) {
	$html.='<tr>
				<td>'.$d_connexions['login'].'</td>
				<td>'.$d_connexions['n_connexion'].'</td>
			</tr>';
}
$html.='</table></td>
	<td><table class="table_sel">
					<tr>
						<th>date</th><th>nombres</th>
					</tr>';
$s_connexions="SELECT COUNT(id_connexion) AS n_connexion, date 
	FROM s_connexions GROUP BY date ORDER BY date DESC LIMIT 40";
$r_connexions=mysql_query($s_connexions) 
	or die ('Impossible de récupérer la liste des connexions : <br/>'.mysql_error());
while ($d_connexions=mysql_fetch_array($r_connexions)) {
	$html.='<tr>
				<td>'.$d_connexions['date'].'</td>
				<td>'.$d_connexions['n_connexion'].'</td>
			</tr>';
}
$html.='</table>
	
	</td></tr>
	</table>';
	// Test de graphes


$html.= '<p><img src="app/includes/graphs/test.php" alt="Mon image de validation n°1" /></p>';




$html .='</div>';




?>
  

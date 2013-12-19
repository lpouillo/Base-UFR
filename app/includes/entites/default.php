<?php
$html='<div id="accueil_gestion">';


foreach($entites as $section => $data) {
	$html.='<h2 class="link" onclick="affElement(\'0\',\'entites\',\''.$section.'\',\'trouver\',\'content\');"><img width="16px" src="public/images/icons/'.$data['icone_titre'].'.png"/> 
				'.$data['titre'].'</h2>
			<ul>
				<li onclick="affElement(\'0\',\'entites\',\''.$section.'\',\'trouver\',\'content\');"><img width="15px" src="public/images/icons/rechercher.png"/> 
					<a href="#"/>Trouver '.$data['element'].'</a></li>
				<li onclick="affElement(\'0\',\'entites\',\''.$section.'\',\'ajouter\',\'content\');"><img width="15px" src="public/images/icons/'.$data['icone_ajout'].'_ajouter.png"/> 
					<a href="#"/>Ajouter '.$data['element'].'</a></li>
			</ul>
			';
	
}

$html.='<h2 class="link" onclick="affElement(\'0\',\'entites\',\'donnees_annexes\',\'trouver\',\'content\');"><img width="16px" src="public/images/icons/annexe.png"/> 
	<a href="#"/>Données annexes</a></h2>';
$html.='</div>';
echo $html;
?>


<?php
$sql_annexes="SHOW TABLES";
$result_annexes=mysql_query($sql_annexes);
while ($data_annexes=mysql_fetch_array($result_annexes)) {
	if (substr($data_annexes['Tables_in_base_ufr'],0,5)=='data_') {
		echo '<li onclick="affElement(\'0\',\'entites\',\'donnees_annexes\',\''.$data_annexes['Tables_in_base_ufr'].'\')">'.
		substr($data_annexes['Tables_in_base_ufr'],5).'</li>';	
	}
}
?>
	</ul>
</div>
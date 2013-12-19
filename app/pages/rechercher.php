<?php
$html='<div class="content_tab">';
$html.= '<form id="form_recherche" action="#" method="post" onsubmit="soumetRecherche(\'form_recherche\');">
		<input type="hidden" name="page" value="rechercher"/>
		<p><input type="text" name="recherche" value="'.htmlentities($_POST['recherche']).'" onkeyup="lancetimer2(\'form_recherche\')";/>
		<input type="submit" value="Rechercher"/></p></form>';
if (isset($_POST['recherche']) AND $_POST['recherche']!='') {	
	$action=($_SESSION['group']=='gestionnaire' or $_SESSION['group']=='admin')?'modifier':'voir';

	foreach($menu['entites'] as $entite => $data) {
		if (sizeof($data['recherche'])>0) {
			$s_rech="SELECT * FROM ".$data['table']." WHERE ";
			$ordre=0;
			foreach($data['recherche'] as $champ) {
				if (!$ordre) {
					$ordre=$champ;
				} 
				$s_rech.=$champ." LIKE '%".secure_mysql($_POST['recherche'])."%' OR ";
			}
			$s_rech.="1<>1 ORDER BY ".$ordre;
			$r_rech=mysql_query($s_rech);
			$n_res=mysql_num_rows($r_rech);
			if ($n_res>0) {
				$html.='<h3><img src="public/images/icons/'.$data['icon'].'.png"/> '.$data['text'].'</h3>
					<ul style="margin-top:0px;">';
				while ($d_rech=mysql_fetch_array($r_rech)) {
					$html.='<li style="list-style-type:square;margin-left:10px;" class="link" onclick="affElement(\''.$d_rech[0].'\',\'entites\',\''.$entite.'\',\''.$action.'\',\'content\')">';
					foreach($data['recherche'] as $champ) {
						if (strlen($d_rech[$champ])>60) {
							$html.=substr($d_rech[$champ],0,60).' ... - ';
						} else {
							$html.=$d_rech[$champ].' - ';
						}
					} 
					$html=substr($html,0,-2);
					$html.='</li>';
				}
				$html.='</ul>';
			}
		}
	}
	
}

$html.='</div>';


echo $html;

?>

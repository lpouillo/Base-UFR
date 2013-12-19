<?php 
// On récupère les années pour créer les entrées dans la table l_doctorat_financement si besoin
$s_annees="SELECT date_debut, date_fin FROM Doctorats WHERE id_doctorat=".$_POST['id'];
$r_annees=mysql_query($s_annees);
$d_annees=mysql_fetch_array($r_annees);
$tmp_deb=explode('-',$d_annees['date_debut']);
$annee_debut=$tmp_deb[0];
$annee_debut=($annee_debut=='0000')?date('Y'):$annee_debut;
$tmp_fin=explode('-',$d_annees['date_fin']);
$annee_fin=$tmp_fin[0];
$annee_fin=($annee_fin=='0000')?date('Y'):$annee_fin;

// Récupération des financements
$financements=array();
$s_financements="SELECT A.annee_debut, DF.id_type_financement   
			FROM l_doctorat_financement DF
			INNER JOIN a_annee_scolaire A 
				ON A.id_annee_scolaire=DF.id_annee_scolaire
			WHERE DF.id_doctorat=".$_POST['id'];
$r_financements=mysql_query($s_financements);
while ($d_financements=mysql_fetch_array($r_financements)) {
	$financements[$d_financements['annee_debut']]=$d_financements['id_type_financement'];
}
$html.='<ul>';
for($annee=$annee_debut;$annee<=$annee_fin;$annee++) {
	if (!isset($financements[$annee])) {
		$id_annee=recuperation_donnees("SELECT id_annee_scolaire FROM a_annee_scolaire WHERE annee_debut='".$annee."'");	
		if ($id_annee[0]['id_annee_scolaire']=='') {
			$_POST['annee_debut']=$annee;
			$id_annee[0]['id_annee_scolaire']=insert_table('a_annee_scolaire');
		}
		$s_insert="INSERT INTO l_doctorat_financement (`id_doctorat`,`id_annee_scolaire`,`date_in`,`date_modif`)
			VALUES ('".$_POST['id']."','".$id_annee[0]['id_annee_scolaire']."',CURDATE(),CURDATE())";
		mysql_query($s_insert)
			or die($s_insert.'<br/>'.mysql_error());
	}
	$html.='<li><strong>'.$annee.'</strong> - '.generation_select('financement['.$annee.']','a_type_financement',array('id_type_financement','libelle'),$financements[$annee]).'</li>';
}
$html.='</ul>';

?>
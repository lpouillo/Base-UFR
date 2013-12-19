<?php

switch ($_POST['id']) {
	case 'ajout_contact':
	case 'ajout_maitre_stage_entreprise_1':
	case 'ajout_maitre_stage_entreprise_2':
		$table='Professionnels';
		$champs=array('id_professionnel','nom','prenom','email');
	break;
	case 'ajout_tuteur_stage_entreprise_1':
	case 'ajout_tuteur_stage_entreprise_2':
		$table='Enseignants';
		$champs=array('id_enseignant','nom','prenom','email_pro');
	break;
}

if (empty($_POST['form_submitted'])) {
?>
	<h3><img src="public/images/icons/professionnel.png"/> Ajouter d'un encadrant</h3>
	<form id="ajout_stage_labo" target="ajout_encadrant" action="index.php?page=popupform" method="post">
	<?php
	require_once('app/classes/formulaire.class.php');
	$mes_infos = new formulaire('SELECT',$table,$champs,array($_POST['id']),'','','rw','index.php','modifier');
	echo $mes_infos->html;
	?>
	<input type="hidden" name="form_submitted" value="oui"/> 
	<input type="hidden" name="id" value="<?php echo $_POST['id'];?>"/>
	<p style="text-align:center;"><input type="submit" value="Créer l'encadrant"/> <a href="#" onclick="cancelPopupForm();">Annuler</a></p>
	</form>
	
	<iframe name="ajout_encadrant" style="display: none;"  src="app/pages/blank.html">
<?php 
} else {
	require_once('app/classes/formulaire.class.php');
	$add_stage_labo = new data('INSERT',$table,'','','','','');
	$add_stage_labo->execution_requete('ajouter');
	$id_new_stage_labo=mysql_insert_id();
	
	?>
	
	<script>
		var select_a_maj=parent.document.getElementById('<?php echo $_POST['id'];?>');
		
		select_a_maj.add(new Option("<?php echo $_POST['nom'].' '.$_POST['prenom'];?>", "<?php echo mysql_insert_id();?>",false,true),null);
		parent.cancelPopupForm();
	</script>
<?php 	
}
?>
<?php 
if (empty($_POST['form_submitted'])) {
?>
	<h3><img src="public/images/icons/ajouter_photo.png"/> Changer la photo ou l'image</h3>
	<form id="upload_photo" target="post_photo" action="index.php?page=popupform" method="post" enctype="multipart/form-data">
		Choisir un fichier :
		<input type="file" name="tmp_photo"/><br/>		
		<input type="hidden" name="id" value="<?php echo $_POST['id'];?>"/> 
		<input type="hidden" name="form_submitted" value="oui"/> 
		<p style="text-align:center;"><input type="submit" value="Envoyer le fichier"/> <a href="#" onclick="cancelPopupForm();">Annuler</a></p>
	</form>
	<iframe name="post_photo" style="display:none;" src="app/pages/blank.html"> 
<?php 
} else {
	$path='tmp/'.$_SESSION['login'];
	if (!file_exists($path)) {
		mkdir($path);
	}
	$path.='/'.$_POST['id'];
	if (!file_exists($path)) {
		mkdir($path);
	}
	$path.='/'.$_FILES['tmp_photo']['name'];
	
	if (!move_uploaded_file($_FILES['tmp_photo']['tmp_name'],$path)) { 
		echo 'La copie de '.$_FILES['tmp_photo']['tmp_name'].' vers '.$path.' a échoué...';
	}
	?>
	
	<script>
		if (parent.document.getElementById('<?php echo $_POST['id'];?>_src')) {
			parent.document.getElementById('<?php echo $_POST['id'];?>_src').value='<?php echo $path;?>';
		}
		if (parent.document.getElementById('<?php echo $_POST['id'];?>_img')) {
			parent.document.getElementById('<?php echo $_POST['id'];?>_img').src='<?php echo $path;?>';
		}
		parent.cancelPopupForm();
	</script>
		
<?php 	
}
?>
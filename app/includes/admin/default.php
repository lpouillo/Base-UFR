<?php

?>
<p>
La page d'administration vous permet d'effectuer des tâches de maintenance ou autre pour la base de gestion. 
Si vous souhaitez éditer la documentation, c'est <a href="#" onClick="affElement('','documentation','','','content');">ICI</a> que cela se passe. 
<form method="post" action="#" id="admin_action">
	<input type="hidden" name="page" value="admin"/>
	Veuillez choisir une action :
	<select name="section" onchange="submitForm('admin_action')" />
		<option value="connexion">Connexions</option>
		<option value="droits">Droits d'accès</option>
		<option value="bugs">Bugs</option>
		<option value="nettoyage">Nettoyage</option>
		<option value="utilisateurs">Utilisateurs</option>
		<option value="fichiers_prives">Fichiers privés</option>		
	</select>
</form>
</p>

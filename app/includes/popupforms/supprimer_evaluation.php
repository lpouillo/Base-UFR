<?php
$ids=explode('%',$_POST['id']);
$html.='<img src="public/images/icons/danger.png" alt="ATTENTION"> Vous allez supprimer une évaluation ainsi que toutes les notes s\'y rapportant.
	<input type="hidden" name="id_evaluation" value="'.$ids[1].'"/>
	<input type="hidden" name="id_ue" value="'.$ids[0].'"/>';

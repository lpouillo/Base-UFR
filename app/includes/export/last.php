<?php

$result=mysql_query($sql);

$string_all='';

while ($data=mysql_fetch_array($result)) {
	foreach ($data as $key =>$element) {
		if (is_int($key)) {
			$string_all.='"'.$element.' ";';
		}
	}
	$string_all.="\n";
}

$fname=$_GET['requete'].'.csv';

header('Content-disposition: filename="'.$fname.'"');
header("Content-Type: text/csv");


echo utf8_decode($string_all);

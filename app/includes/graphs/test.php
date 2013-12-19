<?
// Récupération de la configuration 
require_once ('../../includes/config.php');
// Connexion à la base de données
require_once ('../../includes/common/db_connect.php');

// Standard inclusions   
include('../../classes/pChart/pChart/pData.class');
include('../../classes/pChart/pChart/pChart.class');

// Dataset definition 
$DataSet = new pData;
$s_tous="SELECT COUNT(id_connexion) AS value, date 
	FROM s_connexions GROUP BY date ORDER BY date DESC LIMIT 40";
	
$r_tous=mysql_query($s_tous);
while ($row = mysql_fetch_array($r_tous))  
  { $DataSet->AddPoint($row["value"],"Serie1"); }  
//$DataSet->AddPoint(array(1,4,3,2,3,3,2,1,0,7,4,3,2,3,3,5,1,0,7));
$DataSet->AddSerie();
$DataSet->SetSerieName('Tous',"Serie1");

$fontpath='../../classes/pChart/Fonts/';
$imagepath='../../../public/tmp/';
// Initialise the graph
$Test = new pChart(700,230);
$Test->setFontProperties($fontpath.'tahoma.ttf',10);
$Test->setGraphArea(40,30,680,200);
$Test->drawGraphArea(252,252,252);
$Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,150,150,150,TRUE,0,2);
$Test->drawGrid(4,TRUE,230,230,230,255);

// Draw the line graph
$Test->drawLineGraph($DataSet->GetData(),$DataSet->GetDataDescription());
$Test->drawPlotGraph($DataSet->GetData(),$DataSet->GetDataDescription(),3,2,255,255,255);

// Finish the graph
$Test->setFontProperties($fontpath.'tahoma.ttf',8);
$Test->drawLegend(45,35,$DataSet->GetDataDescription(),255,255,255);
$Test->setFontProperties($fontpath.'tahoma.ttf',10);
$Test->drawTitle(60,22,'Connexions journalières à la base',50,50,50,585);
$Test->Stroke($imagepath.'Naked.png');


// Deconnexion de la base de données
require_once ('../../includes/common/db_close.php');
?>

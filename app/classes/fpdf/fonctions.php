<?
class PDF extends FPDF
{
	//Chargement des données
	function LoadData($file)
	{
    //Lecture des lignes du fichier
    $lines=file($file);
    $data=array();
    foreach($lines as $line)
        $data[]=explode(';',chop($line));
    return $data;
	}
	
	function BasicTable($header,$data)
	{
	    //En-tête
	    foreach($header as $col)
	        $this->Cell(40,7,$col,1);
	    $this->Ln();
	    //Données
	    foreach($data as $row)
	    {
	        foreach($row as $col)
	            $this->Cell(40,6,$col,1);
	        $this->Ln();
	    }
	}	
	


	//Page header
	/*function Header()
	{
		//Logo
		$this->Image('public/images/logo-ipgp.jpg',10,5,10);
		$this->Image('public/images/logo-step.jpg',20,6,10);
		$string='Export depuis la base de gestion - '.date('d/m/Y');
		$this->Cell(130,0,$string,0,0,'C');
		//Arial bold 15
		$this->SetFont('Arial','B',20);
		//Move to the right
		$this->Cell(80);
	}*/

	//Page footer
	/*function Footer()
	{
		//Position at 1.5 cm from bottom
		$this->SetY(-15);
		//Arial italic 8
		$this->SetFont('Arial','I',8);
		//Titre du document
		if ($_GET['type']=='emargement') {
			$this->Cell(60,1,utf8_decode('Feuille d\'émargement 2008-2009'),0,0,'C');
		} elseif ($_GET['type']=='releve_note') {
			$this->Cell(60,1,utf8_decode('Relevé de note 2008-2009'),0,0,'C');
		} elseif (
		//Page number
		$this->Cell(0,0,'Page '.$this->PageNo().'/{nb}',0,0,'R');
	}*/

	function ScolariteTable()
	{
		$this->SetXY(10,165);
		$this->SetFont('Arial','B',12);
	    //Largeurs des colonnes
	    $w=array(35,75,80);
	    //En-tête
	  	$this->Cell($w[0],12,'NOMS',1,0,'C');
	  	$this->Cell($w[1],6,'AVIS ',1,0,'C');
        $this->Cell($w[2],12,'OBSERVATIONS',1,0,'C');
		// sous menu
		$this->SetFont('Arial','',10);
		$this->SetXY(45,171);
        $this->Cell(25,6,'Favorable',1,0,'C');
		$this->SetXY(70,171);
        $this->Cell(25,6,'R�serv�',1,0,'C');
		$this->SetXY(95,171);
        $this->Cell(25,6,'D�favorable',1,0,'C');
        $this->Ln();
		$this->SetXY(10,177);		
	    //Cases à remplir
	    for($i=0;$i<5;$i++)
		{
    	    $this->Cell($w[0],8,' ',1,'LR',0);
    	  	$this->Cell($w[1],8,' ',1,'LR',0);
	        $this->Cell($w[2],8,' ',1,'LR',0);
	        $this->Ln();
   		}
	    //Trait de terminaison
	    $this->Cell(array_sum($w),0,'','T');
	}


	function Proprietes()
	{
		//Propriétés du document
		$this->SetAuthor('Base de gestion');
		$this->SetTitle('Export');
		
		//Choix de la police du document
		$this->SetFont('Arial','',10);
		$this->SetTextColor(0,0,0);
	}
	
	function creer_titre()
	{
		//Titre du document
		$this->SetXY(50,10);
		$this->SetFont('Arial','B',18);
		$this->Cell(110,10,'DOSSIER DE CANDIDATURE 2008/2009',0,0,'C',0);
		$this->SetFont('Arial','',10);
		$this->Cell(-110,25,'MASTER SCIENCES, SANT� ET APPLICATIONS (SSA)',0,0,'C',0);
		$this->Cell(110,35,'SCIENCES DE LA TERRE, DE L\'ENVIRONNEMENT ET DES PLAN�TES (STEP)',0,0,'C',0);
		$this->Cell(-110,45,'Institut de Physique du Globe de Paris - Universite Paris Diderot',0,0,'C',0);
		$this->SetFont('Arial','',10);
		$this->Cell(110,57,'Candidats issus de la m�me sp�cialit� ou fili�re',0,0,'C',0);
		
		// Separateur
		$this->Rect(10,45,190,0);
	}
	
	function identite_candidat()
	{
		//Identité du candidat
		$this->SetXY(10,50);
		$this->SetFont('Arial','BU',10);
		$this->Cell(1,0,'Identit� du candidat');
		$this->SetFont('Arial','',10);
		$this->SetXY(10,55);
		$this->Cell(1,0,'NOM : ');   
		$this->SetXY(70,55);
		$this->Cell(1,0,'Pr�nom : ');
		$this->SetXY(10,60);
		$this->Cell(1,0,'�pouse : ');
		$this->SetXY(10,65);
		$this->Cell(1,0,'N�(e) le : ');
		$this->SetXY(70,65);
		$this->Cell(1,0,'  : ');
		$this->SetXY(10,70);
		$this->Cell(1,0,'�ge ');
		$this->SetXY(70,70);
		$this->Cell(1,0,'Nationalit� : ');
		$this->SetXY(170,47);
		$this->MultiCell(0,18,"Photo d'identit� \n � coller",1,'C',0);
		
		// Separateur
		$this->Rect(10,85,190,0);
	}
	
	function souhaits_candidat()
	{
	//Souhaits du candidats
		$this->SetXY(10,90);
		$this->SetFont('Arial','BU',10);
		$this->Cell(1,0,'Souhait(s) du candidat');
		$this->SetFont('Arial','U',10);
		$this->SetXY(10,95);
		$this->Cell(1,0,'Souhait 1');  
		$this->SetFont('Arial','',10);
		$this->SetXY(40,95);
		$this->Cell(1,0,'Niveau : '); 
		$this->SetXY(100,95);
		$this->Cell(1,0,'Sp�cialit� : '); 
		$this->SetFont('Arial','U',10);
		$this->SetXY(10,100);
		$this->Cell(1,0,'Souhait 2');  
		$this->SetFont('Arial','',10);
		$this->SetXY(40,100);
		$this->Cell(1,0,'Niveau : '); 
		$this->SetXY(100,100);
		$this->Cell(1,0,'Sp�cialit� : '); 
		$this->SetXY(10,105);
		$this->Cell(1,0,'En formation : '); 
	}

	function dernier_diplome()
	{
		//Dernier dipl�me pr�par�
		$this->SetXY(10,115);
		$this->SetFont('Arial','BU',10);
		$this->Cell(1,0,'Dernier dipl�me pr�par� ou obtenu par le candidat');
		$this->SetFont('Arial','B',10);
		$this->SetXY(100,115);
		$this->Cell(1,0,'Bac + :');  
		$this->SetFont('Arial','',10);
		$this->SetXY(10,120);
		$this->Cell(1,0,'Ann�e universitaire :'); 
		$this->SetXY(10,125);
		$this->Cell(1,0,'Niveau : '); 
		$this->SetXY(10,130);
		$this->Cell(1,0,'Sp�cialit� :'); 
		$this->SetXY(10,135);
		$this->Cell(1,0,'Etablissement :'); 
		$this->SetXY(10,140);
		$this->Cell(1,0,'R�sultat :'); 
		$this->SetXY(10,145);
		$this->Cell(1,0,'Moyenne g�n�rale /20 : '); 
		$this->SetXY(110,145);
		$this->Cell(1,0,'Rang dans la promotion : '); 
		
		// Separateur
		$this->Rect(10,150,190,0);
	}

	function cadre_administration()
	{
		$this->SetFont('Arial','BUI',14);
		$this->SetXY(100,157);
		$this->Cell(1,0,'Cadre r�serv� � la Scolarit�',1,0,'C');
		$this->SetXY(10,153);
		$this->Cell(0,120,'',1,0,'C'); 
		$this->ScolariteTable();
	
		$this->SetXY(15,225);
		$this->Cell(180,21,'',1,0,'');
		$this->SetXY(15,246);
		$this->Cell(180,21,'',1,0,'');

		$this->SetFont('Arial','BU',12);
		$this->SetXY(15,228);		
		$this->Cell(1,0,'Souhait 1 :');
		$this->SetFont('Arial','B',10);		
		$this->SetXY(15,231);		
		$this->Cell(35,7,'ADMISSIBILIT�');
		$this->Cell(45,7,'Candidature refus�e');
		$this->Cell(45,7,'Candidature accept�e');
		$this->Cell(70,7,'Convocation � l\'entretien');	
		$this->Ln();
		$this->SetXY(15,238);			
		$this->Cell(35,7,'ADMISSION');
		$this->Cell(45,7,'Refus� apr�s entretien');
		$this->Cell(45,7,'Admis');
		$this->Cell(70,7,'Liste compl�mentaire n� ../...');	

		$this->SetFont('Arial','BU',12);
		$this->SetXY(15,249);		
		$this->Cell(1,0,'Souhait 2 :');
		$this->SetFont('Arial','B',10);		
		$this->SetXY(15,252);		
		$this->Cell(35,7,'ADMISSIBILIT�');
		$this->Cell(45,7,'Candidature refus�e');
		$this->Cell(45,7,'Candidature accept�e');
		$this->Cell(70,7,'Convocation � l\'entretien');	
		$this->Ln();
		$this->SetXY(15,259);			
		$this->Cell(35,7,'ADMISSION');
		$this->Cell(45,7,'Refus� apr�s entretien');
		$this->Cell(45,7,'Admis');
		$this->Cell(70,7,'Liste compl�mentaire n� ../...');	
	}

	function candidatures()
	{
		$this->SetXY(25,10);
		$this->SetFont('Arial','BU',10);
		$this->Cell(1,0,'Candidatures et pr�f�rences','');
		$this->SetFont('Arial','',10);		
		$this->SetXY(25,15);
		$this->Cell(1,0,'Candidature pour une autre formation que STEP :','');		
		$this->SetXY(25,20);
		$this->Cell(1,0,'Choix 1 :','');
		$this->SetXY(25,25);
		$this->Cell(1,0,'Choix 2 :','');
		$this->SetXY(25,30);
		$this->Cell(1,0,'Choix 3 :','');
		$this->SetXY(25,35);
		$this->Cell(1,0,'Choix 4 :','');
		$this->SetXY(25,40);
		$this->Cell(1,0,'Choix 5 :','');
		$this->SetXY(25,45);
		$this->Cell(1,0,'Choix 6 :','');
	}
	
	function interet()
	{
		$this->SetXY(10,55);
		$this->SetFont('Arial','BU',10);
		$this->Cell(1,0,'Vos centres d\'int�r�t','');		
		$this->SetFont('Arial','',10);				
		$this->SetXY(10,60);
		$this->Cell(1,0,'Centre d\'int�r�t 1 :','');		
		$this->SetXY(10,65);
		$this->Cell(1,0,'Centre d\'int�r�t 2 :','');		
		$this->SetXY(10,70);
		$this->Cell(1,0,'Centre d\'int�r�t 3 :','');				
	}
	
	function renseignements()
	{
		$this->SetXY(10,80);
		$this->SetFont('Arial','BU',10);
		$this->Cell(1,0,'Renseignements personnels','');		
		$this->SetFont('Arial','',10);
		$this->SetXY(10,85);
		$this->Cell(1,0,'NOM : ');   
		$this->SetXY(70,85);
		$this->Cell(1,0,'Pr�nom : ');
		$this->SetXY(10,90);
		$this->Cell(1,0,'�pouse : ');
		$this->SetXY(10,95);
		$this->Cell(1,0,'N�(e) le : ');
		$this->SetXY(70,95);
		$this->Cell(1,0,'  : ');
		$this->SetXY(10,100);
		$this->Cell(1,0,'�ge ');
		$this->SetXY(70,100);
		$this->Cell(1,0,'Nationalit� : ');		

		$this->SetXY(10,110);
		$this->Cell(1,0,'Adresse 1 :');
		$this->SetXY(10,115);
		$this->Cell(1,0,'Code postal 1 :');
		$this->SetXY(70,115);
		$this->Cell(1,0,'Ville 1 :');
		$this->SetXY(10,120);
		$this->Cell(1,0,'T�l�phone fixe 1 :');		
		$this->SetXY(10,125);
		$this->Cell(1,0,'Adresse 2 :');
		$this->SetXY(10,130);
		$this->Cell(1,0,'Code postal 2 :');
		$this->SetXY(70,130);
		$this->Cell(1,0,'Ville 2 :');
		$this->SetXY(10,135);
		$this->Cell(1,0,'T�l�phone fixe 2 :');		

		$this->SetXY(70,145);
		$this->Cell(1,0,'Adresse email personnelle :');		
		$this->SetXY(10,145);
		$this->Cell(1,0,'T�l�phone portable:');		
		
		// Separateur
		$this->Rect(10,155,190,0);		
	}
	
	function bilan_dossier()
	{
		$this->SetXY(50,165);
		$this->SetFont('Arial','B',14);		
		$this->Cell(110,0,'Les dossiers incomplets ne seront pas examin�s',0,0,'C',0);		
		$this->SetXY(50,170);
		$this->SetFont('Arial','',14);		
		$this->Cell(110,0,'Avant d\'envoyer votre dossier, v�rifiez qu\'il est complet en cochant les cases',0,0,'C',0);		
		$this->SetXY(10,180);
		$this->SetFont('Arial','',10);		
		$this->Cell(1,0,'Coller sa photo en premi�re page');
		$this->SetXY(10,187);
		$this->SetFont('Arial','',10);		
		$this->Cell(1,0,'Placer les pages du dossier dans une pochette plastifi�e');
		$this->SetXY(10,194);
		$this->SetFont('Arial','',10);		
		$this->Cell(1,0,'Envoyez ou d�poser votre dossier au plus tard le vendredi 30 mai 2008 (cachet de la poste faisant foi) � l\'adresse suivante :');		
		$this->SetFont('Arial','B',10);		
		$this->SetXY(100,205);
		$this->Cell(1,0,'Institut de Physique du Globe de Paris',0,0,'C');		
		$this->SetXY(100,215);
		$this->Cell(1,0,'Pyramide de la Scolarit�',0,0,'C');		
		$this->SetXY(100,220);
		$this->Cell(1,0,'(entre les tours 14 et 24)',0,0,'C');		
		$this->SetXY(100,230);
		$this->Cell(1,0,'4, place Jussieu',0,0,'C');		
		$this->SetXY(100,240);
		$this->Cell(1,0,'75252 Paris Cedex 05',0,0,'C');		
	}
	
	function formations()
	{
		$this->SetXY(25,10);
		$this->SetFont('Arial','BU',10);
		$this->Cell(1,0,'Formation','');
		$this->SetFont('Arial','U',10);		
		$this->SetXY(25,20);
		$this->Cell(1,0,'Bac ou �quivalent','');		
		$this->SetFont('Arial','',10);		
		$this->SetXY(25,25);
		$this->Cell(50,0,'Ann�e d\'obtention :','');		
		$this->Cell(50,0,'Section :','');		
		$this->Cell(50,0,'Acad�mie ou pays :','');				
		$this->SetXY(25,30);
		$this->Cell(70,0,'Moyenne sur 20 :','');		
		$this->Cell(70,0,'Rang dans la promotion :','');		

		$this->SetFont('Arial','U',10);		
		$this->SetXY(10,37);
		$this->Cell(1,0,'Bac +1','');		
		$this->SetFont('Arial','',10);		
		$this->SetXY(10,42);
		$this->Cell(50,0,'Ann�e universitaire :','');		
		$this->Cell(50,0,'Sp�cialit� :','');		
		$this->Cell(50,0,'�tablissement :','');				
		$this->SetXY(10,47);
		$this->Cell(70,0,'Moyenne sur 20 :','');		
		$this->Cell(70,0,'Rang dans la promotion :','');		

		$this->SetFont('Arial','U',10);		
		$this->SetXY(10,54);
		$this->Cell(1,0,'Bac +2','');		
		$this->SetFont('Arial','',10);		
		$this->SetXY(10,59);
		$this->Cell(50,0,'Ann�e universitaire :','');		
		$this->Cell(50,0,'Sp�cialit� :','');		
		$this->Cell(50,0,'�tablissement :','');				
		$this->SetXY(10,64);
		$this->Cell(70,0,'Moyenne sur 20 :','');		
		$this->Cell(70,0,'Rang dans la promotion :','');		

		$this->SetFont('Arial','U',10);		
		$this->SetXY(10,71);
		$this->Cell(1,0,'Bac +3','');		
		$this->SetFont('Arial','',10);		
		$this->SetXY(10,76);
		$this->Cell(50,0,'Ann�e universitaire :','');		
		$this->Cell(50,0,'Sp�cialit� :','');		
		$this->Cell(50,0,'�tablissement :','');				
		$this->SetXY(10,81);
		$this->Cell(70,0,'Moyenne sur 20 :','');		
		$this->Cell(70,0,'Rang dans la promotion :','');		

		$this->SetFont('Arial','U',10);		
		$this->SetXY(10,88);
		$this->Cell(1,0,'Bac +4','');		
		$this->SetFont('Arial','',10);		
		$this->SetXY(10,93);
		$this->Cell(50,0,'Ann�e universitaire :','');		
		$this->Cell(50,0,'Sp�cialit� :','');		
		$this->Cell(50,0,'�tablissement :','');				
		$this->SetXY(10,98);
		$this->Cell(70,0,'Moyenne sur 20 :','');		
		$this->Cell(70,0,'Rang dans la promotion :','');		

		$this->SetFont('Arial','U',10);		
		$this->SetXY(10,105);
		$this->Cell(1,0,'Bac +5','');		
		$this->SetFont('Arial','',10);		
		$this->SetXY(10,110);
		$this->Cell(50,0,'Ann�e universitaire :','');		
		$this->Cell(50,0,'Sp�cialit� :','');		
		$this->Cell(50,0,'�tablissement :','');				
		$this->SetXY(10,115);
		$this->Cell(70,0,'Moyenne sur 20 :','');		
		$this->Cell(70,0,'Rang dans la promotion :','');		
		
		// Separateur
		$this->Rect(10,120,190,0);		
	}
	
	function stages()
	{
		$this->SetXY(10,125);
		$this->SetFont('Arial','BU',10);
		$this->Cell(1,0,'Stages en laboratoire ou en entreprise, stages de terrain, exp�riences professionnelles','');
		$this->SetXY(10,130);		
		$this->SetFont('Arial','B',10);		
		$this->Cell(1,0,'Stages en laboratoires ou en entreprise :        effectu�s depuis 5 ans','');
		
		$this->SetFont('Arial','U',10);		
		$this->SetXY(10,140);		
		$this->Cell(1,0,'Dernier stage :','');
		$this->SetFont('Arial','',10);		
		$this->SetXY(10,145);		
		$this->Cell(1,0,'Nom du laboratoire ou de l\'entreprise :','');
		$this->SetXY(10,150);		
		$this->Cell(1,0,'Adresse du laboratoire ou de l\'entreprise :','');
		$this->SetXY(10,155);		
		$this->Cell(1,0,'Description/sujet/fonction :','');

		$this->SetFont('Arial','U',10);		
		$this->SetXY(10,162);			
		$this->Cell(1,0,'Stage N-1 :','');
		$this->SetFont('Arial','',10);		
		$this->SetXY(10,167);		
		$this->Cell(1,0,'Nom du laboratoire ou de l\'entreprise :','');
		$this->SetXY(10,172);		
		$this->Cell(1,0,'Adresse du laboratoire ou de l\'entreprise :','');
		$this->SetXY(10,177);		
		$this->Cell(1,0,'Description/sujet/fonction :','');

		$this->SetFont('Arial','U',10);		
		$this->SetXY(10,184);			
		$this->Cell(1,0,'Stage N-2 :','');
		$this->SetFont('Arial','',10);		
		$this->SetXY(10,189);		
		$this->Cell(1,0,'Nom du laboratoire ou de l\'entreprise :','');
		$this->SetXY(10,194);		
		$this->Cell(1,0,'Adresse du laboratoire ou de l\'entreprise :','');
		$this->SetXY(10,199);		
		$this->Cell(1,0,'Description/sujet/fonction :','');

		$this->SetFont('Arial','U',10);		
		$this->SetXY(10,206);			
		$this->Cell(1,0,'Stage N-3 :','');
		$this->SetFont('Arial','',10);		
		$this->SetXY(10,211);		
		$this->Cell(1,0,'Nom du laboratoire ou de l\'entreprise :','');
		$this->SetXY(10,216);		
		$this->Cell(1,0,'Adresse du laboratoire ou de l\'entreprise :','');
		$this->SetXY(10,221);		
		$this->Cell(1,0,'Description/sujet/fonction :','');

		$this->SetFont('Arial','U',10);		
		$this->SetXY(10,228);			
		$this->Cell(1,0,'Stage N-4 :','');
		$this->SetFont('Arial','',10);		
		$this->SetXY(10,233);		
		$this->Cell(1,0,'Nom du laboratoire ou de l\'entreprise :','');
		$this->SetXY(10,238);		
		$this->Cell(1,0,'Adresse du laboratoire ou de l\'entreprise :','');
		$this->SetXY(10,243);		
		$this->Cell(1,0,'Description/sujet/fonction :','');

		$this->SetFont('Arial','U',10);		
		$this->SetXY(10,250);			
		$this->Cell(1,0,'Stage N-5 :','');
		$this->SetFont('Arial','',10);		
		$this->SetXY(10,255);		
		$this->Cell(1,0,'Nom du laboratoire ou de l\'entreprise :','');
		$this->SetXY(10,260);		
		$this->Cell(1,0,'Adresse du laboratoire ou de l\'entreprise :','');
		$this->SetXY(10,265);		
		$this->Cell(1,0,'Description/sujet/fonction :','');

		$this->AddPage();
		
		$this->SetXY(25,10);		
		$this->SetFont('Arial','B',10);		
		$this->Cell(1,0,'Stages de terrain :        effectu�s depuis 5 ans','');
		
		$this->SetFont('Arial','U',10);		
		$this->SetXY(25,16);		
		$this->Cell(1,0,'Dernier stage de terrain :','');
		$this->SetFont('Arial','',10);		
		$this->SetXY(25,21);		
		$this->Cell(1,0,'Etablissement :','');
		$this->SetXY(25,26);		
		$this->Cell(1,0,'Encadrant :','');
		$this->SetXY(25,31);		
		$this->Cell(1,0,'Description/sujet/fonction :','');

		$this->SetFont('Arial','U',10);		
		$this->SetXY(10,37);		
		$this->Cell(1,0,'Stage de terrain N-1 :','');
		$this->SetFont('Arial','',10);		
		$this->SetXY(10,42);		
		$this->Cell(1,0,'Etablissement :','');
		$this->SetXY(10,47);		
		$this->Cell(1,0,'Encadrant :','');
		$this->SetXY(10,52);		
		$this->Cell(1,0,'Description/sujet/fonction :','');
		
		$this->SetFont('Arial','U',10);		
		$this->SetXY(10,58);		
		$this->Cell(1,0,'Stage de terrain N-2 :','');
		$this->SetFont('Arial','',10);		
		$this->SetXY(10,63);		
		$this->Cell(1,0,'Etablissement :','');
		$this->SetXY(10,68);		
		$this->Cell(1,0,'Encadrant :','');
		$this->SetXY(10,73);		
		$this->Cell(1,0,'Description/sujet/fonction :','');

		$this->SetFont('Arial','U',10);		
		$this->SetXY(10,79);		
		$this->Cell(1,0,'Stage de terrain N-3 :','');
		$this->SetFont('Arial','',10);		
		$this->SetXY(10,84);		
		$this->Cell(1,0,'Etablissement :','');
		$this->SetXY(10,89);		
		$this->Cell(1,0,'Encadrant :','');
		$this->SetXY(10,94);		
		$this->Cell(1,0,'Description/sujet/fonction :','');

		$this->SetFont('Arial','U',10);		
		$this->SetXY(10,100);		
		$this->Cell(1,0,'Stage de terrain N-4 :','');
		$this->SetFont('Arial','',10);		
		$this->SetXY(10,105);		
		$this->Cell(1,0,'Etablissement :','');
		$this->SetXY(10,110);		
		$this->Cell(1,0,'Encadrant :','');
		$this->SetXY(10,115);		
		$this->Cell(1,0,'Description/sujet/fonction :','');

		$this->SetFont('Arial','U',10);		
		$this->SetXY(10,121);		
		$this->Cell(1,0,'Stage de terrain N-5 :','');
		$this->SetFont('Arial','',10);		
		$this->SetXY(10,126);		
		$this->Cell(1,0,'Etablissement :','');
		$this->SetXY(10,131);		
		$this->Cell(1,0,'Encadrant :','');
		$this->SetXY(10,136);		
		$this->Cell(1,0,'Description/sujet/fonction :','');

		$this->SetXY(10,143);		
		$this->SetFont('Arial','B',10);		
		$this->Cell(1,0,'Exp�riences professionnelles :        effectu�es depuis 5 ans','');
		
		$this->SetFont('Arial','U',10);		
		$this->SetXY(10,149);		
		$this->Cell(1,0,'Derni�re exp�rience professionnelle :','');
		$this->SetFont('Arial','',10);		
		$this->SetXY(10,154);		
		$this->Cell(1,0,'Nom de l\'entreprise :','');
		$this->SetXY(10,159);		
		$this->Cell(1,0,'Adresse de l\'entreprise :','');
		$this->SetXY(10,164);		
		$this->Cell(1,0,'Description/sujet/fonction :','');

		$this->SetFont('Arial','U',10);		
		$this->SetXY(10,170);			
		$this->Cell(1,0,'Exp�rience professionnelle N-1 :','');
		$this->SetFont('Arial','',10);		
		$this->SetXY(10,175);		
		$this->Cell(1,0,'Nom de l\'entreprise :','');
		$this->SetXY(10,180);		
		$this->Cell(1,0,'Adresse de l\'entreprise :','');
		$this->SetXY(10,185);		
		$this->Cell(1,0,'Description/sujet/fonction :','');

		$this->SetFont('Arial','U',10);		
		$this->SetXY(10,191);			
		$this->Cell(1,0,'Exp�rience professionnelle N-2 :','');
		$this->SetFont('Arial','',10);		
		$this->SetXY(10,196);		
		$this->Cell(1,0,'Nom de l\'entreprise :','');
		$this->SetXY(10,201);		
		$this->Cell(1,0,'Adresse de l\'entreprise :','');
		$this->SetXY(10,206);		
		$this->Cell(1,0,'Description/sujet/fonction :','');

		$this->SetFont('Arial','U',10);		
		$this->SetXY(10,212);			
		$this->Cell(1,0,'Exp�rience professionnelle N-3 :','');
		$this->SetFont('Arial','',10);		
		$this->SetXY(10,217);		
		$this->Cell(1,0,'Nom de l\'entreprise :','');
		$this->SetXY(10,222);		
		$this->Cell(1,0,'Adresse de l\'entreprise :','');
		$this->SetXY(10,227);		
		$this->Cell(1,0,'Description/sujet/fonction :','');

		$this->SetFont('Arial','U',10);		
		$this->SetXY(10,233);			
		$this->Cell(1,0,'Exp�rience professionnelle N-4 :','');
		$this->SetFont('Arial','',10);		
		$this->SetXY(10,238);		
		$this->Cell(1,0,'Nom de l\'entreprise :','');
		$this->SetXY(10,243);		
		$this->Cell(1,0,'Adresse de l\'entreprise :','');
		$this->SetXY(10,248);		
		$this->Cell(1,0,'Description/sujet/fonction :','');

		$this->SetFont('Arial','U',10);		
		$this->SetXY(10,254);			
		$this->Cell(1,0,'Exp�rience professionnelle N-5 :','');
		$this->SetFont('Arial','',10);		
		$this->SetXY(10,259);		
		$this->Cell(1,0,'Nom de l\'entreprise :','');
		$this->SetXY(10,264);		
		$this->Cell(1,0,'Adresse de l\'entreprise :','');
		$this->SetXY(10,269);		
		$this->Cell(1,0,'Description/sujet/fonction :','');

		// Separateur
		$this->Rect(10,274,190,0);		
	}
}
?>

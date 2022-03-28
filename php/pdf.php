
<?php
// la connexion a la base de donnes
$get_id=(int) trim(htmlentities($_GET['id']));//on recupere l'id de la categorie pour lister les topics qui appartient a cette catégorie
try {
    $bdd=new PDO('mysql:host=localhost;dbname=artisanat;charset=utf8','root','');
    }
    catch(Exception $e)
    {
    die('Erreur:'.$e->getMessage());
    }
    $projets=$bdd->prepare("SELECT * FROM projets WHERE id=?");
    $projets->execute(array($get_id));
    $projet=$projets->fetch();

    $partenaires=$bdd->prepare("SELECT * FROM partenaires WHERE id_projet=?");
    $partenaires->execute(array($get_id));

    //on preleve 
    //les donnes de l'architecte
    $architecte=$bdd->prepare("SELECT * FROM architectes WHERE id_projet=?");
    $architecte->execute(array($get_id));
    $architecte=$architecte->fetch();
    
    //les donnes deu bureau d'etude
    $bet=$bdd->prepare("SELECT * FROM bet WHERE id_projet=?");
    $bet->execute(array($get_id));
    $bet=$bet->fetch();

    //les donnes du bc
    $bc=$bdd->prepare("SELECT * FROM bc WHERE id_projet=?");
    $bc->execute(array($get_id));
    $bc=$bc->fetch();

    $topographe=$bdd->prepare("SELECT * FROM topographe WHERE id_projet=?");
    $topographe->execute(array($get_id));
    $topographe=$topographe->fetch();

    $entreprise_construction=$bdd->prepare("SELECT * FROM entreprise_construction WHERE id_projet=?");
    $entreprise_construction->execute(array($get_id));
    $entreprise_construction=$entreprise_construction->fetch();
    if (setlocale(LC_TIME, 'fr_FR') == '') {
        setlocale(LC_TIME, 'FRA');  //correction problème pour windows
        $format_jour = '%B';
    } else {
        $format_jour = '%e';
    }

require('../tfpdf/tfpdf.php');
$nom=$projet['intitule_projet'];
$date=$projet['date_signature'];

class PDF extends tFPDF
{
// En-tête
function Header()
{
    $this->ln(5);
    // Logo
    //$this->Image('../images/fiche technique.png',10,7,200,25);
    // Police Arial gras 15
    //$this->Ln(30);
}
function TitreChapitre($libelle)
{
 
    // Arial 12
    $this->SetTextColor(0,0,0);
    $this->SetFont('Times','B',13);
    // Couleur de fond
    $this->SetFillColor(196, 173, 143);
    // Titre
    $this->Cell(0,8,$libelle,0,1,'L',true);
    // Saut de ligne
    $this->Ln(4);
}

function Titre($libelle)
{ 
    // Arial 12
    $this->SetTextColor(255, 255, 255);
    
    $this->SetFont('times','B',15);
    // Couleur de fond
    $this->SetFillColor(70, 47, 17);
    // Titre
    $this->SetFont('DejaVuSans-Bold','',13);    // Titre
    $this->Cell(0,15,$libelle,0,1,'C',true);
    // Saut de ligne
    $this->Ln(4);
}
// Pied de page
function Footer()
{
    global $date;
    global $nom;
    global $format_jour;
    // Positionnement à 1,5 cm du bas
    $this->SetY(-20);
    // Police Arial italique 8
    $this->SetTextColor(100, 83, 60);
    $this->SetFont('DejaVuSans','',7);    // Titre
    $this->cell(6);
    $this->Cell(0,0,'Fiche Projet '. $nom.' / DSPC - '.strftime("$format_jour  %Y", strtotime($date))
    ,0,1,'L');
    // Numéro de page
    $this->SetFont('Arial','',8);
    $this->Cell(0,20,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
function FancyTable($header, $data)
{
    // Couleurs, épaisseur du trait et police grasse
    
    // Restauration des couleurs et de la police
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('DejaVuSans','',11);
    // Données
    $fill = false;
        // En-tête
    $w = array(100,25); 
    $this->cell(35);
    foreach($data as $row)
    {
        $this->Cell($w[0],6,$row[0],1,'','LR',0,'L');
        $this->Cell($w[1],6,$row[1],1,'','C',0,'R');    
        $this->Ln();
        $this->cell(35);
    }
    $this->SetFillColor(70, 47, 17);
    $this->SetTextColor(255);
    $this->SetDrawColor(187, 185, 183);
    $this->SetLineWidth(.3);
    $this->SetFont('DejaVuSans-Bold','',11);
        $this->Cell($w[0],7,$header[0],1,0,'C',true);
        $this->Cell($w[1],7,number_format($header[1],0,',',' '),1,0,'C',true);

    $this->Ln();
    // Trait de terminaison
}
}




$data=array(array("Ministere",$projet['budget_minis']));
$total=$projet['budget_minis'];
while($partenaire=$partenaires->fetch()) {
    $data[]=array($partenaire['nom'],$partenaire['contribution']);
    $total+=$partenaire['contribution'];
}

// Instanciation de la classe dérivée
$somme=$architecte['depense']+$bet['depense']+$bc['depense']+$topographe['depense']+$entreprise_construction['depense'];
$data2=array(array('Architecte',$architecte['depense']),array('BET',$bet['depense']),array('BC',$bc['depense']),array('Topographe',$topographe['depense']),array('Entreprise',$entreprise_construction['depense']));
$header2=array('Total Dépenses(en MDh)',$somme);
$header=array('Total Resources (en Mdh)',$total);
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
// Logo
    $pdf->Image('../images/fiche technique.png',10,7,200,25);
    // Police Arial gras 15
    $pdf->Ln(30);

$pdf->AddFont('DejaVuSans','','DejaVuSans.ttf',true);
$pdf->AddFont('DejaVuSans-Bold','','DejaVuSans-Bold.ttf',true);

$pdf->SetFont('DejaVuSans-Bold','U',20);







// Saut de la ligne
// Titre
$pdf->Titre($projet['intitule_projet']);
// Saut de ligne
$pdf->ln(8);
$pdf->TitreChapitre("Identification Du Projet");

    $pdf->SetFont('DejaVuSans','U',12);
    // Titre
    $pdf->Cell(0,8,"Localisation:",0,1);
    // Saut de ligne
    $pdf->cell(27);
    $pdf->SetFont('DejaVuSans','',11);
    // Titre
    $pdf->Cell(0,8,"Region        :".$projet['region'],0,1);
    // Saut de ligne
    $pdf->cell(27);
    // Titre
    $pdf->Cell(0,8,"Province      :".$projet['province'],0,1);
    // Saut de ligne
    $pdf->cell(27);
    // Titre
    $pdf->Cell(0,8,"Lieu           :".$projet['lieu'],0,1);
    // Saut de ligne
    $pdf->Ln(2);


  
    $pdf->SetFont('DejaVuSans','U',12);    // Titre
    $pdf->Cell(0,8,"Foncier:",0,1);
    // Saut de ligne

    $pdf->cell(27);
    $pdf->SetFont('DejaVuSans','',11);
    // Titre
    $pdf->Cell(0,8,"Situation du foncier: ".$projet['situe_foncier'],0,1);
    // Saut de ligne
    $pdf->Ln(1);
    $pdf->cell(27);
    // Titre
    $pdf->Cell(0,8,"Superficie globale: ".$projet['superficie_glob']." m2",0,1);
    // Saut de ligne
    $pdf->Ln(1);
    $pdf->cell(27);
    // Titre
    $pdf->Cell(0,8,"Superficie construite:".$projet['superficie_const']." m2",0,1);
    // Saut de ligne
    $pdf->Ln(2);
    $pdf->SetFont('DejaVuSans','U',12);
    // Titre
    $pdf->Cell(0,8,"Localisation:",0,1);
    // Saut de ligne
    $pdf->Ln(4);
    $pdf->Image('../projets/site/'.$projet['maps'],15,null,185,100);
    $pdf->Ln(10);

    $pdf->TitreChapitre("Objectifs");
    $pdf->cell(5);
    $pdf->SetFont('DejaVuSans','',12);    // Titre
    $pdf->MultiCell(0,8,$projet['objectifs'],0,'L',false);
    // Saut de ligne
    // Titre
    $pdf->Ln(10);

  

    $pdf->TitreChapitre("Montage institutionnel et financier");
    $pdf->SetTextColor(277,108,10);
    $pdf->SetFont('DejaVuSans','',11); 
    $pdf->Cell(0,8,"Resources",0,1);
    $pdf->Ln(6);
    $pdf->FancyTable($header, $data);
    $pdf->Ln(6);
    $pdf->SetTextColor(277,108,10);
    $pdf->SetFont('DejaVuSans','',11); 
    $pdf->Cell(0,8,"Dépenses",0,1);
    $pdf->Ln(6);
    $pdf->FancyTable($header2, $data2);
    $pdf->ln(6);

    $pdf->TitreChapitre("Etapes franchies");
    $pdf->cell(5);
    $pdf->SetFont('DejaVuSans','',12);    // Titre
    $pdf->MultiCell(0,8,$projet['observation'],0,'L',false);
    // Saut de ligne
    // Titre
    $pdf->Ln(10);

    $pdf->TitreChapitre("Hommes de l'Art");
    $pdf->Ln(3);
    $pdf->SetFillColor(224,235,255);
    $pdf->SetTextColor(0);
    //ajouter cette ligne pour lunicode
    $pdf->SetFont('DejaVuSans','',11);
    // Données
    $fill = false;
    $data=array(array('Architecte',$architecte['nom']),
    array('BET',$bet['nom']),
    array('BC',$bc['nom']),
    array('Topographe',$topographe['nom']),
    array('Entreprise',$entreprise_construction['nom']));
        // En-tête
    $w = array(30,170); 
    foreach($data as $row)
    {
        $pdf->Cell($w[0],6,$row[0],1,'','',0,'L');
        $pdf->Cell($w[1],6,$row[1],1,'','',0,'L');       
        $pdf->Ln();
    }
    $pdf->AddPage();

    $pdf->Ln(10);
    $pdf->TitreChapitre("Annexes");
  $pdf->cell(20);
    $pdf->SetFont('Times','U',15);
    // Titre
    $pdf->Cell(0,8,"Plans Du projet:",0,1);
    // Saut de ligne
    $pdf->Image('../projets/plans/'.$projet['plans'],5,80,200);
     $pdf->Output();
?>




<?php
//code xml

session_start();
if(!isset($_SESSION['id']))
{
  header('Location:index.php');
  exit;
}
try {
    $bdd=new PDO('mysql:host=localhost;dbname=artisanat;charset=utf8','root','');
    }
    catch(Exception $e)
    {
    die('Erreur:'.$e->getMessage());
    }
$convention=$bdd->prepare("SELECT * FROM projets WHERE superficie_glob=''");
$convention->execute();
$nbr_convention=0;
while($con=$convention->fetch()) {
    $nbr_convention++;
}
$convention=$bdd->prepare("SELECT * FROM projets WHERE superficie_glob!=0 and ordre_service=0000-00-00");
$convention->execute();
$nbr_etude=0;
while($con=$convention->fetch()) {
    $nbr_etude++;
}
$convention=$bdd->prepare("SELECT * FROM projets WHERE ordre_service!=0000-00-00 and date_lancement_AMI=0000-00-00");
$convention->execute();
$nbr_chantier=0;
while($con=$convention->fetch()) {
    $nbr_chantier++;
}
$convention=$bdd->prepare("SELECT * FROM projets WHERE date_lancement_AMI!=0000-00-00 and directeur=''");
$convention->execute();
$nbr_acheves=0;
while($con=$convention->fetch()) {
    $nbr_acheves++;
}
$convention=$bdd->prepare("SELECT * FROM projets WHERE directeur!='' and date_lancement_AMI!=0000-00-00");
$convention->execute();
$nbr_oper=0;
while($con=$convention->fetch()) {
    $nbr_oper++;
}

$regions=$bdd->prepare("SELECT * FROM projets ");
$regions->execute();
$region=array();
while($value=$regions->fetch()) {
    $region[]=$value['region'];
}

$result=array_count_values($region);
$dataPoints=array();
foreach($result as $key=>$value) {
  $dataPoints[]=array('label'=>$key,'y'=>$value);
}

?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Tableau de Bord - GIIPC Artisanat</title>
         <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
         <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
         <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
         <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
        <link href="../css/styles.css" rel="stylesheet" />
        <link href="../style/accueil.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav">
        <nav class="sb-topnav navbar navbar-expand navbar">
                        <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
            <img src="../images/logo.png" alt="" class="ministere">
           <!--- <div class=" ml-auto mr-0 mr-md-3 my-2 my-md-0">
            </div>
            ---->
            <div class="util">
                <div class="dec">
                <i class="fa fa-power-off" aria-hidden="true"></i><a href="deconnexion.php">Déconnexion</a>
                </div>
                <!--<div class="profil">
                    <a href="info sur l'util"><?= $_SESSION['prenom'].' '.$_SESSION['nom']?></a><img src="../images/user.png">
                </div>--->
            </div>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <a class="nav-link active" href="accueil.php">
                                <div class="sb-nav-link-icon"> <i class="fas fa-chart-area mr-1"></i></div>
                                 Tableau de bord
                            </a>
                            <a class="nav-link" href="convention.php">
                                <div class="sb-nav-link-icon"><i class="fa fa-address-book" aria-hidden="true"></i></div>
                                Projets En cours De mobilisation Foncier
                            </a>
                            <a class="nav-link" href="etude.php">
                                <div class="sb-nav-link-icon"><i class="fa fa-book" aria-hidden="true"></i></div>
                                Projets En cours d'Etude
                            </a>
                            <a class="nav-link" href="chantier.php">
                                <div class="sb-nav-link-icon"><i class="fa fa-gavel" aria-hidden="true"></i></div>
                                Projets En cours de chantier
                            </a>
                            <a class="nav-link" href="acheves non operationels.php">
                                <div class="sb-nav-link-icon"><i class="fa fa-university" aria-hidden="true"></i></div>
                                Projets acheves non operationels
                            </a>
                            <a class="nav-link" href="acheves operationels.php">
                                <div class="sb-nav-link-icon"><i class="fa fa-university" aria-hidden="true"></i></div>
                                Projets acheves operationels
                            </a>

                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                       
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                      
                        <div class="row cartee">
                            <div class="col-xl-3 col-md-6">
                                <div class="card etude ticket text-white mb-4">
                                    <div class="card-body">
                                        <div class="item">
                                        <i class="fa fa-address-book carte" aria-hidden="true"></i>
                                        </div>
                                        <div class="title">
                                           Infrastractures En mobilisation Foncier<br><h3><?=$nbr_convention?></h3>
                                        </div>
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="convention.php">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card ticket chnatier  text-white mb-4">
                                    <div class="card-body">
                                        <div class="item">
                                        <i class="fa fa-book carte" aria-hidden="true"></i>
                                        </div>
                                        <div class="title">
                                           Infrastractures En Cours D'Etude<br><h3><?=$nbr_etude?></h3>
                                        </div>
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="etude.php">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card ticket const text-white mb-4">
                                    <div class="card-body">
                                        <div class="item">
                                           <i class="fa fa-gavel carte" aria-hidden="true"></i>
                                        </div>
                                        <div class="title">
                                           Infrastractures En Cours De constrution<br><h3><?=$nbr_chantier?></h3>
                                        </div>
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="chantier.php">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card ticket foncier text-white mb-4">
                                    <div class="card-body">
                                        <div class="item">
                                        <i class="fa fa-university carte" aria-hidden="true"></i>
                                        </div>
                                        <div class="title">
                                           Infrastractures Achevés<br><h3><?=$nbr_acheves+$nbr_oper?></h3>
                                        </div>
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="acheves operationels.php">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-area mr-1"></i>
                                        le nombre des infrastractures.
                                    </div>
                                    <div id="myfirstchart" style="height: 250px;"></div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-bar mr-1"></i>
                                      les infrastractures selon les Régions.
                                    </div>
                                    <div id="mysecondChart" style="height: 250px;"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                           
                        </div>
                    </div>
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Your Website 2020</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="../js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="../assets/demo/chart-area-demo.js"></script>
        <script src="../assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

        <script>
             CanvasJS.addColorSet("greenShades",
                [//colorSet Array
                "rgb(199,128,35)",
                "rgb(239,178,97)",
                "rgb(255,132,0)",
                "rgb(255,165,0)",
                "rgb(251, 216, 149)"                
                ]);
                window.onload = function () {
 
 var chart = new CanvasJS.Chart("mysecondChart", {
    colorSet: "greenShades",
     animationEnabled: true,
     data: [{
		type: "doughnut",
         legendText: "{label}",
         indexLabelFontSize: 16,
         indexLabel: "{label} - #percent%",
         dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
     }]
 });
 chart.render();
  
 }

Morris.Bar({
  // ID of the element in which to draw the chart.
  element: 'myfirstchart',
  
  // Chart data records -- each entry in this array corresponds to a point on
  // the chart.
  data: [
    { y: 'En cours de mobilisation foncier',a: <?=$nbr_convention?>},
    { y: "En cours d'etude",a:<?=$nbr_etude?>},
    {y: 'En cours de chantier',a: <?=$nbr_chantier?>},
    {y: 'Achevés non opérationnels',a:<?=$nbr_acheves?>},
    {y: 'Achevés opérationnels',a:<?=$nbr_oper?>}
  ],
  xkey:'y',
  ykeys:['a'],
  barColors: ["rgba(250, 185, 112, 0.383)"],
  labels:['nombre'],
});
        </script>
    
    
    </body>
</html>
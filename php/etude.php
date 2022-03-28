<?php
//connexion à la base De données
session_start();
if(!isset($_SESSION['id'])) {
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


//Le traitement De la recherche
    if(isset($_POST['rechercher'])) {
        $intitule=trim(htmlentities($_POST['intitule']));
        $province=trim(htmlentities($_POST['province']));
        $region=trim(htmlentities($_POST['region']));
        $region=substr($region,0,1);
        $province=substr($province,0,1);
        if(empty($province) && empty($region)) {
            $projets=$bdd->prepare('SELECT * FROM projets WHERE intitule_projet LIKE ?');
            $projets->execute(array('%'.$intitule.'%'));
        }elseif(empty($province) && empty($intitule)) {
            $projets=$bdd->prepare('SELECT * FROM projets WHERE region LIKE ?');
            $projets->execute(array($region.'%'));
        }elseif(empty($region) && empty($intitule)) {
            $projets=$bdd->prepare('SELECT * FROM projets WHERE province LIKE ?');
            $projets->execute(array($province.'%'));
        }elseif(empty($intitule)) {
            $projets=$bdd->prepare('SELECT * FROM projets WHERE province LIKE ? AND region LIKE ?');
            $projets->execute(array($province.'%',$region.'%'));
        }elseif(empty($province)) {
            $projets=$bdd->prepare('SELECT * FROM projets WHERE region LIKE ? AND intitule_projet LIKE ?');
            $projets->execute(array($region.'%','%'.$intitule.'%'));
        }elseif(empty($region)) {
            $projets=$bdd->prepare('SELECT * FROM projets WHERE province LIKE ? AND intitule_projet LIKE ?');
            $projets->execute(array($province.'%','%'.$intitule.'%'));
        }else {
            $projets=$bdd->prepare('SELECT * FROM projets WHERE intitule_projet LIKE ? AND province LIKE ? AND region LIKE ?');
            $projets->execute(array('%'.$intitule.'%',$province.'%',$region.'%'));
        }


    }else {
        $projets=$bdd->prepare("SELECT * FROM projets");
        $projets->execute();
    }
    $regions=array("","L'Oriental","Marrakech-Safi","Drâa-Tafilalet","Fès-Meknès","Guelmim-oued Noun","Tanger-Tétouan-Al Hoceima","Souss-Massa","Casablanca-Settat","Dakhla-Oued Eddahab","Beni Mellal-Khénifra","Rabat-Salé-Kénitra","Laâyoune-Sakia Al Hamra");

$provinces=array("L’Oriental"=>array("","Oujda-Angad","Nador","Driouch","Jerada","Berkan","Taourirt","Guercif","Figuig"));
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Projets en cours d'etude
            
        </title>
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
                            <a class="nav-link" href="accueil.php">
                                <div class="sb-nav-link-icon"> <i class="fas fa-chart-area mr-1"></i></div>
                                Tableau de bord
                            </a>
                            <a class="nav-link" href="convention.php">
                                <div class="sb-nav-link-icon"><i class="fa fa-address-book" aria-hidden="true"></i></div>
                                Projets En cours De mobilisation Foncier
                            </a>
                            <a class="nav-link active" href="etude.php">
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
                        <div class="ajout-recherche">
                            <div class="recherche">
                            <form action="etude.php" method="POST">
                                    Intitulé Du projet :<input type="text" name="intitule" value="" placeholder="Intitulé Du projet">
                                    Région:
                                    <!--<input type="text" name="region" value="" placeholder="la région">--->
                                    <select id="region" onchange="choixRegion(this)" name="region">
                                    <?php foreach($regions as $key => $value) {?>
                                      <option value="<?=$value?>"><?=$value?></option>
                                    <?php }?>
                                    </select>
                                    Province:
                                    <select id="provinces"  name="province">
                                      <?php foreach($provinces["L’Oriental"] as $valeur) {?>
                                      <option value="<?=$valeur?>" >  <?=$valeur?></option>
                                      <?php }?>
                                    </select>
                                    <button type="submit" name="rechercher" class="btn btn-primary" id="R"><i class="fas fa-search"></i> Rechercher</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <table  class="table table-bordered">
                            <thead>
                                <tr>
                                <th>Référence</th>
                                <th scope="col">Intitulé du Projet</th>
                                <th scope="col">Région</th>
                                <th scope="col">Province</th>
                                <th scope="col">Fiche technique</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while($projet=$projets->fetch()){
                                    if($projet['ordre_service']==0000-00-00 and $projet['superficie_glob']!=0){
                                ?>
                                    <tr>
                                        <th><?=$projet['id']?></th>
                                        <th scope="row"><i class="fa fa-folder-open" aria-hidden="true"></i>&nbsp;<a href="rapport_etude.php?id=<?=$projet['id']?>"><?=str_replace("\'","'",$projet['intitule_projet'])?></th>
                                        <td><?=$projet['region']?></td>

                                        <td><?=$projet['province']?></td>
                                        <td><a href="pdf.php?id=<?=$projet['id']?>">Fiche technique.pdf</a></td>   
                                    </tr>
                                <?php }}?>
                            </tbody>
                    </table>
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
        <script src="../assets/demo/datatables-demo.js"></script>
        <script>
        var province= {"L'Oriental": ["","Oujda-Angad","Nador","Driouch","Jerada","Berkan","Taourirt","Guercif","Figuig"],
"Marrakech-Safi":["","Marrakech","Chichaoua","Al Haouz","Kelâa des Sraghna","Essaouira","Rehamna","Safi","Youssoufia"],
"Drâa-Tafilalet":["","Errachidia","Ouarzazate","Midelt","Tinghir","Zagora​"],
"Fès-Meknès":["","Fès","Meknès","El Hajeb","Ifrane","Moulay Yacoub","Sefrou","Boulemane","Taounate","Taza"],
"Guelmim-oued Noun":["","Guelmim","Assa-Zag","Tan-Tan","Sidi Ifni"],
"Tanger-Tétouan-Al Hoceima":["","Tanger-Assilah","M'diq-Fnideq","Tétouan","Fahs-Anjra","Larache","Al Hoceima","Chefchaouen","Ouazzane"],
"Souss-Massa":["","Agadir Ida-Ou-Tanane","Inezgane-Aït Melloul","Chtouka-Aït Baha","Taroudannt","Tiznit","Tata"],
"Casablanca-Settat":["","Casablanca","Mohammadia","El Jadida","Nouaceur","Médiouna","Benslimane","Berrechid","Settat","Sidi Bennour"],
"Beni Mellal-Khénifra":["","Béni Mellal","Azilal","Fquih Ben Salah","Khénifra","Khouribga​"],
"Rabat-Salé-Kénitra":["","Rabat","Salé","Skhirate-Témara","Kénitra","Khémisset","Sidi Kacem","Sidi Slimane"],
"Laâyoune-Sakia Al Hamra": ["","Laâyoune","Boujdour","Tarfaya","Es-Semara​"]
        };
    
        function choixRegion(regionSelected) {
  //on prend le id du select a supprimer 
  var oSelect = document.getElementById('provinces');
        //on reprend les options
        var opts = oSelect.getElementsByTagName('option');
        //then we delete the previous options
        while(opts[0]) {
    oSelect.removeChild(opts[0]);
    }
    //we add a new options selon la variable regionSelected 
     var a=regionSelected.value;
    var x=province[a];

 for(var i=0 ; i<x.length; i++) {
    provinces.options.add (new Option (x[i]),"valeu");
 }


        //il faut afficher provinces x dans le choix des provinces
}
    </script>
    </body>
</html>

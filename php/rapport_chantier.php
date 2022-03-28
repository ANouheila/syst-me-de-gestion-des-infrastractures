<?php
//connexion à la base De données
session_start();
$get_id=(int) trim(htmlentities($_GET['id']));//on recupere l'id de la categorie pour lister les topics qui appartient a cette catégorie
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
        $projet=$bdd->prepare("SELECT * FROM projets WHERE id=$get_id");
        $projet->execute();
        $projet=$projet->fetch();

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title><?=$projet['intitule_projet']?></title>
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
                                Dashboard
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
                        <div class="small">Logged in as:</div>
                        Start Bootstrap
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        <div class="rapport">
                           <h3>Détail Du <?=$projet['intitule_projet']?></h3>
                            <div>
                                <div class="phase">
                                    Identification Du projet
                                </div>
                                <div class="item">
                                <b>Intitulé Du projet</b> :<?=$projet['intitule_projet']?>.
                                </div>
                                <div class="item"><b>province</b> : <?=$projet['province']?></div>
                                <div class="item"><b>Région </b> : <?=$projet['region']?></div>
                                <div class="item"><b>Localité</b> : <?=$projet['lieu']?></div>
                                <div class="item"><b>Convention signé le</b> : <?=$projet['date_signature']?></div>
                                <div class="item"><b>Prix Du marché</b>: <?=$projet['budget']?> Dhs</div>
                                <div class="item"><b>Contribution Du ministére</b> : <?=$projet['budget_minis']?> Dhs</div>
                                <div class="phase">
                                   Objectifs
                                </div>
                                <?=str_replace(",","<br>", $projet['objectifs'])?></div>

                                <div class="phase">
                                   Partenariat
                                </div>
                                <table class="table table-bordered">
                                                <tr>
                                                    <th scope="col">Nom du Partenaire</th>
                                                    <th scope="col">Siege Sociale</th>
                                                    <th scope="col">Contribution Financiere</th>
                                                    <th scope="col">Montant Versé</th>
                                                </tr>
                                                <?php 
                                             //recuperer la liste des partenaires
                                            $partenaires=$bdd->prepare("SELECT * FROM partenaires WHERE id_projet=?");
                                            $partenaires->execute(array($projet['id']));
                                            ?>
                                            <?php while($partenaire=$partenaires->fetch()){?>
                                                <tr>
                                                    <td><abbr title="Mail:<?=$partenaire['mail']?> - Telephone:<?=$partenaire['telephone']?>"><?=$partenaire['nom']?></abbr></td>
                                                    <td><?=$partenaire['siege']?></td>
                                                    <td><?=$partenaire['contribution']?>Dhs</td>
                                                    <td><?=$partenaire['montant_verse']?>Dhs</td>
                                                </tr>
                                            <?php }?>
                                </table>
                                <div class="act-etude">
                                    <button class="btn btn-primary"><a href="modifier_convention.php?id=<?=$projet['id']?>">Modifier</a></button>
                                    
                                </div>
                                <div class="phase">
                                   Foncier
                                </div>
                                <div class="item"><b>Situation Du Foncier</b> : <?=$projet['situe_foncier']?></div>
                                <div class="item"><b>Superficie Globale</b> : <?=$projet['superficie_glob']?>&nbsp m<sup>2</sup></div>
                                <div class="item"><b>Superficie Construite</b> : <?=$projet['superficie_const']?>&nbsp m<sup>2</sup></div>
                                <div class="phase">
                                     Montage institutionnel et financier
                                </div>
                                <table class="table table-bordered archi">
                                            <tr>
                                                <th class="h"></th>
                                                <th>Nom</th>
                                                <th>Dépense Financiere</th>
                                                <th>Mail</th>
                                                <th>N°Télephone</th>
                                            </tr>
                                            <?php 
                                            $bureaux=array('architecte'=>'architectes','BET'=>'bet','BC'=>'bc','topographe'=>'topographe','Entrepise de construction'=>'entreprise_construction');
                                            foreach($bureaux as $bureau=>$value){
                                            ?>
                                                <tr>
                                                    <th scope="col"><?=$bureau?></th>
                                                    <?php 
                                                    $requete=$bdd->prepare("SELECT * FROM $value WHERE id_projet=?");
                                                    $requete->execute(array($get_id));
                                                    $requet=$requete->fetch()?>
                                                    <td><?=$requet['nom']?></td>
                                                    <td><?=$requet['depense']?>Dhs</td>
                                                    <td><?=$requet['mail']?> </td>
                                                    <td><?=$requet['tele']?></td>
                                                </tr>
                                            <?php } ?>
                                </table>
                                <div class="phase">
                                    Consistance Physique
                                </div>
                                <div class="item">
                                    <?=$projet['consistance_physique']?>
                                </div>

                                <div class="phase">
                                    etapes franchies
                                </div>
                                <div class="item">
                                    <?=str_replace('.','.<br>',$projet['observation'])?>
                                </div>
                                <div class="act-etude">
                                    <button class="btn btn-primary"><a href="modifier_etude.php?id=<?=$projet['id']?>">Modifier La phase d'etude</a></button>
                                </div>
                                <div class="phase">
                                        Suivie De chantier
                                    </div>
                                <div class="item"><b>Ordre de service</b> : <?=$projet['ordre_service']?></div>
                                <div class="item"><b>Date previsionelle d'achevement</b> : <?=$projet['date_achevement_pre']?></div>
                                <div class="item"><b>Date d'achevement du projet</b> : <?=$projet['date_achevement']?></div>
                                <div class="phase">
                                    Documents associés
                                </div>
                                <button class="btn btn-primary"><a href="<?php if($projet['doc_convention']=='') {echo "erreur.php" ; }else { echo "../projets/convention/".$projet['doc_convention'] ; }?>">Convention.pdf</a></button>
                                <button class="btn btn-primary"><a href="pdf.php?id=<?=$projet['id']?>">Fiche technique.pdf</a></button>
                                <button class="btn btn-primary"><a href="<?php if($projet['plan_masse']=='') {echo "erreur.php" ; }else { echo "../projets/plan_masse/".$projet['plan_masse'] ; }?>">Plan de masse</a></button>
                                <button class="btn btn-primary"><a href="<?php if($projet['plans']=='') {echo "erreur.php" ; }else { echo "../projets/plans/".$projet['plans'] ; }?>">Plans.pdf</a></button>
                                <button class="btn btn-primary"><a href="<?php if($projet['etude_technique']=='') {echo "erreur.php" ;} else {echo "../projets/etude_technique/".$projet['etude_technique'] ;} ?>">Rapport des etudes techniques</a></button>
                                <button class="btn btn-primary obs"><a href="<?php if($projet['etude_geotechnique']=='') {echo "erreur.php" ;} else {echo "../projets/etude_geotechnique/".$projet['etude_geotechnique'];} ?>">Rapport des etudes geotechniques</a></button>
                                <button class="btn btn-primary obs"><a href="<?php if($projet['cps']=='') {echo "erreur.php" ;} else {echo "../projets/cps/".$projet['cps'] ;} ?>">Cahier de prescriptions spéciales (cps)</a></button>
                                <button class="btn btn-primary obs"><a href="<?php if($projet['site']=='') {echo "erreur.php" ;} else {echo "../projets/site/".$projet['site'] ;} ?>">site</a></button>
                                <button class="btn btn-primary"><a href="<?php if($projet['planning_realisation']=='') {echo "erreur.php" ;} else { echo "../projets/planning_realisation/".$projet['planning_realisation'];}?> ">Planning De realisation</a></button>

                                <div class="phase">
                                    Observation
                                </div>
                                <div class="item">
                                    <?=str_replace('.','.<br>',$projet['observation'])?>
                                </div>
                                <div class="act">
                                    <button class="btn btn-primary"><a href="modifier_chantier.php?id=<?=$projet['id']?>">Modifier</a></button>
                                    <button class="btn btn-primary"><a href="ajouter_non_opeationels.php?id=<?=$get_id?>">Projet Achevé</a></button>
                                    <button class="btn btn-primary"><a href="../php/supprimer.php?id=<?=$projet['id']?>" onclick="return confirm('etes vous sur de vouloir supprimer ce projet')">Supprimer</a></button>
                                </div>
                                
                                </div>
                        </div>
                    </div>
                </main>
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
    </body>
</html>
<?php 
session_start();
if(!isset($_SESSION['id'])) {
    header('Location:index.php');
    exit;
}
//Le cas d'une modification

try {
    $bdd=new PDO('mysql:host=localhost;dbname=artisanat;charset=utf8','root','');
    }
    catch(Exception $e)
    {
    die('Erreur:'.$e->getMessage());
    }
$get_id=(int) trim(htmlentities($_GET['id']));
if(isset($_POST['ajouter'])){
    $etapes=addslashes($etapes);
     extract($_POST);
     $observation_oper=htmlentities(addslashes($observation_oper));
     echo $observation_oper;
     //on fait la connexion a la base de donnes
     //on enregistre leprojet  dans la base des projets
     $demande=$bdd->prepare("UPDATE projets SET directeur=:directeur,comission=:comission,etat_exploitation=:etat_exploitation,contrat=:contrat,reglement=:reglement,association=:association,signalisation=:signalisation,equipement=:equipement,observation=:observation,date_exploitation=:date_exploitation,etapes=:etapes WHERE id=:id");
     $demande->execute(array(
        'directeur'=>$directeur,
        'etat_exploitation'=>$etat_exploitation,
        'contrat'=>$contrat,
        'date_exploitation'=>$date_exploitation,
        'reglement'=>$reglement,
        'association'=>$association,
        'signalisation'=>$signalisation,
        'equipement'=>$equipement,
        'comission'=>$comission,
        'observation'=>$observation,
        'etapes'=>$etapes,
          'id'=>$get_id,
     ));
        ///*Traitement des fichers*/




        if(!empty($_FILES['liste_beneficiaire']['name']))
        { 
            $taillemax=2097152 ;
            //le nombre octet la taille maximale du photo
            $extensionsvalides=array('jpg','pdf','doc','png','xlsx');
                 //substr ne permet d'ignorer'le premiere caractere de la chaine .jpg=>jpg(car on amis 1)
                 //strchr nous permet de divider la chiane en deux parties
                   $extensionUpload=strtolower(substr(strrchr($_FILES['liste_beneficiaire']['name'],'.'),1)) ;//pour extraire l'extension
                   if(in_array($extensionUpload,$extensionsvalides)){
                    //la variable chemin stocke le chemin dans lequelle sera upload notre photo
                    $chemin="../projets/liste_beneficiaire/".$get_id.".".$extensionUpload;
                    //tmp_name c'est le dossier ou existe le fichier auparavant
                    $resultat=move_uploaded_file($_FILES['liste_beneficiaire']['tmp_name'],$chemin);
                    if($resultat)
                    {
                         //on va ajouter le fichier du profil dans la base de donnes
                    $planning=$get_id.".".$extensionUpload;
                    $connexion=$bdd->prepare(" UPDATE projets SET liste_beneficiaire='$planning' WHERE id=$get_id");
                    $connexion->execute();
                }
                    else{
                        $er_fichier="il y'a eu une erreur durant l'importation du fichier";
                    }
                   }
                   else{
                       $er_fichier="votre fichier doit etre du format pdf,docx,or png";
                   }
    
             
        }

          

        if(!empty($_FILES['pv_reunion']['name']))
        { 
            $taillemax=2097152 ;
            //le nombre octet la taille maximale du photo
            $extensionsvalides=array('jpg','pdf','doc','png','xlsx');
                 //substr ne permet d'ignorer'le premiere caractere de la chaine .jpg=>jpg(car on amis 1)
                 //strchr nous permet de divider la chiane en deux parties
                   $extensionUpload=strtolower(substr(strrchr($_FILES['pv_reunion']['name'],'.'),1)) ;//pour extraire l'extension
                   if(in_array($extensionUpload,$extensionsvalides)){
                    //la variable chemin stocke le chemin dans lequelle sera upload notre photo
                    $chemin="../projets/pv_reunion/".$get_id.".".$extensionUpload;
                    //tmp_name c'est le dossier ou existe le fichier auparavant
                    $resultat=move_uploaded_file($_FILES['pv_reunion']['tmp_name'],$chemin);
                    if($resultat)
                    {
                         //on va ajouter le fichier du profil dans la base de donnes
                    $planning=$get_id.".".$extensionUpload;
                    $connexion=$bdd->prepare(" UPDATE projets SET pv_reunion='$planning' WHERE id=$get_id");
                    $connexion->execute();
                }
                    else{
                        $er_fichier="il y'a eu une erreur durant l'importation du fichier";
                    }
                   }
                   else{
                       $er_fichier="votre fichier doit etre du format pdf,docx,or png";
                   }
    
             
        }



        header('Location:acheves operationels.php');
        exit;

}

$projets=$bdd->prepare("SELECT * FROM projets");
$projets->execute();
$projet=$projets->fetch();

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
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        <div class="ajout-formulaire">
                                    <h3><?=$projet['intitule_projet']?></h3>
                                    <form class="row g-3" action="ajouter_operationels.php?id=<?=$get_id?>" method="POST" enctype="multipart/form-data">
                                    <div class="col-md-4 chantier">
                                        <label for="validationServerUsername" class="form-label">Existence de Directeur d'infrastracture</label><br>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="directeur" id="inlineRadio1" value="1">
                                            <label class="form-check-label" for="inlineRadio1">Oui</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="directeur" id="inlineRadio2" value="0">
                                            <label class="form-check-label" for="inlineRadio2">non</label>
                                        </div>
                                    </div>
                               
                                    <div class="col-md-4 chantier">
                                        <label for="validationServerUsername" class="form-label">Existence de Réglement intérieur</label><br>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="reglement" id="inlineRadio1" value="1">
                                            <label class="form-check-label" for="inlineRadio1">Oui</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="reglement" id="inlineRadio2" value="0" >
                                            <label class="form-check-label" for="inlineRadio2">non</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 chantier">
                                        <label for="validationServerUsername" class="form-label">Existence de l'association des exploitants</label><br>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="association" id="inlineRadio1" value="1" >
                                            <label class="form-check-label" for="inlineRadio1">Oui</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="association" id="inlineRadio2" value="0">
                                            <label class="form-check-label" for="inlineRadio2">non</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 chantier">
                                        <label for="validationServerUsername" class="form-label">Signalitique</label><br>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="signalisation" id="inlineRadio1" value="1">
                                            <label class="form-check-label" for="inlineRadio1">Oui</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="signalisation" id="inlineRadio2" value="0">
                                            <label class="form-check-label" for="inlineRadio2">non</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 chantier">
                                        <label for="validationServerUsername" class="form-label">Equipement de la salle d'exposition</label><br>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="equipement"  id="inlineRadio1" value="1">
                                            <label class="form-check-label" for="inlineRadio1">Oui</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="equipement" id="inlineRadio2" value="0">
                                            <label class="form-check-label" for="inlineRadio2">non</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 chantier">
                                        <label for="validationServerUsername" class="form-label">Existence de comission de gestion</label><br>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="comission" id="inlineRadio1" value="1">
                                            <label class="form-check-label" for="inlineRadio1">Oui</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="comission" id="inlineRadio2" value="0">
                                            <label class="form-check-label" for="inlineRadio2">non</label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-8 chantier">
                                        <label for="validationServerUsername" class="form-label">Existence de contrat d'exploitation</label><br>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="contrat" id="inlineRadio1" value="1">
                                            <label class="form-check-label" for="inlineRadio1">Oui</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="contrat" id="inlineRadio2" value="0">
                                            <label class="form-check-label" for="inlineRadio2">non</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 chantier">
                                        <label for="validationServerUsername" class="form-label">Etat d'exploitation total(en pourcentage)</label>
                                        <div class="input-group has-validation">
                                        <input type="number" step="0.1" class="form-control" id="validationServerUsername" name="etat_exploitation"  aria-describedby="inputGroupPrepend3 validationServerUsernameFeedback">
                                        </div>
                                    </div>
                                    <div class="col-md-4 chantier">
                                        <label for="validationServerUsername" class="form-label">Date de Début d'exploitation</label>
                                        <div class="input-group has-validation">
                                        <input type="date" class="form-control" id="validationServerUsername" name="date_exploitation" aria-describedby="inputGroupPrepend3 validationServerUsernameFeedback">
                                        </div>
                                    </div>
                                
                                    <div class="col-12 field">
                                            <label class="form-check-label" for="invalidCheck3">
                                               Etapes franchies
                                            </label>
                                    </div>
                                    <div class="col-md-12">
                                       <div class="input-group">
                                        <span class="input-group-text">Etapes franchies</span>
                                        <textarea class="form-control" name="etapes" aria-label="With textarea"><?=$projet['etapes']?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-12 field">
                                            <label class="form-check-label" for="invalidCheck3">
                                               Documents à joindre
                                            </label>
                                    </div>
                                    <div class="col-md-12">
                                        <div>
                                        <label for="validationServer02" class="form-label">Pv de Reunion</label>
                                            <input type="file" class="form-control file" name="pv_reunion" aria-label="file example">
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div>
                                        <label for="validationServer02" class="form-label">Liste des bénificiaires</label>
                                            <input type="file" class="form-control file" name="liste_beneficiaire" aria-label="file example">
                                        </div>
                                    </div>
                                    <div class="col-12 field">
                                            <label class="form-check-label" for="invalidCheck3">
                                               Observations
                                            </label>
                                    </div>
                                    <div class="col-md-4"></div>
                                    <div class="col-md-12 chantier ">
                                    <div class="input-group">
                                        <span class="input-group-text">Observations</span>
                                        <textarea class="form-control"  name="observation"><?=str_replace('.','.<br>',$projet['observation'])?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-10"></div>
                                    <div class="col-md-2">
                                    <a><button class="btn btn-primary " type="submit" name="ajouter">Ajouter</button></a>

                                    </div>
                                    </form>
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
        <script src="../assets/demo/datatables-demo.js"></script>
    </body>
</html>


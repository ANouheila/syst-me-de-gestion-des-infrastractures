<?php
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



    /*l etraitement d'ajout*/
$get_id=(int) trim(htmlentities($_GET['id']));
if(!empty($_POST))
{
    extract($_POST);
    $date=date("Y-m-d");
    $valid=TRUE;
    if(isset($_POST['ajouter'])){
        
        try {
            $bdd=new PDO('mysql:host=localhost;dbname=artisanat;charset=utf8','root','');
            }
            catch(Exception $e)
            {
            die('Erreur:'.$e->getMessage());
            }
            //id intitule region province lieu foncier superficie_glob superficie_const
        $foncier=htmlentities(trim($foncier));
        $consistance=htmlentities(trim($consistance));
        //$consistance=addslashes($consistance);
       // $observation=addslashes($observation);
       // $etapes=addslashes($etapes);
        $type=trim($type);
            //on fait la connexion a la base de donnes
            //on enregistre leprojet  dans la base des projets
            $demande=$bdd->prepare("UPDATE projets SET situe_foncier=:foncier,coor_x=:coor_x,coor_y=:coor_y,superficie_glob=:glob,superficie_const=:superficie_const,observation=:observation,type=:type,consistance_physique=:consistance,date_fiche=:date_fiche,etapes=:etapes WHERE id=:id");
            $demande->execute(array(
                'observation'=>$observation,
                'type'=>$type,
                'foncier'=>$foncier,
                'consistance'=>$consistance,
                'glob'=>$superficie_glob,
                'superficie_const'=>$superficie_const,
                 'id'=>$get_id,
                 'date_fiche'=>$date,
                 'coor_x'=>$coor_x,
                 'coor_y'=>$coor_y,
                 'etapes'=>$etapes
                 
            ));

            //retourner les info du projets*/
            $bureaux=array('architecte'=>'architectes','BET'=>'bet','BC'=>'bc','topographe'=>'topographe','Entrepise de construction'=>'entreprise_construction');
            foreach($bureaux as $bureau=>$value){
                    $requet= $bdd->prepare("INSERT INTO $value(id_projet,nom,mail,tele,depense) VALUES(
                        :id,:nom,:mail,:tele,:depense)");
                    $requet->execute(array(
                    'id'=>$get_id,
                    'nom'=>$_POST['nom_'.$value],
                    'depense'=>$_POST['finan_'.$value],
                    'tele'=>$_POST['tele_'.$value],
                    'mail'=>$_POST['mail_'.$value],)
                );
    
            }
    


        if(!empty($_FILES['maps']['name']))
        {
            $taillemax=2097152 ;
            //le nombre octet la taille maximale du photo
            $extensionsvalides=array('jpg','jpeg','gif','png','docx','xlsx','pptx');
             if($_FILES['maps']['size']<=$taillemax){
                 //substr ne permet d'ignorer'le premiere caractere de la chaine .jpg=>jpg(car on amis 1)
                 //strchr nous permet de divider la chiane en deux parties
                   $extensionUpload=strtolower(substr(strrchr($_FILES['maps']['name'],'.'),1)) ;//pour extraire l'extension
                   if(in_array($extensionUpload,$extensionsvalides)){
                    //la variable chemin stocke le chemin dans lequelle sera upload notre photo
                    $chemin="../projets/localisation/".$get_id.".".$extensionUpload;
                    //tmp_name c'est le dossier ou existe le fichier auparavant
                    $resultat=move_uploaded_file($_FILES['maps']['tmp_name'],$chemin);
                    if($resultat)
                    {
                         //on va ajouter la photo du profil dans la base de donnes
                         $avatar=$row['id'].".".$extensionUpload;
                    $connexion=$bdd->prepare("UPDATE projets SET maps='$avatar' WHERE id=$get_id");
                    $connexion->execute();
                }
                    else{
                        $er_photo="il y'a eu une erreur durant l'importation du fichier";
                    }
                   }
                   else{
                       $er_photo="votre photo de profil doit etre du format jpeg,jpg,or gif";
                   }
    
             }
             else{
                 $er_photo="votre photo de profil ne doit pas depasser 2 MEGA OCTET";
             }
        }



        if(!empty($_FILES['cps']['name']))
        {
            $taillemax=2097152 ;
            //le nombre octet la taille maximale du photo
            $extensionsvalides=array('jpg','jpeg','gif','png','pdf','docx','xlsx','pptx');
             if($_FILES['cps']['size']<=$taillemax){
                 //substr ne permet d'ignorer'le premiere caractere de la chaine .jpg=>jpg(car on amis 1)
                 //strchr nous permet de divider la chiane en deux parties
                   $extensionUpload=strtolower(substr(strrchr($_FILES['cps']['name'],'.'),1)) ;//pour extraire l'extension
                   if(in_array($extensionUpload,$extensionsvalides)){
                    //la variable chemin stocke le chemin dans lequelle sera upload notre photo
                    $chemin="../projets/cps/".$get_id.".".$extensionUpload;
                    //tmp_name c'est le dossier ou existe le fichier auparavant
                    $resultat=move_uploaded_file($_FILES['cps']['tmp_name'],$chemin);
                    if($resultat)
                    {
                         //on va ajouter la photo du profil dans la base de donnes
                         $avatar=$get_id.".".$extensionUpload;
                    $connexion=$bdd->prepare("UPDATE projets SET cps='$avatar' WHERE id=$get_id");
                    $connexion->execute();
                }
                    else{
                        $er_photo="il y'a eu une erreur durant l'importation du fichier";
                    }
                   }
                   else{
                       $er_photo="votre photo de profil doit etre du format jpeg,jpg,or gif";
                   }
    
             }
             else{
                 $er_photo="votre photo de profil ne doit pas depasser 2 MEGA OCTET";
             }
        }


        if(!empty($_FILES['maps']['name']))
        {
            $taillemax=2097152 ;
            //le nombre octet la taille maximale du photo
            $extensionsvalides=array('jpg','jpeg','gif','png','pdf','docx','xlsx','pptx');
             if($_FILES['maps']['size']<=$taillemax){
                 //substr ne permet d'ignorer'le premiere caractere de la chaine .jpg=>jpg(car on amis 1)
                 //strchr nous permet de divider la chiane en deux parties
                   $extensionUpload=strtolower(substr(strrchr($_FILES['maps']['name'],'.'),1)) ;//pour extraire l'extension
                   if(in_array($extensionUpload,$extensionsvalides)){
                    //la variable chemin stocke le chemin dans lequelle sera upload notre photo
                    $chemin="../projets/plan_masse/".$get_id.".".$extensionUpload;
                    //tmp_name c'est le dossier ou existe le fichier auparavant
                    $resultat=move_uploaded_file($_FILES['maps']['tmp_name'],$chemin);
                    if($resultat)
                    {
                         //on va ajouter la photo du profil dans la base de donnes
                         $avatar=$get_id.".".$extensionUpload;
                    $connexion=$bdd->prepare("UPDATE projets SET plan_masse='$avatar' WHERE id=$get_id");
                    $connexion->execute();
                }
                    else{
                        $er_photo="il y'a eu une erreur durant l'importation du fichier";
                    }
                   }
                   else{
                       $er_photo="votre photo de profil doit etre du format jpeg,jpg,or gif";
                   }
    
             }
             else{
                 $er_photo="votre photo de profil ne doit pas depasser 2 MEGA OCTET";
             }
        }


        
        if(!empty($_FILES['site']['name']))
        {
            $taillemax=2097152 ;
            //le nombre octet la taille maximale du photo
            $extensionsvalides=array('jpg','jpeg','gif','png');
             if($_FILES['site']['size']<=$taillemax){
                 //substr ne permet d'ignorer'le premiere caractere de la chaine .jpg=>jpg(car on amis 1)
                 //strchr nous permet de divider la chiane en deux parties
                   $extensionUpload=strtolower(substr(strrchr($_FILES['site']['name'],'.'),1)) ;//pour extraire l'extension
                   if(in_array($extensionUpload,$extensionsvalides)){
                    //la variable chemin stocke le chemin dans lequelle sera upload notre photo
                    $chemin="../projets/site/".$get_id.".".$extensionUpload;
                    //tmp_name c'est le dossier ou existe le fichier auparavant
                    $resultat=move_uploaded_file($_FILES['site']['tmp_name'],$chemin);
                    if($resultat)
                    {
                         //on va ajouter la photo du profil dans la base de donnes
                         $avatar=$get_id.".".$extensionUpload;
                    $connexion=$bdd->prepare("UPDATE projets SET site='$avatar' WHERE id=$get_id");
                    $connexion->execute();
                }
                    else{
                        $er_photo="il y'a eu une erreur durant l'importation du fichier";
                    }
                   }
                   else{
                       $er_photo="votre photo de profil doit etre du format jpeg,jpg,or gif";
                   }
    
             }
             else{
                 $er_photo="votre photo de profil ne doit pas depasser 2 MEGA OCTET";
             }
        }


        //traitement du plan

        if(!empty($_FILES['plan']['name']))
        {
            $taillemax=2097152 ;
            //le nombre octet la taille maximale du photo
            $extensionsvalides=array('pdf','doc','jpg','jpeg','gif','png');
             if($_FILES['plan']['size']<=$taillemax){
                 //substr ne permet d'ignorer'le premiere caractere de la chaine .jpg=>jpg(car on amis 1)
                 //strchr nous permet de divider la chiane en deux parties
                   $extensionUpload=strtolower(substr(strrchr($_FILES['plan']['name'],'.'),1)) ;//pour extraire l'extension
                   if(in_array($extensionUpload,$extensionsvalides)){
                    //la variable chemin stocke le chemin dans lequelle sera upload notre photo
                    $chemin="../projets/plans/".$get_id.".".$extensionUpload;
                    //tmp_name c'est le dossier ou existe le fichier auparavant
                    $resultat=move_uploaded_file($_FILES['plan']['tmp_name'],$chemin);
                    if($resultat)
                    {
                         //on va ajouter la photo du profil dans la base de donnes
                         $avatar=$row['id'].".".$extensionUpload;
                    $connexion=$bdd->prepare(" UPDATE projets SET plans='$avatar' WHERE id=?");
                    $connexion->execute(array($get_id));
                }
                    else{
                        $er_photo="il y'a eu une erreur durant l'importation du fichier";
                    }
                   }
                   else{
                       $er_photo="votre photo de profil doit etre du format jpeg,jpg,or gif";
                   }
    
             }
             else{
                 $er_photo="votre photo de profil ne doit pas depasser 2 MEGA OCTET";
             }
        }


        if(!empty($_FILES['etude_technique']['name']))
        {
            $taillemax=2097152 ;
            //le nombre octet la taille maximale du photo
            $extensionsvalides=array('pdf','doc','jpg','jpeg','gif','png','docx','xlsx','pptx');
             if($_FILES['plan']['size']<=$taillemax){
                 //substr ne permet d'ignorer'le premiere caractere de la chaine .jpg=>jpg(car on amis 1)
                 //strchr nous permet de divider la chiane en deux parties
                   $extensionUpload=strtolower(substr(strrchr($_FILES['etude_technique']['name'],'.'),1)) ;//pour extraire l'extension
                   if(in_array($extensionUpload,$extensionsvalides)){
                    //la variable chemin stocke le chemin dans lequelle sera upload notre photo
                    $chemin="../projets/etude_technique/".$get_id.".".$extensionUpload;
                    //tmp_name c'est le dossier ou existe le fichier auparavant
                    $resultat=move_uploaded_file($_FILES['etude_technique']['tmp_name'],$chemin);
                    if($resultat)
                    {
                         //on va ajouter la photo du profil dans la base de donnes
                         $avatar=$get_id.".".$extensionUpload;
                    $connexion=$bdd->prepare(" UPDATE projets SET etude_technique='$avatar' WHERE id=?");
                    $connexion->execute(array($get_id));
                }
                    else{
                        $er_photo="il y'a eu une erreur durant l'importation du fichier";
                    }
                   }
                   else{
                       $er_photo="votre photo de profil doit etre du format jpeg,jpg,or gif";
                   }
    
             }
             else{
                 $er_photo="votre photo de profil ne doit pas depasser 2 MEGA OCTET";
             }
        }


        
        if(!empty($_FILES['etude_geotechnique']['name']))
        {
            $taillemax=2097152 ;
            //le nombre octet la taille maximale du photo
            $extensionsvalides=array('pdf','doc','jpg','jpeg','gif','png','docx','xlsx','pptx');
             if($_FILES['etude_geotechnique']['size']<=$taillemax){
                 //substr ne permet d'ignorer'le premiere caractere de la chaine .jpg=>jpg(car on amis 1)
                 //strchr nous permet de divider la chiane en deux parties
                   $extensionUpload=strtolower(substr(strrchr($_FILES['etude_geotechnique']['name'],'.'),1)) ;//pour extraire l'extension
                   if(in_array($extensionUpload,$extensionsvalides)){
                    //la variable chemin stocke le chemin dans lequelle sera upload notre photo
                    $chemin="../projets/etude_geotechnique/".$get_id.".".$extensionUpload;
                    //tmp_name c'est le dossier ou existe le fichier auparavant
                    $resultat=move_uploaded_file($_FILES['etude_geotechnique']['tmp_name'],$chemin);
                    if($resultat)
                    {
                         //on va ajouter la photo du profil dans la base de donnes
                         $avatar=$get_id.".".$extensionUpload;
                    $connexion=$bdd->prepare(" UPDATE projets SET etude_geotechnique='$avatar' WHERE id=?");
                    $connexion->execute(array($get_id));
                }
                    else{
                        $er_photo="il y'a eu une erreur durant l'importation du fichier";
                    }
                   }
                   else{
                       $er_photo="votre photo de profil doit etre du format jpeg,jpg,or gif";
                   }
    
             }
             else{
                 $er_photo="votre photo de profil ne doit pas depasser 2 MEGA OCTET";
             }
        }





       header('Location:etude.php');
      exit;
    }
      }
      $requet=$bdd->prepare("SELECT * FROM projets WHERE id=?");
      $requet->execute(array($get_id));
      $projet=$requet->fetch();
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
                        <div class="ajout-formulaire">
                        <h3><?=$projet['intitule_projet']?></h3>
                        <form class="row g-3" method="post" action="ajouter_etude.php?id=<?=$get_id?>" enctype="multipart/form-data">
                            <div class="col-12 field">
                                <label class="form-check-label" for="invalidCheck3">
                                    Coordonnées Du Projet
                                </label>
                            </div>
                            <div class="col-md-3">
                                <label for="validationServer01" class="form-label">Type de l'infrastracture</label>
                                <input type="text" class="form-control" id="validationServer01" name="type">
                            </div>
                            <div class="col-md-3">
                                <label for="validationServer01" class="form-label">Situation du foncier</label>
                                <input type="text" class="form-control" id="validationServer01" name="foncier">
                            </div>
                            
                            <div class="col-md-3">
                            <label for="validationServer02" class="form-label">Superficie Globale</label>
                              <div class="input-group has-validation">
                                <span class="input-group-text" id="inputGroupPrepend2">m<sup>2</sup></span>
                                <input type="number" class="form-control" id="validationServer02" name="superficie_glob">
                               </div>
                            </div>
                            <div class="col-md-3">
                                <label for="validationServerUsername" class="form-label">Superficie Construite</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text" id="inputGroupPrepend3">m<sup>2</sup></span>
                                    <input type="number" class="form-control" id="validationServerUsername" name="superficie_const" >
                                </div>
                            </div>
                            <div class="col-12 field">
                                <label class="form-check-label" for="invalidCheck3">
                                    Montage institutionnel et financier
                                </label>
                            </div>    
                            <?php 
                            $bureaux=array('architecte'=>'architectes','BET'=>'bet','BC'=>'bc','topographe'=>'topographe','Entrepise de construction'=>'entreprise_construction');
                            foreach($bureaux as $bureau=>$value){
                            ?>
                            <div class="col-md-3">
                                <input type="text" class="form-control" id="validationServer01" name="nom_<?=$value?>" placeholder="<?=$bureau?>">
                            </div>
                            <div class="col-md-3">
                                <div class="input-group has-validation">
                                <span class="input-group-text" id="inputGroupPrepend3">Dhs</span>
                                <input type="text" class="form-control" id="validationServerUsername" name="finan_<?=$value?>" placeholder="<?php if($value=='entreprise_construction') { echo 'Montant marché';}else { echo 'Montant horaire';}?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control" id="validationServer02" name="tele_<?=$value?>" placeholder="N° Télephone">
                            </div>
                            <div class="col-md-3">
                                <input type="mail" class="form-control" id="validationServer02" name="mail_<?=$value?>" placeholder="adresse mail" >
                            </div> 
                            <?php }?>
                            <div class="col-12 field">
                                <label class="form-check-label" for="invalidCheck3">
                                   Consistance physique
                                </label>
                            </div>            
                            <div class="col-md-12">
                                <div class="input-group">
                                <span class="input-group-text">Consistance physique</span>
                                <textarea class="form-control" name="consistance" aria-label="With textarea" required></textarea>
                                </div>
                            </div>


                            <div class="col-12 field">
                                <label class="form-check-label" for="invalidCheck3">
                                    Etapes Franchies
                                </label>
                            </div>            
                            <div class="col-md-12">
                                <div class="input-group">
                                <span class="input-group-text">Etapes franchies</span>
                                <textarea class="form-control" name="etapes" aria-label="With textarea" required ><?=$projet['etapes']?></textarea>
                                </div>
                            </div>
                            

                            <div class="col-12 field">
                                            <label class="form-check-label" for="invalidCheck3">
                                                Localisation
                                            </label>
                                        </div>
                                        <div class="col-md-6">
                                            <div>
                                            <label for="validationServer02" class="form-label">Plan de masse</label>
                                                <input type="file" class="form-control file" name="maps" aria-label="file example">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                                <div>
                                                <label for="validationServer02" class="form-label">le site (Format image)</label>
                                                    <input type="file" class="form-control file" name="site" aria-label="file example" required>
                                                </div>
                                        </div>
                                        <div class="col-md-6 obs">
                                                <div>
                                                <label for="validationServer02" class="form-label">les coordonnées Lambert</label>
                                                    <input type="number" step="0.1" class="form-control" name="coor_x" aria-label="file example" placeholder="x">
                                                </div>
                                        </div>
                                        <div class="col-md-6">
                                                <div>
                                                <br><br>
                                                    <input type="number" step="0.1" class="form-control" name="coor_y" aria-label="file example" placeholder="y">
                                                </div>
                                        </div>

                            <div class="col-12 field">
                                <label class="form-check-label" for="invalidCheck3">
                                    Documents Associés
                                </label>
                            </div>

          
                            <div class="col-md-12">
                                    <div>
                                    <label for="validationServer02" class="form-label">Les plans Du projet(format image)</label>
                                        <input type="file" class="form-control file" name="plan" aria-label="file example" required>
                                    </div>
                            </div>
                            <div class="col-md-12">
                                    <div>
                                    <label for="validationServer02" class="form-label">Rapport des etudes techniques</label>
                                        <input type="file" class="form-control file" name="etude_technique" aria-label="file example">
                                    </div>
                            </div>
                            <div class="col-md-12">
                                    <div>
                                    <label for="validationServer02" class="form-label">Rapport des etudes Geotechnique</label>
                                        <input type="file" class="form-control file" name="etude_geotechnique" aria-label="file example">
                                    </div>
                            </div>
                            <div class="col-md-12">
                                        <div>
                                            <label for="validationServer02" class="form-label">Cahier de prescriptions spéciales (CPS)</label>
                                                <input type="file" class="form-control file" name="cps" aria-label="file example">
                                            </div>
                             </div>
                            <div class="col-12 field">
                                <label class="form-check-label" for="invalidCheck3">
                                    Observations
                                </label>
                            </div>
                            <div class="col-md-12">
                                <div class="input-group">
                                <span class="input-group-text">Observations</span>
                                <textarea class="form-control" name="observation" aria-label="With textarea" ></textarea>
                                </div>
                            </div>
                           <div class="col-md-10"></div>
                            <div class="col-md-2">
                                <a><button class="btn btn-primary convention" type="submit" name="ajouter">Ajouter</button></a>
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



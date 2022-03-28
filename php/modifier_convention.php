<?php 
session_start();
if(!isset($_SESSION['id']))
{
  header('Location:index.php');
  exit;
}
$get_id=(int) trim(htmlentities($_GET['id']));//on recupere l'id de la categorie pour lister les topics qui appartient a cette catégorie
//Le cas d'une modification

try {
    $bdd=new PDO('mysql:host=localhost;dbname=artisanat;charset=utf8','root','');
    }
    catch(Exception $e)
    {
    die('Erreur:'.$e->getMessage());
    }
if(isset($_POST['modifier'])){
     extract($_POST);
    $intitule=trim($intitule);

        //on fait la connexion a la base de donnes
        //on enregistre leprojet  dans la base des projets
        $demande=$bdd->prepare("UPDATE projets SET intitule_projet=:intitule,date_signature=:date_signa,budget=:budget,budget_minis=:budget_mini,region=:region,province=:province,lieu=:lieu,observation=:observation,objectifs=:objectifs,etapes=:etapes WHERE id=:id");
        $demande->execute(array(
            'observation'=>$observation,
            'etapes'=>$etapes,
            'objectifs'=>$objectif,
            'intitule'=>$intitule,
            'date_signa'=>$date_signa,
            'budget'=>$budget,
            'budget_mini'=>$budget_mini,
            'region'=>$region,
            'province'=>$province,
            'lieu'=>$lieu,
             'id'=>$get_id
        ));
        //retourner les info du projets*/
        $requet= $bdd->prepare('UPDATE partenaires SET nom=:nom,mail=:mail,telephone=:telephone,siege=:siege,contribution=:contribution,montant_verse=:montant WHERE id=:id');
        //ici necessairement les trois partenaires
        $partenaires=$bdd->prepare("SELECT * FROM partenaires WHERE id_projet=?");
        $partenaires->execute(array($get_id));
        $nbr=0;
        //afin de connaitre le nombre total des partenaires
        while($partenaire=$partenaires->fetch()) {
            $nbr+=1;
        }
        $partenaires=$bdd->prepare("SELECT * FROM partenaires WHERE id_projet=?");
        $partenaires->execute(array($get_id));
        $partenaire=$partenaires->fetch();
 
        $partenariat=array($partenaire['id']);
        for($i=1;$i<$nbr;$i++) {
            $partenariat[$i]=$partenariat[$i-1]+1;
        }
  
        $i=0;
        foreach($partenariat as $cle){
            $requet->execute(array(
                'nom'=>$nom_partenaire[$i],
                'mail'=>$mail[$i],
                'telephone'=>$telephone[$i],
                'siege'=>$siege_social[$i],
                'contribution'=>$contribution_financiere[$i],
                'montant'=>$montant[$i],
                'id'=>$cle));
                $i++;
        }

              //le traitement Du Document
    if(!empty($_FILES['convention']['name']))
    {
        $taillemax=2097152 ;
        //le nombre octet la taille maximale du photo
        $extensionsvalides=array('jpg','pdf','docx','png');
             //substr ne permet d'ignorer'le premiere caractere de la chaine .jpg=>jpg(car on amis 1)
             //strchr nous permet de divider la chiane en deux parties
               $extensionUpload=strtolower(substr(strrchr($_FILES['convention']['name'],'.'),1)) ;//pour extraire l'extension
               if(in_array($extensionUpload,$extensionsvalides)){
                //la variable chemin stocke le chemin dans lequelle sera upload notre photo
                $chemin="../projets/convention/".$get_id.".".$extensionUpload;
                //tmp_name c'est le dossier ou existe le fichier auparavant
                $resultat=move_uploaded_file($_FILES['convention']['tmp_name'],$chemin);
                if($resultat)
                {
                     //on va ajouter le fichier du profil dans la base de donnes
                $convention=$get_id.".".$extensionUpload;
                $connexion=$bdd->prepare(" UPDATE projets SET doc_convention='$convention' WHERE id=$get_id");
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
        header('Location:convention.php');
        exit;


      

}

$projets=$bdd->prepare("SELECT * FROM projets WHERE id='$get_id'");
$projets->execute();
$projet=$projets->fetch();
$regions=array("L'Oriental","Marrakech-Safi","Drâa-Tafilalet","Fès-Meknès","Guelmim-oued Noun","Tanger-Tétouan-Al Hoceima","Souss-Massa","Casablanca-Settat","Dakhla-Oued Eddahab","Beni Mellal-Khénifra","Rabat-Salé-Kénitra","Laâyoune-Sakia Al Hamra");

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
        <title>Dashboard - SB Admin</title>
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
                            <a class="nav-link" href="etude.php">
                                <div class="sb-nav-link-icon"><i class="fa fa-book" aria-hidden="true"></i></div>
                                Projets En cours d'Etude
                            </a>
                            <a class="nav-link" href="chnatier.php">
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
                                  <form class="row g-3" method="post" action="modifier_convention.php?id=<?=$get_id?>" enctype="multipart/form-data">
                                    <div class="col-12 field">
                                            <label class="form-check-label" for="invalidCheck3">
                                                Coordonnées Du Projet
                                            </label>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="validationServer01" class="form-label">Intitulé Du Projet</label>
                                        <input type="text" class="form-control" name="intitule" id="validationServer01" value="<?=$projet['intitule_projet']?>" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="validationServerUsername" class="form-label">Région</label>
                                        <div class="input-group has-validation">
                                        <select id="region" onchange="choixRegion(this)" name="region">
                                            <option value="<?=$projet['region']?>" selected><?=$projet['region']?></option>
                                            <?php foreach($regions as $key => $value) {
                                                if($value!=$projet['region']) {
                                                ?>
                                            <option value="<?=$value?>"><?=$value?></option>
                                            <?php } }?>
                                        </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                                    <label for="validationServer02" class="form-label">Province</label>
                                                    <select id="provinces"  name="province" required>
                                                    <option value="<?=$projet['province']?>" selected><?=$projet['province']?></option>
                                                        <?php foreach($provinces["L’Oriental"] as $valeur) { 
                                                             if($valeur!=$projet['province']) {
                                                            ?>
                                                        <option value="<?=valeur?>" ><?=$valeur?></option>
                                                        <?php }}?>
                                                   </select>
                                    </div>
                                   
                                    <div class="col-md-3">
                                        <label for="validationServer05" class="form-label">Lieu</label>
                                        <input type="text" class="form-control" id="validationServer05" name="lieu" value="<?=$projet['lieu']?>" required>
                                    </div>
                                    <div class="col-md-4">
                                                    <label for="validationServer02" class="form-label">Convention Signé le:</label>
                                                    <input type="date" class="form-control" name="date_signa" value="<?=$projet['date_signature']?>" id="validationServer02"  required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="validationServerUsername" class="form-label">Budget Total:</label>
                                                    <div class="input-group has-validation">
                                                    <span class="input-group-text" id="inputGroupPrepend3">Mdh</span>
                                                    <input type="number" step="0.01" class="form-control" name="budget" value="<?=$projet['budget']?>" id="validationServerUsername"  aria-describedby="inputGroupPrepend3 validationServerUsernameFeedback" required>
                                                
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="validationServerUsername" class="form-label">Contribution Du ministere</label>
                                                    <div class="input-group has-validation">
                                                    <span class="input-group-text" id="inputGroupPrepend3">Mdh</span>
                                                    <input type="number" step="0.01" class="form-control" name="budget_mini" value="<?=$projet['budget_minis']?>" id="validationServerUsername" aria-describedby="inputGroupPrepend3 validationServerUsernameFeedback" required>
                                                    
                                                    </div>
                                                </div>
                         
                                    <div class="col-12 field">
                                            <label class="form-check-label" for="invalidCheck3">
                                                Partenariat
                                            </label>
                                        </div>
                                    <?php
                                    $partenaires=$bdd->prepare("SELECT * FROM partenaires WHERE id_projet=?");
                                            $partenaires->execute(array($projet['id']));
                                            ?>
                                            <?php while($partenaire=$partenaires->fetch()){
                                                ?>
                                    <div class="col-md-2">
                                        <label for="validationServer01" class="form-label">Nom du Partenaire</label>
                                        <input type="text" class="form-control" name="nom_partenaire[]" value="<?=$partenaire['nom']?>" id="validationServer01">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="validationServer02" class="form-label">siege Social</label>
                                        <input type="text" class="form-control" name="siege_social[]" value="<?=$partenaire['siege']?>" id="validationServer02" >
                                    </div>
                                    <div class="col-md-2">
                                        <label for="validationServer02" class="form-label">Mail</label>
                                        <input type="mail" class="form-control" name="mail[]" value="<?=$partenaire['mail']?>"  id="validationServer02" >
                                    </div>
                                    <div class="col-md-2">
                                        <label for="validationServer02" class="form-label">Télephone</label>
                                        <input type="text" class="form-control" name="telephone[]" value="<?=$partenaire['telephone']?>" id="validationServer02" >
                                    </div>
                                    <div class="col-md-2">
                                        <label for="validationServerUsername" class="form-label">Contribution</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text" id="inputGroupPrepend3">Mdh</span>
                                            <input type="number" step="0.01" class="form-control" name="contribution_financiere[]" value="<?=$partenaire['contribution']?>" id="validationServerUsername" aria-describedby="inputGroupPrepend3 validationServerUsernameFeedback">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="validationServerUsername" class="form-label">Montant Versé</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text" id="inputGroupPrepend3">Mdh</span>
                                            <input type="number" step="0.01" class="form-control" name="montant[]" id="validationServerUsername" value="<?=$partenaire['montant_verse']?>" aria-describedby="inputGroupPrepend3 validationServerUsernameFeedback">
                                        </div>
                                    </div>
                          
                                    <?php }?>
                                    <div class="col-12 field">
                                            <label class="form-check-label" for="invalidCheck3">
                                                Objectifs Du Projet
                                            </label>
                                         </div> 
                                    <div class="col-md-12">
                                            <div class="input-group">
                                            <span class="input-group-text">objectifs</span>
                                            <textarea class="form-control" name="objectif" aria-label="With textarea" required><?=str_replace(".<br>",".",$projet['objectifs'])?></textarea>
                                            </div>
                                        </div>
                                        <div class="col-12 field">
                                            <label class="form-check-label" for="invalidCheck3">
                                                Etapes Franchies
                                            </label>
                                         </div> 
                                        <div class="col-md-12 obs">
                                            <div class="input-group">
                                            <span class="input-group-text">Etapes franchies</span>
                                            <textarea class="form-control" name="etapes" aria-label="With textarea" required><?=str_replace(".<br>",".",$projet['etapes'])?></textarea>
                                            </div>
                                        </div>
                                    <div class="col-md-12">
                                        <div class="col-12 field">
                                            <label class="form-check-label" for="invalidCheck3">
                                                Document Du convention
                                            </label>
                                        </div>
                                            <div>
                                            <label for="validationServer02" class="form-label">Document de Convention</label>
                                                <input type="file" class="form-control file" name="convention" aria-label="file example">
                                            </div>
                                    </div>
                                    <div class="col-12 field">
                                            <label class="form-check-label" for="invalidCheck3">
                                               Observations
                                            </label>
                                         </div> 
                                    <div class="col-md-12 obs">
                                            <div class="input-group">
                                            <span class="input-group-text">Observations</span>
                                            <textarea class="form-control" name="observation" aria-label="With textarea" ><?=str_replace(".<br>",".",$projet['observation'])?></textarea>
                                            </div>
                                    </div>
                                    <div class="col-md-10"></div>
                                    <div class="col-md-2">
                                        <button class="btn btn-primary convention" name="modifier" type="submit">Modifier</button>
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
    
    <script>
        var province= {"L'Oriental": ["Oujda-Angad","Nador","Driouch","Jerada","Berkan","Taourirt","Guercif","Figuig"],
"Marrakech-Safi":["Marrakech","Chichaoua","Al Haouz","Kelâa des Sraghna","Essaouira","Rehamna","Safi","Youssoufia"],
"Drâa-Tafilalet":["Errachidia","Ouarzazate","Midelt","Tinghir","Zagora​"],
"Fès-Meknès":["Fès","Meknès","El Hajeb","Ifrane","Moulay Yacoub","Sefrou","Boulemane","Taounate","Taza"],
"Guelmim-oued Noun":["Guelmim","Assa-Zag","Tan-Tan","Sidi Ifni"],
"Tanger-Tétouan-Al Hoceima":["Tanger-Assilah","M'diq-Fnideq","Tétouan","Fahs-Anjra","Larache","Al Hoceima","Chefchaouen","Ouazzane"],
"Souss-Massa":["Agadir Ida-Ou-Tanane","Inezgane-Aït Melloul","Chtouka-Aït Baha","Taroudannt","Tiznit","Tata"],
"Casablanca-Settat":["Casablanca","Mohammadia","El Jadida","Nouaceur","Médiouna","Benslimane","Berrechid","Settat","Sidi Bennour"],
"Beni Mellal-Khénifra":["Béni Mellal","Azilal","Fquih Ben Salah","Khénifra","Khouribga​"],
"Rabat-Salé-Kénitra":["Rabat","Salé","Skhirate-Témara","Kénitra","Khémisset","Sidi Kacem","Sidi Slimane"],
"Laâyoune-Sakia Al Hamra": ["Laâyoune","Boujdour","Tarfaya","Es-Semara​"],
"Dakhla-Oued Eddahab" : ["Oued Ed Dahab","Aousserd"]

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
</html>
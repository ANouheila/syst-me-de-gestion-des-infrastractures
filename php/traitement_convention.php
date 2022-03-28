<?php
session_start();
if(!isset($_SESSION['id']))
{
  header('Location:index.php');
  exit;
}
if(!empty($_POST))
{
    extract($_POST);
    $valid=TRUE;
    if(isset($_POST['ajouter'])){
        try {
            $bdd=new PDO('mysql:host=localhost;dbname=artisanat;charset=utf8','root','');
            }
            catch(Exception $e)
            {
            die('Erreur:'.$e->getMessage());
            }
        $intitule=trim($intitule);
        $intitule=addslashes($intitule);
        $observation=addslashes($observation);
        $objectif=addslashes($objectif);
        $etapes=addslashes($etapes);


            //on fait la connexion a la base de donnes
            //on enregistre leprojet  dans la base des projets
            $demande="INSERT INTO projets (intitule_projet,date_signature,budget,budget_minis,region,province,lieu,observation,objectifs,etapes) VALUES('$intitule','$date_signa',$budget,$budget_mini,'$region','$province','$lieu','$observation','$objectif','$etapes')";
            $query=$bdd->query($demande);
            //retourner les info du projets*/
            $requet=$bdd->prepare("SELECT * FROM projets WHERE intitule_projet=?");
            $requet->execute(array($intitule));
            $row=$requet->fetch();

            /*modifier les partenaire*/
            $requet= $bdd->prepare('INSERT INTO partenaires(id_projet,nom,mail,telephone,siege,contribution,montant_verse) VALUES
            (:id,:nom,:mail,:telephone,:siege,:contribution,:montant)');
            for($i=0;$i<count($nom_partenaire);$i++) {
                if($nom_partenaire[$i]!='') {
                      $requet->execute(array(
                    'id'=>$row['id'],
                    'nom'=>$nom_partenaire[$i],
                    'mail'=>$mail[$i],
                    'telephone'=>$telephone[$i],
                    'siege'=>$siege_social[$i],
                    'contribution'=>$contribution_financiere[$i],
                    'montant'=>$montant[$i]));
                }
              
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
                    $chemin="../projets/convention/".$row['id'].".".$extensionUpload;
                    //tmp_name c'est le dossier ou existe le fichier auparavant
                    $resultat=move_uploaded_file($_FILES['convention']['tmp_name'],$chemin);
                    if($resultat)
                    {
                         //on va ajouter le fichier du profil dans la base de donnes
                    $convention=$row['id'].".".$extensionUpload;
                    $connexion=$bdd->prepare(" UPDATE projets SET doc_convention='$convention' WHERE id={$row['id']}");
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
                               <h3>Ajouter un Projet En cours De mobilisation Foncier</h3>
                                <form class="row g-3" method="post" action="traitement_convention.php" enctype="multipart/form-data">
                                        <div class="col-12 field">
                                            <label class="form-check-label" for="invalidCheck3">
                                                Coordonnées Du Projet
                                            </label>
                                        </div>
                                                <div class="col-md-3">
                                                    <label for="validationServer01" class="form-label">Intitulé Du Projet</label>
                                                    <input type="text" class="form-control" name="intitule" id="validationServer01" required>
                                                </div>
                                    <div class="col-md-3">
                                        <label for="validationServerUsername" class="form-label">Région</label>
                                        <select id="region" onchange="choixRegion(this)" name="region" required>
                                            <?php foreach($regions as $key => $value) {?>
                                            <option value="<?=$value?>"><?=$value?></option>
                                            <?php }?>
                                        </select>
                                    
                                    </div>
                                                <div class="col-md-3">
                                                    <label for="validationServer02" class="form-label">Province</label>
                                                    <select id="provinces"  name="province" required>
                                                        <?php foreach($provinces["L’Oriental"] as $valeur) {?>
                                                        <option value="<?=valeur?>" >  <?=$valeur?></option>
                                                        <?php }?>
                                                   </select>
                                    </div>
                                   
                                    <div class="col-md-3">
                                        <label for="validationServer05" class="form-label">Lieu</label>
                                        <input type="text" class="form-control" id="validationServer05" name="lieu" required>
                                    </div>
                                    <div class="col-md-4">
                                                    <label for="validationServer02" class="form-label">Convention Signé le:</label>
                                                    <input type="date" class="form-control" name="date_signa" id="validationServer02"  required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="validationServerUsername" class="form-label">Total Ressources(en Mdh):</label>
                                                    <div class="input-group has-validation">
                                                    <span class="input-group-text" id="inputGroupPrepend3">Mdh</span>
                                                    <input type="number" step="0.01" class="form-control" id="somme" name="budget" id="validationServerUsername"  aria-describedby="inputGroupPrepend3 validationServerUsernameFeedback" required>
                                                
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="validationServerUsername" class="form-label">Contribution Du ministere</label>
                                                    <div class="input-group has-validation">
                                                    <span class="input-group-text" id="inputGroupPrepend3">MDh</span>
                                                    <input type="number" step="0.01" class="form-control"  id="ministere" name="budget_mini" id="validationServerUsername" aria-describedby="inputGroupPrepend3 validationServerUsernameFeedback" required>
                                                    
                                                    </div>
                                                </div>
                         
                                        <div class="col-12 field">
                                            <label class="form-check-label" for="invalidCheck3">
                                                Partenariat
                                            </label>
                                        </div>
                                        <?php for($i=1;$i<=5;$i++) {?>
                                        <div class="col-md-2">
                                            <label for="validationServer01" class="form-label">Nom du Partenaire</label>
                                            <input type="text"  class="form-control " name="nom_partenaire[]" id="validationServer01">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="validationServer02" class="form-label">siege Social</label>
                                            <input type="text" class="form-control" name="siege_social[]" id="validationServer02" >
                                        </div>
                                        <div class="col-md-2">
                                            <label for="validationServer02" class="form-label">Mail</label>
                                            <input type="mail" class="form-control" name="mail[]" id="validationServer02" >
                                        </div>
                                        <div class="col-md-2">
                                            <label for="validationServer02" class="form-label">Télephone</label>
                                            <input type="text" class="form-control" name="telephone[]" id="validationServer02" >
                                        </div>
                                        <div class="col-md-2">
                                            <label for="validationServerUsername" class="form-label">Contribution</label>
                                            <div class="input-group has-validation">
                                            <span class="input-group-text" id="inputGroupPrepend3"> Mdh</span>
                                            <input type="number" step="0.01" class="form-control x<?=$i?>" id="x<?=$i?>"    name="contribution_financiere[]" id="validationServerUsername" aria-describedby="inputGroupPrepend3 validationServerUsernameFeedback">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="validationServerUsername" class="form-label">Montant Versé</label>
                                            <div class="input-group has-validation">
                                            <span class="input-group-text" id="inputGroupPrepend3">Mdh</span>
                                            <input type="number" step="0.01" class="form-control" name="montant[]" id="validationServerUsername" aria-describedby="inputGroupPrepend3">
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
                                            <textarea class="form-control" name="objectif" aria-label="With textarea" required></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-12 obs">
                                            <div class="input-group">
                                            <span class="input-group-text">Etapes franchies</span>
                                            <textarea class="form-control" name="etapes" aria-label="With textarea" required></textarea>
                                            </div>
                                        </div>
                                
                                        <div class="col-md-12 obs">
                                            <div class="input-group">
                                            <span class="input-group-text">Observations</span>
                                            <textarea class="form-control" name="observation" aria-label="With textarea" ></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                                <div class="col-12 field">
                                                    <label class="form-check-label" for="invalidCheck3">
                                                     Document Du Convention
                                                    </label>
                                                </div>
                                                <input type="file" class="form-control file" name="convention" aria-label="file example">
                                        </div>
                                        <div class="col-md-10"></div>
                                        <div class="col-md-2">
                                            <button class="btn btn-primary convention" name="ajouter" type="submit">Ajouter</button>
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
"Laâyoune-Sakia Al Hamra": ["","Laâyoune","Boujdour","Tarfaya","Es-Semara​"],
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




function updateValue(e) {
  log.textContent = e.target.value;
}


	
} 







    //verififier si le total des resources ne depasse pas lle total

    const l = document.querySelector('.x1');
    l.addEventListener('change', verif_champ);
   
    var x2= document.querySelector('.x2');
    x2.addEventListener('change', verif_champ);

    var x3= document.querySelector('.x3');
    x3.addEventListener('change', verif_champ);
    var x4= document.querySelector('.x4');
    x4.addEventListener('change', verif_champ);
    var x5= document.querySelector('.x5');
    x5.addEventListener('change', verif_champ);
    function verif_champ()
    {     var total_c=Number(document.getElementById("x1").value);
              total_c=total_c+Number(document.getElementById("x2").value);
              total_c=total_c+Number(document.getElementById("x3").value);
              total_c=total_c+Number(document.getElementById("x4").value);
              total_c=total_c+Number(document.getElementById("x5").value);
              var somme = Number(document.getElementById("somme").value);
              var ministere=Number(document.getElementById("ministere").value);

       if(total_c>(somme-ministere)) {
            alert("la contribution du partenaire ne doit pas dépasser le total des ressources");        
        }
    }

    </script>
    </body>
</html>

<!---
                           
                    ------------>
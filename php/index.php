<?php 
session_start();
 //s'il ya une ssesion alors on retourne plus sur cette page
 if(isset($_SESSION['id']))
 {
     header('Location:accueil.php');
     exit;
 }
if(!empty($_POST))
{
  extract($_POST);
  $valid=true;
  if(isset($_POST['connexion'])) {
    $mail=htmlentities(trim($email));
    $mdp=trim($mdp);
    if(empty($mail) or empty($mdp)) {
      $valid=false;
    }
    if($mdp!='ARTISANAT') {
     $mdp=md5($mdp);
    }
   //la connexion a la base de donnes
    try {
      $bdd=new PDO('mysql:host=localhost;dbname=artisanat;charset=utf8','root','');
    }
    catch(Exception $e)
    {
      die('Erreur:'.$e->getMessage());
    }
     $requet=$bdd->prepare("SELECT * FROM users WHERE email=? AND mdp=? ");
     $requet->execute(array($mail,$mdp));
     $rows=$requet->rowCount();
     if($rows==0)
    {
      $erreur="The email or the password is incorrect";
    }else {
      $row=$requet->fetch();
      //si le compte est valide
      $_SESSION['id']=$row['id'];
      $_SESSION['nom']=$row['nom'];
      $_SESSION['prenom']=$row['prenom'];
      $_SESSION['mail']=$row['mail'];
      if($mdp=="ARTISANAT") {
        header('Location:modifier_mdp.php');
        exit;
      }else {
        header('Location:accueil.php');
        exit;
      }
    
    }
  }
}



?>





<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css"/>    
    <link rel="stylesheet" href="../style/index.css">
    <title>GIIPC Artisanat</title>
</head>
<body>
    <div class="col-md-3"></div>
    <div class="col-md-4"></div>
    <div class="col-md-4">
        <div id="layoutAuthentication">
          <div id="layoutAuthentication_content">
                <div class="container">
                    <div class="row justify-content-center">
                            <div class="card shadow-lg  rounded-lg mt-5">
                                <div class="card-header"><h3 class="text-center font-weight-light my-4">Login</h3></div>
                                <div class="card-body">
                                    <form method="post" id="form" action="index.php">
                                        <div class="form-group">
                                            <label class="small mb-1" for="inputEmailAddress">Email</label>
                                            <i class="fa fa-user" aria-hidden="true"></i>
                                            <input class="form-control <?php if(isset($erreur)) { echo "is-invalid"; } ?> py-4" name="email" id="inputEmailAddress"  type="email" placeholder="Enter email address" />
                                        </div>
                                        <div class="form-group">
                                            <label class="small mb-1" for="inputPassword">Password</label>
                                            <i class="fa fa-unlock-alt" aria-hidden="true"></i>
                                            <input class="form-control <?php if(isset($erreur)) { echo "is-invalid"; } ?> py-4" name="mdp" id="inputPassword" type="password" placeholder="Enter password" />
                                        </div>
                                        <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                                            <button type="submit" class="btn" name="connexion">Connexion</button>  
                                        </div>
                                    </form>
                                </div>
                               
                            </div>
                        </div>
                    </div>
                </div>
          </div>
        </div>
      </div>
      <div class="col-md-1"></div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
    
</body>
</html>
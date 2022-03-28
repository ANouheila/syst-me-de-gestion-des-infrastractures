<?php 
session_start();
//s'il ya une ssesion alors on retourne plus sur cette page

if(!empty($_POST))
{
  extract($_POST);
  if(isset($_POST['connexion'])) {
      if($an_mdp=='ARTISANAT') {
        $mdp=md5($new_mdp);
        try {
            $bdd=new PDO('mysql:host=localhost;dbname=artisanat;charset=utf8','root','');
          }
          catch(Exception $e)
          {
            die('Erreur:'.$e->getMessage());
          }
          $demande=$bdd->prepare("UPDATE users SET mdp=:mdp WHERE id=:id");
          $demande->execute(array(
            'mdp'=>$mdp,
            'id'=>$_SESSION['id']
        ));
        header('Location:accueil.php');
        exit;
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
    <title>Artisanat</title>
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
                                <div class="card-header"><h3 class="text-center font-weight-light my-4">Modifier Votre mot de passe</h3></div>
                                <div class="card-body">
                                    <form method="post" id="form" action="modifier_mdp.php">
                                        <div class="form-group">
                                            <label class="small mb-1" for="inputEmailAddress">mot de passe actuel</label>
                                            <i class="fa fa-user" aria-hidden="true"></i>
                                            <input class="form-control py-4" name="an_mdp" id="inputEmailAddress" type="password" placeholder="Mot de passe actuel" />
                                        </div>
                                        <div class="form-group">
                                            <label class="small mb-1" for="inputPassword">nouveau mot de passe</label>
                                            <i class="fa fa-unlock-alt" aria-hidden="true"></i>
                                            <input class="form-control py-4" name="new_mdp" id="inputPassword" type="password" placeholder="Nouveau mot de passe" />
                                        </div>
                                        <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                                            <button type="submit" class="btn" name="connexion">Modifier</button>  
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
    <script type="text/javascript">
        const form=document.getElementById('form');
        const mail=document.getElementById('inputEmailAddress');
        const mdp=document.getElementById('inputPassword');
        form.addEventListener('submit',function(e){
            <?php if(isset($erreur)){
            ?>
            mail.className='form-control is-invalid';
            mdp.className='form-control is-invalid';
            console.log(mail);
            <?php }?>
        });

    </script>
</body>
</html>
<?php 
$get_id=(int) trim(htmlentities($_GET['id']));
try {
    $bdd=new PDO('mysql:host=localhost;dbname=artisanat;charset=utf8','root','');
    }
    catch(Exception $e)
    {
    die('Erreur:'.$e->getMessage());
    }
$req=$bdd->prepare('DELETE FROM projets WHERE id=:id');
$req->execute(array('id'=>$get_id,));

$requete=$bdd->prepare('DELETE FROM partenaires WHERE id_projet=:id');
$requete->execute(array('id'=>$get_id,));

$bureaux=array('architecte'=>'architectes','BET'=>'bet','BC'=>'bc','topographe'=>'topographe','Entrepise de construction'=>'entreprise_construction');
foreach($bureaux as $bureau=>$value) {
    $requete=$bdd->prepare("DELETE FROM $value WHERE id_projet=:id");
    $requete->execute(array('id'=>$get_id,));
}

header('location:convention.php');
?>
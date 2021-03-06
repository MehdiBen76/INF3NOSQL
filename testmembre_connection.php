<?php
session_start();
$bdd= new PDO('mysql:host=localhost;dbname=inf3','root','root');
$_SESSION['Start']= date("H:i:s");
?>
<html>
<head>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <title>INF3</title>
 </head>
<?php
if (isset($_POST['formconnect'])){
  $mailconnect=htmlspecialchars($_POST['mailconnect']);
  $mdpconnect= sha1($_POST['mdpconnect']);//encodage le meme utilisé que dans lespace inscription
  if(!empty($mailconnect) AND !empty($mdpconnect)){
    $requser = $bdd ->prepare("SELECT * FROM user WHERE mail = ? AND mdp =?");
    $requser->execute(array($mailconnect,$mdpconnect));
    $userexist = $requser->rowCount();
    if($userexist==1){
      $userinfo=$requser->fetch();
      $_SESSION['id']=$userinfo['idUser'];
      $_SESSION['mdp']=$userinfo['mdp'];
      $_SESSION['mail']=$userinfo['mail'];
      $_SESSION['nom']=$userinfo['nom'];
      header("Location: profil.php?id=".$_SESSION['id']);
    }
    else {
      $erreur="Mauvais mail ou mdp";
    }
  }
  else {
    $erreur="remplissez tous les champs";
  }

}
 ?>
 <body>
<h3 align ="center">Connexion</h3>
  <br><br><br>
<form align ="center" method ="POST" action ="">
<label class="col-sm-2 col-form-label"for="mail">Email :</label>
<input type="email" placeholder="Votre email" name="mailconnect"/><br>
<label class="col-sm-2 col-form-label" for="mdpconnect">mot de passe :</label>
<input type="password" placeholder="Votre mdp" name="mdpconnect"/><br><br>
<input class="col-sm-2 col-form-label" type="submit" value="Je me connecte" name="formconnect" class="btn btn-primary"/>
<br> <br>
<button>
  <a href="indexINF3.html"> Pas encore inscrit ? </a>
</button>

<?php
if(isset($erreur)){
  echo $erreur;
}
 ?>

  </form>

</body>
</html>

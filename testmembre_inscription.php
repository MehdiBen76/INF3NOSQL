<html>
<head>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <title> INF3 </title>

 <div class="carre">
   <p >
     <a href="indexINF3.html">
     <p id ="main"> Votre site e-commerce </p> <br>
     <p id ="main2">
 </p>
 </div>
<?php
//session_start();
$bdd= new PDO('mysql:host=localhost;dbname=inf3','root','root');
if (isset($_POST['forminscription'])){
  if(!empty($_POST['email']) AND !empty($_POST['motdepasse']))
  {
    $motdepasse= sha1($_POST['motdepasse']); #hachez le mot de passe
    $email= htmlspecialchars($_POST['email']);
    $nom= htmlspecialchars($_POST['nom']);
    if(filter_var($email, FILTER_VALIDATE_EMAIL)){ //SECURITE verifie si c'est une adresse mail non modifier dans le html
      $reqmail=$bdd->prepare("SELECT * FROM user WHERE mail = ?");
      $reqmail->execute(array($email));
      $mailexist = $reqmail->rowCount();// rentre le nombres de colonnes qui existe pour ce qu'on a rentrer
      if($mailexist==0){
          $insertmbr = $bdd->prepare('INSERT INTO user(nom,mail,mdp) VALUES(?,?,?)');
          $insertmbr->execute(array($nom,$email,$motdepasse));
          $erreur="Votre compte à bien été creer <a href= \"testmembre_connection.php\"> Me connecter </a>";
        }

      else {
        $erreur="adresse mail deja utilisée";
      }

  }
  else{
    $erreur = "l'adresse mail n'est pas valide";
  }
 }
  else
  {
    $erreur="tous les champs doivent etre complétés";
  }

}
 ?>

<html><head><title>Notre site e-commerce</title><meta charset="utf-8"></head>
<body>
<div align="center">
  <h3> Inscription</h3>
  <br><br>
  <form class="form-group row" method ="POST" action ="">
<label class="col-sm-2 col-form-label" for="email">Email :</label>
<input  type="email" placeholder="Votre email" name="email"/><br>
<label class="col-sm-2 col-form-label" for="nom">votre nom :</label>
<input  type="text" placeholder="Votre nom" name="nom"/><br>
<label class="col-sm-2 col-form-label" for="motdepasse">mot de passe :</label>
<input type="password" placeholder="Votre mdp" name="motdepasse"/><br><br><br><br>
<input type="submit" value="je m'inscris" name="forminscription"/>
<br><br>
<button><a href="pageconnection_iscription.html"> Deja inscrit ? </a></button>


<?php
if(isset($erreur)){
  echo $erreur;
}
 ?>

  </form>

</body>
</html>

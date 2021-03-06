<?php
session_start();
$bdd= new PDO('mysql:host=localhost;dbname=inf3','root','root');
if(isset($_SESSION['id'])){
  echo "<h1> Bonjour ". $_SESSION['nom']."</h1>";
  //$requser=$bdd->prepare("SELECT * FROM membre WHERE id = ?");
  //$requser->execute(array($_SESSION['id']));
  //$user = $requser->fetch();


  //changement mail
  if(isset($_POST['newmail']) AND !empty($_POST['newmail']) AND $_POST['newmail'] != $_SESSION['mail']){
    $newmail=htmlspecialchars($_POST['newmail']);
    $insertmail=$bdd->prepare("UPDATE user SET mail = ? WHERE id = ?");
    $insertmail->execute(array($newmail,$_SESSION['id']));
    $_SESSION['mail']=$_POST['newmail'];
    header('Location:profil.php?id='.$_SESSION['id']);
  }
  //changement mdp
  if(isset($_POST['newmdp']) AND !empty($_POST['newmdp'])){
    $newmdp=sha1($_POST['newmdp']);
    if($_POST['newmdp'] != $_SESSION['mdp']){
      $insertmdp=$bdd->prepare("UPDATE user SET mdp = ? WHERE id = ?");
      $insertmdp->execute(array($newmdp,$_SESSION['id']));
      $_SESSION['mdp']=$_POST['newmdp'];
      header('Location:profil.php?id='.$_SESSION['id']);
    }

  }

?>
<html>
<head><title> Editer mon profil </title></head>
  <body>
  <form method="POST" action="">
<label for="newmail"> Nouvelle adresse mail : </label>
<input type="text" name ="newmail" placeholder="Mail" value="<?php echo $_SESSION['mail'] ?>"/><br><br>
<label for="newmdp"> Nouveau mot de passe : </label>
<input type="password" name ="newmdp" placeholder="votre mot de passe" /><br><br>
  <input type="submit" value="mettre à jour mon profil" />
</form>
</body>
</html>
<?php
}
else{
  header("Location:testmembre_connection.php");
}
?>

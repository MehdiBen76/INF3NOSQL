<?php
session_start();
$bdd= new PDO('mysql:host=localhost;dbname=inf3','root','');
if(isset($_GET['id']) AND $_GET['id']>0){
  $getid=intval($_GET['id']); //securisation
  $requser=$bdd->prepare('SELECT * FROM user WHERE idUser = ?');
  $requser->execute(array($getid));
  $userinfo=$requser->fetch(); // on va chercher les informations
 ?>

 <html>
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>INF3</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
   </head>
<body>
<div align="center">
  <h1> Mon Profil </h1>
  <br>
  <h6>Vos informations</h6>
  <?php
echo " votre mail : ".$_SESSION['mail']."<br> votre nom : ". $_SESSION['nom'] . "<br><br>";

// verirfions si la personne est bien la titulaire du compte ou est bien connecté
if(isset($_SESSION['id']) AND $userinfo['idUser'] == $_SESSION['id']){
?>
<button>
  <a href='editerprofil.php'>Editer mon profil </a>
</button>

<button>
  <a href='histo-commande.php'>Mes Commandes </a>
</button>

<button>
  <a href='deconnexion.php'>Se deconnecter </a>
</button>
  <br><br>

<a>Acceder aux ventes privées : </a>

<button>
  <a href="elast.php"> Rechercher produits </a>
</button>
<br><br>


<?php }// si oui elle peut editer son profil
if($_SESSION['id']==20 AND $userinfo['idUser'] == $_SESSION['id'] ){

 ?>
<a href='admincommande.php'> Mettre à jour les commandes effectué</a><br><br>
<a href='adminprix.php'> Mettre à jour les prix</a><br>
<?php } ?>
  </form>

</body>
</html>
<?php
}
 ?>

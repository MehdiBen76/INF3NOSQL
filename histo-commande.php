<?php
session_start();
$bdd= new PDO('mysql:host=localhost;dbname=inf3','root','');
$h=$bdd ->prepare("SELECT DISTINCT * FROM commande WHERE  commande.idUser= ? GROUP BY commande.idCommande");
$h->execute(array($_SESSION['id']));
$hist=$h->fetchAll();

?>
<html>
<head>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="style.css">
  <title> Historique de vos commandes </title>
  <h1 align="center"> Historique de vos commandes </h1>
</head>
<body>
<div align="center">
<?php
foreach ($hist as $key) {
  $result = array_unique($key);
  echo "<br><br>  <h2 align=\"center\"> Nous avons </h2> <br>";
  foreach ($result as $key2 => $value) {
    echo "<br> ".$key2 ." : ".$value;

}
?> <br>
<?php
        $a3=$bdd->prepare("SELECT produit.nomProduit, ligne_commande.quantité FROM produit, ligne_commande WHERE produit.idProduit = ligne_commande.idProduit AND ligne_commande.idCommande = ?");
        $a3->execute(array($result['idCommande']));
        $ha2=$a3->fetchAll();
        foreach ($ha2 as $key2 => $value) {
            echo "  <a align=\"center\"> Le produit : ".$value['nomProduit']. " avec comme quantité : ". $value['quantité']. " </a><br>";
      }

}
?> </div>
<?php
if(isset($_POST['retour'])){
  header('Location: profil.php?id='.$_SESSION['id']);
  die();
}
if(isset($_POST['deconnexion'])){
  header('Location: deconnexion.php');
  die();
}
if(isset($_POST['x'])){
  header('Location: indexINF3.html');
  die();
}
 ?>

  <br>
<div align="center">
<form method="POST">
<input type="submit" name="retour" value="retour">
<input type="submit" name="deconnexion" value="deconnexion">
<input type="submit" name="x" value="Retour à la page principale"></form>
</div>

</body>
</html>

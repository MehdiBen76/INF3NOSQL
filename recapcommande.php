<?php
session_start();
$bdd= new PDO('mysql:host=localhost;dbname=inf3','root','root');
 ?>
 <html>
 <head>

   <link rel="stylesheet" type="text/css" href="styleYB.css">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

<div align="right">
<a href='deconnexion.php'>Se deconnecter </a><br><br></div>
</head><body>
  <div align="center">

  <?php
  $recapTB = $bdd ->prepare(' SELECT TB.nomTB, TB.prixTB FROM TB, commandeTB WHERE TB.idTB=commandeTB.idTB AND commandeTB.IdCommande = ? ');
  $recapTB->execute(array($_SESSION['IdCommande']));
  $recapinfoTB=$recapTB->fetchAll();
  $sommeTB=0;
  $iTB=0;
  ?>
  <div align="center">

    <h4>Recapitulatif de votre commande

    <?php
    echo $_SESSION['pseudo']." pour la commande ".$_SESSION['IdCommande']." ! <h4><br> A livrer le ".$_SESSION['dateLiv']." entre ".$_SESSION['HD']." et ".$_SESSION['HF']."<br><h4> Vos choix pour le type de biscuit </h4>";
foreach($recapinfoTB as $key=>$value){
  foreach ($value as $key2 => $value2) {
    if(!is_numeric($key2) && is_numeric($value2)){
    $sommeTB+=$value2;
    }
    else{
      if(!is_numeric($key2)){
      echo "<i> ". $value2 ." </i> <br>";}
  }}}
echo "Cela vous revient à : ".$sommeTB." euros pour la base de votre gateau <br>";

?>

<br> <form method="post" action="">
<input type="submit" value="recommencer mon choix pour le type de biscuit" name="recommencerTB"/></form>
<br><br>
<a href="profil.php">profil</a>
<br><br><h4> Vos choix pour la garniture </h4>
<?php


//--------------------------------Recapitulatif garniture-------------------------------


$recapG = $bdd ->prepare(' SELECT G.nomG, G.prixG FROM G, commandeG WHERE G.idG=commandeG.idG AND commandeG.IdCommande = ? ');
$recapG->execute(array($_SESSION['IdCommande']));
$recapinfoG=$recapG->fetchAll();
$sommeG=0;
$iG=0;
foreach($recapinfoG as $key=>$value){
  foreach ($value as $key2 => $value2) {
    if(!is_numeric($key2) && is_numeric($value2)){
    $sommeG+=$value2;
    }
    else{
      if(!is_numeric($key2)){
      echo "<i> ". $value2 ." </i> <br>";}
  }}}

//--------------------------------CALCUL PRIX FINAL--------------------------------------------

$_SESSION['somme']=$sommeG+$sommeTB;
echo "Cela vous revient à : ".$sommeG." euros pour la base de votre gateau <br><br>"."<h4>Votre gateau vous reviens à : ".$_SESSION['somme']." euros <h4><br>";

//------------------------------------------recommencer---------------------------------------------------------
if(isset($_POST['recommencerTB'])){
  $supTB=$bdd->prepare("DELETE FROM commandeTB WHERE IdCommande = ? ");
  $supTB->execute(array($_SESSION['IdCommande']));
  header("Location: choixTB.php");
  die();
}
if(isset($_POST['recommencerG'])){
  $supG=$bdd->prepare("DELETE FROM commandeG WHERE IdCommande = ? ");
  $supG->execute(array($_SESSION['IdCommande']));
  header("Location: choixG.php");
  die();
}
//-------------------------------------------Annuler--------------------------------------------------------------

  if(isset($_POST['annulation'])){
    $supG=$bdd->prepare("DELETE FROM commandeG WHERE IdCommande = ? ");
    $supG->execute(array($_SESSION['IdCommande']));
    $supTB=$bdd->prepare("DELETE FROM commandeTB WHERE IdCommande = ? ");
    $supTB->execute(array($_SESSION['IdCommande']));
    header('Location: profil.php?id='.$_SESSION['id']);
    die();

}
//-------------------------------------Continuer-------------------------------------------------------------------
if(isset($_POST['profil'])){
  $prixfinal=$bdd->prepare("UPDATE Commande SET prixfinal = ? WHERE IdCommande = ?");
  $prixfinal->execute(array($_SESSION['somme'],$_SESSION['IdCommande']));
  header('Location: profil.php?id='.$_SESSION['id']);
  die();
}
 ?>

 <form method="POST" action="">
<br> <input type="submit" value="recommencergarniture" name="recommencerG"/><br><br>
<input type="submit" value="profil" name="profil" />
<br><br><input type="submit" value="Annuler" name="annulation"/></form>
</div></body></html>

<?php
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=inf3','root','');
$redis = new Redis(); 
$redis->connect('127.0.0.1', 6379); 
$list = $redis->lrange("panier", 0, -1);
$total = 0;
$date = date('d-m-Y');
$id = $_SESSION['id'];
$bdd->exec("INSERT INTO commande (idUser, dateCom, etat, prix_total) VALUES ($id, '$date', 'EC', 'null')"); // etat vraiment necessaire ?!
$commande = $bdd->lastInsertId();
foreach ($list as $article) {
	$article = explode("_", $article);
	$bdd->exec("INSERT INTO produit (nomProduit) VALUES ('$article[0]')");
	$produit = $bdd->lastInsertId();
	$bdd->exec("INSERT INTO ligne_commande VALUES ($commande, $produit, $article[3])");
	$total = $total + $article[2];
	$redis->del($article[0]);
}
$bdd->exec("UPDATE commande SET prix_total = $total WHERE idCommande = $commande");
$redis->del("panier");
header("Location: https://localhost/inf3-nosql/histo-commande.php");
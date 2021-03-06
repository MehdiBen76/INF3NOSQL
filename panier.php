<?php 
   //Connecting to Redis server on localhost 
   $redis = new Redis(); 
   $redis->connect('127.0.0.1', 6379); 
   
 $cles = $redis->keys("*");
for ($i = 0; $i < count($cles); $i++) {
	if ($cles[$i] == "panier") {
		unset($cles[$i]);
	}
}

 $redis->del("panier");
foreach ($cles as $value) {
//	echo $redis->get($value);
	$redis->expire($value, 300);
	$redis->rpush("panier", $redis->get($value));
}
$redis->expire("panier", 300);
   

// var_dump($articles);
 

   if (isset($_POST['panier'])) {
   	
   		switch ($_POST['panier']) {
   		case 'Ajouter': 		

/*$redis->lrem("panier", $_POST["nom"]."_".$_POST["cat"]."_".$_POST["prix"]."_".$_POST["qte"], 0);

echo $qte;
   				$redis->rpush("panier", $_POST["nom"]."_".$_POST["cat"]."_".$_POST["prix"]."_".$qte);*/

$redis->del($_POST["nom"]);
$qte = $_POST["qte"] + 1;
$redis->set($_POST["nom"], $_POST["nom"]."_".$_POST["cat"]."_".$_POST["prix"]."_".$qte);
$redis->del("panier");
$cles = $redis->keys("*");
foreach ($cles as $value) {
	$redis->expire($value, 300);
	$redis->rpush("panier", $redis->get($value));
}
$redis->expire("panier", 300);

   				break;

   			
   			default:
   				//$redis->lrem("panier", $_POST["nom"]."_".$_POST["cat"]."_".$_POST["prix"], -1);
$redis->del($_POST["nom"]);
$qte = $_POST["qte"] - 1 ;

if ($qte > 0) {
$redis->set($_POST["nom"], $_POST["nom"]."_".$_POST["cat"]."_".$_POST["prix"]."_".$qte);
$redis->del("panier");
$cles = $redis->keys("*");
foreach ($cles as $value) {
	$redis->expire($value, 300);
	$redis->rpush("panier", $redis->get($value));
   				
   		}
} else {
	$redis->del("panier");
$cles = $redis->keys("*");
foreach ($cles as $value) {
	$redis->expire($value, 300);
	$redis->rpush("panier", $redis->get($value));
   				
   		}
}

$redis->expire("panier", 300);


   }}

  $list = $redis->lrange("panier", 0, -1);
  //var_dump($list);
  //echo 1;
?>


<!DOCTYPE html>
<html>
<head>
	<title>Panier</title>
	<meta charset="utf-8">
	<style type="text/css">
		table {
border: medium solid #6495ed;
border-collapse: collapse;
width: 100%;
}
th {
font-family: monospace;
border: thin solid #6495ed;
width: 50%;
padding: 5px;
background-color: #D0E3FA;
background-image: url(sky.jpg);
}
td {
font-family: sans-serif;
border: thin solid #6495ed;
width: 50%;
padding: 5px;
text-align: center;
background-color: #ffffff;
}
	</style>
</head>
<body>
<a href="elast.php">
	<button>Retourner dans la liste des articles</button>
</a>
<br>
<br>

<?php 
if ($redis->llen("panier") > 0) {
?>
<a href="commander.php">
	<button>Commander</button>
</a>
<br>
<br>
<?php
}


?>



	<center>
		<h1>Panier</h1>
	</center>
	<table>
		<tr>
			<th>Nom</th>
			<th>Catégorie</th>
			<th>Prix</th>
			<th>Quantité</th>

			<th>Ajouter</th>
			<th>Supprimer</th>
			

		</tr>


		<?php
$i = 1;
foreach ($list as $value) {
	$elements = explode('_', $value);
	//var_dump($elements);
	echo <<<BEGIN
	<form action="panier.php" method="post">
<tr>
	<td>$elements[0] <input type="text" name="nom" value="$elements[0]" hidden></td>
		<td>$elements[1]<input type="text" name="cat" value="$elements[1]" hidden></td>
		<td>$elements[2]<input type="text" name="prix" value="$elements[2]" hidden></td>
		<td>$elements[3]<input type="text" name="qte" value="$elements[3]" hidden></td>
	<td><input type="submit" value="Ajouter" name="panier" ></td>
	<td><input type="submit" value="Supprimer" name="panier" ></td>
</tr>
</form>

BEGIN;
$i++;
}

?>
	</table>
	



	


</body>
</html>

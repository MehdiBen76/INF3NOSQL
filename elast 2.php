<?php
session_start();
addPanier();
   $redis = new Redis();
   $redis->connect('127.0.0.1', 6379);
?>
<html>
<head>
  <link rel="stylesheet" type="text/css" href="style.css">
 <meta charset="UTF-8">
</head>
<?php
function callAPI($method, $url, $headers = false, $data = false)
{
    $curl = curl_init();
    //$url = getAPIUrl()."".$url;
    switch ($method) {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);
            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
            case "GET":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
                if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    // Optional Authentication:
    //curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    //curl_setopt($curl, CURLOPT_USERPWD, "username:password");
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    curl_close($curl);
    return json_decode($result);
}
/**
 * @param $url
 * @param $jsonString string object
 * @param $token
 */
function postJSON($url,$jsonString,$token = false){
    $url = getAPIUrl()."".$url;
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonString);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $headers = array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($jsonString));
    if($token){
        $headers[] = "Authorization: bearer $token";
    }
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($curl);
    curl_close($curl);
    return $result;
}
function deleteAPI($url,$token = false){
    $url = getAPIUrl()."".$url;
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $headers = array();
    if($token){
        $headers[] = "Authorization: bearer $token";
    }
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($curl);
    curl_close($curl);
    return $result;
}
?>


<body>

        <a href="panier.php">
        <button>Accéder au panier de <?= $redis->llen("panier") . " article(s)" ?></button>
        <br>
        <br>
    </a>

  <form method="get" action="">
    <input type="search" name="clientsearch" >
    <input type="submit" value="Rechercher" name="search"/>
  </form>

<?php

if (isset($_GET['clientsearch'])) {
$params = "{\"query\": {\"match\": {\"catProduit\" : \"". $_GET['clientsearch']."\"}}}";




$header = array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($params));


if($_GET['search']){
$response = callAPI("GET","localhost:9200/produit/_doc/_search",$header,$params);
if($response){
  $array = $response->hits->hits;
  //print_r($array[0]->_source->nomProduit);
  echo "<h4 style=\"text-align:center\"> resultat pour votre recherche : ".$_GET['clientsearch']."</h4>";
  foreach ($array as $array) {
    $nomProduit = $array->_source->nomProduit;
    $catProduit = $array->_source->catProduit;
    $prixProduit = $array->_source->prixProduit;
    afficherProduit($nomProduit,$catProduit,$prixProduit);
  }
}
  else {
    echo "<a> Aucun resultat </a>";
  }
}
}
function afficherProduit($nomProduit,$catProduit,$prixProduit){
$clientsearch = $_GET['clientsearch'];
$search = $_GET['search'];
    echo "
    <div class=\"grid-container\">
    <div class=\"card\">

<form action=\"elast.php\" method=\"post\">
      <!-- <img alt=\"Produit 1\" style=\"width:100%\"> -->
      <h1>".$nomProduit."</h1>
      <p class=\"price\" name=\"prix\">".$prixProduit."€</p>
      <p>".$catProduit."</p>
      <p><input type=\"submit\" value=\"Ajouter au panier\" name=\"panier\"></p>
      <input type=\"hidden\" value=\"$nomProduit\" name=\"nom\" >
      <input type=\"hidden\" value=\"$catProduit\" name=\"cat\" >
      <input type=\"hidden\" value=\"$prixProduit\" name=\"prix\" >
    <input type=\"hidden\" value=\"$clientsearch\" name=\"client\" >
    <input type=\"hidden\" value=\"$search\" name=\"search\" >

</form>
      </div>"

      ;
}
 ?>


<?php

function addPanier() {

$redis = new Redis();
   $redis->connect('127.0.0.1', 6379);

   //echo $_POST['nom']."\n";
   //echo $_POST['cat']."\n";
   //echo $_POST['prix']."\n";

   //exit;
if (isset($_POST['panier'])) {
   // echo $_POST['cat']."\n";


   $redis->set($_POST['nom'], $_POST['nom']. "_".$_POST['cat']."_".$_POST['prix']."_1");
   $redis->expire($_POST['nom'], 300);

header("Location: https://localhost/inf3-nosql/elast.php?clientsearch=" . $_POST['client'] . "&search=" . $_POST['search']);

}
}
?>

</body>
</html>

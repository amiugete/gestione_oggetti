<?php
session_set_cookie_params($lifetime);
session_start();


if(!isset($_COOKIE['un'])) {
    //echo "Cookie named un is not set!";
  } else {
    //echo "Cookie un is set!<br>";
    //echo "Value is: " . $_COOKIE['un'];
    $_SESSION['username']=$_COOKIE['un'];
  }


//$id=pg_escape_string($_GET['id']);

$user = $_SERVER['AUTH_USER'];

$username = $_SERVER['PHP_AUTH_USER'];


if (!$_SESSION['username']){
  //echo 'NON VA BENE';
  $_SESSION['origine']=basename($_SERVER['PHP_SELF']);
  $_COOKIE['origine']=basename($_SERVER['PHP_SELF']);
  header("location: ./login.php");
  //exit;
}    
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="roberto">

    <title>Gestione oggetti - Interventi </title>
    <?php 
require_once('./req.php');

the_page_title();

if ($_SESSION['test']==1) {
  require_once ('./conn_test.php');
} else {
  require_once ('./conn.php');
}
?>





</head>

<body>

    <?php require_once('./navbar_up.php');
$name=dirname(__FILE__);
?>


    <div class="container">




    <h2> Guida in linea </h2>
    L'applicativo chiamato gestione oggetti prevede attualmente le funzionalità per la <b>manutenzione contenitori</b> dedicate 
   all'<b><i>Unità Ripristino Contenitori</b></i>. 

    
    <hr>
    <h2>Modalità di accesso</h2>
    
    <hr>
    <h2>Pagine</h2>
    Attualmente sono presenti 4 pagine:

    <ul>
        <li> Elenco interventi aperti / sospesi</li>
        <li> Elenco ordini di lavoro</li>
        <li> Elenco squadre</li>
        <li> Pagina per la chiusura di interventi</li>
    </ul>


    <div class="row">   
    






    </div>
    </div>

    <?php
require_once('req_bottom.php');
require('./footer.php');
?>

</body>

</html>
<?php
session_start();


$id=$_POST['id'];
$stato=$_POST['s'];
echo $id."<br>";
echo $stato."<br>";

if ($_SESSION['test']==1) {
    require_once ('./conn_test.php');
  } else {
    require_once ('./conn.php');
  }


  

// cambio stato --> INSERT

$query1= "UPDATE gestione_oggetti.squadra
SET valido=$1
WHERE id=$2";
$result1 = pg_prepare($conn, "my_query1", $query1);
$result1 = pg_execute($conn, "my_query1", array($stato,$id));
$status1= pg_result_status($result1);

if ($status1==1){
    $des='Squadra '.$id.' Valido='.$stato.'';
} else {
    $des='ERROR: cambio stato squadra';
}

$query2="INSERT INTO util_go.sys_history
(type, action, description, datetime, id_user)
VALUES ('VALIDITA SQUADRA', 'UPDATE', $1, now(), (select id_user from util_go.sys_users su where name ilike $2));";
$result2 = pg_prepare($conn, "my_query2", $query2);
$result2 = pg_execute($conn, "my_query2", array($des, $_SESSION['username']));



?>
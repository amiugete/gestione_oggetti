<?php
session_start();


$id=$_POST['id'];



if ($_SESSION['test']==1) {
    require_once ('./conn_test.php');
  } else {
    require_once ('./conn.php');
  }


  

// cambio stato --> INSERT
// 1 aperto 
$stato_aperto=1;
$query1= "INSERT INTO gestione_oggetti.intervento_tipo_stato_intervento
(tipo_stato_intervento_id, intervento_id, data_ora)
VALUES($1, $2, now());";
$result1 = pg_prepare($conn, "my_query1", $query1);
$result1 = pg_execute($conn, "my_query1", array($stato_aperto,$id));
$status1= pg_result_status($result1);

if ($status1==1){
    $des='Int '.$id.' ri-aperto';
} else {
    $des='ERROR: Int '.$id.' non ri-aperto';
}

$query2="INSERT INTO util_go.sys_history
(type, action, description, datetime, id_user, id_piazzola, id_intervento, id_elemento)
VALUES ('CAMBIO STATO', 'INSERT', $1, now(), (select id_user from util_go.sys_users su where name ilike $2),
(select piazzola_id from gestione_oggetti.intervento i where id = $3),
$4,
(select elemento_id from gestione_oggetti.intervento i where id = $5));";
$result2 = pg_prepare($conn, "my_query2", $query2);
$result2 = pg_execute($conn, "my_query2", array($des, $_SESSION['username'],$id, $id, $id));



$query3="UPDATE gestione_oggetti.intervento
    SET odl_id=NULL
    WHERE id=$1 ;";
$result3 = pg_prepare($conn, "my_query3", $query3);
$result3 = pg_execute($conn, "my_query3", array($id));
$status3= pg_result_status($result3);


if ($status3==1) {
    $des='Rimosso odl da int'.$id.'';
} else {
    $des='ERRORE: rimozione odl da int. '.$id.'';
}


$query4="INSERT INTO util_go.sys_history
(type, action, description, datetime, id_user, id_piazzola, id_intervento, id_elemento)
VALUES ('ODL', 'UPDATE', $1, now(), (select id_user from util_go.sys_users su where name ilike $2),
(select piazzola_id from gestione_oggetti.intervento i where id = $3),
$4,
(select elemento_id from gestione_oggetti.intervento i where id = $5));";
$result4 = pg_prepare($conn, "my_query4", $query4);
$result4 = pg_execute($conn, "my_query4", array($des, $_SESSION['username'],$id, $id, $id));


?>
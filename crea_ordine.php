<?php
session_start();


$id_squadra=$_GET['s'];
$data=$_GET['d'];
$interventi=$_GET['ii'];

echo date('Y/m/d H:i:s', strtotime($data))."<br>";

$unixtime=strtotime($data);
echo $unixtime."<br>";



echo $interventi."<br>";
$interventi = explode(',', $interventi);



$array_interventi=array();
$i=0;
while ($i< count($interventi)-1) {
    //echo $interventi[$i]."<br>";
    $array_interventi[]=$interventi[$i];
    $i=$i+1;
}

//print_r($array_interventi)."<br>";



if ($_SESSION['test']==1) {
    echo "'Ok<br>";
    require_once ('./conn_test.php');
} else {
    require_once ('./conn.php');
}


$id_odl=0;
$creo_ordine= "INSERT INTO gestione_oggetti.odl
(id, data_prevista, data_creazione, chiuso, squadra_id)
VALUES (DEFAULT, to_timestamp($1), now(), 0, $2) RETURNING id;";

$result = pg_prepare($conn, "my_query0", $creo_ordine);
$result = pg_execute($conn, "my_query0", array($unixtime, $id_squadra));

$status= pg_result_status($result);


while($r = pg_fetch_assoc($result)) {
    $id_odl=$r['id'];
}

echo $id_odl."<br>";


if ($id_odl>0){
    $des='Odl '.$id_odl.' creat';
} else {
    $des='ERROR: creazione odl';
}

$query2="INSERT INTO util_go.sys_history
(type, action, description, datetime, id_user, id_odl)
VALUES ('CREAZIONE ODL', 'INSERT', $1, now(), (select id_user from util_go.sys_users su where name ilike $2),
$3);";
$result2 = pg_prepare($conn, "my_query0bis", $query2);
$result2 = pg_execute($conn, "my_query0bis", array($des, $_SESSION['username'],$id_odl));




$i=0;
while ($i< count($interventi)-1) {
    echo $interventi[$i]."<br>";
    echo $id_odl."<br>";
    //$array_interventi[]=$interventi[$i];
    // inserisco il numero dell'ordine di lavoro alla tabella
    $update1="UPDATE gestione_oggetti.intervento SET odl_id=$1 WHERE id = $2;";
    $result1 = pg_prepare($conn, "my_query1", $update1);
    $result1 = pg_execute($conn, "my_query1", array($id_odl, $interventi[$i]));
    $status1= pg_result_status($result1);
    echo $update1."<br>";
    echo "STATUS1:".$status1."<br>";
    if ($status1==1){
        $des='Int '.$interventi[$i].' aggiunto odl';
    } else {
        $des='ERROR: aggiunta odl';
    }
    
    $query2="INSERT INTO util_go.sys_history
    (type, action, description, datetime, id_user, id_piazzola, id_intervento, id_elemento, id_odl)
    VALUES ('ADD ODL', 'UPDATE', $1, now(), (select id_user from util_go.sys_users su where name ilike $2),
    (select piazzola_id from gestione_oggetti.intervento i where id = $3),
    $4,
    (select elemento_id from gestione_oggetti.intervento i where id = $5), $6);";
    $result2 = pg_prepare($conn, "my_query1bis", $query2);
    $result2 = pg_execute($conn, "my_query1bis", array($des, $_SESSION['username'],$interventi[$i], $interventi[$i], $interventi[$i], $id_odl));

    // cambio lo stato
    $stato="INSERT INTO gestione_oggetti.intervento_tipo_stato_intervento 
    (tipo_stato_intervento_id, intervento_id, data_ora)
    VALUES (5, $1, now());";
    $result2 = pg_prepare($conn, "my_query2", $stato);
    $result2 = pg_execute($conn, "my_query2", array($interventi[$i]));
    $status2= pg_result_status($result2);
    echo "STATUS2:".$status2."<br>";
    if ($status2==1){
        $des='Int '.$interventi[$i].' preso in carico';
    } else {
        $des='ERROR: presa in carico';
    }

    $query2="INSERT INTO util_go.sys_history
    (type, action, description, datetime, id_user, id_piazzola, id_intervento, id_elemento, id_odl)
    VALUES ('CAMBIO STATO', 'INSERT', $1, now(), (select id_user from util_go.sys_users su where name ilike $2),
    (select piazzola_id from gestione_oggetti.intervento i where id = $3),
    $4,
    (select elemento_id from gestione_oggetti.intervento i where id = $5), $6);";
    $result2 = pg_prepare($conn, "my_query2bis", $query2);
    $result2 = pg_execute($conn, "my_query2bis", array($des, $_SESSION['username'],$interventi[$i], $interventi[$i], $interventi[$i], $id_odl));


    $i=$i+1;
}

    



?>
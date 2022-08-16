<?php
session_set_cookie_params($lifetime);
session_start();

require_once('funzioni_jwt.php');


$id=$_POST['id'];
$nc=$_POST['nc'];
$el=$_POST['el'];


if ($_SESSION['test']==1) {
    require_once ('./conn_test.php');
  } else {
    require_once ('./conn.php');
  }


  // verifico se c'è di mezzo il SIT
$query0="SELECT intervento_id, ti.gestione_sit  
FROM gestione_oggetti.intervento_tipo_intervento iti  
JOIN gestione_oggetti.tipo_intervento ti ON ti.id = iti.tipo_intervento_id 
WHERE intervento_id = $1 and gestione_sit='t' ";
$result = pg_prepare($conn, "my_query0", $query0);
$result = pg_execute($conn, "my_query0", array($id));

$gestione_sit=0;
while($r = pg_fetch_assoc($result)) {
    //echo $r['intervento_id'];
    $gestione_sit=1;
}

$stato_chiuso=3;
if ($gestione_sit==1){
    // devo chiamare il WS di SIT
    //echo "Devo prima chiamare il sit <br>";
    //$stato_chiuso=4;



    $query_role='SELECT  su.id_user, su.email, sr.id_role, sr."name" as "role" FROM util.sys_users su
        join util.sys_roles sr on sr.id_role = su.id_role  
        where su."name" ilike $1;';
    $result_n = pg_prepare($conn, "my_query_navbar1", $query_role);
    $result_n = pg_execute($conn, "my_query_navbar1", array($_SESSION['username']));
    $status1= pg_result_status($result_n);
    //echo $status1."<br>";
    // recupero i dati dal DB di SIT
    while($r = pg_fetch_assoc($result_n)) {
        $role_SIT=$r['role'];
        $id_role_SIT=$r['id_role'];
        $id_user_SIT=$r['id_user'];
        $mail_SIT=$r['email'];
    }

    // creo il JWT
    $issuedAt   = new DateTimeImmutable();
    $expire     = $issuedAt->modify('+420 minutes')->getTimestamp();

    $headers = array('alg'=>'HS256','typ'=>'JWT');
    $payload = array('role'=>$role_SIT,
            'name'=> $_SESSION['username'],
            "userId"=> $id_user_SIT,
            "roleId"=>$id_role_SIT,
            "userMail"=>$mail_SIT,
            'iss' => $iss,
            'grants' => '',
            'iat'  => $issuedAt->getTimestamp(),         // Issued at: time when the token was generated
            'nbf'  => $issuedAt->getTimestamp(),
            //'exp'	=>(time() + 60)
            'exp'  => $expire,                           // Expire
        );
    
    $jwt = generate_jwt($headers, $payload, $secret_pwd);
    
    //echo $jwt."<br><hr>";

    $url_ok=$url_api_chiusura."".$id;
    echo $url_ok ."<br>";
    $curl = curl_init($url_ok);
    curl_setopt($curl, CURLOPT_URL, $url_ok);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);


    $headers = array(
    "Accept: application/json",
    "Authorization: Bearer {$jwt}",
    );
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    //for debug only!
    //curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    //curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    $resp = curl_exec($curl);
    echo $resp;
    if ($resp == $el OR $resp >0){
        $stato_chiuso=3;
    } else {
        $stato_chiuso=4;
    }
    //exit;
}



// a questo punto faccio il resto

// cambio stato --> INSERT
// 3 chiuso 
// 4 chiuso con riserva 
$query1= "INSERT INTO gestione_oggetti.intervento_tipo_stato_intervento
(tipo_stato_intervento_id, intervento_id, data_ora)
VALUES($1, $2, now());";
$result1 = pg_prepare($conn, "my_query1", $query1);
$result1 = pg_execute($conn, "my_query1", array($stato_chiuso,$id));
$status1= pg_result_status($result1);



// scrivo tabella mail --> INSERT
$query2= "INSERT INTO gestione_oggetti.email
(tipo_mail, intervento_id, data_creazione)
VALUES('CHIUSO', $1, now()::date);";
$result2 = pg_prepare($conn, "my_query2", $query2);
$result2 = pg_execute($conn, "my_query2", array($id));
$status2= pg_result_status($result2);


if ($nc!='ND'){
    // aggiungo note chiusura --> UPDATE
    $query3="UPDATE gestione_oggetti.intervento
    SET note_chiusura=$1, elemento_id=$2
    WHERE id=$3 ;";
    $result3 = pg_prepare($conn, "my_query3", $query3);
    $result3 = pg_execute($conn, "my_query3", array($nc,$resp, $id));
    $status3= pg_result_status($result3);
} else {
    $query3="UPDATE gestione_oggetti.intervento
    SET elemento_id=$1
    WHERE id=$2 ;";
    $result3 = pg_prepare($conn, "my_query3", $query3);
    $result3 = pg_execute($conn, "my_query3", array($resp, $id));
    $status3= pg_result_status($result3);
    //$status3=1;
}


$res=$status1+$status2+$status3;


if ($res==3) {
    $des='Chiusura intervento con successo';
    $type='CHIUSURA';
} else {
    $des='Status1: '.$status1.' - Status2: '.$status2.' - Status3: '.$status3;
    $type='ERRORE - problema in fase di chiusura';
}

$query4="INSERT INTO util_go.sys_history
(type, action, description, datetime, id_user, id_piazzola, id_intervento, id_elemento)
VALUES ($1, 'INSERT and UPDATE', $2, now(), (select id_user from util_go.sys_users su where name ilike $3),
(select piazzola_id from gestione_oggetti.intervento i where id = $4),
$5,
(select elemento_id from gestione_oggetti.intervento i where id = $6));";
$result4 = pg_prepare($conn, "my_query4", $query4);
$result4 = pg_execute($conn, "my_query4", array($type, $des, $_SESSION['username'],$id, $id, $id));


if ($res==3){
    echo $status4;
} else {
    echo 'PROBLEMA CHIUSURA: '.$status1.' - ';
    echo $status2.' - ';
    echo $status3.' - ';
    echo $status4;
}
?>
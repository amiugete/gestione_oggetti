
<?php
session_start();
#require('../validate_input.php');


if ($_SESSION['test']==1) {
    require_once ('../conn_test.php');
} else {
    require_once ('../conn.php');
}
//echo "OK";

require_once('interventi_query.php');

if(!$conn) {
    die('Connessione fallita !<br />');
} else {
    
    
    $query= "SELECT data_creazione, 
    tipo_intervento, 
    ut,
    richieste_pervenute,
    richieste_chiuse,
    richieste_aperte
    FROM report.v_report_interventi_aperti_ut_tipo;";

    //print $query;

    $result = pg_prepare($conn, "my_query", $query);
    $result = pg_execute($conn, "my_query", array());

    $rows = array();
    while($r = pg_fetch_assoc($result)) {
        $rows[] = $r;
        //print $r['id'];
    }
    
    
    //pg_close($conn);
	#echo $rows ;
	if (empty($rows)==FALSE){
		//print $rows;
		print json_encode(array_values(pg_fetch_all($result)));
	} else {
		echo "[{\"NOTE\":'No data'}]";
	}
}


?>
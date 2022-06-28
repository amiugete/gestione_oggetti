
<?php
session_start();
#require('../validate_input.php');

$stato=$_GET['s'];

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

    $query_sq="SELECT s.id, s.descrizione, s.valido,
    max(o.data_prevista::date) as last_odl
    FROM gestione_oggetti.squadra s
    left join gestione_oggetti.odl o on o.squadra_id = s.id 
    where valido=$1
    group by s.id, s.descrizione, s.valido
    order by 4 desc";


    $result = pg_prepare($conn, "query_sq1", $query_sq);
    
    $result = pg_execute($conn, "query_sq1", array($stato));

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
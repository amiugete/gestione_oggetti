
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
    
    
    $query= "SELECT id_tipo, tipo_intervento, richieste_pervenute, 
    (
    select count(id) as richieste_chiuse 
    from gestione_oggetti.v_intervento vi  
    where tipo= rr.tipo_intervento and stato in (2,3,4)
    ), 
    (
    select count(id) as richieste_aperte 
    from gestione_oggetti.v_intervento vi  
    where tipo= rr.tipo_intervento and stato =1
    )
    from 
    (select ti.id as id_tipo, ti.descrizione as tipo_intervento, 
    count(i.id) as richieste_pervenute 
    from gestione_oggetti.intervento i 
    join gestione_oggetti.intervento_tipo_intervento iti on iti.intervento_id = i.id 
    join gestione_oggetti.tipo_intervento ti on ti.id = iti.tipo_intervento_id 
    group by ti.id, ti.descrizione
    ) rr
    order by 1
    ";

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
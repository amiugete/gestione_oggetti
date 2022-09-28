
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
    
    
    $query= "SELECT ut, richieste_pervenute, 
    (
    select count(id) as richieste_chiuse 
    from gestione_oggetti.v_intervento vi  
    where ut= rr.ut and stato in (2,3,4)
    ), 
    (
    select count(id) as richieste_aperte 
    from gestione_oggetti.v_intervento vi  
    where ut= rr.ut and stato =1
    )
    from 
    (select u.descrizione as ut, 
    count(i.id) as richieste_pervenute 
    from gestione_oggetti.intervento i 
    join elem.piazzole p on p.id_piazzola = i.piazzola_id 
    join elem.aste a on a.id_asta =p.id_asta 
    join topo.vie v on v.id_via = a.id_via 
    join topo.ut u on u.id_ut = a.id_ut 
    group by u.descrizione
    ) rr
    order by 1";

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
<?php
session_start();
#require('../validate_input.php');
?>
// Build the chart
Highcharts.chart('squadre', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Interventi - Squadre'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    accessibility: {
        point: {
            valueSuffix: '%'
        }
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                connectorColor: 'silver'
            }
        }
    },
    series: [{
        name: 'Share3',
        data:<?php

if ($_SESSION['test']==1) {
    require_once ('../conn_test.php');
} else {
    require_once ('../conn.php');
}
//echo "OK";


if(!$conn) {
    die('Connessione fallita !<br />');
} else {


    $query = "select vo.squadra as name,
    round(count(distinct intervento_id)*100/(select count(distinct intervento_id) from gestione_oggetti.v_odl)::numeric,2)  as y
    from gestione_oggetti.v_odl vo 
    group by vo.squadra";
    
    

    //print $query;

    $result = pg_prepare($conn, "my_query", $query);
    $result = pg_execute($conn, "my_query", array());

    $rows = array();
    while($r = pg_fetch_assoc($result)) {
        $rows[] = $r;
        //print $r['id'];
    }
    
    header('Content-Type: application/json');
    //pg_close($conn);
	#echo $rows ;
	if (empty($rows)==FALSE){
		//print $rows;
		echo json_encode(pg_fetch_all($result), JSON_NUMERIC_CHECK );
	} else {
		echo "[{\"NOTE\":'No data'}]";
	}
}


?>

}]
});
<?php
?>
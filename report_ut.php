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
    <meta name="author" content="roberto" >

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

    


      

  

      <div id="tabella">
      <hr>
      
        <h4>Produzione giornaliera per squadra</h4>



            <div class="row">

                  <!--div id="toolbar">
        <button id="showSelectedRows" class="btn btn-primary" type="button">Crea ordine di lavoro</button>
      </div-->
				
				<table  id="prod_squadra" class="table-hover table-sm" 
        idfield="id"
        data-toggle="table" data-url="./tables/<?php echo basename($_SERVER['PHP_SELF']);?>" 
        data-group-by="false"
        data-group-by-field="piazzola"
        data-show-search-clear-button="true"   
        data-show-export="true" 
        data-export-type=['json', 'xml', 'csv', 'txt', 'sql', 'excel', 'doc', 'pdf'] 
				data-search="true" data-click-to-select="true" data-show-print="false"  
				data-pagination="false" data-page-size=75 data-page-list=[10,25,50,75,100,200,500]
				data-sidePagination="false" data-show-refresh="true" data-show-toggle="true"
				data-filter-control="true"
        data-toolbar="#toolbar" >
        
        
<thead>



 	<tr>
        <!--th data-checkbox="true" data-field="id"></th-->  
        <!--th data-field="state" data-checkbox="true" ></th-->   
        <!--th data-field="data_creazione" data-sortable="true" data-visible="true" data-formatter="dateFormatter" data-filter-control="input">Data<br>prevista</th-->
        <!--th data-field="tipo_intervento" data-sortable="true" data-visible="true" data-filter-control="select">Tipo<br>Intervento</th-->
        <th data-field="ut" data-sortable="true" data-visible="true" data-filter-control="select">UT</th>
        <th data-field="richieste_pervenute" data-sortable="true" data-visible="true" data-filter-control="input">Interventi<br>pervenuti</th>
        <th data-field="richieste_chiuse" data-sortable="true" data-visible="true" data-filter-control="input">Interventi<br>chiusi</th>
        <th data-field="richieste_aperte" data-sortable="true" data-visible="true" data-filter-control="input">Interventi<br>aperti</th>
    </tr>
</thead>
</table>


<script>
  $(function() {
    $('#prod_squadra').bootstrapTable()
  })

  /*data.forEach(d=>{
       data_creazione = moment(d.data_creazione).format('DD/MM/YYYY HH24:MI')
    });*/
    
    function dateFormatter(date) {
      moment.locale('it');
      var data= moment(date).locale('it');
      var data_ok= data.format('DD/MM/YYYY (dddd)');
      return data_ok;
      //moment(date).format('dd DD/MM/YYYY');
      //var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
      //return days[moment(date).getDay()] );
    }

</script>


</div>	<!--tabella-->
           


</div> <!--row-->






</div>
</div>

<?php


# FINE

require_once('req_bottom.php');
require('./footer.php');
?>















</body>

</html>
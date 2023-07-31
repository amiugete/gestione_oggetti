<?php
session_set_cookie_params($lifetime);
session_start();

/*
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
}   */ 
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="roberto" >

    <title>Gestione oggetti - Ordini di lavoro </title>
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
 
        <!--h2> Gestione oggetti (<i class="fas fa-user"></i> ) 
        </h2-->
        <!--a href='report_pesi1.php' class='btn btn-info'> Grafici </a-->

      

        <script type="text/javascript">
        
        function clickButton1(id) {
            console.log("Riapertura intervento");

            var id=id;
            //var materiale= document.getElementById('materiale').text();
            console.log(id);
                  


            var http = new XMLHttpRequest();
            var url = 'riapri.php';
            var params = 'id='+encodeURIComponent(id)+'';
            http.open('POST', url, true);

            //Send the proper header information along with the request
            http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

            http.onreadystatechange = function() {//Call a function when the state changes.
                if(http.readyState == 4 && http.status == 200) {
                    console.log('La chiamata ha funzionato');
                }
            }
            http.send(params);
            
            $('#interventi2').bootstrapTable('refresh', {silent: true});
            
            $('#int_ok').toast('show');
            return false;

        }
      </script>




<hr>
        <h4>Ordini di lavoro (interventi presi in carico)</h4>



        <div id="int_ok" class="toast  text-white bg-success  " data-autohide="false">
  <div class="toast-header">
      <strong class="mr-auto text-primary">Intervento rimosso correttamente</strong>
      <!--small id="ora_notifica" class="text-muted">piccolo</small-->
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close">&times;</button>
    </div>
    <div id="notifica" class="toast-body">
      <b>Vai alla pagina <a class="link-light" href="interventi.php">interventi</a> </b>  
    </div>
  </div>


            <div class="row">
			      <!--div id="toolbar">
        <button id="showSelectedRows" class="btn btn-primary" type="button">Crea ordine di lavoro</button>
				</div-->
				<div id="tabella">
				<table  id="interventi2" class="table-hover" 
        idfield="id"
        data-toggle="table" data-url="./tables/interventi_pc.php" 
        data-group-by="true"
        data-group-by-field="odl_id"
        data-group-by-formatter=odl
        data-show-search-clear-button="true"   
        data-show-export="true" 
        data-export-type=['json', 'xml', 'csv', 'txt', 'sql', 'excel', 'doc', 'pdf'] 
				data-search="true" data-click-to-select="true" data-show-print="false"  
				data-pagination="true" data-page-size=75 data-page-list=[10,25,50,75,100,200,500]
				data-sidePagination="true" data-show-refresh="true" data-show-toggle="true"
				data-filter-control="true"
        data-toolbar="#toolbar" >
        
        
<thead>



 	<tr>
        <!--th data-checkbox="true" data-field="id"></th-->  
        <!--th data-field="state" data-checkbox="true" ></th-->   
        <!--th data-field="piazzola" data-sortable="true" data-visible="true" data-filter-control="input">Piazzola</th-->
        <th data-field="stato_intervento" data-sortable="true" data-visible="true" data-formatter="stato" data-filter-control="select">Stato</th>
        <th data-field="tipo_intervento" data-sortable="true" data-visible="true" data-filter-control="select">Tipo</th>
        <th data-field="data_creazione" data-sortable="true" data-visible="true" data-filter-control="input">Data<br>apertura</th>
        <th data-field="priorita" data-sortable="true" data-visible="true" data-filter-control="select">Priorita</th>
        <!--th data-field="DATA" data-sortable="true" data-visible="true" data-formatter="winLOSSFormatter" data-filter-control="input">Data</th-->
        <th data-field="volume" data-sortable="true" data-visible="true" data-filter-control="select">Vol [l]</th>
        <th data-field="rifiuto" data-sortable="true" data-visible="true" data-filter-control="select">Rifiuto</th>
        <th data-field="quartiere" data-sortable="true" data-visible="true" data-filter-control="select">Quartiere<br>/Comune</th>
        <th data-field="ut" data-sortable="true" data-visible="true" data-filter-control="select">UT</th>
        <th data-field="desc_intervento" data-sortable="true" data-visible="true" data-filter-control="input">Descrizione</th>
    </tr>
</thead>
</table>
<script type="text/javascript" >
function odl(value) {
    //var icon = row.id % 2 === 0 ? 'fa-star' : 'fa-star-and-crescent'
    return '<i class="fa-solid fa-print"></i> <a href=odl.php?id='+value+'>Ordine di lavoro ' + value +'</a>';
  }

  function stato(value,row) {
    //var icon = row.id % 2 === 0 ? 'fa-star' : 'fa-star-and-crescent'
    return '<button onclick="clickButton1('+row.id+')" class="btn btn-success btn-sm"  > \
    <i class="fa-solid fa-arrow-rotate-left" title="Riapri intervento"></i></a>';
  }

</script>

</div>




</div>
</div>
</div>

<?php
require_once('req_bottom.php');
require('./footer.php');
?>

<script type="text/javascript" >

// con questa parte scritta in JQuery si evita che 
// l'uso del tasto enter abbia effetto sul submit del form


$("input#zona").on({
  keydown: function(e) {
    if (e.which === 32)
      return false;
  },
  change: function() {
    this.value = this.value.replace(/\s/g, "");
  }
});


$(document).on("keydown", ":input:not(textarea)", function(event) {
    if (event.key == "Enter") {
        event.preventDefault();
    }
});

</script>









<script>
	function writelist(){
		var codvia_value=$("#via-list option:selected").val(); //get the value of the current selected option.
        console.log(codvia_value);
        var via_text=$("#via-list option:selected").text();
		console.log(via_text);


        document.getElementById("lista_vie").value +='\n'+ codvia_value+ ', '+via_text;

        //document.querySelector('#textlista_vie2').innerHTML = document.codvia_value

	} 

</script>
</body>

</html>
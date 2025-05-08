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



<script type="text/javascript">
        
        function cambia_stato(id, stato) {
            console.log("Cambio lo stato della squadra");

            var id=id;
            console.log(id);
                  
            var stato=stato;
            console.log(stato);
            var http = new XMLHttpRequest();
            var url = 'squadre_valido.php';
            var params = new FormData();
            params.append('id', id);
            params.append('s', stato);
            console.log(params);
            //var params = 'id='+encodeURIComponent(id)+'&s'+encodeURIComponent(stato)+'';
            http.open('POST', url, true);

            //Send the proper header information along with the request
            /*http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

            http.onreadystatechange = function() {//Call a function when the state changes.
                if(http.readyState == 4 && http.status == 200) {
                    console.log('La chiamata ha funzionato');
                }
            }*/
            http.onload = function () {
                // do something to response
                console.log(this.responseText);
                $('#squadre1').bootstrapTable('refresh', {silent: true});
                $('#squadre2').bootstrapTable('refresh', {silent: true});
            };
            http.send(params);
            
            
            //$('#int_ok').toast('show');
            return false;

        }
      </script>



</head>

<body>

<?php require_once('./navbar_up.php');
$name=dirname(__FILE__);
?>


      <div class="container">

    


      

    <!--div class="alert alert-danger alert-dismissible fade show" role="alert">
  <strong>ATTENZIONE!</strong> Devi selezionare almento un intervento.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div-->
 
        <!--h2> Gestione oggetti (<i class="fas fa-user"></i> ) 
        </h2-->
        <!--a href='report_pesi1.php' class='btn btn-info'> Grafici </a-->
        <?php if ($id_role>0) {  #solo se non sono VIEWER ?>
        <hr>

        <script type="text/javascript">
        
        function clickButton() {
            console.log("Bottone  form cliccato");

            var sq=document.getElementById('sq').value;
            //var materiale= document.getElementById('materiale').text();
            console.log(sq);

            // get the URL
            var http = new XMLHttpRequest();
            var url = 'crea_squadra.php';
            var params = new FormData();
            params.append('sq', sq);
            //console.log(params);
            //var params = 'id='+encodeURIComponent(id)+'&s'+encodeURIComponent(stato)+'';
            http.open('POST', url, true);

            //Send the proper header information along with the request
            /*http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

            http.onreadystatechange = function() {//Call a function when the state changes.
                if(http.readyState == 4 && http.status == 200) {
                    console.log('La chiamata ha funzionato');
                }
            }*/
            http.onload = function () {
                // do something to response
                console.log(this.responseText);
                $('#squadre1').bootstrapTable('refresh', {silent: true});
                $('#squadre2').bootstrapTable('refresh', {silent: true});
            };
            http.send(params);
            
            
            //$('#odl_ok').toast('show');

            //window.location.href = "ordini.php";
            return false;

        }
      </script>

        <form autocomplete="off" id="prospects_form" action="" onsubmit="return clickButton();">
        <input autocomplete="false" name="hidden" type="text" style="display:none;">
        <div class="row">
        
        
      <div class="form-group col-lg-3">           
				<input type="text" class="form-control" name="sq" id="sq" placeholder="Inserisci il nome della squadra" required>
      </div>
      <div name="conferma2" id="conferma2" class="form-group col-lg-3">
      <button type="submit" class="btn btn-primary">
      <i class="fa-solid fa-plus"></i> Crea Squadra
      </button>


      

      <!--input  name="conferma2" id="conferma2" type="submit" class="btn btn-primary glyphicon glyphicon-plus" value="Crea ODL"-->
      </div>


      <div id="no_sel" class="toast  text-white bg-danger  " data-autohide="false">  
      <div class="toast-header">
      <strong class="mr-auto text-primary">ATTENZIONE</strong>
      <!--small id="ora_notifica" class="text-muted">piccolo</small-->
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close">&times;</button>
    </div>
    <div id="notifica" class="toast-body">
      <b>Devi selezionare almento un intervento.</b>  
    </div>
  </div>


  
  <div id="odl_ok" class="toast  text-white bg-success  " data-autohide="false">
  <div class="toast-header">
      <strong class="mr-auto text-primary">Ordine di Lavoro creato</strong>
      <!--small id="ora_notifica" class="text-muted">piccolo</small-->
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close">&times;</button>
    </div>
    <div id="notifica" class="toast-body">
      <b>Visualizza l'odl nella pagina <a class="link-light" href="ordini.php">ordini</a> </b>  
    </div>
  </div>
      </div>
      </form>

      <?php }  # chiudo l'if del role?> 
      

      



        
        <hr>
        <h4>Squadre a disposizione</h4>
        <div class="row">

        <div id="tabella">
				<table  id="squadre1" class="table-hover table-sm" 
        idfield="id"
        data-toggle="table" data-url="./tables/squadre.php?s=t" 
        data-group-by="true"
        data-show-search-clear-button="true"   
        data-show-export="false" 
				data-search="true" data-click-to-select="true" data-show-print="false"  
				data-pagination="false" data-show-refresh="true" data-show-toggle="true"
				data-filter-control="true"
        data-toolbar="#toolbar" >
        
        
<thead>



 	<tr>
        <th data-field="descrizione" data-sortable="true" data-visible="true" data-filter-control="select">Nome squadra</th>
        <th data-field="id" data-sortable="true" data-visible="false" data-filter-control="select">Id</th>
        <th data-field="last_odl" data-sortable="true" data-visible="true" data-filter-control="input">Ultimo utilizzo</th>
        <?php if ($id_role > 0) { ?>
        <th data-field="valido" data-visible="true" data-formatter="pausa" data-filter-control="select">Stato</th>
        <?php } ?>
      </thead>
</table>
<script type="text/javascript" >
function odl(value) {
    //var icon = row.id % 2 === 0 ? 'fa-star' : 'fa-star-and-crescent'
    return '<i class="fa-solid fa-print"></i> <a href=odl.php?id='+value+'>Ordine di lavoro ' + value +'</a>';
  }

  function pausa(value,row) {
    //var icon = row.id % 2 === 0 ? 'fa-star' : 'fa-star-and-crescent'
    if (value=='t'){
        return 'Squadra in funzione <button onclick="cambia_stato('+row.id+', \'f\')" class="btn btn-danger btn-sm"  > \
    <i class="fa-solid fa-pause" title="Metti in pausa la squadra"></i></a>';
    } 
  }

</script>

</div>
        </div> <!--row-->



        <hr>
        <h4>Squadre storiche</h4>
        <div class="row">

        <div id="tabella">
				<table  id="squadre2" class="table-hover table-sm" 
        idfield="id"
        data-toggle="table" data-url="./tables/squadre.php?s=f" 
        data-group-by="true"
        data-show-search-clear-button="true"   
        data-show-export="false" 
				data-search="true" data-click-to-select="true" data-show-print="false"  
				data-pagination="false" data-show-refresh="true" data-show-toggle="true"
				data-filter-control="true"
        data-toolbar="#toolbar" >
        
        
<thead>



 	<tr>
   <th data-field="descrizione" data-sortable="true" data-visible="true" data-filter-control="select">Nome squadra</th>
        <th data-field="id" data-sortable="true" data-visible="false" data-filter-control="select">Id</th>
        <th data-field="last_odl" data-sortable="true" data-visible="true" data-filter-control="input">Ultimo utilizzo</th>
        <?php if ($id_role > 0) { ?>
        <th data-field="valido" data-visible="true" data-formatter="play" data-filter-control="select">Stato</th>
        <?php } ?>
</thead>
</table>
<script type="text/javascript" >
function odl(value) {
    //var icon = row.id % 2 === 0 ? 'fa-star' : 'fa-star-and-crescent'
    return '<i class="fa-solid fa-print"></i> <a href=odl.php?id='+value+'>Ordine di lavoro ' + value +'</a>';
  }

  function play(value,row) {
    //var icon = row.id % 2 === 0 ? 'fa-star' : 'fa-star-and-crescent'
    return '<button onclick="cambia_stato('+row.id+', \'t\')" class="btn btn-success btn-sm"  > \
    <i class="fa-solid fa-play" title="Metti squadra in funzione"></i></a>';
  }

</script>

</div>
        </div> <!--row-->






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
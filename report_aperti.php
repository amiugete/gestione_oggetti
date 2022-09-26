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
  
    <h3> Contatore interventi </h3>
    <div class="row">
    <div class="card col-sm-12" >
    <div class="card-body">
      <h5 class="card-title"><i class="fa-solid fa-list-check"></i> 
        <?php
        $query1="select count(distinct id) as interventi_totali
        from gestione_oggetti.v_intervento vi;";
        $result = pg_prepare($conn, "my_query1", $query1);
        $result = pg_execute($conn, "my_query1", array());
        while($r = pg_fetch_assoc($result)) {
            echo $r['interventi_totali'];
        }
    ?></h5>
    <h6 class="card-subtitle mb-2 text-muted">Interventi richiesti</h6>
    <p class="card-text"></p>
    </div>
    </div>

    </div>
    <div class="row">
  
    <div class="card col-sm-3" style="background: #66ff66;">
    <div class="card-body">
      <h5 class="card-title"><i class="fa-solid fa-check-double"></i>
        <?php
        $query2="select count(distinct id) as chiusi
        from gestione_oggetti.v_intervento vi
        where stato_descrizione ilike 'chiuso%';";
        $result = pg_prepare($conn, "my_query2", $query2);
        $result = pg_execute($conn, "my_query2", array());
        while($r = pg_fetch_assoc($result)) {
            echo $r['chiusi'];
        }
    ?></h5>
    <h6 class="card-subtitle mb-2 text-muted">Interventi chusi</h6>
    <p class="card-text"></p>
    </div>
    </div>


    <div class="card col-sm-3" style="background: #66ff66;">
    <div class="card-body">
      <h5 class="card-title"><i class="fa-solid fa-envelope-circle-check"></i> 
        <?php
        $query4="select count(distinct id) as abort
        from gestione_oggetti.v_intervento vi
        where stato_descrizione ilike 'abort%';";
        $result = pg_prepare($conn, "my_query4", $query4);
        $result = pg_execute($conn, "my_query4", array());
        while($r = pg_fetch_assoc($result)) {
            echo $r['abort'];
        }
    ?></h5>
    <h6 class="card-subtitle mb-2 text-muted">Interventi non necessari</h6>
    <p class="card-text"></p>
    </div>
    </div>



    <div class="card col-sm-3" style="background: #ffff66;">
    <div class="card-body">
      <h5 class="card-title"><i class="fa-solid fa-person-digging"></i> 
        <?php
        $query3="select count(distinct id) as inlav
        from gestione_oggetti.v_intervento vi
        where stato_descrizione ilike 'preso%';";
        $result = pg_prepare($conn, "my_query3", $query3);
        $result = pg_execute($conn, "my_query3", array());
        while($r = pg_fetch_assoc($result)) {
            echo $r['inlav'];
        }
    ?></h5>
    <h6 class="card-subtitle mb-2 text-muted">Interventi presi in carico</h6>
    <p class="card-text"></p>
    </div>
    </div>

    


    <div class="card col-sm-3" style="background: #ff5050;">
    <div class="card-body">
      <h5 class="card-title"><i class="fa-solid fa-pause"></i> 
        <?php
        $query5="select count(distinct id) as aperti
        from gestione_oggetti.v_intervento vi
        where stato_descrizione ilike 'aperto%';";
        $result = pg_prepare($conn, "my_query5", $query5);
        $result = pg_execute($conn, "my_query5", array());
        while($r = pg_fetch_assoc($result)) {
            echo $r['aperti'];
        }
    ?></h5>
    <h6 class="card-subtitle mb-2 text-muted">Interventi aperti</h6>
    <p class="card-text"></p>
    </div>
    </div>


  </div>
      

  





</div>

<?php


# FINE

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


  (function(){
    function refreshTable() {
      var selectedRows = getRowSelections();
      var selectedItems = '\n';
      $.each(selectedRows, function(index, value) {
        selectedItems += value.id + '\n';
      });
    
      if (selectedItems == '\n'){
        console.log('Refresh table');
        $('#interventi').bootstrapTable('refresh', {silent: true});
      } else {
        console.log('Non faccio refresh');
      }
    } 
    setInterval(refreshTable, 60000);
  })();

</script>




</body>

</html>
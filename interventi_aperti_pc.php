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
}  */  
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

    


      

    <!--div class="alert alert-danger alert-dismissible fade show" role="alert">
  <strong>ATTENZIONE!</strong> Devi selezionare almento un intervento.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div-->
 
        <!--h2> Gestione oggetti (<i class="fas fa-user"></i> ) 
        </h2-->
        <!--a href='report_pesi1.php' class='btn btn-info'> Grafici </a-->
        

        <h4>Interventi aperti e presi in carico</h4>




            <div class="row">

                  <!--div id="toolbar">
        <button id="showSelectedRows" class="btn btn-primary" type="button">Crea ordine di lavoro</button>
      </div-->
				<div id="tabella">
				<table  id="interventi_aperti_pc" class="table-hover" 
        idfield="id"
        data-toggle="table" data-url="./tables/interventi_aperti_pc.php" 
        data-group-by="false"
        data-group-by-field="piazzola"
        data-show-search-clear-button="true"   
        data-show-export="true" 
        data-export-type=['json', 'xml', 'csv', 'txt', 'sql', 'excel', 'doc', 'pdf'] 
				data-search="true" data-click-to-select="true" data-show-print="false"  
				data-pagination="false" data-page-size=75 data-page-list=[10,25,50,75,100,200,500]
				data-side-pagination="false"
        data-show-refresh="true" data-show-toggle="true"
				data-filter-control="true"
        data-toolbar="#toolbar" 
        data-query-params="queryParams">
        
        
<thead>



 	<tr>
        <!--th data-checkbox="true" data-field="id"></th-->  
        <!--th data-field="state" data-checkbox="true" ></th-->   
        <th data-field="piazzola" data-sortable="true" data-visible="true" data-filter-control="input">Piazzola</th>
        <th data-field="stato_intervento" data-sortable="true" data-visible="true" data-filter-control="select">Stato</th>
        <th data-field="tipo_intervento" data-sortable="true" data-visible="true" data-filter-control="select">Tipo</th>
        <th data-field="data_creazione" data-sortable="true" data-visible="true" data-formatter="dateFormatter" data-filter-control="input">Data<br>apertura</th>
        <th data-field="data_last" data-sortable="true" data-visible="true" data-formatter="dateFormatter" data-filter-control="input">Data agg.<br>stato</th>
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


<script>
  $(function() {
    $('#interventi_aperti_pc').bootstrapTable()
  })

  //************************************
  // Per esportare tutto
  var $table = $('#interventi_aperti_pc')

  $(function() {
    $table.bootstrapTable()
  })

  function queryParams(params) {
    var options = $table.bootstrapTable('getOptions')
    if (!options.pagination) {
      params.limit = options.totalRows
    }
    return params
  }
  //************************************


  /*data.forEach(d=>{
       data_creazione = moment(d.data_creazione).format('DD/MM/YYYY HH24:MI')
    });*/
    
    function dateFormatter(date) {
      return moment(date).format('DD/MM/YYYY HH:mm')
    }

</script>


<script type="text/javascript">  
  var $table = $('#interventi_aperti_pc');

  function getRowSelections() {
    return $.map($table.bootstrapTable('getSelections'), function(row) {
      return row;
    })
  }

  $('#showSelectedRows').click(function() {
    console.log("Bottone cliccato")
    var selectedRows = getRowSelections();
    var selectedItems = '\n';
    $.each(selectedRows, function(index, value) {
      selectedItems += value.id + '\n';
    });

    alert('The following products are selected: ' + selectedItems);
  
  




  });





  var verifyCheckBoxQuartieri = function () {
    console.log("Verifica")
    var selectedRows = getRowSelections();
    var selectedItems = '\n';
    $.each(selectedRows, function(index, value) {
      selectedItems += value.id + '\n';
    });
    /*if (selectedItems == '\n') {
      alert('Seleziona almeno un intervento');
    }*/
    var button = document.getElementById("conferma2");
    console.log(selectedItems);
    if (selectedItems == '\n'){
      $('#no_sel').toast('show');
    } else {
      return '500';
    }
    
};

$('#conferma2').click(verifyCheckBoxQuartieri);
  

  $(document).ready(function() {
    $('#js-date').datepicker({
        format: "yyyy-mm-dd",
        clearBtn: true,
        autoclose: true,
        todayHighlight: true
    });


    $("#prospects_form").submit(function(e) {
    e.preventDefault();
});
    
});
</script>
</div>	<!--tabella-->






           


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
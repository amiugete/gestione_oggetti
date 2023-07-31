<?php
session_set_cookie_params($lifetime);
session_start();

$id_odl=$_GET['id'];

/*if(!isset($_COOKIE['un'])) {
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
} */   
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
require_once('./tables/interventi_query.php');
$query_odl="SELECT o.data_prevista, o.chiuso, s.descrizione as squadra 
FROM gestione_oggetti.odl o
JOIN gestione_oggetti.squadra s on s.id = o.squadra_id
WHERE o.id = $1;";
$result = pg_prepare($conn, "my_query1", $query_odl);
$result = pg_execute($conn, "my_query1", array($id_odl));
while($r = pg_fetch_assoc($result)) {
  //echo "entrato <br>";
  $data_odl=$r['data_prevista'];
  $chiuso=$r['chiuso'];
  $sq=$r['squadra'];
}

?> 




</head>

<body>

<?php require_once('./navbar_up.php');
$name=dirname(__FILE__);
?>


      <div id="pagina" class="container">



      <div class="row">
        <h2> Ordine di lavoro n. <?php echo $id_odl;?> del <?php echo date('d/m/Y', strtotime($data_odl));?>
         (<i class="fas fa-users"></i><?php echo $sq;?> ) 
         <button class="btn btn-info noprint" onclick="printDiv('pagina')">
			<i class="fa fa-print" aria-hidden="true"></i> Stampa ODL </button>
        </h2> <hr>

        <?php 

        $query_where = "where id_stato_intervento not in (3,4) and odl_id =$1";
            
            
        $query= $query0." ". $query_where." ".$query_group;

        //print $query;


        /*id, tipo_intervento,
    stato_intervento, data_creazione, 
    elemento_id, 
    piazzola_id,
    utente,
    odl_id,
    priorita,
    desc_intervento,
    indirizzo, 
    quartiere, ut*/ 

     
        $result = pg_prepare($conn, "my_query2", $query);
        $result = pg_execute($conn, "my_query2", array($id_odl));
        while($r = pg_fetch_assoc($result)) {
          echo '<table style="width:100%"><tr><td><ul>';
          echo "<li> Tipo intervento: ".$r['tipo_intervento']."</li>";
          echo "<li> Contenitore: ".$r['rifiuto']. " da ".$r['volume']. " l</li>";
          echo "<li> Piazzola: ".$r['piazzola']."</li>";
          echo "<li> Posizione:";
          echo '<img src="https://chart.apis.google.com/chart?cht=qr&chs=150x150&chl=https://www.google.it/maps/dir//'.$r['lat'].','.$r['lon'].'/@'.$r['lat'].','.$r['lon'].',18z" alt="Link to Google Maps" width="150" height="150">';
          echo "</li>";
          echo "<li> Quartiere/comune e UT: ".$r['quartiere']. " - " .$r['ut']. "</li>";
          //Altri dettagli elemento 
          
            
            $result2 = pg_prepare($conn, "my_query3", $select_el);
            $result2 = pg_execute($conn, "my_query3", array($r['elemento_id'],$r['elemento_id']));
            while($r2 = pg_fetch_assoc($result2)) {
                if ($r2['matricola']!='') {
                  echo "<li> Matricola: ".$r2['matricola']. "</li>";
                }
                if ($r2['serratura']==1) {
                  $check_serratura=1;
                } else{
                  $check_serratura=0;
                }
                if ($r2['categoria']!='') {
                  echo "<li> Elemento PAP: ".$r2['categoria']. " - " .$r2['nome']. "</li>";
                } else {
                  echo "<li> Nessun cliente </li>";
                }

            }
          ?>
          </ul></td><td>
          <?php
          if ($check_serratura==1){
              echo '<i title="Elemento con serratura" class="fa-solid fa-key"></i>';
          } 
           echo "Id intervento: ".$r['id']."<br><br>";
          require 'vendor/autoload.php';

          // This will output the barcode as HTML output to display in the browser
          $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
          echo $generator->getBarcode($r['id'], $generator::TYPE_CODE_128);
         
          
          ?>
        
        
          </td></tr></table><b> Note aggiuntive: </b>
          <p> </p>
          <br><br><br><br>
          <hr>
          <?php 
        }
        
      echo "";
      ?>

     


				

</div>  <!-- /.row -->

<div class="row printFooter">
                
   <hr>     
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <?php
    $date = date_create(date(), timezone_open('Europe/Berlin'));
    $data = date_format($date, 'd-m-Y');
    $ora = date_format($date, 'H:i');
    //$data = date("d-m-Y");
    //$ora = date("H:i:s");
      echo "<table style=\"width:100%\"><tr><td>
      <small>Stampa ODL in data ".$data ." alle ore " .$ora." - applicativo Gestione Oggetti
      </small></td><td align=\"right\"><img style=\"height:30px;\" class=\"rounded\" src=\"./img/logo_amiu.jpg\" alt=\"\"> </td></tr></table>
      ";

    ?>
  </div>
</div> <!-- /.row -->


</div> <!-- pagina -->








</div>
</div>

<?php
require_once('req_bottom.php');
require('./footer.php');
?>

<script type="text/javascript" >


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
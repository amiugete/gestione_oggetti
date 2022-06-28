<?php
session_set_cookie_params($lifetime);
session_start();

$id_intervento="";
if ($_POST['id']){
  $id_intervento=$_POST['id'];
}

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

    <title>Gestione oggetti - Chiusura interventi </title>
<?php 
require_once('./req.php');

the_page_title();

if ($_SESSION['test']==1) {
  require_once ('./conn_test.php');
  
} else {
  require_once ('./conn.php');
}


  

?> 
<style>
#successo { display: none; }
</style>


</head>

<body>

<?php require_once('./navbar_up.php');
$name=dirname(__FILE__);
?>


      <div class="container">

      <div class="row">
        <h2> Chiusura interventi 
        </h2> <hr>

        
        <?php
        require_once('./blocco_viewer.php');

        if ($id_intervento==''){
          ?>
          
          <form autocomplete="off" id="prospects_form" method="post">
            <div class="form-group">
              <label for="exampleInputEmail1">Id intervento</label>
              <input type="number" required="" class="form-control" id="id" name="id" aria-describedby="idHelp" min =1 placeholder="Inserisci / pistola l'id intervento">
              <small id="idHelp" class="form-text text-muted">E' possibile usare la pistola USB per leggere il codice a barre.</small>
            </div>
            <button type="submit" class="btn btn-primary">Visualizza dettagli e chiudi</button>
          </form>

          <?php

        } else {
        ?> 
        
        <div id="dettagli">
        <?php 
          require_once('./tables/interventi_query.php');



          $query_where = "where id_stato_intervento = 5 and id =$1";
            
            
          $query= $query0." ". $query_where." ".$query_group;

 

          $check=0;
          $result = pg_prepare($conn, "my_query2", $query);
          $result = pg_execute($conn, "my_query2", array($id_intervento));
          while($r = pg_fetch_assoc($result)) {
            $check=1;
            echo '<ul>';
            echo "<li> Id intervento: ".$r['id']."</li>";
            echo "<li> Tipo intervento: ".$r['tipo_intervento']."</li>";
            echo "<li> Contenitore: ".$r['rifiuto']. " da ".$r['volume']. " l</li>";
            echo '<li> Piazzola: <a href='.$url_sit.'/#!/home/edit-piazzola/'.$r["piazzola_id"].'/" target="_new">'.$r["piazzola"].'</a></li>';
            echo "<li> Quartiere/comune e UT: ".$r['quartiere']. " - " .$r['ut']. "</li>";
            //Altri dettagli elemento
            ?>
            </ul>
            
             
            <h4> Altri dettagli elemento: </h4><ul>
            <?php 
            $result2 = pg_prepare($conn, "my_query3", $select_el);
            $result2 = pg_execute($conn, "my_query3", array($r['elemento_id'],$r['elemento_id']));
            while($r2 = pg_fetch_assoc($result2)) {
              echo "<li> Matricola: ";
                if ($r2['matricola']!='') {
                  echo "".$r2['matricola']. "</li>";
                } else {
                  echo "nd</li>";
                }
                echo "<li> Materiale: ";
                if ($r2['matricola']!='') {
                  echo "".$r2['materiale']. "</li>";
                } else {
                  echo "nd</li>";
                }
                if ($r2['serratura']==1) {
                  echo '<li><i class="fa-solid fa-key"></i> Serratura presente</li>';
                } else{
                  echo "<li>Nessuna serratura</li>";
                }
                if ($r2['categoria']!='') {
                  echo '<li> <i class="fa-solid fa-user-tie"></i> Elemento PAP: '.$r2["categoria"]. ' - ' .$r2["nome"]. '</li>';
                } else {
                  echo '<li> Nessun cliente </li>';
                }
          
              } 
            ?>
            </ul>


            <script type="text/javascript">
        
        function clickButton() {
            console.log("Bottone  form cliccato");

            var id=document.getElementById('id').value;
            //var materiale= document.getElementById('materiale').text();
            console.log(id);
            var el=document.getElementById('el').value;
            //var materiale= document.getElementById('materiale').text();
            console.log(el);
            var nc='';
            nc=document.getElementById('nc').value;
            if (nc==''){
              nc= 'ND';
            }
            console.log(nc);
        
      
				


            var http = new XMLHttpRequest();
            var url = 'chiusura2.php';
            var params = 'id='+encodeURIComponent(id)+'&nc='+encodeURIComponent(nc)+'&el='+encodeURIComponent(el)+'';
            http.open('POST', url, true);

            //Send the proper header information along with the request
            http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

            http.onreadystatechange = function() {//Call a function when the state changes.
                if(http.readyState == 4 && http.status == 200) {
                    if (http.responseText == 3) {
                      //alert("Intervento chiuso con successo");
                    } else {
                      //alert(http.responseText);
                    }
                }
            }
            http.send(params);
            
            
            
            /*console.log(url);
            // get the URL
            http = new XMLHttpRequest(); 
            http.open("GET", url, true);
            http.send();
            console.log('Verifichiamo lo stato');
            console.log(http.readyState);*/


            //window.location.href = "chiusura.php";
            $("#dettagli").hide();
            $("#successo").show();
            return false;

        }
      </script>

            <form autocomplete="off" id="prospects_form2" action="" onsubmit="return clickButton();">
            <input type="hidden" id="id" name="id" value="<?php echo $r['id']?>">
            <input type="hidden" id="el" name="el" value="<?php echo $r['elemento_id']?>">
            <div class="form-group">
              <label for="exampleInputEmail1">Note chiusura</label>
              <textarea type="textarea" class="form-control" id="nc" name="nc" aria-describedby="ncHelp" placeholder="Specificare qua eventuali note chiusura" rows="4"></textarea>
              <small id="ncHelp" class="form-text text-muted">Inserire qua eventuali note di chiusura.</small>
            </div>
            <button type="submit" class="btn btn-primary">Procedi</button>
            <button class="btn btn-warning" onClick="window.location.reload();">Annulla</button>
          </form>
        
          </div>  

            <div id="successo">
              <h3> Intervento chiuso con successo</h3>
              <form autocomplete="off" id="prospects_form3" action="" method="post">
              <button class="btn btn-warning" onClick="window.location.reload();">Passa a un nuovo intervento</button>
              </form>
            </div>
     

            <?php 
          }

          if ($check==0){ 
            echo '<h3> <i class="fa-solid fa-triangle-exclamation"></i> Non esiste nessun intervento preso in carico con id '.$id_intervento.'</h3>';
            ?>
            <form autocomplete="off" id="prospects_form4" action="" method="post">
              <button class="btn btn-warning" onClick="window.location.reload();">Ricarica la pagina</button> 
            </form>
            
            <?php
          }
        
      echo "";
      }
      ?>

     


				

</div>




</div>
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
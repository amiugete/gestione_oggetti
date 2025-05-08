<?php

require_once('./check_utente.php');

$query_role='SELECT  sr.id_role, sr."name" as "role" FROM util_go.sys_users su
join util_go.sys_roles sr on sr.id_role = su.id_role  
where su."name" ilike $1;';
$result_n = pg_prepare($conn, "my_query_navbar", $query_role);
$result_n = pg_execute($conn, "my_query_navbar", array($_SESSION['username']));

$check=0;
while($r = pg_fetch_assoc($result_n)) {
  $role=$r['role'];
  $id_role=$r['id_role'];
  $check=1;
}

if ($check==0){
  #inserisco l'utente nella tabella
  $id_role=0;
  $role="VIEWER";
  $insert= "INSERT INTO util_go.sys_users
(domain_name, \"name\", id_role, last_access, email)
VALUES('DSI', $1, 0, now(), null);";
  $result_n = pg_prepare($conn, "my_query_insert_user", $insert);
  $result_n = pg_execute($conn, "my_query_insert_user", array($_SESSION['username']));

  $des="Inserito nuovo utente ".$_SESSION['username']." con permessi in sola visualizzazione";
  $query_h="INSERT INTO util_go.sys_history
(type, action, description, datetime, id_user)
VALUES ('NUOVO UTENTE ', 'INSERT', $1, now(), (select id_user from util_go.sys_users su where name ilike $2));";
  $result_h = pg_prepare($conn, "my_query_new_user", $query_h);
  $result_h = pg_execute($conn, "my_query_new_user", array($des, $_SESSION['username']));


} else {
  $update='UPDATE util_go.sys_users
  SET last_access=now()
  WHERE "name" ilike $1';
  $result_n = pg_prepare($conn, "my_query_update_user", $update);
  $result_n = pg_execute($conn, "my_query_update_user", array($_SESSION['username']));
}


// Faccio il controllo anche su SIT

$query_role='SELECT  su.id_user, sr.id_role, sr."name" as "role" FROM util.sys_users su
join util.sys_roles sr on sr.id_role = su.id_role  
where su."name" ilike $1;';
$result_n = pg_prepare($conn, "my_query_navbar1", $query_role);
$result_n = pg_execute($conn, "my_query_navbar1", array($_SESSION['username']));

$check_SIT=0;
while($r = pg_fetch_assoc($result_n)) {
  //$role_SIT=$r['role'];
  //$id_role_SIT=$r['id_role'];
  //$id_user_SIT=$r['id_user'];
  $check_SIT=1;
}

if ($check_SIT==0){
  #inserisco l'utente nella tabella
  $insert= "INSERT INTO util.sys_users
(domain_name, \"name\", id_role, last_access, email)
VALUES('DSI', $1, 0, now(), null);";
  $result_n = pg_prepare($conn, "my_query_insert_user_sit", $insert);
  $result_n = pg_execute($conn, "my_query_insert_user_sit", array($_SESSION['username']));

  $des="Inserito nuovo utente ".$_SESSION['username']." su SIT con permessi in sola visualizzazione";
  $query_h="INSERT INTO util_go.sys_history
(type, action, description, datetime, id_user)
VALUES ('NUOVO UTENTE ', 'INSERT', $1, now(), (select id_user from util_go.sys_users su where name ilike $2));";
  $result_h = pg_prepare($conn, "my_query_new_user_sit", $query_h);
  $result_h = pg_execute($conn, "my_query_new_user_sit", array($des, $_SESSION['username']));
}

?>

<div class="banner"> <div id="banner-image"></div> 
<h3>  <a class="navbar-brand link-light" href="#">
    <img class="pull-left" src="img\amiu_small_white.png" alt="SIT" width="85px">
    <span>Gestione oggetti- Reportistica</span> 
  </a> 
</h3>
</div>
<nav class="navbar navbar-inverse navbar-fixed-top navbar-expand-lg navbar-light">
  <div class="container-fluid">
    <!--a class="navbar-brand" href="#">
    <img class="pull-left" src="img\amiu_small_white.png" alt="SIT" width="85px">
    </a-->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <ul class="navbar-nav ms-auto flex-nowrap">
        <!--li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Home</a>
        </li-->
        <?php if ($_SESSION['username']=='Marzocchi') { ?>
        <li class="nav-item">
          <a class="nav-link" href="./interventi.php">Interventi aperti (T)</a>
        </li>
        
        <li class="nav-item">
          <a class="nav-link" href="./ordini.php">Ordini di lavoro (T)</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="./squadre.php">Squadre (T)</a>
        </li>
        <?php if ($id_role > 0) { ?>
        <li class="nav-item">
          <a class="nav-link" href="./chiusura.php">Chiusura interventi (T)</a>
        </li>
        <?php } ?>
        <?php } ?>
        <li class="nav-item">
          <a class="nav-link" href="./statistiche.php">Statistiche</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Report
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="./interventi.php">Interventi aperti (esclusi presi in carico)</a></li>
            <li><a class="dropdown-item" href="./interventi_aperti_pc.php">Interventi aperti e/o presi in carico</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="./report_prod_squadra.php">Report produzione giornaliera per squadra</a></li>
            <li><a class="dropdown-item" href="./report_prod_reparto.php">Report produzione giornaliera reparto</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="./report_ut.php">Report interventi per UT</a></li>
            <li><a class="dropdown-item" href="./report_tipo.php">Report interventi per Tipo</a></li>
            <li><a class="dropdown-item" href="./interventi_chiusi.php">Report interventi chiusi</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="./report_data_ut_tipo.php">Report richieste giornaliere per UT e tipo</a></li>
            <li><a class="dropdown-item" href="./report_data_ut.php">Report richieste giornaliere per UT</a></li>
            <li><a class="dropdown-item" href="./report_data_tipo.php">Report richieste giornaliere per tipo</a></li>
          </ul>
        </li>
        <!--li class="nav-item">
          <a class="nav-link" href="#">Pricing</a>
        </li>
        <li class="nav-item">
          <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
        </li-->
      </ul>
      <div class="collapse navbar-collapse flex-grow-1 text-right" id="myNavbar">
        <ul class="navbar-nav ms-auto flex-nowrap">
          <i class="fas fa-user"></i>Connesso come <?php echo $_SESSION['username'];?> (<?php echo $role;?>)
      </ul>

    </div>
  </div>
</nav>

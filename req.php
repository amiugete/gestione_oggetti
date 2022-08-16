<?php
session_start();

?>


<!--link rel="stylesheet" href="https://kit-free.fontawesome.com/releases/latest/css/free.min.css"-->
<link rel="stylesheet" href="./vendor/fontawesome-free-6.1.1-web/css/all.min.css">

<script
  src="https://code.jquery.com/jquery-3.6.0.min.js"
  integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
  crossorigin="anonymous"></script>


<!-- Production version -->
<!--script src="https://unpkg.com/@popperjs/core@2" crossorigin="anonymous"></script-->
  


<link rel='icon' href='./favicon.ico' type='image/x-icon' sizes="16x16">

<!--script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script-->

<!-- Bootstrap Core CSS -->
<link rel="stylesheet" href="./vendor/twbs/bootstrap/dist/css/bootstrap.min.css">


<!--ICONE -->
<!--link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css"-->
<link rel="stylesheet" href="./vendor/twbs/bootstrap-icons/font/bootstrap-icons.css">


<!-- Bootstrap Plugins -->
<!-- BOOTSTRAP TABLE -->
<!--link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.18.3/dist/bootstrap-table.min.css"-->
<link rel="stylesheet" href="./vendor/bootstrap-table/dist/bootstrap-table.min.css">
<link rel="stylesheet" href="./vendor/bootstrap-table/dist/extensions/filter-control/bootstrap-table-filter-control.css">
<link rel="stylesheet" href="./vendor/bootstrap-table/dist/extensions/group-by-v2/bootstrap-table-group-by.min.css">


<!-- BOOTSTRAP SELECT -->
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">


<!-- BOOTSTRAP DATEPICKER -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<link href="./main.css" rel="stylesheet">

<!--link href="./bootstrap-table-1.18.3/dist/bootstrap-table.css" rel="stylesheet"-->

<!--link href="./bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet"-->


<!--script src="./jquery.js"></script-->
<!-- jQuery -->
<script src="./vendor/jquery/jquery-3.6.0.min.js"></script>



<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<style>
.highcharts-figure,
.highcharts-data-table table {
    min-width: 320px;
    max-width: 660px;
    margin: 1em auto;
}

.highcharts-data-table table {
    font-family: Verdana, sans-serif;
    border-collapse: collapse;
    border: 1px solid #ebebeb;
    margin: 10px auto;
    text-align: center;
    width: 100%;
    max-width: 500px;
}

.highcharts-data-table caption {
    padding: 1em 0;
    font-size: 1.2em;
    color: #555;
}

.highcharts-data-table th {
    font-weight: 600;
    padding: 0.5em;
}

.highcharts-data-table td,
.highcharts-data-table th,
.highcharts-data-table caption {
    padding: 0.5em;
}

.highcharts-data-table thead tr,
.highcharts-data-table tr:nth-child(even) {
    background: #f8f8f8;
}

.highcharts-data-table tr:hover {
    background: #f1f7ff;
}
</style>


<?php
$problemi =  'contattare l\'<a href="mailto:assterritorio@amiu.genova.it">amministratore di sistema</a>';

function the_page_title()
{
    $page_name = getcwd(); // getcwd() gets the directory of the file you call the function from
    $each_page_name = explode('/', $page_name);
    $len_page_dir = count($each_page_name) -1;
    $temp = explode('_', $each_page_name[$len_page_dir]);
    $len_temp=count($temp)-1;
    if ($temp[$len_temp]=='oggetti'){
        $_SESSION['test']=0;
    } else if($temp[$len_temp]=='test') {
        ?>
        <style>
            .navbar {
                background: #ff0000;
            }
        </style>
        <?php
        $test=1;
        $_SESSION['test']=1;
    } else {
        echo "L'indirizzo è sbagliato. Contattare l'amministratore di sistema assterritorio@amiu.genova.it";
    }
    return $test;
}

?>
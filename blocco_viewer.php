<?php 
if ($id_role == 0) { 
?>
    <h4>  <i class="fa-solid fa-circle-minus"  style="color:red" ></i>
    In quanto <?php echo $role;?> non sei abilitato a visualizzare le funzionalità di questa pagina</h4>
    <hr>
<?php 
    exit;
}  
?>
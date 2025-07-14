<?php
include("../inc/fonction.php");
$id_emprunt = $_POST['id_emprunt'];
$temp = $_POST['duree'] ;
set_date_fin($temp,$id_emprunt);
header("Location: objects.php");
?>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//echo "coucou";
include("../inc/fonction.php");
$id_emprunt = $_POST['id_emprunt'];
$temp = $_POST['duree'];
set_date_fin($temp, $id_emprunt);
header("Location: objects.php");
exit;
?>
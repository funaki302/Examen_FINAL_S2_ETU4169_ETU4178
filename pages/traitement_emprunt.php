<?php
session_start();
include("../inc/fonction.php");
$id_objet = $_GET["id_obj"];

$id_emprunt = emprunt( $id_objet ,$_SESSION['user_id']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="real_traitement1.php" method="post">
        <input type="hidden" name="id_emprunt" value="<?=$id_emprunt?>">
        <input type="number" name="duree" id="">
        <input type="submit" value="Valider">
    </form>
</body>
</html>
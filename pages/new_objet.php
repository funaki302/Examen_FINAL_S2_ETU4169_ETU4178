<?php
include("../inc/fonction.php");
session_start();
$id = $_SESSION['user_id'];
$categories = get_all_categorie();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../asset/css/style.css">
</head>

<body>
    <form action="uploder.php" method="post">
        <input type="hidden" name="id_membre" value="<?=$id?>">
        <p>Nom:<input type="text" name="nom_objet" id=""></p>
        <p>Categorie:<select name="categorie" id="">
            <?php foreach($categories as $c){?>
                <option value="<?=$c['id_categorie']?>"><?=$c['nom_categorie']?></option>
            <?php }?>
        </select></p>
        <p>Images:<input type="file" id="media" name="photos" accept="image/*" required></p>
        <input type="submit" value="Ajouter">
    </form>
</body>

</html>
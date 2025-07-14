<?php
session_start();
require_once __DIR__ . '/../inc/fonction.php';


if (!isset($_SESSION['user_id'])) {
    header("Location:index.php");
    exit;
}
$categories = getCategories();

$filter_category = $_GET['category'] ?? '';

$objects = getObjects( $filter_category);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <title>Liste des </title>
    <link rel="stylesheet" href="../asset/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../asset/css/style.css">

</head>
<body>
    <div class="container mt-4">
        <h1>Liste des objets</h1>
        <p>| <a href="profil.php" >Profil</a> |<a href="index.php">Deconnexion</a></p>
        <p><a href="new_objet.php">Publier</a></p>
        <h1><?= htmlspecialchars($_SESSION['user_name']) ?> </h1>
        <p><a href="recherche.php"> Rechercher </a></p>    
        <form method="get" class="form-inline mb-3">
            <label for="category" class="mr-2">Filtrer par categorie :</label>
            <select name="category" id="category" class="form-control mr-2">
                <option value="">Toutes</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id_categorie'] ?>" <?= ($filter_category == $cat['id_categorie']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['nom_categorie']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary">Filtrer</button>
        </form>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nom de l'objet</th>
                    <th>Categorie</th>
                    <th>Date de retour (si emprunte)</th>
                    <th>emprunte</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($objects as $obj): ?>
                    <tr>
                        <td><?= htmlspecialchars($obj['nom_objet']) ?></td>
                        <td><?= htmlspecialchars($obj['nom_categorie']) ?></td>
                        <td><?= $obj['date_retour'] ? ("Disponible le :"+ htmlspecialchars($obj['date_retour']) ): 'Disponible' ?></td>
                        <td><a href="traitement_emprunt.php?id_obj=<?=$obj['id_objet']?>">Emprunter</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
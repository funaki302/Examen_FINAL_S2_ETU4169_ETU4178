<?php
session_start();
require_once __DIR__ . '/../inc/fonction.php';


if (!isset($_SESSION['user_id'])) {
    header("Location:index.php");
    exit;
}

$bdd = connecterBase();

$categories = getCategories($bdd);

$filter_category = $_GET['category'] ?? '';

$objects = getObjects($bdd, $filter_category);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <title>Liste des objets - Site d'emprunt d'objets</title>
    <link rel="stylesheet" href="../asset/bootstrap/css/bootstrap.min.css" />
</head>

<body>
    <div class="container mt-4">
        <h1>Liste des objets</h1>
        <p>Bienvenue, <?= htmlspecialchars($_SESSION['user_name']) ?> | <a href="index.php">Déconnexion</a></p>

        <form method="get" class="form-inline mb-3">
            <label for="category" class="mr-2">Filtrer par catégorie :</label>
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
                    <th>Catégorie</th>
                    <th>Date de retour (si emprunté)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($objects as $obj): ?>
                    <tr>
                        <td><?= htmlspecialchars($obj['nom_objet']) ?></td>
                        <td><?= htmlspecialchars($obj['nom_categorie']) ?></td>
                        <td><?= $obj['date_retour'] ? htmlspecialchars($obj['date_retour']) : 'Disponible' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
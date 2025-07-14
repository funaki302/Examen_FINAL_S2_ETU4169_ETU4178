<?php
session_start();
require_once __DIR__ . '/../inc/fonction.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$nom = $_GET['nom'] ?? '';
$categorie = $_GET['category'] ?? '';
$disponible = isset($_GET['disponible']) ? intval($_GET['disponible']) : null;

$categories = getCategories();
$objects = $_SESSION['search_results'] ?? [];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Liste des objets</title>
    <link rel="stylesheet" href="../asset/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../asset/css/style.css">
</head>
<body>
<div class="container mt-4">
    <h1>Liste des objets</h1>
    <p>Bienvenue, <?= htmlspecialchars($_SESSION['user_name']) ?> | <a href="index.php?logout=1">Déconnexion</a></p>
    
    <!-- Formulaire de recherche -->
     <p><a href="objects.php">Retour</a></p>
    <form method="get" action="traitement_recherche.php" class="mb-3">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="nom">Nom de l'objet</label>
                    <input type="text" name="nom" id="nom" class="form-control" value="<?= htmlspecialchars($nom) ?>" placeholder="Entrez un nom d'objet">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="category">Catégorie</label>
                    <select name="category" id="category" class="form-control">
                        <option value="">Toutes</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id_categorie'] ?>" <?= ($categorie == $cat['id_categorie']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['nom_categorie']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="disponible">Disponibilité</label>
                    <select name="disponible" id="disponible" class="form-control">
                        <option value="" <?= $disponible === null ? 'selected' : '' ?>>Tous</option>
                        <option value="1" <?= $disponible === 1 ? 'selected' : '' ?>>Disponible</option>
                        <option value="0" <?= $disponible === 0 ? 'selected' : '' ?>>Indisponible</option>
                    </select>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-2">Rechercher</button>
    </form>

    <!-- Affichage des résultats -->
    <?php if (empty($objects)): ?>
        <p class="mt-3">Aucun objet trouvé.</p>
    <?php else: ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nom de l'objet</th>
                    <th>Catégorie</th>
                    <th>Propriétaire</th>
                    <th>Image</th>
                    <th>Disponibilité</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($objects as $obj): ?>
                    <tr>
                        <td><?= htmlspecialchars($obj['nom_objet']) ?></td>
                        <td><?= htmlspecialchars($obj['nom_categorie']) ?></td>
                        <td><?= htmlspecialchars($obj['proprietaire']) ?></td>
                        <td>
                            <?php if ($obj['image']): ?>
                                <img src="../images/<?= htmlspecialchars($obj['image']) ?>" alt="Image de l'objet" width="50">
                            <?php else: ?>
                                Aucune image
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php
                            $bdd = dbconnect();
                            $query = "SELECT date_retour FROM emprunt WHERE id_objet = " . intval($obj['id_objet']) . " AND date_retour IS NULL";
                            $result = mysqli_query($bdd, $query);
                            echo mysqli_num_rows($result) > 0 ? 'Indisponible' : 'Disponible';
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<script src="../asset/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
session_start();
require_once __DIR__ . '/../inc/fonction.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$bdd = dbconnect();
$id_membre = intval($_SESSION['user_id']);

$query_membre = "SELECT nom, date_naissance, genre, email, ville, image_profil 
                 FROM membre 
                 WHERE id_membre = $id_membre";
$result_membre = mysqli_query($bdd, $query_membre);

if (!$result_membre || mysqli_num_rows($result_membre) == 0) {
    header("Location: index.php?message=" . urlencode("Membre non trouve."));
    exit;
}

$membre = mysqli_fetch_assoc($result_membre);
$query_objets = "SELECT o.id_objet, o.nom_objet, c.nom_categorie, 
                        (SELECT nom_image FROM images_objet WHERE id_objet = o.id_objet LIMIT 1) AS image
                 FROM objet o
                 JOIN categorie_objet c ON o.id_categorie = c.id_categorie
                 WHERE o.id_membre = $id_membre";
$result_objets = mysqli_query($bdd, $query_objets);
$objects = [];
while ($row = mysqli_fetch_assoc($result_objets)) {
    $objects[] = $row;
}

$emprunts = get_list_emprunt($id_membre);

$message = isset($_GET['message']) ? urldecode($_GET['message']) : '';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <title>Fiche du membre connecte</title>
    <link rel="stylesheet" href="../asset/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../asset/css/style.css">
</head>

<body>
    <div class="container mt-4">
        <h1>Fiche du membre connecte</h1>
        <p>Bienvenue, <?= htmlspecialchars($_SESSION['user_name']) ?> | <a href="index.php?logout=1">Deconnexion</a> |
            <a href="objects.php">Retour à la liste des objets</a>
        </p>

        <!-- Afficher un message si present -->
        <?php if ($message): ?>
            <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <!-- Informations du membre -->
        <h2>Informations personnelles</h2>
        <div class="card mb-4">
            <div class="card-body">
                <p><strong>Nom :</strong> <?= htmlspecialchars($membre['nom']) ?></p>
                <p><strong>Date de naissance :</strong> <?= htmlspecialchars($membre['date_naissance']) ?></p>
                <p><strong>Genre :</strong>
                    <?= htmlspecialchars($membre['genre'] === 'M' ? 'Masculin' : ($membre['genre'] === 'F' ? 'Feminin' : 'Autre')) ?>
                </p>
                <p><strong>Email :</strong> <?= htmlspecialchars($membre['email']) ?></p>
                <p><strong>Ville :</strong> <?= htmlspecialchars($membre['ville']) ?></p>
                <p><strong>Image de profil :</strong>
                    <?php if ($membre['image_profil']): ?>
                        <img src="../images/<?= htmlspecialchars($membre['image_profil']) ?>" alt="Image de profil"
                            width="100">
                    <?php else: ?>
                        Aucune image
                    <?php endif; ?>
                </p>
            </div>
        </div>

        <!-- Liste des objets du membre -->
        <h2>Vos objets</h2>
        <?php if (empty($objects)): ?>
            <p>Vous n'avez aucun objet.</p>
        <?php else: ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom de l'objet</th>
                        <th>Categorie</th>
                        <th>Image</th>
                        <th>Disponibilite</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($objects as $obj): ?>
                        <tr>
                            <td><?= htmlspecialchars($obj['id_objet']) ?></td>
                            <td><?= htmlspecialchars($obj['nom_objet']) ?></td>
                            <td><?= htmlspecialchars($obj['nom_categorie']) ?></td>
                            <td>
                                <?php if ($obj['image']): ?>
                                    <img src="../images/<?= htmlspecialchars($obj['image']) ?>" alt="Image de l'objet" width="50">
                                <?php else: ?>
                                    Aucune image
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
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

        <!-- Liste des emprunts non rendus -->
        <h2>Vos emprunts en cours</h2>
        <?php if (empty($emprunts)): ?>
            <p>Vous n'avez aucun emprunt en cours.</p>
        <?php else: ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Objet</th>
                        <th>Categorie</th>
                        <th>Proprietaire</th>
                        <th>Image</th>
                        <th>Date d'emprunt</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($emprunts as $emprunt): ?>
                        <tr>
                            <td><?= htmlspecialchars($emprunt['nom_objet']) ?></td>
                            <td><?= htmlspecialchars($emprunt['nom_categorie']) ?></td>
                            <td><?= htmlspecialchars($emprunt['proprietaire']) ?></td>
                            <td>
                                <?php if ($emprunt['image']): ?>
                                    <img src="../images/<?= htmlspecialchars($emprunt['image']) ?>" alt="Image de l'objet"
                                        width="50">
                                <?php else: ?>
                                    Aucune image
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($emprunt['date_emprunt']) ?></td>
                            <td>
                                <form method="post" action="real_traitement1.php" class="d-inline">
                                    <input type="hidden" name="id_emprunt"
                                        value="<?= htmlspecialchars($emprunt['id_emprunt']) ?>">
                                    <button type="submit" class="btn btn-primary btn-sm">Rendre</button>
                                </form>
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
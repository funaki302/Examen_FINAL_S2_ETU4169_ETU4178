<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Inscription - Site d'emprunt d'objets</title>
    <link rel="stylesheet" href="../asset/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../asset/css/style.css">
</head>
<body>
<div class="container mt-4">
    <h1>Inscription</h1>
    <?php if (isset($_GET['message'])): ?>
        <div class="alert alert-info"><?= htmlspecialchars(urldecode($_GET['message'])) ?></div>
    <?php endif; ?>
    <form method="post" action="traitement_register.php">
        <div class="form-group">
            <label>Nom</label>
            <input type="text" name="nom" class="form-control" required />
        </div>
        <div class="form-group">
            <label>Date de naissance</label>
            <input type="date" name="date_naissance" class="form-control" required />
        </div>
        <div class="form-group">
            <label>Genre</label>
            <select name="genre" class="form-control" required>
                <option value="M">Masculin</option>
                <option value="F">Feminin</option>
            </select>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required />
        </div>
        <div class="form-group">
            <label>Ville</label>
            <input type="text" name="ville" class="form-control" required />
        </div>
        <div class="form-group">
            <label>Mot de passe</label>
            <input type="password" name="mdp" class="form-control" required />
        </div>
        <button type="submit" name="register" class="btn btn-success">S'inscrire</button>
    </form>
    <p class="mt-3">Dejà un compte ? <a href="../pages/index.php">Connectez-vous ici</a></p>
</div>
<script src="../asset/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
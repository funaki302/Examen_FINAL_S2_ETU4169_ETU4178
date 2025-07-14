<?php
session_start();

// Gestion de la déconnexion
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Connexion - Site d'emprunt d'objets</title>
    <link rel="stylesheet" href="../asset/bootstrap/css/bootstrap.min.css" />
        <link rel="stylesheet" href="../asset/css/style.css">

</head>
<body>
<div class="container mt-4">
    <h1>Connexion</h1>
    <?php if (isset($_GET['message'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars(urldecode($_GET['message'])) ?></div>
    <?php endif; ?>
    <form method="post" action="traitement_login.php" class="mb-3">
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required />
        </div>
        <div class="form-group">
            <label>Mot de passe</label>
            <input type="password" name="mdp" class="form-control" required />
        </div>
        <button type="submit" name="login" class="btn btn-primary">Se connecter</button>
    </form>
    <p>Pas encore de compte ? <a href="register.php">Inscrivez-vous ici</a></p>
</div>
<script src="../asset/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
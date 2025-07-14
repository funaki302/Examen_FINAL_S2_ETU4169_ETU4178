<?php
session_start();
require_once __DIR__ . '/../inc/fonction.php';

/* Suppression de la redirection automatique vers objects.php pour que index.php s'ouvre toujours en premier */
// if (isset($_SESSION['user_id'])) {
//     header("Location: objects.php");
//     exit;
// }

$bdd = connecterBase();
$message = '';

if (isset($_POST['login'])) {
    $email = $_POST['email'] ?? '';
    $mdp = $_POST['mdp'] ?? '';

    if ($email && $mdp) {
        $email = mysqli_real_escape_string($bdd, $email);
        $query = "SELECT * FROM membre WHERE email = '$email'";
        $result = mysqli_query($bdd, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            if (password_verify($mdp, $user['mdp'])) {
                $_SESSION['user_id'] = $user['id_membre'];
                $_SESSION['user_name'] = $user['nom'];
                header("Location: objects.php");
                exit;
            } else {
                $message = "Email ou mot de passe incorrect.";
            }
        } else {
            $message = "Email ou mot de passe incorrect.";
        }
    } else {
        $message = "Veuillez remplir tous les champs.";
    }
}

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
</head>
<body>
<div class="container mt-4">
    <h1>Connexion</h1>
    <?php if ($message): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <form method="post" class="mb-3">
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
<script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>

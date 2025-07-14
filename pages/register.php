<?php
session_start();
require_once __DIR__ . '/../inc/fonction.php';

$bdd = connecterBase();
$message = '';


if (isset($_POST['register'])) {
    $nom = $_POST['nom'] ;
    $date_naissance = $_POST['date_naissance'] ;
    $genre = $_POST['genre'] ;
    $email = $_POST['email'] ;
    $ville = $_POST['ville'] ;
    $mdp = $_POST['mdp'] ;

    if ($nom && $date_naissance && $genre && $email && $ville && $mdp) {
        $email = mysqli_real_escape_string($bdd, $email);
        $query = "SELECT * FROM membre WHERE email = '$email'";
        $result = mysqli_query($bdd, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            $message = "Cet email est déjà utilisé.";
        } else {
            $hashed_mdp = password_hash($mdp, PASSWORD_DEFAULT);
            $nom = mysqli_real_escape_string($bdd, $nom);
            $date_naissance = mysqli_real_escape_string($bdd, $date_naissance);
            $genre = mysqli_real_escape_string($bdd, $genre);
            $ville = mysqli_real_escape_string($bdd, $ville);
            $query = "INSERT INTO membre (nom, date_naissance, genre, email, ville, mdp) VALUES ('$nom', '$date_naissance', '$genre', '$email', '$ville', '$hashed_mdp')";
            if (mysqli_query($bdd, $query)) {
                $message = "Inscription réussie. Vous pouvez maintenant vous connecter.";
            } else {
                $message = "Erreur lors de l'inscription.";
            }
        }
    } else {
        $message = "Veuillez remplir tous les champs.";
    }
}


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
</head>
<body>
<div class="container mt-4">
    <h1>Inscription</h1>
    <?php if ($message): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <form method="post">
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
                <option value="F">Féminin</option>
                <option value="Autre">Autre</option>
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
    <p class="mt-3">Déjà un compte ? <a href="index.php">Connectez-vous ici</a></p>
</div>
<script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>

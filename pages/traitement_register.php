<?php
session_start();
require_once __DIR__ . '/../inc/fonction.php';


if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$bdd = dbconnect();

if (isset($_POST['register'])) {
    $nom = $_POST['nom'];
    $date_naissance = $_POST['date_naissance'];
    $genre = $_POST['genre'];
    $email = $_POST['email'];
    $ville = $_POST['ville'];
    $mdp = $_POST['mdp'];


    if ($nom && $date_naissance && $genre && $email && $ville && $mdp) {
        $query = "SELECT * FROM membre WHERE email = '$email'";
        $result = mysqli_query($bdd, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            header("Location: register.php?message=" . urlencode("Cet email est déjà utilisé."));
            exit;
        } else {

            $query = "INSERT INTO membre (nom, date_naissance, genre, email, ville, mdp) VALUES ('$nom', '$date_naissance', '$genre', '$email', '$ville', '$mdp')";
            if (mysqli_query($bdd, $query)) {
                header("Location: register.php?message=" . urlencode("Inscription réussie. Vous pouvez maintenant vous connecter."));
                exit;
            } else {
                header("Location: register.php?message=" . urlencode("Erreur lors de l'inscription."));
                exit;
            }
        }
    } else {
        header("Location: register.php?message=" . urlencode("Veuillez remplir tous les champs."));
        exit;
    }
} else {
    header("Location: register.php?message=" . urlencode("Requête invalide."));
    exit;
}
?>
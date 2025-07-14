<?php
session_start();
require_once __DIR__ . '/../inc/fonction.php';

$bdd = dbconnect();


    $email = $_POST['email'] ?? '';
    $mdp = $_POST['mdp'] ?? '';

    if ($email && $mdp) {
        $query = "SELECT * FROM membre WHERE email = '$email'";
        $result = mysqli_query($bdd, $query);
        if ( mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
                $_SESSION['user_id'] = $user['id_membre'];
                $_SESSION['user_name'] = $user['nom'];
                header("Location: objects.php");
                exit;
            } else {
                header("Location: index.php?message=" . urlencode("Email ou mot de passe incorrect."));
                exit;
            }
        } else {
            header("Location: index.php?message=" . urlencode("Email ou mot de passe incorrect."));
            exit;
        }

?>
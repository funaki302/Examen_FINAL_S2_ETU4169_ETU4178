<?php
include("../inc/fonction.php");

$uploadDir = 'Uploads/';
$maxSize = 10 * 1024 * 1024; // 10 Mo
$allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photos'])) {
    $tmpName = $_FILES['photos']['tmp_name'];
    $fileName = $_FILES['photos']['name'];
    $fileSize = $_FILES['photos']['size'];
    $fileError = $_FILES['photos']['error'];

    $ok = true;

    if ($fileError !== UPLOAD_ERR_OK) {
        echo "Erreur lors de l’upload du fichier $fileName.<br>";
        $ok = false;
    }

    if ($fileSize > $maxSize) {
        echo "Le fichier $fileName est trop volumineux.<br>";
        $ok = false;
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $tmpName);
    finfo_close($finfo);

    if (!in_array($mime, $allowedMimeTypes)) {
        echo "Type de fichier non autorisé pour $fileName : $mime<br>";
        $ok = false;
    }

    $originalName = pathinfo($fileName, PATHINFO_FILENAME);
    $extension = pathinfo($fileName, PATHINFO_EXTENSION);
    $newName = $originalName . '_' . uniqid() . '.' . $extension;

    if ($ok && !move_uploaded_file($tmpName, $uploadDir . $newName)) {
        echo "Échec du déplacement du fichier $fileName.<br>";
        $ok = false;
    }

    if ($ok) {
        $id_I = $_POST['id_membre'];
        $description = $_POST['nom_objet'];
        $cat = $_POST['categorie'];
        $id_objet = add_object($description, $cat, $id_I);
        set_pictures($id_objet, $newName);

        header('Location: ../pages/objects.php');
        exit;
    } else {
        echo "Le fichier n'a pas été traité correctement.";
    }
} else {
    echo "Aucun fichier reçu ou méthode incorrecte.";
}
?>
<?php
require_once __DIR__ . '/connexion.php';


function getCategories()
{
    $query = "SELECT * FROM categorie_objet";
    $result = mysqli_query(dbconnect(), $query);
    $categories = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row;
    }
    return $categories;
}

function getObjects( $filter_category = '')
{
    if ($filter_category) {
        $filter_category = mysqli_real_escape_string(dbconnect(), $filter_category);
        $query = "
            SELECT o.*, c.nom_categorie, e.date_retour
            FROM objet o
            JOIN categorie_objet c ON o.id_categorie = c.id_categorie
            LEFT JOIN emprunt e ON o.id_objet = e.id_objet AND e.date_retour IS NULL
            WHERE c.id_categorie = '$filter_category'
        ";
        $result = mysqli_query(dbconnect(), $query);
    } else {
        $query = "
            SELECT o.*, c.nom_categorie, e.date_retour
            FROM objet o
            JOIN categorie_objet c ON o.id_categorie = c.id_categorie
            LEFT JOIN emprunt e ON o.id_objet = e.id_objet AND e.date_retour IS NULL
        ";
        $result = mysqli_query(dbconnect(), $query);
    }
    $objects = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $objects[] = $row;
    }
    return $objects;
}

function rch_all($nom, $categorie, $disponible) {
    $conditions = [];

    if (!empty($nom)) {
        $nom = mysqli_real_escape_string(dbconnect(), $nom);
        $conditions[] = "o.nom_objet LIKE '%$nom%'";
    }


    if (!empty($categorie)) {
        $categorie = intval($categorie);
        $conditions[] = "o.id_categorie = $categorie";
    }

    if ($disponible !== null) {
        if ($disponible == 1) {
            $conditions[] = "o.id_objet NOT IN (
                SELECT id_objet FROM emprunt WHERE date_retour IS NULL
            )";
        } else {
            $conditions[] = "o.id_objet IN (
                SELECT id_objet FROM emprunt WHERE date_retour IS NULL
            )";
        }
    }

    $sql = "SELECT o.id_objet, o.nom_objet, c.nom_categorie, m.nom AS proprietaire, 
                   (SELECT nom_image FROM images_objet WHERE id_objet = o.id_objet LIMIT 1) AS image
            FROM objet o
            JOIN categorie_objet c ON o.id_categorie = c.id_categorie
            JOIN membre m ON o.id_membre = m.id_membre";

    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    $result = mysqli_query(dbconnect(), $sql);
    $retour = [];
    while ($donnes = mysqli_fetch_assoc($result)) {
        $retour[] = $donnes;
    }

    return $retour;
}


?>
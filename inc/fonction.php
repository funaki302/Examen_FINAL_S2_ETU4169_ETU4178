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

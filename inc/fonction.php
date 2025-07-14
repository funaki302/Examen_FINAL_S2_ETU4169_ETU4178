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
function add_object($nom_objet, $id_categorie, $id_membre)
{
    $req = "INSERT INTO objet (nom_objet,id_categorie,id_membre) values ('$nom_objet',$id_categorie,$id_membre)";
    $conn = dbconnect();
    $sql = mysqli_query($conn, $req);
    return mysqli_insert_id($conn); // <-- Ajouté
}

function set_pictures($id_object, $image)
{
    $req = "INSERT INTO image_objet (id_object,nom_image) values ($id_object, $image)";
    $conn = dbconnect();
    $sql = mysqli_query($conn, $req);
   }

   function get_all_categorie() {
    $bdd = dbconnect(); 
    $query = "SELECT id_categorie, nom_categorie FROM categorie_objet";
    $result = mysqli_query($bdd, $query);

    $categories = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row;
    }

    return $categories;
}

function one_image($id_object)
{
    $req = "SELECT nom_image FROM v_image_objet WHERE id_object = $id_object LIMIT 1";
    $conn = dbconnect();
    $sql = mysqli_query($conn, $req);
    return mysqli_fetch_assoc($sql);
}

function emprunt($id_objet, $id_membre){
    $req = "INSERT into emprunt (id_objet, id_membre, date_emprunt) values ($id_objet, $id_membre, NOW())";
    $conn = dbconnect();
    $sql = mysqli_query($conn, $req);
    return mysqli_insert_id($conn);
}

function set_date_fin($jours,$id_emprunt){
    $req1 = "SELECT date_emprunt FROM emprunt WHERE id_emprunt = $id_emprunt";
    $sql1 = mysqli_query(dbconnect(), $req1);
    $date_debut = mysqli_fetch_assoc($sql1)['date_emprunt'];
    $date_fin = date('Y-m-d', strtotime($date_debut . ' + ' . $jours . ' days'));
    echo $date_fin;

    $req = "UPDATE emprunt SET date_retour = '$date_fin' WHERE id_emprunt = '$id_emprunt'";
    mysqli_query(dbconnect(), $req);
}
function info_membre($id_membre) {
    $conn = dbconnect();
    
    $req = "SELECT * FROM po_membre WHERE id_membre = ?";
    $stmt = mysqli_prepare($conn, $req);
    mysqli_stmt_bind_param($stmt, "i", $id_membre);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $membre = mysqli_fetch_assoc($result);
    
    mysqli_stmt_close($stmt);
    
    return $membre ? $membre : false;
}


function get_list_emprunt($_id_membre) {
    $bdd = dbconnect(); 
    $_id_membre = intval($_id_membre); 


    $query = "SELECT e.id_emprunt, e.id_objet, o.nom_objet, c.nom_categorie, m.nom AS proprietaire, 
                     (SELECT nom_image FROM images_objet WHERE id_objet = e.id_objet LIMIT 1) AS image, 
                     e.date_emprunt
              FROM emprunt e
              JOIN objet o ON e.id_objet = o.id_objet
              JOIN categorie_objet c ON o.id_categorie = c.id_categorie
              JOIN membre m ON o.id_membre = m.id_membre
              WHERE e.id_membre = $_id_membre AND e.date_retour IS NULL";

    $result = mysqli_query($bdd, $query);
    $emprunts = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $emprunts[] = $row;
    }

    return $emprunts;
}




?>
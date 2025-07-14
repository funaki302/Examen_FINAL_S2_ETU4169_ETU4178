<?php
session_start();
require_once __DIR__ . '/../inc/fonction.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$nom = $_GET['nom'] ?? '';
$categorie = $_GET['category'] ?? '';
$disponible = isset($_GET['disponible']) ? intval($_GET['disponible']) : null;

$results = rch_all($nom, $categorie, $disponible);


$_SESSION['search_results'] = $results;

header("Location: recherche.php?nom=" . urlencode($nom) . "&category=" . urlencode($categorie) . "&disponible=" . urlencode($disponible));
exit;
?>
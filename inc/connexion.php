function connecterBase()
{
    $bdd = mysqli_connect('localhost', 'root', '', 'emprunt_objets');
    if ($bdd) {

        return $bdd;
    } else {
        die('Erreur de connexion à la base de données : ' . mysqli_connect_error());
    }
}

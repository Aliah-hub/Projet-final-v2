<?php
require_once 'connexion.php';

function inscrire_membre($nom, $date_naissance, $genre, $mail, $ville, $mdp, $image_profil = '../Uploads/images/default_profile.png') {
    global $bdd;
    $mail = mysqli_real_escape_string($bdd, $mail);
    $requete_verif = "SELECT * FROM vmembre WHERE mail = '$mail'";
    $resultat_verif = mysqli_query($bdd, $requete_verif);
    if (mysqli_num_rows($resultat_verif) > 0) {
        echo "Erreur : Ce mail est déjà utilisé.";
        return false;
    }

    $nom = mysqli_real_escape_string($bdd, $nom);
    $date_naissance = mysqli_real_escape_string($bdd, $date_naissance);
    $genre = mysqli_real_escape_string($bdd, $genre);
    $ville = mysqli_real_escape_string($bdd, $ville);
    $mdp = mysqli_real_escape_string($bdd, $mdp);
    $image_profil = mysqli_real_escape_string($bdd, $image_profil);

    $requete = "INSERT INTO vmembre (nom, date_naissance, genre, mail, ville, mdp, image_profil) 
                VALUES ('$nom', '$date_naissance', '$genre', '$mail', '$ville', '$mdp', '$image_profil')";
    $resultat = mysqli_query($bdd, $requete);
    if (!$resultat) {
        echo 'Erreur SQL : ' . mysqli_error($bdd);
        return false;
    }
    return true;
}

function connecter_membre($mail, $mdp) {
    global $bdd;
    $mail = mysqli_real_escape_string($bdd, $mail);
    $mdp = mysqli_real_escape_string($bdd, $mdp);
    $requete = "SELECT * FROM vmembre WHERE mail = '$mail' AND mdp = '$mdp'";
    $resultat = mysqli_query($bdd, $requete);
    if (!$resultat) {
        echo 'Erreur SQL : ' . mysqli_error($bdd);
        return false;
    }
    $membre = mysqli_fetch_assoc($resultat);
    if ($membre) {
        return $membre;
    }
    return false;
}

function lister_categories() {
    global $bdd;
    $requete = "SELECT * FROM vcategorie_objet";
    $resultat = mysqli_query($bdd, $requete);
    $categories = [];
    while ($row = mysqli_fetch_assoc($resultat)) {
        $categories[] = $row;
    }
    return $categories;
}

function lister_objets($categorie_id) {
    global $bdd;
    $requete = "SELECT 
                    o.id_objet,
                    o.nom_objet,
                    c.nom_categorie,
                    m.nom AS proprietaire,
                    e.date_retour,
                    o.id_categorie,
                    o.image_principale
                FROM vobjet o
                JOIN vcategorie_objet c ON o.id_categorie = c.id_categorie
                JOIN vmembre m ON o.id_membre = m.id_membre
                LEFT JOIN vemprunt e ON o.id_objet = e.id_objet AND e.date_retour >= CURDATE()";
    if ($categorie_id) {
        $categorie_id = mysqli_real_escape_string($bdd, $categorie_id);
        $requete .= " WHERE o.id_categorie = '$categorie_id'";
    }
    $resultat = mysqli_query($bdd, $requete);
    if (!$resultat) {
        echo 'Erreur SQL : ' . mysqli_error($bdd);
        return [];
    }
    $objets = [];
    while ($row = mysqli_fetch_assoc($resultat)) {
        $objets[] = $row;
    }
    return $objets;
}
?>
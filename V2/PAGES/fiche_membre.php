<?php
require_once '../includes/fonctions.php';
session_start();
if (!isset($_SESSION['id_membre'])) {
    header("Location: login.php");
    exit();
}

$id_membre = $_SESSION['id_membre'];
$requete_membre = "SELECT * FROM vmembre WHERE id_membre = '$id_membre'";
$resultat_membre = mysqli_query($bdd, $requete_membre);
$membre = mysqli_fetch_assoc($resultat_membre);

$requete_objets = "SELECT 
                        o.id_objet,
                        o.nom_objet,
                        c.nom_categorie,
                        o.image_principale
                    FROM vobjet o
                    JOIN vcategorie_objet c ON o.id_categorie = c.id_categorie
                    WHERE o.id_membre = '$id_membre'
                    ORDER BY c.nom_categorie";
$resultat_objets = mysqli_query($bdd, $requete_objets);
$objets = [];
while ($row = mysqli_fetch_assoc($resultat_objets)) {
    $objets[$row['nom_categorie']][] = $row;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fiche du membre</title>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
    <div class="container">
        <h1>Profil de <?php echo htmlspecialchars($membre['nom']); ?></h1>
        <p><strong>Mail :</strong> <?php echo htmlspecialchars($membre['mail']); ?></p>
        <p><strong>Date de naissance :</strong> <?php echo htmlspecialchars($membre['date_naissance']); ?></p>
        <p><strong>Genre :</strong> <?php echo htmlspecialchars($membre['genre']); ?></p>
        <p><strong>Ville :</strong> <?php echo htmlspecialchars($membre['ville']); ?></p>
        <p><img src="<?php echo htmlspecialchars($membre['image_profil']); ?>" alt="Photo de profil" width="100"></p>
        <h2>Mes objets</h2>
        <?php if (empty($objets)) { ?>
            <p>Vous n'avez aucun objet.</p>
        <?php } else { ?>
            <?php foreach ($objets as $categorie => $liste_objets) { ?>
                <h3><?php echo htmlspecialchars($categorie); ?></h3>
                <table>
                    <tr>
                        <th>Image</th>
                        <th>Nom de l'objet</th>
                        <th>Action</th>
                    </tr>
                    <?php foreach ($liste_objets as $objet) { ?>
                        <tr>
                            <td><img src="<?php echo htmlspecialchars($objet['image_principale']); ?>" alt="Image principale" width="50"></td>
                            <td><?php echo htmlspecialchars($objet['nom_objet']); ?></td>
                            <td><a href="fiche_objet.php?id=<?php echo $objet['id_objet']; ?>">Voir détails</a></td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } ?>
        <?php } ?>
        <a href="liste_objets.php">Retour à la liste</a>
    </div>
</body>
</html>
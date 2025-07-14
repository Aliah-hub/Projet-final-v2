<?php
require_once '../includes/fonctions.php';
session_start();
if (!isset($_SESSION['id_membre'])) {
    header("Location: login.php");
    exit();
}

$categorie_id = isset($_GET['categorie']) && is_numeric($_GET['categorie']) ? $_GET['categorie'] : '';
$nom_objet = isset($_GET['nom_objet']) ? trim($_GET['nom_objet']) : '';
$disponible = isset($_GET['disponible']) ? 1 : 0;

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
$conditions = [];
if ($categorie_id) {
    $categorie_id = mysqli_real_escape_string($bdd, $categorie_id);
    $conditions[] = "o.id_categorie = '$categorie_id'";
}
if ($nom_objet) {
    $nom_objet = mysqli_real_escape_string($bdd, $nom_objet);
    $conditions[] = "o.nom_objet LIKE '%$nom_objet%'";
}
if ($disponible) {
    $conditions[] = "e.date_retour IS NULL";
}
if (!empty($conditions)) {
    $requete .= " WHERE " . implode(" AND ", $conditions);
}

$resultat = mysqli_query($bdd, $requete);
if (!$resultat) {
    die('Erreur SQL : ' . mysqli_error($bdd) . '<br>Requête : ' . $requete);
}
$objets = [];
while ($row = mysqli_fetch_assoc($resultat)) {
    $objets[] = $row;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des objets</title>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
    <div class="container wide">
        <h1>Liste des objets</h1>
        <a href="fiche_membre.php">Mon profil</a> | 
        <a href="ajouter_objet.php">Ajouter un objet</a> | 
        <a href="login.php">Se déconnecter</a>
        <form method="GET" action="">
            <label>Filtrer par catégorie :</label>
            <select name="categorie">
                <option value="">Toutes</option>
                <?php
                $categories = lister_categories();
                foreach ($categories as $categorie) {
                    $selected = ($categorie_id == $categorie['id_categorie']) ? 'selected' : '';
                    echo "<option value='{$categorie['id_categorie']}' $selected>{$categorie['nom_categorie']}</option>";
                }
                ?>
            </select>
            <label>Nom de l'objet :</label>
            <input type="text" name="nom_objet" value="<?php echo htmlspecialchars($nom_objet); ?>"><br>
            <label>Disponible uniquement :</label>
            <input type="checkbox" name="disponible" <?php echo $disponible ? 'checked' : ''; ?>><br>
            <button type="submit">Filtrer</button>
            <a href="liste_objets.php">Réinitialiser</a>
        </form>
        <?php if (empty($objets)) { ?>
            <p>Aucun objet trouvé.</p>
        <?php } else { ?>
            <table>
                <tr>
                    <th>Image</th>
                    <th>Objet</th>
                    <th>Catégorie</th>
                    <th>Propriétaire</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($objets as $objet) { ?>
                    <tr>
                        <td><img src="<?php echo htmlspecialchars($objet['image_principale']); ?>" alt="Image principale" width="50"></td>
                        <td><?php echo htmlspecialchars($objet['nom_objet']); ?></td>
                        <td><?php echo htmlspecialchars($objet['nom_categorie']); ?></td>
                        <td><?php echo htmlspecialchars($objet['proprietaire']); ?></td>
                        <td><?php echo $objet['date_retour'] ? "Emprunté (retour le {$objet['date_retour']})" : "Disponible"; ?></td>
                        <td><a href="fiche_objet.php?id=<?php echo htmlspecialchars($objet['id_objet']); ?>">🔍</a></td>
                    </tr>
                <?php } ?>
            </table>
        <?php } ?>
    </div>
</body>
</html>
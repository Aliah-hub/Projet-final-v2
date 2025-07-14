<?php
        require_once '../includes/fonctions.php';
        if (isset($_POST['inscription'])) {
            $nom = $_POST['nom'];
            $date_naissance = $_POST['date_naissance'];
            $genre = $_POST['genre'];
            $mail = $_POST['mail'];
            $ville = $_POST['ville'];
            $mdp = $_POST['mdp']; 

            $resultat = inscrire_membre($nom, $date_naissance, $genre, $mail, $ville, $mdp);
            if ($resultat) {
                echo "<p>Inscription reussie ! <a href='login.php'>Connectez-vous</a></p>";
            } else {
                echo "<p>Erreur lors de l'inscription : Verifiez vos informations ou essayez un autre mail.</p>";
            }
        }
        ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
    <div class="container">
        <h1>Inscription</h1>
        <form method="POST" action="">
            <label>Nom :</label>
            <input type="text" name="nom" required><br>
            <label>Date de naissance :</label>
            <input type="date" name="date_naissance" required><br>
            <label>Genre :</label>
            <select name="genre" required>
                <option value="">Choisir un genre</option>
                <option value="Homme">Homme</option>
                <option value="Femme">Femme</option>
                <option value="Autre">Autre</option>
            </select><br>
            <label>Mail :</label>
            <input type="email" name="mail" required><br>
            <label>Ville :</label>
            <input type="text" name="ville" required><br>
            <label>Mot de passe :</label>
            <input type="password" name="mdp" required><br>
            <button type="submit" name="inscription">S'inscrire</button>
        </form>
        <a href="login.php">Deja un compte ? Se connecter</a>

        
    </div>
</body>
</html>
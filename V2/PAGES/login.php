<?php
        session_start();
        require_once '../includes/fonctions.php';
        if (isset($_POST['connexion'])) {
            $mail = $_POST['mail'];
            $mdp = $_POST['mdp'];
            $membre = connecter_membre($mail, $mdp);
            if ($membre) {
                $_SESSION['id_membre'] = $membre['id_membre'];
                $_SESSION['nom'] = $membre['nom'];
                header("Location: liste_objets.php");
                exit();
            } else {
                echo "<p>Erreur : Mail ou mot de passe incorrect. Verifiez vos informations ou inscrivez-vous.</p>";
            }
        }
        ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
    <div class="container">
        <h1>Connexion</h1>
        <form method="POST" action="">
            <label>Mail :</label>
            <input type="email" name="mail" required><br>
            <label>Mot de passe :</label>
            <input type="password" name="mdp" required><br>
            <button type="submit" name="connexion">Se connecter</button>
        </form>
        <a href="inscription.php">Pas de compte ? S'inscrire</a>

        
    </div>
</body>
</html>
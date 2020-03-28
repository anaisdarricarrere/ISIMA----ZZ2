<?php include "./lib/sql.php";
// Connexion à la base de données
$db = connect_bdd();
?>

<!DOCTYPE html>
<html>
 <head>
  <meta name="author" content="TP Projet" charset= utf-8 />
   <link rel="stylesheet" href="styles.css" media = "all" type = "text/css">
 <title>Top Modélisme.com | Le leader du modelisme en ligne</title>
 </head>
<body>
  <div class="main">
      <?php 
          // Affichage de l'entête avec le logo et le nom du site
          affichage_en_tete();
          // Affichage de la partie authentification / connexion
          affichage_authentification(); ?>
    <article class="body">
      <?php 
            // Affichage de la page visitée par l'utilisateur : les familles d'articles ou les articles
            affichage_famille_article($db);
            // Affichage du panier correspondant à la commande en cours de l'utilisateur
            affichage_commande($db); ?>
    </article>
    <?php
        // Affichage du pied de page avec des informations sur le site (adresse)  
        affichage_pied_de_page(); ?>
  </div>
</body>
</html>
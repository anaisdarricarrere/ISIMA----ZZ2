<!--                AFFICHAGE.PHP               -->
<!-- Ce fichier est composé de toutes les fonctions qui gère la mise en page du site -->

<!-- Fonction : Mise en page de l'entête du site -->
<?php function affichage_en_tete() 
{
    echo '<header class="header-site">';
        echo '<img src="./img/logo_500px.gif" alt="Logo TopModélisme.com" class="logo">';
        echo '<div class="titre">Le leader du modélisme en ligne</div>';
    echo '</header>';
} ?>


<!-- Fonction : Mise en page de la partie authentification / connexion-->
<!-- Remarque : En cliquant sur "Se connecter" ou "Créer un compte", on revient simplement à la page d'accueil-->
<?php function affichage_authentification() 
{
    echo '<div class="authentification">';
      echo '<div>';
          echo '<label for="authentification" class="authentification-email">adresse mail</label>';
          echo '<br>';
          echo '<input type="text" name="Adresse mail" placeholder="votre adresse mail" required minlength="4" maxlength="8" size="20" >';
        echo '</div>';
        echo '<div>';
          echo '<label for="authentification" class="authentification-mdp">mot de passe</label>';
          echo '<br>';
          echo '<input type="password" name="Password" placeholder="votre mot de passe" required minlength="4" maxlength="8" size="20">';
       echo '</div>';
        echo '<div class="authentification-boutons">';
          echo '<a class="myButton" href="index.php">Se connecter</a>';
          echo '<a class="myButton" href="index.php">Créer un compte</a>';
        echo '</div>';
    echo '</div>';
} ?>

<!-- Fonction : Gestion de la page à afficher -->
<?php function affichage_famille_article($db) 
{
    echo '<div class="contenu">'; 
        if(isset($_GET['famille']))
        {   // Si dans l'url, on a index.php?famille=x où x est l'id famille qui existe dans la bdd, on affiche les articles de la famille correspondante
            affiche_articles_bdd($db, $_GET['famille']); 
        }
        else 
        {   // Sinon, on affiche l'écran principal avec l'affichage des familles
            affiche_familles_bdd($db);
        } 
    echo '</div>';
} ?>

<!-- Fonction : Mise en page de l'affichage des familles -->
<?php function affichage_famille($image, $libelle, $id) 
{
    echo '<div class="affiche_famille">';
        // On affiche les informations de l'enregistrement en cours
        echo '<a href="index.php?famille='.$id.'" class="famille-img">';
            echo '<img src="./img_familles/'.$image.'" alt="'.$libelle.'" width="120px" />';
        echo '</a>';
        echo '<br>';
        echo '<div class="titre_center">'.$libelle.'</div>';
    echo '</div>';
} ?>

<!-- Fonction : Mise en page de l'affichage des articles -->
<?php function affichage_article($image, $libelle, $id, $detail, $prix_ttc, $id_fam)
{
    echo '<div class="affiche_article">';
    echo '<table>';
        echo '<tr>';
            echo '<td>';
                echo '<img src="./img_articles/'.$image.'" alt="'.$libelle.'" class="affiche_article-img" width="70px"/>';
            echo '</td>';
            echo '<td>';
                echo '<div class="table-contenu"';
                    echo '<div class="article-libelle">'.$libelle.'</div>';
                    echo '<div class="article-detail">'.$detail.'</div>';
                    echo '<div class="prix_et_bouton">';
                        echo '<div class="titre_center article-prix">'.$prix_ttc.' €</div>';
                        echo '<a class="myButton ajust-button" href="index.php?famille='.$id_fam.'&commander='.$id.'">Commander</a>';
                    echo '</div>';
                echo '</div>';
            echo '</td>';
        echo '</tr>';
    echo '</table>';
    echo '</div>';
} ?>

<!-- Fonction : Affichage du bouton retour -->
<?php function affichage_bouton_retour()
{
    echo '<table>';
        echo '<tr>';
            echo '<th>';
                echo '<a href="index.php"><div class="myButton">retour</div></a>';
            echo '</th>';
        echo'</tr>';
    echo '</table>';
} ?>

<!-- Fonction : Gestion du panier -->
<?php function affichage_commande($db) 
{
    echo '<div class="section-panier">';
        echo '<div class="votre-panier">votre panier</div>';
            // Si l'utilisateur a cliqué sur 'commander', alors on ajoute l'article au panier
            if (isset($_GET['commander']))
            {
              ajouter_article($_GET['commander'], $db);
            }
            // On affiche l'état du panier
            afficher_etat_panier($db);
    echo '</div>';
} ?>

<!-- Fonction : Mise en page de l'affichage du panier vide -->
<?php function affichage_panier_vide()
{
    echo '<p class="panier-vide">Votre panier est vide</p>';
} ?>

<!-- Fonction : Mise en page de l'affichage d'un panier non vide -->
<?php function affichage_article_panier($quantite, $prix_ttc, $libelle, $total)
{
    // $sous_total correspond au total de chaque ligne du panier
    $sous_total = $quantite * $prix_ttc;
    echo '<table class="panier-table">';
        echo '<tr>';     
            echo '<td colspan="2" class="panier-libelle">' . $libelle . '</td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td class="panier-adroite">' . $quantite . ' x ' . $prix_ttc . ' = ' . number_format($sous_total, 2, '.', '') . ' €</td>';
        echo '</tr>';
    echo '</table>';

    // $total correspond au coût total du panier qu'on modifie à chaque affichage d'une ligne
    $total += $sous_total;
    return $total;
} ?>

<!-- Fonction : Mise en place de l'affichage du total à payer dans le panier -->
<!-- Remarque : En cliquant sur "Commander", on revient simplement à la page d'accueil-->
<?php function affichage_total_panier($total)
{
    echo '<div>';
        echo '<hr>';
            echo '<table class="panier-table panier-total">';
                echo '<tr>';
                    echo '<td class="panier-adroite">TOTAL : '. number_format($total,2,'.','') .' €</td>';
                echo '</tr>';
            echo '</table>';
            echo '<a class="myButton" href="index.php?Vide_panier=1">Vider panier</a>';
            echo '<a class="myButton" href="index.php">Commander</a>';
    echo '</div>';
} ?>

<!-- Fonction : Mise en place de l'affichage du pied de page du site -->
<?php function affichage_pied_de_page() 
{
        echo '<footer class="footer">';
            echo '<p> TOPModelisme.com est enregistré au R.C.S. sous le numero 1234567890 </p>';
            echo '<p> 13 avenue du Pre la Reine - 75007 PARIS </p>';
        echo '</footer>'; 
} ?>
<!--                SQL.PHP                 -->
<!-- Ce fichier est composé de toutes les fonctions qui font appel à la base de données et donc qui contiennent des requêtes SQL -->

<?php include "./lib/affichage.php"; ?>

<!-- Fonction : Connexion à la base de données -->
<?php function connect_bdd()
{        
        // On se connecte à la base de données vente_en_ligne
        $db = mysqli_connect('localhost', 'root', '', 'vente_en_ligne') or die('Erreur SQL : '. mysqli_error($db));
        $db->query('SET NAMES UTF8');
        return $db;
} ?>

<!-- Fonction : Extraction des données des familles pour leur affichage -->
<?php function affiche_familles_bdd($db)
{
    // On récupère les informations des familles pour l'affichage
    $sql = 'SELECT libelle, image, id  
    FROM famille 
    ORDER BY ordre_affichage ASC;';
    $result = $db->query ($sql) or die ('ErreurSQL : '.mysqli_error($db));

    // On fait une boucle pour lire les informations de chaque famille une par une
    while ($data = mysqli_fetch_array($result))
    {
        // On affiche les informations de la famille en cours
        affichage_famille($data['image'], $data['libelle'], $data['id']);
    }
} ?>

<!-- Fonction : Extraction des donnnées des articles de la famille d'indice $id_fam pour leur affichage -->
<?php function affiche_articles_bdd($db, $id_fam)
{
        // On vérifie que la famille $id_fam existe au cas où il y aurait une modification de l'URL directement
        $sql_exist = 'SELECT COUNT(distinct id) AS num_fam
        FROM article 
        WHERE id_famille = '.$id_fam.';';
        $result_exist = $db->query($sql_exist) or die ('ErreurSQL : '.mysqli_error($db));
        $data_exist = mysqli_fetch_array($result_exist);

        // Si la famille existe, on affiche les articles de la famille correspondante
        if($data_exist['num_fam'] != 0)
        {
            // On récupère les informations des articles correspondants pour l'affichage
            $sql_art = 'SELECT  image, libelle, id, detail, prix_ttc
            FROM article 
            WHERE id_famille='.$id_fam.';';
            $result_art = $db->query ($sql_art) or die ('ErreurSQL : '.mysqli_error($db));

            // Affichage du bouton "retour"
            affichage_bouton_retour();
            

            // On fait une boucle pour lire les informations de chaque article un par un
            while ($data_art = mysqli_fetch_array($result_art))
            {
                // On affiche les informations de l'article en cours
                affichage_article($data_art['image'], $data_art['libelle'], $data_art['id'], $data_art['detail'], $data_art['prix_ttc'], $id_fam);
            }           
        }
        //Si la famille n'existe pas, on renvoie à la page d'accueil
        else
        {
            affiche_familles_bdd($db);
        }
}?>

<!-- Fonction : Ajout de l'article d'indice $id_art au panier -->
<!-- Remarque : On ne traite pas l'authentification donc le panier est créé pour le client ayant l'identifiant 1 et le panier 1 -->
<?php function ajouter_article($id_art, $db)
{
    // On récupère les informations nécessaires pour l'ajout de l'article $id_art au panier
    $sql_ajout = 'SELECT taux, prix_ttc
    FROM article 
    INNER JOIN tva ON (article.id_tva = tva.id)
    WHERE article.id = '.$id_art.';';
    $result_ajout = $db->query($sql_ajout) or die ('ErreurSQL : '.mysqli_error($db));
    $data_ajout = mysqli_fetch_array($result_ajout);

    // On vérifie que l'article existe vraiment au cas où il y aurait une modification de l'URL directement
    if(!empty($data_ajout)) 
    {
        // On vérifie si l'article n'est pas déjà dans le panier
        $sql_exist = 'SELECT * 
        FROM panier_article 
        WHERE id_article = '.$id_art.';';
        $result_exist = $db->query($sql_exist) or die ('ErreurSQL : '.mysqli_error($db));
        $data_exist = mysqli_fetch_array($result_exist);

        //Si l'article est déjà dans le panier, on incrémente sa quantité dans la base de données
        if(!empty($data_exist)) 
        {
            $sql_update = 'UPDATE panier_article
                        SET quantite = quantite+1
                        WHERE id_article='.$id_art.';';
            $db->query($sql_update) or die ('ErreurSQL : '.mysqli_error($db));
        }
        else // Si l'article n'est pas dans le panier, on l'y ajoute
        { 
            // On calcule le prix hors-taxe $prix_ht et le prix TVA $prix_tva afin de remplir correctement la table panier_article dans la base de données
            $prix_ht = $data_ajout['prix_ttc'] / (1+$data_ajout['taux']/100);
            $prix_tva = $data_ajout['prix_ttc'] - $prix_ht;

            // On ajoute l'article au panier dans la base de données
            $sql_insert = 'INSERT INTO panier_article 
            VALUES (1, '. $id_art .', 1, ' . $prix_ht . ', ' . $prix_tva . ',  ' . $data_ajout['prix_ttc'] . ');';
            $db->query($sql_insert) or die ('ErreurSQL : '.mysqli_error($db));
        }
    }
} ?>

<!-- Fonction : Affichage de l'état du panier en cours -->
<?php function afficher_etat_panier($db)
{
    // Si l'utilisateur a cliqué sur "vider panier", alors on vide le panier
    if(isset($_GET['Vide_panier'])){
        $sql_vider ='DELETE FROM panier_article
        WHERE id_panier = 1 ;';
        $result_vider = $db->query ($sql_vider) or die ('ErreurSQL : '.mysqli_error($db));
    }

    // On récupère toutes les informations du panier
    $sql_panier = 'SELECT quantite, panier_article.prix_ttc, libelle 
    FROM panier_article 
    INNER JOIN article ON panier_article.id_article = article.id ;';
    $result_panier = $db->query($sql_panier) or die ('ErreurSQL : '.mysqli_error($db));
    $data_panier = mysqli_fetch_array($result_panier);

    // Si le panier est vide, on affiche "Le panier est vide"
    if(empty($data_panier))
    {
        affichage_panier_vide();
    }
    else // Si le panier n'est pas vide, on l'affiche
    {   
        // $total correspond au coût total du panier en cours
        $total = 0;
        do {
            $total = affichage_article_panier($data_panier['quantite'], $data_panier['prix_ttc'], $data_panier['libelle'], $total);
            }while($data_panier = mysqli_fetch_array($result_panier));
        affichage_total_panier($total);
    }
} ?>
<?php
echo "Tableau : ".$_SESSION['nomTableau']."<br/>";
echo "Tour numéro : ".$_SESSION['nbTours']."<br/>";
echo "Evenements actifs ce tours-ci<br/>";
foreach ($evenementsActifs as $unEvenement){
    $evenement = chercherUnePropriete($unEvenement,"tous");
    $lesProprietes = explode("||",$evenement);
    echo "----------------<br/>";
    echo "Titre = ".$lesProprietes[0]."<br/>";
    echo "Description = ".$lesProprietes[1]."<br/>";
    echo "Effet = ".$lesProprietes[2]."<br/>";
    echo "----------------<br/>";
}
foreach ($monTableau as $unTableau){
    if (is_array($unTableau)){ //Evite avertissement resulte d’un appel à foreach sur une variable qui n’est pas un tableau.
        foreach ($unTableau as $unElement){
            echo $unElement;  
        }
    }
}
?>
<br/><br/>
<a href="index.php">Retour à l'index</a>
<form action='index.php?uc=actionSurEvenement&action=creerEvenement' method='POST'>
    <div class='dropdown' style='position:relative; '>
        <a href='#' class='btn btn-primary dropdown-toggle' data-toggle='dropdown'>Créer un événement</a>
        <ul class='dropdown-menu'>Création d'un événement
            <li><a class='dropdown-header'>Titre de l'événement</a></li>
            <li><input type='text' class='form-control' name='titreEvenement' required></li>
            <li><a class='dropdown-header'>Description de l'événement</a></li>
            <li><textarea rows='3' class='form-control' name='descriptionEvenement' required></textarea></li>
            <li><a class='dropdown-header'>Effet de l'événement</a></li>
            <li><textarea rows='2' class='form-control' name='effetEvenement' required></textarea></li>
            <li><a class='dropdown-header'>Nombre de tours que dure l'événement</a></li>
            <li><input type="number" name="nbTours" min="0" max="1000" required></li>
            <li><button class='btn btn-primary btn-sm' type='submit'>Valider</button>
            <button class='btn btn-secondary-outline btn-sm' type='reset'>Tous effacer</button>
        </ul>
    </div>
</form><br/>
<form action='index.php?uc=actionSurEvenement&action=supprimerEvenement' method='POST'>
    <div class='dropdown' style='position:initial'>
        <a href='#' class='btn btn-primary dropdown-toggle' data-toggle='dropdown'>Supprimer un événement</a>
        <ul class='dropdown-menu'>Suppression d'un événement
            <li><a class='dropdown-header'>Titre de l'événement</a></li>
            <select name='numeroPropriete'><?php
                foreach ($lesProprietesRecuperer as $unePropriete){
                    echo $unePropriete;
                }?>
            </select>
            <li><button class='btn btn-primary btn-sm' type='submit'>Valider</button>
            <button class='btn btn-secondary-outline btn-sm' type='reset'>Tous effacer</button>
        </ul>
    </div>
</form><br/><br/>
<form action='index.php?uc=genererTableau&action=constructionTableau' method='POST'>
    <div class='dropdown' style='position:initial'>
        <a href='#' class='btn btn-primary dropdown-toggle' data-toggle='dropdown'>Ajouter un tableau</a>
        <ul class='dropdown-menu'>Ajouter un tableau
            Nb Colonne : <input type="text" name="colonne"><br/>
            Nb Ligne : <input type="text" name="ligne"><br/>
            Plateau <select name="numeroPlateau">
                <?php 
                foreach ($_SESSION['lesPlateaux'] as $unPlateau){
                    echo $unPlateau;
                }?>
            </select><br/>
            Sens du plateau (haut par défaut)<select name="sensPlateau">
                <option value="h">Haut</option>
                <option value="d">Droite</option>
                <option value="g">Gauche</option>
                <option value="b">Bas</option>
            </select><br/>
            <input type="submit" value="Valider">
        </ul>
    </div>
</form><br/><br/>
<form action='index.php?uc=genererTableau&action=tourSuivant' method='POST'>
    <div class='dropdown' style='position:initial'>
        <input type='hidden' name='nomTableau' value='<?php echo $_SESSION['nomTableau'] ?>'>
        <input type='hidden' name='nbTours' value='<?php echo $_SESSION['nbTours'] ?>'>
        <p align='center'><button class='btn btn-primary' type='submit'>Tour suivant</button></p>
    </div>
</form>
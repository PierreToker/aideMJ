<p>Bonjour ! Si vous souuhaitez créer un nouveau tableau donner les spécificités du tableau, sinon, selectionner le tableau pour le générer.</p>
<h4>Créer un nouveau tableau</h4>
<form method='POST' action='index.php?uc=genererTableau&action=constructionTableau'>
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
    Titre du tableau général : <input type="text" name="nomTableau"><br/>
    <input type="submit" value="Valider">
</form><br/>
<h4>Selectionner un tableau déjà existant</h4>
<form method='POST' action='index.php?uc=genererTableau&action=genererTableau'>
    <select name="nomTableau"><?php
        foreach ($resultat as $unElement){
            echo "<option value='$unElement'>$unElement</option>";
        }?>
    </select>
    <input type="submit" value="Valider">
</form>
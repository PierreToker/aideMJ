<p>Bonjour ! Si vous souuhaitez créer un nouveau tableau donner les spécificités du tableau, sinon, selectionner le tableau pour le générer.</p>
<h4>Créer un nouveau tableau</h4>
<form method='POST' action='index.php?uc=genererTableau&action=genererTableau'>
    Nb Colonne : <input type="text" name="colonne"><br/>
    Nb Ligne : <input type="text" name="ligne"><br/>
    Titre du tableau général : <input type="text" name="nomTableau"><br/>
    <input type="submit" value="Valider">
</form><br/>
<h4>Selectionner un tableau déjà existant</h4>
<form method='POST' action='index.php?uc=genererTableau&action=genererTableau'>
    <select name="listeTableau"><?php
//        foreach (combienDeTableau() as $unElement){
//            echo $unElement; 
//            echo "<option value=''></option>";
//        }?>
    </select>
</form>
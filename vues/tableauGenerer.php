<form method='POST' action='index.php?uc=generationTableau&action=genererTableau'>
    Nb Colonne : <input type="text" name="colonne"><br>
    Nb Ligne : <input type="text" name="ligne"><br>
    <input type="submit" value="Valider">
</form>
<?php
foreach ($monTableau as $unElement){
    $check = stripos($unElement,'t1_');
    if ($check === false){ //Si ce n'est pas un code de cellule
        echo $unElement;  
    }
}
?>
<?php

function constructionTableau($longueur,$largeur){
    $tableau = array();
    $tableau[0] = "<table>";
    for($i=0;$i<$largeur;$i++){ //Fait d'abord la largeur puis la longueur
        if($i == 0){
            $lettre = 'a';
        }else{
            $lettre++;
        }
        $tableau[count($tableau)+1] = "<tr>";
        for($x=0;$x<$longueur;$x++){
            $tableau[count($tableau)+1] = "c_".$lettre.$x; //code de la cellule
            $tableau[count($tableau)+1] = "<td>0</td>"; //contenant de la cellule
        }
        $tableau[count($tableau)+1] = "</tr>";
    }
    $tableau[count($tableau)+1] = "</table>";
    return $tableau;
}



function connaitreTouteProprietes($codeCellule){
    $maj = fopen("tableau.txt","r"); // On ouvre le fichier en lecture/écriture
    
$fichier = fgets($maj, 8000); 
echo $fichier;
    preg_match($codeCellule, $fichier, $matches,PREG_OFFSET_CAPTURE); //On cherche dans le fichier
    print_r($matches);
    fclose($maj);                      // On ferme le fichier
    
}
?>
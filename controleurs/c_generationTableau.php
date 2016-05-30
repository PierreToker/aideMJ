<?php
include("include/fonctions.php");
$monTableau = constructionTableau(2,3);
foreach ($monTableau as $unElement){
    $check = stripos($unElement,'c_');
    if ($check === false){ //Si ce n'est pas un code de cellule
        echo $unElement;  
    }
}
echo connaitreTouteProprietes('/a0/');
?>

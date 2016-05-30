<?php
switch ($action){
    case "genererTableau":
        $colonne = $_REQUEST['colonne'];
        $ligne = $_REQUEST['ligne'];
        $monTableau = constructionTableau($colonne,$ligne);
        break;
    case "voirDetailCellule":
        $cellule = $_POST['cellule'];
        echo $cellule;
        break;
    default :
        echo "Rien, nada !";
        
}

$celluleTableau = array();
array_push($celluleTableau, 't1_a1');
$tableauProprietes = connaitreTouteProprietes($celluleTableau); //Sert à connaitre les proprietes d'une cellule d'un tableau
foreach ($tableauProprietes as $uneCellule){
    foreach($uneCellule as $unePropriete){
        echo $unePropriete."<br/>";
    }
}
include ("vues/tableauGenerer.php");
?>

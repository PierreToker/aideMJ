<?php
if(!isset($_REQUEST['action'])){
    $_REQUEST['action'] = 'genererTableau';
}
$action = $_REQUEST['action'];
switch ($action){
    case "genererTableau":
        $colonne = $_REQUEST['colonne'];
        $ligne = $_REQUEST['ligne'];
        $monTableau = constructionTableau($colonne,$ligne);
        include ("vues/tableauGenerer.php");
        break;
    case "voirDetailCellule":
        $cellule = $_POST['cellule'];
        $celluleTableau = array();
        array_push($celluleTableau, $cellule);
        $tableauProprietes = connaitreTouteProprietes($celluleTableau); //Sert à connaitre les proprietes d'une cellule d'un tableau
        foreach ($tableauProprietes as $uneCellule){
            foreach($uneCellule as $unePropriete){
                echo $unePropriete."<br/>";
            }
        }
        break;
    default :
        echo "<br/>Rien selectionné dans le controleur 'c_actionTableau' !<br/>";
        
}
?>

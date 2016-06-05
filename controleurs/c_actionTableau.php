<?php
if(!isset($_REQUEST['action'])){
    $_REQUEST['action'] = 'genererTableau';
}
$action = $_REQUEST['action'];
switch ($action){
    case "genererTableau":
        $colonne = isset($_REQUEST['colonne']) ? $_REQUEST['colonne'] : NULL; //Si variable $_REQUEST['colonne'] existe, alors $colonne = $_REQUEST['colonne'] sinon == NULL 
        $ligne = isset($_REQUEST['ligne']) ? $_REQUEST['ligne'] : NULL;
        $nomTableau = isset($_REQUEST['nomTableau']) ? $_REQUEST['nomTableau'] : NULL;
        if (connaitreTableau("certains",$nomTableau)){
            echo "il existe déjà";
            //$monTableau = constructionTableau($colonne,$ligne);
            //include ("vues/tableauGenerer.php");
        }else{
            echo "existe pas";
            //$monTableau = constructionTableau($colonne,$ligne);
            //include ("vues/tableauGenerer.php");
        }
        break;
    case "ajouterEvementCellule":
        $nomEvenement = isset($_POST['titreEvenement']) ? $_POST['titreEvenement'] : NULL;
        ajoutProprietesCellule($nomEvenement);
        break;
    case "supprimerCellule":
        $codePropriete = isset($_POST['codePropriete']) ? $_POST['codePropriete'] : NULL;
        $codeCellule = isset($_POST['codeCellule']) ? $_POST['codeCellule'] : NULL;
        $codeASupprimer = $codeCellule."_".$codePropriete;
        suppressionProprietesDansCellule($codeASupprimer);
        break;
    case "modifierCellule":
        echo "La modification fonctionne";
        break;
    default :
        echo "<br/>Rien selectionné dans le controleur 'c_actionTableau' !<br/>";
}
?>

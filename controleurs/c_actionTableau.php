<?php
if(!isset($_REQUEST['action'])){
    $_REQUEST['action'] = 'genererTableau';
}
$action = $_REQUEST['action'];
switch ($action){
    case "genererTableau":
        $colonne = isset($_REQUEST['colonne']) ? $_REQUEST['colonne'] : NULL; //Si variable $_REQUEST['colonne'] existe, alors $colonne = $_REQUEST['colonne'] sinon == NULL 
        $ligne = isset($_REQUEST['ligne']) ? $_REQUEST['ligne'] : NULL;
        $monTableau = constructionTableau($colonne,$ligne);
        include ("vues/tableauGenerer.php");
        break;
    case "ajouterCellule":
        $titre = isset($_POST['titreEvenement']) ? $_POST['titreEvenement'] : NULL;
        $description = isset($_POST['descriptionEvenement']) ? $_POST['descriptionEvenement'] : NULL;
        
        break;
    case "supprimerCellule":
        $codePropriete = isset($_POST['codePropriete']) ? $_POST['codePropriete'] : NULL;
        $codeCellule = isset($_POST['codeCellule']) ? $_POST['codeCellule'] : NULL;
        $codeASupprimer = $codeCellule."_".$codePropriete;
        if (suppressionProprietesDansCellule($codeASupprimer) == true){
            echo "<div class='alert alert-success'><strong><span class='glyphicon glyphicon-ok'></span> Réussite !</strong><br/>La propriété a été effacée de la cellule.</div>";
        }else{
            echo "<div class='alert alert-danger'><span class='glyphicon glyphicon-remove'></span> <strong>Avertissement !</strong><br/>La propriété n'a pas été effacée de la cellule ou la propriété n'existe pas.</div>";
        }
        break;
    case "modifierCellule":
        echo "La modification fonctionne";
        break;
    default :
        echo "<br/>Rien selectionné dans le controleur 'c_actionTableau' !<br/>";
        
}
?>

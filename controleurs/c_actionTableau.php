<?php
if(!isset($_REQUEST['action'])){
    $_REQUEST['action'] = 'genererTableau';
}
$action = $_REQUEST['action'];
switch ($action){
    case "genererTableau":
        echo "En travaux pour le moment";
        $nomTableau = isset($_REQUEST['nomTableau']) ? $_REQUEST['nomTableau'] : NULL;
        $nomTableau = str_replace(" ", "_", $nomTableau);
        //$monTableau = constructionTableau($colonne,$ligne,$nomTableau);
        //include ("vues/tableauGenerer.php");
        break;
    case "constructionTableau": //anciennement "générationTableau
        $colonne = isset($_REQUEST['colonne']) ? $_REQUEST['colonne'] : NULL; //Si variable $_REQUEST['colonne'] existe, alors $colonne = $_REQUEST['colonne'] sinon == NULL 
        $ligne = isset($_REQUEST['ligne']) ? $_REQUEST['ligne'] : NULL;
        $nomTableau = isset($_REQUEST['nomTableau']) ? $_REQUEST['nomTableau'] : NULL;
        $nomTableau = str_replace(" ", "_", $nomTableau);
        if (connaitreTableau("certains",$nomTableau)){
            echo "<div class='alert alert-danger'><span class='glyphicon glyphicon-remove'></span> <strong>Erreur</strong><br/>Le tableau ne peut pas étre créé, ce nom est déjà attribué à un autre tableau.</div>";
            header('Refresh:2;url=index.php');
        }else{
            echo "existe pas";
            //$monTableau = constructionNouveauTableau($colonne,$ligne,$nomTableau);
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

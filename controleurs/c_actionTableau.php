<?php
if(!isset($_REQUEST['action'])){
    $_REQUEST['action'] = 'genererTableau';
}
$action = $_REQUEST['action'];
switch ($action){
    case "genererTableau":
        $_SESSION['nomTableau'] = isset($_REQUEST['nomTableau']) ? $_REQUEST['nomTableau'] : $_SESSION['nomTableau'];
        $_SESSION['nomTableau'] = str_replace(" ", "_", $_SESSION['nomTableau']);
        $_SESSION['nbTours'] = determinerTour("../aideMJ/ressources/Maps/".$_SESSION['nomTableau']."/compteurTours.txt");
        $i = 0;
        $monTableau = array(1 => "monTableau1", 2=> "monTableau2");
        $lesEvenements = array();
        foreach (connaitreTableau("combienTableau", $_SESSION['nomTableau']) as $unTableau){
            ++$i;
            $cheminTableau = "../aideMJ/ressources/Maps/".$_SESSION['nomTableau']."/".$unTableau;
            $fichier = fopen($cheminTableau,"r");
            if ($fichier){
                while (($buffer = fgets($fichier)) !== false) { 
                    $dimension = explode("_", $buffer);
                    $colonne = $dimension[0];
                    $ligne = $dimension[1];
                    break;
                }
            }
            $monTableau[$i] = constructionTableau($colonne,$ligne,$cheminTableau);
            fclose($fichier);
        }
        $lesEvenements = determinerEvenementCeTour($_SESSION['nbTours'],$_SESSION['nomTableau']);
        include ("vues/tableauGenerer.php");
        break;
    case "constructionTableau": //anciennement "générationTableau"
        $monTableau = array(1 => "monTableau1");
        $colonne = isset($_REQUEST['colonne']) ? $_REQUEST['colonne'] : NULL; //Si variable $_REQUEST['colonne'] existe, alors $colonne = $_REQUEST['colonne'] sinon == NULL 
        $ligne = isset($_REQUEST['ligne']) ? $_REQUEST['ligne'] : NULL;
        $nomTableau = isset($_REQUEST['nomTableau']) ? $_REQUEST['nomTableau'] : NULL;
        $nomTableau = str_replace(" ", "_", $nomTableau);
        if (connaitreTableau("certains",$nomTableau)){
            echo "<div class='alert alert-danger'><span class='glyphicon glyphicon-remove'></span> <strong>Erreur</strong><br/>Le tableau ne peut pas étre créé, ce nom est déjà attribué à un autre tableau.</div>";
            header('Refresh:2;url=index.php');
        }else{
            $monTableau = constructionNouveauTableau($colonne,$ligne,$nomTableau);
            echo "<div class='alert alert-success'><span class='glyphicon glyphicon-ok'></span> <strong>Réussite !</strong><br/>La map a bien été créée. vous allez être redirigé automatiquement vers la selection des tableaux.</div>";
            header('Refresh:3;url=index.php');
        }
        break;
    case "ajouterEvementCellule":
        $nomEvenement = isset($_POST['titreEvenement']) ? $_POST['titreEvenement'] : NULL;
        $cheminTableau = isset($_POST['cheminTableau']) ? $_POST['cheminTableau'] : NULL;
        $quand = isset($_POST['quand']) ? $_POST['quand'] : NULL;
        ajoutProprietesCellule($nomEvenement,$cheminTableau,$quand);
        break;
    case "supprimerCellule":
        $codePropriete = isset($_POST['codePropriete']) ? $_POST['codePropriete'] : NULL;
        $codeCellule = isset($_POST['codeCellule']) ? $_POST['codeCellule'] : NULL;
        $cheminTableau = isset($_POST['cheminTableau']) ? $_POST['cheminTableau'] : NULL;
        $codeASupprimer = $codeCellule."_".$codePropriete;
        suppressionProprietesDansCellule($codeASupprimer,$cheminTableau);
        break;
    case "tourSuivant":
        $_SESSION['nbTours'] = isset($_POST['nbTours']) ? $_POST['nbTours'] : NULL;
        $_SESSION['nomTableau'] = isset($_POST['nomTableau']) ? $_POST['nomTableau'] : NULL;
        incrementerTours($_SESSION['nbTours'],"../aideMJ/ressources/Maps/".$_SESSION['nomTableau']."/compteurTours.txt");
        header('Refresh:0;url=index.php?uc=genererTableau&action=genererTableau');
        break;
    case "modifierEvementCellule":
        $cheminTableau = isset($_POST['cheminTableau']) ? $_POST['cheminTableau'] : NULL;
        $dureeEvenement = isset($_POST['dureeEvenement']) ? $_POST['dureeEvenement'] : NULL;
        $demarreQuand = isset($_POST['demarreQuand']) ? $_POST['demarreQuand'] : NULL;
        $codeCelluleEtPropriete = isset($_POST['codeCelluleEtPropriete']) ? $_POST['codeCelluleEtPropriete'] : NULL;
        modifierEvementCellule($cheminTableau,$demarreQuand,$dureeEvenement,$codeCelluleEtPropriete);
        echo "<div class='alert alert-success'><span class='glyphicon glyphicon-ok'></span> <strong>Réussite !</strong><br/>L'événement a été mis à jours.</div>";
        header('Refresh:2;url=index.php?uc=genererTableau&action=genererTableau');
        break;
    default :
        echo "<br/>Rien selectionné dans le controleur 'c_actionTableau' !<br/>"; 
}
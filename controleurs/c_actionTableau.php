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
        $evenementsActifs = determinerEvenementCeTour($_SESSION['nbTours'],$_SESSION['nomTableau']);
        $i = 0;
        $monTableau = array(1 => "monTableau1", 2=> "monTableau2");
        $lesProprietesRecuperer = array();
        $lesProprietes = getToutesLesProprietes();
        foreach ($lesProprietes as $unePropriete){
            switch(true){
                case stristr($unePropriete,'lenom='):
                    $numeroPropriete = substr($unePropriete,6);
                    break;
                case stristr($unePropriete,'titre='):
                    $rest = substr($unePropriete,6);
                    array_push($lesProprietesRecuperer,"<option value='$numeroPropriete'>$rest</option>");
                    $numeroPropriete = "";
                    break;
            }
        }
        foreach (connaitreTableau("combienTableau", $_SESSION['nomTableau']) as $unTableau){
            ++$i;
            $cheminTableau = "../aideMJ/ressources/Maps/".$_SESSION['nomTableau']."/".$unTableau;
            $fichier = fopen($cheminTableau,"r");
            if ($fichier){
                while (($buffer = fgets($fichier)) !== false) { 
                    $dimension = explode("_", $buffer);
                    $imageFond = $dimension[0];
                    $sensPlateau = $dimension[1];
                    $colonne = $dimension[2];
                    $ligne = $dimension[3];
                    break;
                }
            }
            $monTableau[$i] = constructionTableau($colonne,$ligne,$imageFond,$cheminTableau,$sensPlateau);
            fclose($fichier);
        }
        include ("vues/tableauGenerer.php");
        break;
    case "constructionTableau":
        $monTableau = array(1 => "monTableau1");
        $colonne = isset($_REQUEST['colonne']) ? $_REQUEST['colonne'] : NULL; //Si variable $_REQUEST['colonne'] existe, alors $colonne = $_REQUEST['colonne'] sinon == NULL 
        $ligne = isset($_REQUEST['ligne']) ? $_REQUEST['ligne'] : NULL;
        $numeroPlateau = isset($_REQUEST['numeroPlateau']) ? $_REQUEST['numeroPlateau'] : NULL;
        $sensPlateau = isset($_REQUEST['sensPlateau']) ? $_REQUEST['sensPlateau'] : NULL;
        $nomTableau = isset($_REQUEST['nomTableau']) ? $_REQUEST['nomTableau'] : NULL;
        $nomTableau = str_replace(" ", "_", $nomTableau);
        if (connaitreTableau("certains",$nomTableau)){
            echo "<div class='alert alert-danger'><span class='glyphicon glyphicon-remove'></span> <strong>Erreur</strong><br/>Le tableau ne peut pas étre créé, ce nom est déjà attribué à un autre tableau.</div>";
            header('Refresh:2;url=index.php');
        }else{
            constructionNouveauTableau($colonne,$ligne,$nomTableau,$numeroPlateau,$sensPlateau);
            echo "<div class='alert alert-success'><span class='glyphicon glyphicon-ok'></span> <strong>Réussite !</strong><br/>La map a bien été créée. vous allez être redirigé automatiquement vers la selection des tableaux.</div>";
            header('Refresh:2;url=index.php');
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
        $erreur = suppressionProprietesDansCellule($codeASupprimer,$cheminTableau);
        if ($erreur == false){
        echo "<div class='alert alert-success'><strong><span class='glyphicon glyphicon-ok'></span> Réussite !</strong><br/>La propriété a été effacée de la cellule.</div>";
        }else{
            echo "<div class='alert alert-danger'><span class='glyphicon glyphicon-remove'></span> <strong>Avertissement !</strong><br/>La propriété n'a pas été effacée de la cellule ou la propriété n'existe pas.</div>";
        }
        header('Refresh:2;url=index.php?uc=genererTableau&action=genererTableau');
        break;
    case "tourSuivant":
        $_SESSION['nbTours'] = isset($_POST['nbTours']) ? $_POST['nbTours'] : NULL;
        $_SESSION['nomTableau'] = isset($_POST['nomTableau']) ? $_POST['nomTableau'] : NULL;
        $_SESSION['nbTours'] = incrementerTours($_SESSION['nbTours'],"../aideMJ/ressources/Maps/".$_SESSION['nomTableau']."/compteurTours.txt");
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
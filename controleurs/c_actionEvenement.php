<?php
if(!isset($_REQUEST['action'])){
    $_REQUEST['action'] = 'genererTableau';
}
$action = $_REQUEST['action'];
switch ($action){
    case "creerEvenement":
        $titre = isset($_POST['titreEvenement']) ? $_POST['titreEvenement'] : NULL;
        $description = isset($_POST['descriptionEvenement']) ? $_POST['descriptionEvenement'] : NULL;
        $effet = isset($_POST['effetEvenement']) ? $_POST['effetEvenement'] : NULL;
        $nbTours = isset($_POST['nbTours']) ? $_POST['nbTours'] : NULL;
        ajouterNouveauEvenement($titre,$description,$nbTours,$effet);
        break;
    case "supprimerEvenement":
        $numeroPropriete = isset($_POST['numeroPropriete']) ? $_POST['numeroPropriete'] : NULL;
        echo "ATTENTION BUG : la suppression laisse derrière elle 2 ligne '-----', ce qui est faux !. A corriger au possible.";
        echo "num propriete = ".$numeroPropriete;
        $erreur = supprimerPropriete($numeroPropriete);
        //gestionErreur($natureAction, $erreur);
        break;
}


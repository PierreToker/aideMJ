<?php
if(!isset($_REQUEST['action'])){
    $_REQUEST['action'] = 'genererTableau';
}
$action = $_REQUEST['action'];
switch ($action){
    case "creerEvenement":
        $titre = isset($_POST['titreEvenement']) ? $_POST['titreEvenement'] : NULL;
        $description = isset($_POST['descriptionEvenement']) ? $_POST['descriptionEvenement'] : NULL;
        $nbTours = isset($_POST['nbTours']) ? $_POST['nbTours'] : NULL;
        ajouterNouveauEvenement($titre,$description,$nbTours);
        break;
    case "supprimerEvenement":
        echo "pas encore fait";
        break;
}


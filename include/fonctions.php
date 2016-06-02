<?php

// --- constructionTableau ---
// Construit le tableau souhaité par l'utilisateur
// Demande 2 Int (1= la longueur du tableau ET 1 = largeur du tableau)
// Retourne un Array (le tableau à reconstuire avec 1 Foreach)
function constructionTableau($colonne,$ligne){
    $tableau = array();
    $lettre = 'a';
    $fichier = fopen("tableau.txt","r"); 
    verifierSiTableauComplet($colonne,$ligne);
    $tableau[0] = "<div class='dropdown' style='position:relative'><table border='3'>";
    for($i=0;$i<$ligne;$i++){
        if($i != 0){
            $lettre++;
        }
        $tableau[count($tableau)+1] = "<tr>";
        for($x=0;$x<$colonne;$x++){
            $position = "t1_".$lettre.$x;
            $celluleTableau = array();
            array_push($celluleTableau, $position);
            $tableau[count($tableau)+1] = "<td id=\"leTD\"><a href='#' class='btn btn-primary dropdown-toggle' data-toggle='dropdown'>Click here</a><ul class='dropdown-menu'>
                <li><a>$position</a></li>
                <li class='dropdown-header'>Caractéristiques de la cellule</li>";
                foreach (connaitreTouteProprietes($celluleTableau) as $unResultat){
                    foreach ($unResultat as $laPropriete){
                        $pieces = explode("||", $laPropriete);
                        $codePropriete = trim($pieces[0]); 
                        if (!isset($codePropriete)){
                            $codePropriete = "";
                        }
                        $textePropriete = trim($pieces[1]);
                        $tableau[count($tableau)+1] = ""
                            . "<li><a class='trigger right-caret'>$textePropriete</a>"
                                . "<ul class='dropdown-menu sub-menu'>"
                                    . "<li><a class='trigger right-caret'>Modifier attribut</a></li>"
                                    . "<li><a href='#' class='btn btn-primary btn-lg active btn-sm' onclick=\"transfertSuppression('$codePropriete','$position')\">Supprimer attribut</a></li>"
                                . "</ul>"
                            . "</li>";
                    }
                } //onclick du ajout bouton onclick=\"transfertAjout('$position')\"
            $tableau[count($tableau)+1] = "<li><a href='#' role='button' class='trigger right-caret btn btn-primary btn-lg active btn-sm'>Ajouter un attribut</a>"
                                . "<ul class='dropdown-menu sub-menu'><form action='index.php?uc=genererTableau&action=ajouterCellule' method='POST'>"
                                    . "<li><a class='dropdown-header'>Titre de l'événement</a></li>"
                                    . "<li><input type='text' class='form-control' name='titreEvenement'></li>"
                                    . "<li><a class='dropdown-header'>Description de l'événement</a></li>"
                                    . "<li><textarea rows='3' class='form-control' name='descriptionEvenement'></textarea></li>"
                                    . "<li><button class='btn btn-primary btn-sm' type='submit'>Valider</button>"
                                    . "<button class='btn btn-secondary-outline btn-sm' type='reset'>Tous effacer</button></li>"
                                . "</form></ul>"
                            . "</li></ul></td>"; 
        }
        $tableau[count($tableau)+1] = "</tr>";
    }
    $tableau[count($tableau)+1] = "</table></form>";
    fclose($fichier);
    return $tableau;
}

function verifierSiTableauComplet($colonne,$ligne){
    $lettre = 'a';
    $fichier = fopen("tableau.txt","r+"); 
    for($i=0;$i<$ligne;$i++){
        if($i != 0){
            $lettre++;
        }
        for($x=0;$x<$colonne;$x++){
            $position = "t1_".$lettre.$x;
            $fichierA = fopen("tableau.txt","r"); 
            if ($fichierA){
               while (!feof($fichierA)) {
                    $buffer = fgets($fichierA,4096);
                    if ($buffer == ""){
                        $buffer = "null";
                    }
                    $compteur = 0;
                    if(strpos($buffer, $position) !== FALSE ){ // Si la cellule existe déjà
                        $compteur++;
                        break;
                    }
               }
               if ($compteur == 0){ //Si la cellule n'existe pas, il faut la créer
                    $position = $position."\r\n";
                    fputs($fichier, $position);
                    fputs($fichier,"\r\n");
               }
               $compteur = 0;
               fclose($fichierA);
            }
        }
    }
}

function getToutesLesProprietes(){
    
}

function ajoutProprietesCellule(){
    
}

// --- suppressionProprietesDansCellule ---
// Parcourt le fichier (tableau.txt) à la recherche de la propriété à supprimer
// Demande 1 String (= code du tableau + code de la propriété (concaténé au préalable))
// Retourne un boolean résumant le résultat de la fonction
function suppressionProprietesDansCellule($codeCellulePropriete){
    $fichier = fopen("tableau.txt","r+");
    if ($fichier){
        while (($buffer = fgets($fichier, 4096)) !== false) {
            if(strpos($buffer, $codeCellulePropriete) !== false) {
                file_put_contents("tableau.txt", str_replace($buffer, "", file_get_contents("tableau.txt")));
                return true;
            }
        }
        if (!feof($fichier)) {
            echo "Erreur: fgets() de suppression a échoué\n";
        }
    }
    fclose($fichier);
}

// --- rechercheDansFichierCelluleProprietes ---
// Parcourt un fichier à la recherche des proprietes d'une cellule dans le fichier tableau.txt
// Demande 1 String (= proprietes de la cellule qu'on veut trouver)
// Retourne un Array
function rechercheDansFichierCelluleProprietes($uneCellule){ 
    $resultat = array();
    $fichier = fopen("tableau.txt","r"); // On ouvre le fichier en lecture (lecture/ecriture = r+)  
    if ($fichier){
        while (($buffer = fgets($fichier, 4096)) !== false) {
            if (preg_match("#".$uneCellule."#", $buffer)){
                $rest = substr($buffer,6);
                array_push($resultat, $rest);
            }
        }
        if (!feof($fichier)) {
            echo "Erreur: fgets() a échoué\n";
        }
    }
    fclose($fichier); // On ferme le fichier
    return $resultat;
}

// --- rechercheDansFichierProprietes ---
// Parcourt un fichier à la recherche des proprietes dans le fichier proprietes.txt
// Demande 1 String (= le texte de la proprietes qu'on veut trouver)
// Retourne un Array
function rechercheDansFichierProprietes($uneCellule){ 
    $resultat = array();
    $check = false;
    $fichier = fopen("proprietes.txt","r");
    if ($fichier){
        while (($buffer = fgets($fichier, 4096)) !== false) {
            if ($check == true){
                array_push($resultat, $uneCellule."||".$buffer);
                $check = false;
            }else if ($uneCellule === $buffer){
               $check = true;
            }
        }
        if (!feof($fichier)) {
            echo "Erreur: fgets() a échoué\n";
        }
    }
    fclose($fichier); 
    return $resultat;
}

// --- connaitreTouteProprietes ---
// Sert à connaitre les proprietes d'une cellule d'un tableau
// Demande un Array
// Retourne un Array dans un array (un tableau de cellule contenant les proprietes par cellule)
function connaitreTouteProprietes($codeCellule){ 
    $lesProprietesDuTableau = array();
    $lesProprietes = array();
    $lescodeProprietes = array();
    foreach($codeCellule as $uneCellule){ //On va connaitre les codeProprietes d'une cellule
        array_push($lesProprietesDuTableau, rechercheDansFichierCelluleProprietes($uneCellule)); 
    }
    foreach ($lesProprietesDuTableau as $uneCelluleTableau) { //On va connaitre les attributs d'une cellule grace à un codeProprietes
        foreach ($uneCelluleTableau as $uneProprietesTableau){  
            array_push($lesProprietes, rechercheDansFichierProprietes($uneProprietesTableau));
        }
    }
    return $lesProprietes;
}
?>
<?php

// --- constructionTableau ---
// Construit le tableau souhaité par l'utilisateur
// Demande 2 Int (1= la longueur du tableau ET 1 = largeur du tableau)
// Retourne un Array (le tableau à reconstuire avec 1 Foreach)
function constructionTableau($colonne,$ligne){
    $tableau = array();
    $lettre = 'a';
    $lesProprietes = getToutesLesProprietes();
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
                        $tableau[count($tableau)+1] = "<li><a class='trigger right-caret'>$textePropriete</a>"
                                . "<ul class='dropdown-menu sub-menu'>"
                                    . "<li><a class='trigger right-caret'>Modifier attribut</a></li>"
                                    . "<li><a href='#' class='btn btn-primary btn-lg active btn-sm' onclick=\"transfertSuppression('$codePropriete','$position')\">Supprimer attribut</a></li>"
                                . "</ul>"
                            . "</li>";
                    }
                } //onclick du ajout bouton onclick=\"transfertAjout('$position')\"
            $tableau[count($tableau)+1] = "<li><a href='#' role='button' class='trigger right-caret btn btn-primary btn-lg active btn-sm'>Ajouter un événement</a>"
                                . "<ul class='dropdown-menu sub-menu'><form action='index.php?uc=genererTableau&action=ajouterEvementCellule' method='POST'>"
                                    . "<li><a class='dropdown-header'>Choisir l'événement à ajouter</a></li>"
                                    . "<li><select name='titreEvenement'>";
                                    foreach ($lesProprietes as $unePropriete){
                                        switch(true){
                                        case stristr($unePropriete,'lenom='):
                                            $numeroPropriete = $unePropriete."||".$position;
                                            break;
                                        case stristr($unePropriete,'titre='):
                                            $rest = substr($unePropriete,6);
                                            $tableau[count($tableau)+1] =  "<option value='$numeroPropriete'>$rest</option>";
                                            $numeroPropriete = "";
                                            break;
                                        }
                                    }
            $tableau[count($tableau)+1] = "</select></li><br/>"
                                    . "<li><button class='btn btn-primary btn-sm' type='submit'>Valider</button>"
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

// --- getToutesLesProprietes ---
// Parcourt le fichier (proprietes.txt) à la recherche de toutes les propriétés
// Retourne un Array contenant toutes les proprietes présents dans le fichier proprietes.txt
function getToutesLesProprietes(){
    $lesProprietes = array();
    $fichier = fopen("proprietes.txt","r");
    if ($fichier){
        while (($buffer = fgets($fichier, 4096)) !== false) {
            array_push($lesProprietes, $buffer);
            array_push($lesProprietes, "||");
        }
        if (!feof($fichier)) {
            echo "Erreur: fgets() de getToutesLesProprietes a échoué\n";
        }
    }
    return $lesProprietes;
}

function ajoutProprietesCellule($lenomEvenement){ //BUG == $contenuApres saute des lignes au fur et à mesure que l'on refresh la page
    $pieces = explode("||", $lenomEvenement);
    $lenomEvenement = substr($pieces[0],6); $cellule = false;
    $celluleTableau = $pieces[1]; $contenuAvant = array(); $contenuApres = array();
    $combienCaracteres = 0; $i = 0; $positionCellule = 0;
    $fichier = fopen("tableau.txt","r+");
    if ($fichier){
        while (($buffer = fgets($fichier, 4096)) !== false) {
            if (strpos($buffer, "-----") !== false && $cellule == false){
                $cellule = true;
            }
            if(strpos($buffer, $celluleTableau) !== false && $cellule == false) {
                $combienCaracteres = $combienCaracteres + strlen($buffer);
                array_push($contenuAvant,$buffer."\n");
                if ($positionCellule == 0){
                    $positionCellule = $i;
                }
            }
            if($cellule == true) {
                $delimiteur = $i;
                array_push($contenuApres,$buffer."\n");
            }
            $i++;
        }
        //ftruncate($fichier,0);
        //fseek($fichier, 0);
        foreach($contenuAvant as $unElement){
            echo $unElement."<br/>";
            //fputs($fichier,$unElement);
        }
        echo "ICI JE PLACE MON TRUC <br/>";
        //fputs($fichier, $celluleTableau."_".$lenomEvenement."\n");
        foreach($contenuApres as $unElement){
            echo $unElement."<br/>";
            //fputs($fichier,$unElement);
        }
        
        
        
        fseek($fichier,$combienCaracteres-1);
        array_push($contenuApres,"-----");

        //ftruncate($fichier,0);
//        fputs($fichier,$contenuAvant);
//        fputs($fichier,"\n");
//        fputs($fichier, $celluleTableau."_".$lenomEvenement."\n");
//        fputs($fichier,$contenuApres);
        
    }
    fclose($fichier);
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
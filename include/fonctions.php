<?php
// --- actionFichier ---
// Effectue l'action sur le fichier en gérant les erreurs
// Demande 3 string (1 = l'action voulue sur le fichier, 1 = le chemin du fichier, 1 = le mode d'accès au fichier r+,r...)
// Retourne un boolean, le résultat de l'opération. FALSE = erreur / TRUE = pas d'erreur
//function actionFichier($action,$nomFichier,$modeAcces){
//    switch ($action){
//        case "ouvrir":
//            if (!$fp = fopen($nomFichier,$modeAcces)) {
//                echo "Echec de l'ouverture du fichier";
//                return $resultat = false;
//            } else {
//                return $resultat = true;
//            }
//            break;
//        case "fermer":
//            if (!$fp = fclose($nomFichier)) {
//                echo "Echec de fermeture du fichier";
//                return $resultat = false;
//            } else {
//                return $resultat = true;
//            }
//            break;
//    }
//}


function constructionNouveauTableau($colonne,$ligne,$nomTableau){
    $chemin = '../aideMJ/ressources/Maps/'.$nomTableau;
    if (!mkdir($chemin, 0777, true)) {
        die('Echec lors de la création du dossier...');
    }
    $tableau = array();
    $lettre = 'a';
    $cheminTableau = "../aideMJ/ressources/Maps/$nomTableau/tableau_1.txt";
    $fichier = fopen($cheminTableau,"a+"); 
    verifierSiTableauComplet($colonne,$ligne,$cheminTableau);
    $lesProprietes = getToutesLesProprietes();
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
                <li><a>$position</a></li>";
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



function verifierSiTableauComplet($colonne,$ligne,$cheminTableau){
    $lettre = 'a';
    $fichier = fopen($cheminTableau,"r+"); 
    for($i=0;$i<$ligne;$i++){
        if($i != 0){
            $lettre++;
        }
        for($x=0;$x<$colonne;$x++){
            $position = "t1_".$lettre.$x;
            $fichierA = fopen($cheminTableau,"r"); 
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

// --- ajouterNouveauEvenement ---
// Ajoute l'événement voulue par l'utilisateur sur le fichier (proprietes.txt), gére l'encodage UTF-8
// Demande 3 String (1 = le titre de l'événement, 1 = la description de l'événement, 1 = le nombre de tour que dure l'événement)
function ajouterNouveauEvenement($titreEvenement,$descriptionEvenement,$nbTours){
    $fichier = fopen("proprietes.txt","r+");
    $i = 0;
    if ($fichier){
        while (($buffer = fgets($fichier)) !== false) {
            if(strpos($buffer, "lenom=") !== false){
                ++$i;
            }
        }
        if (!feof($fichier)) {
            echo "Erreur: fgets() de suppression a échoué\n";
            $resulatat = true;
        }
        fseek($fichier, 0, SEEK_END);
        fputs($fichier,"\r\n");
        fputs($fichier,'lenom=p'.$i."\r\ntitre=".$titreEvenement."\r\ndescr=".$descriptionEvenement."\r\n-------------------");
        $resultat = false;
    }
    if ($resultat == false){
        echo "<div class='alert alert-success'><span class='glyphicon glyphicon-ok'></span> <strong>Réussite !</strong><br/>Opération effectuée avec succès.</div>";
        header('Refresh:2;url=index.php?uc=genererTableau&action=genererTableau');
    }else{
        echo "<div class='alert alert-danger'><span class='glyphicon glyphicon-remove'></span> <strong>Echec !</strong><br/>L'opération n'a pas aboutie car la cellule comporte déjà cet attribut.</div>";
        header('Refresh:2;url=index.php?uc=genererTableau&action=genererTableau');
    }
}

// --- ajoutProprietesCellule ---
// Parcourt le fichier (tableau.txt) pour implémenter la nouvelle propriété, gére les doublons
// Demande 1 Array contenant le nom de l'événement (ex: p0) et la celulle du tableau (ex: t1_a0)
function ajoutProprietesCellule($lenomEvenement){
    $contenuAvant = array(); $contenuApres = array();
    $delimiteur = 0;
    $resultat = false; $existeDeja = false;
    $pieces = explode("||", $lenomEvenement);
    $lenomEvenement = substr($pieces[0],6); 
    $celluleTableau = $pieces[1]; 
    $celluleEtEvenement = $celluleTableau."_".$lenomEvenement;
    $fichier = fopen("tableau.txt","r+");
    array_push($contenuApres,"\n");
    if ($fichier){
        while (($buffer = fgets($fichier, 4096)) !== false) { //Recherche la cellule
            if(strpos($buffer, $celluleTableau) !== true) {
                $delimiteur += strlen($buffer);
                array_push($contenuAvant,$buffer."\n");
            }
            if(strpos($buffer, $celluleTableau) !== false){
                break;
            }
        }
        fseek($fichier, $delimiteur);
        while (($buffer = fgets($fichier, 4096)) !== false) { //Va jusqu'au délimiteur (-----) en partant du nom de cellule
            if(strpos($buffer, "-----") !== false) {
                break;
            }else{
                if(strpos($buffer,$celluleEtEvenement) !== false){
                    $existeDeja = true;
                    break;
                }
                $delimiteur += strlen($buffer);
                array_push($contenuAvant,$buffer."\n");
            }
        }
        if ($existeDeja == false){
            fseek($fichier, $delimiteur);
            while (($buffer = fgets($fichier, 4096)) !== false) { //Prend tout le reste du fichier
                array_push($contenuApres,$buffer."\n");
            }
            ftruncate($fichier,0);
            fseek($fichier, 0);
            foreach($contenuAvant as $unElement){
                fputs($fichier,$unElement);
            }
            fputs($fichier, $celluleTableau."_".$lenomEvenement."\n");
            foreach($contenuApres as $unElement){
                fputs($fichier,$unElement);
            }   
        }
    }
    fclose($fichier);
    if ($existeDeja == false){
        echo "<div class='alert alert-success'><span class='glyphicon glyphicon-ok'></span> <strong>Réussite !</strong><br/>Opération effectuée avec succès.</div>";
        header('Refresh:2;url=index.php?uc=genererTableau&action=genererTableau');
    }else{
        echo "<div class='alert alert-danger'><span class='glyphicon glyphicon-remove'></span> <strong>Echec !</strong><br/>L'opération n'a pas aboutie car la cellule comporte déjà cet attribut.</div>";
        header('Refresh:2;url=index.php?uc=genererTableau&action=genererTableau');
    }
}

// --- suppressionProprietesDansCellule ---
// Parcourt le fichier (tableau.txt) à la recherche de la propriété à supprimer
// Demande 1 String (= code du tableau + code de la propriété (concaténé au préalable))
function suppressionProprietesDansCellule($codeCellulePropriete){
    $fichier = fopen("tableau.txt","r+");
    $resultat = false;
    if ($fichier){
        while (($buffer = fgets($fichier, 4096)) !== false) {
            if(strpos($buffer, $codeCellulePropriete) !== false) {
                file_put_contents("tableau.txt", str_replace($buffer, "", file_get_contents("tableau.txt")));
                $resultat = true;
            }
        }
        if (!feof($fichier)) {
            echo "Erreur: fgets() de suppression a échoué\n";
        }
    }
    fclose($fichier);
    if ($resultat == true){
        echo "<div class='alert alert-success'><strong><span class='glyphicon glyphicon-ok'></span> Réussite !</strong><br/>La propriété a été effacée de la cellule.</div>";
    }else{
        echo "<div class='alert alert-danger'><span class='glyphicon glyphicon-remove'></span> <strong>Avertissement !</strong><br/>La propriété n'a pas été effacée de la cellule ou la propriété n'existe pas.</div>";
    }
    header('Refresh:2;url=index.php?uc=genererTableau&action=genererTableau');
}

// --- rechercheDansFichierCelluleProprietes ---
// Parcourt un fichier à la recherche des proprietes d'une cellule dans le fichier tableau.txt
// Demande 1 String (= proprietes de la cellule qu'on veut trouver)
// Retourne un Array contenant propriétés de la cellule voulue
function rechercheDansFichierCelluleProprietes($uneCellule){ 
    $resultat = array();
    $fichier = fopen("tableau.txt","r"); //(lecture/ecriture = r+)  
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
    fclose($fichier);
    return $resultat;
}

// --- rechercheDansFichierProprietes ---
// Parcourt un fichier à la recherche des proprietes dans le fichier proprietes.txt
// Demande 1 String (= le code de la propriété)
// Retourne un Array
function rechercheDansFichierProprietes($uneCellule){ 
    $resultat = array();
    $check = false;
    $fichier = fopen("proprietes.txt","r");
    if ($fichier){
        while (($buffer = fgets($fichier, 4096)) !== false) {
            if ($check == true){
                $buffer = substr($buffer, 6);
                array_push($resultat, $uneCellule."||".$buffer);
                $check = false;
            }else if ("lenom=".$uneCellule === $buffer){
               $check = true;
            }
        }
        if (!feof($fichier)) {
            echo "Erreur: fgets() de rechercheDansFichierProprietes() a échoué\n";
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

// --- combienDeTableau ---
// Sert à connaitre tous les tableaux déjà créé par l'utilisateur
// Retourne un Array (les noms des tableaux)
function connaitreTableau($action,$nomTableau){
    $dirname = '../aideMJ/ressources/Maps';
    $dir = opendir($dirname); 
    switch ($action){
        case "certains":
            $aVerifier = $dirname."/".$nomTableau;
            $lesFichiers = false;
            if(file_exists($aVerifier)){
                $lesFichiers = true;
            }
            closedir($dir);
            return $lesFichiers;
            break;
        case "tous":
            $lesFichiers = array(); 
            while($file = readdir($dir)) {
                if($file != '.' && $file != '..' && !is_dir($dirname.$file)){
                    array_push($lesFichiers,str_replace("_", " ", $file));
                }
            }
            closedir($dir);
            return $lesFichiers;
            break;
        default :
            echo "le choix sur la fonction connaitreTableau() n'existe pas.";
    }
}
?>
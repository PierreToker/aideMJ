<?php
// --- constructionNouveauTableau ---
// Construit le tableau souhaité par l'utilisateur dans un fichier txt quand la map n'existe pas
// Demande 2 Int (1= la longueur du tableau ET 1 = largeur du tableau) et 1 String (1= le nom de la map)
function constructionNouveauTableau($colonne,$ligne,$nomTableau,$numeroPlateau,$sensPlateau,$numeroTableau){
    $chemin = '../aideMJ/ressources/Maps/'.$nomTableau;
    try{
        if (!file_exists($chemin)) {
            if (!mkdir($chemin, 0777, true))
                die('Echec lors de la création du dossier.');
        }
        if (!file_exists('../aideMJ/ressources/Maps/elementsDecor')) {
            if (!mkdir('../aideMJ/ressources/Maps/elementsDecor', 0777, true))
                die('Echec lors de la création du dossier.');
        }
        $fichier = fopen("../aideMJ/ressources/Maps/$nomTableau/tableau_$numeroTableau.txt","a+");
        fclose($fichier);
        $cheminTableau = "../aideMJ/ressources/Maps/$nomTableau/";
        construireTableauFichier($colonne,$ligne,$cheminTableau,$numeroPlateau,$sensPlateau,$numeroTableau);
    }catch(Exception $e){
        print_r($e);
    }
}

// --- constructionTableau ---
// Construit le tableau souhaité par l'utilisateur
// Demande 2 Int (1= la longueur du tableau ET 1 = largeur du tableau)
// Retourne un Array (le tableau à reconstuire avec 1 Foreach)
function constructionTableau($colonne,$ligne,$imageFond,$cheminTableau,$sensPlateau,$numeroPlateau){
    $tableau = array(); $lesEvements = array();
    $lettre = 'a';
    $lesProprietes = getToutesLesProprietes();
    $lesEvements = getLesEvenements($_SESSION['nomTableau']);
    $lesElementsDecors = getLesDecors($_SESSION['nomTableau']);
    $fichier = fopen($cheminTableau,"r"); 
    $tableau[0] = "<div class='dropdown' style='position:absolute;z-index:2;'><table border='3' class='rotation_$sensPlateau'>";
    for($i=0;$i<$ligne;$i++){
        if($i != 0)
            ++$lettre;
        $tableau[count($tableau)+1] = "<tr>";
        for($x=0;$x<$colonne;$x++){
            $elementDecor = "";
            $position = "t".$numeroPlateau."_".$lettre.$x;
            $celluleTableau = array();
            if (array_key_exists($position,$lesElementsDecors))
                $elementDecor = determinerElementDecor($lesElementsDecors,$position);
            array_push($celluleTableau, $position);
            $tableau[count($tableau)+1] = "<td id=\"leTD\"><a href='#' data-toggle='dropdown'><section class='blur'><img id='imageBackground' src='../aideMJ/ressources/Images/$imageFond/$imageFond"."$lettre"."$x.jpg' height='70' width='70' onerror=\"this.src='../aideMJ/ressources/Images/default.jpg'\"/></section>$elementDecor</a><ul class='dropdown-menu'>
                <li><a>$position</a></li>
                <li class='dropdown-header'>Evenement affectant la cellule</li>";
            if(in_array($position,$lesEvements)){
            foreach (connaitreTouteProprietes($celluleTableau,$cheminTableau) as $unResultat){
                foreach ($unResultat as $laPropriete){
                    $pieces = explode("||", $laPropriete);
                    $codePropriete = trim($pieces[0]); 
                    if (!isset($codePropriete))
                        $codePropriete = "";
                    $textePropriete = trim($pieces[1]);
                    $tableau[count($tableau)+1] = "<li><a class='trigger right-caret'>$textePropriete</a>"
                            . "<ul class='dropdown-menu sub-menu'>"
                                . "<li>Caractéristiques de l'événement";
                                        $detailEvenement = chercherUnePropriete($codePropriete,"tous");
                                        $codeCelluleEtPropriete = $position."_".$codePropriete;
                                        $demarreQuand = explode("||", determinerDemarreQuand($codeCelluleEtPropriete,$cheminTableau));
                                        if ($demarreQuand[1] == 0)
                                            $demarreQuand[1] = "Ce tour-ci uniquement";
                                        $lesDetails = explode("||", $detailEvenement);
                                        $tableau[count($tableau)+1] = "<li class='dropdown-header'>Titre de l'événement</li>"
                                            . "<li>$lesDetails[0]</li>"
                                            . "<li class='dropdown-header'>Description de l'événement</li>"
                                            . "<li>$lesDetails[1]</li>"
                                            . "<li class='dropdown-header'>Conséquence de l'événement</li>"
                                            . "<li>$lesDetails[2]</li>"
                                            . "<li class='dropdown-header'>Nombre de tours que dure l'événement</li>"
                                            . "<li><a class='trigger right-caret'>$demarreQuand[1]</a>"
                                                . "<ul class='dropdown-menu sub-menu'><form action='index.php?uc=genererTableau&action=modifierEvementCellule' method='POST'>"
                                                    . "<li>Donner une nouvelle valeur</li>"
                                                    . "<li><input type='number' name='dureeEvenement' min='0' max='1000' required></li>"
                                                . "<li><button class='btn btn-primary btn-sm' type='submit'>Valider</button></li>"
                                                . "<input type='hidden' name='codeCelluleEtPropriete' value='$codeCelluleEtPropriete'><input type='hidden' name='cheminTableau' value='$cheminTableau'><input type='hidden' name='demarreQuand' value='$demarreQuand[0]'></form></ul>"
                                            . "</li>"
                                            . "<li class='dropdown-header'>Quand démarre l'événement ? </li>"
                                            . "<li><a class='trigger right-caret'>Tour numéro : $demarreQuand[0]</a>"
                                                . "<ul class='dropdown-menu sub-menu'><form action='index.php?uc=genererTableau&action=modifierEvementCellule' method='POST'>"
                                                . "<li>Donner une nouvelle valeur</li>"
                                                    . "<li><input type='number' name='demarreQuand' min=".$_SESSION['nbTours']." max='1000' required></li>"
                                                . "<li><button class='btn btn-primary btn-sm' type='submit'>Valider</button></li>"
                                                . "<input type='hidden' name='codeCelluleEtPropriete' value='$codeCelluleEtPropriete'><input type='hidden' name='cheminTableau' value='$cheminTableau'><input type='hidden' name='dureeEvenement' value='$lesDetails[4]'></form></ul>"
                                            . "</li>"
                                . "<li><a href='#' class='btn btn-primary btn-lg active btn-sm' onclick=\"transfertSuppression('$codePropriete','$position','$cheminTableau')\">Supprimer attribut</a></li>"
                            . "</ul>"
                        . "</li>";
                }
            }
            }//onclick du ajout bouton onclick=\"transfertAjout('$position')\"
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
                                    . "<li><a class='dropdown-header'>A quelle tour commencera l'événément</a></li>"
                                    . "<li><input type='number' name='quand' min=".$_SESSION['nbTours']." max='1000' required></li>"
                                    . "<li><button class='btn btn-primary btn-sm' type='submit'>Valider</button>"
                                . "<input type='hidden' name='cheminTableau' value='$cheminTableau'></form></ul><br/>"
                            . "</li>"
                        . "<li><a href='#' role='button' class='trigger right-caret btn btn-primary btn-lg active btn-sm'>Ajouter un élément du décor</a>"
                        . "<ul class='dropdown-menu sub-menu'><form action='index.php?uc=genererTableau&action=ajouterDecor' method='POST'>"
                            . "<li><a class='dropdown-header'>Choississez l'élément à ajouter</a></li>"
                            . "<li><select name='nomElement'>"
                                . "<option value='arbre'>Arbre</option>"
                                . "<option value='pilier'>Pilier</option>"
                                . "<option value='porte'>Porte</option>"
                            . "</select></li>"
                            . "<li><a class='dropdown-header'>Ou se situe la porte ?</a></li>"
                            . "<li><input type='text' name='champsConcernes'></li><br/>" //BUG INPUT == NE FONCTIONNE PAS, RAISON INCONNUE
                            . "<li><button class='btn btn-primary btn-sm' type='submit'>Valider</button>"
                        . "<input type='hidden' name='cellule' value='$position'></form></ul></li></ul></td>"; 
        }
        $tableau[count($tableau)+1] = "</tr>";
    }
    $tableau[count($tableau)+1] = "</table></form>";
    fclose($fichier);
    return $tableau;
}

// --- construireTableauFichier ---
// Constuit le tableau dans le fichier 
// NOTE : ne gére que le premier tableau actuellement
function construireTableauFichier($colonne,$ligne,$cheminTableau,$numeroPlateau,$sensPlateau,$numeroTableau){
    $fichierTours = fopen($cheminTableau."/compteurTours.txt","a+");
    fputs($fichierTours, 1);
    fclose($fichierTours);
    $lettre = 'a';
    $cheminTableau = $cheminTableau."/tableau_$numeroTableau.txt";
    $fichier = fopen($cheminTableau,"r+"); 
    fputs($fichier,$numeroPlateau."_".$sensPlateau."_".$colonne."_".$ligne."\r\n");
    fputs($fichier, "-----\r\n");
    for($i=0;$i<$ligne;$i++){
        if($i != 0)
            $lettre++;
        for($x=0;$x<$colonne;$x++){
            $position = "t".$numeroPlateau."_".$lettre.$x;
            $fichierA = fopen($cheminTableau,"r"); 
            if ($fichierA){
               while (!feof($fichierA)) {
                    $buffer = fgets($fichierA,4096);
                    if ($buffer == "")
                        $buffer = "null";
                    $compteur = 0;
                    if(strpos($buffer, $position) !== FALSE ){ // Si la cellule existe déjà
                        ++$compteur;
                        break;
                    }
               }
               if ($compteur == 0){ //Si la cellule n'existe pas, il faut la créer
                    $position = $position."\r\n";
                    fputs($fichier, $position);
                    fputs($fichier,"\r");
                    fputs($fichier, "-----\r\n");
               }
               $compteur = 0;
               fclose($fichierA);
            }
        }
    }
    fclose($fichier);
}

// --- ajoutProprietesCellule ---
// Parcourt le fichier (tableau.txt) pour implémenter la nouvelle propriété, gére les doublons
// Demande 1 Array contenant le nom de l'événement (ex: p0) et la celulle du tableau (ex: t1_a0) et 1 String (le chemin du tableau)
function ajoutProprietesCellule($lenomEvenement,$cheminTableau,$quand){
    $contenuAvant = array(); $contenuApres = array();
    $delimiteur = 0;
    $resultat = false; $erreur = false;
    $pieces = explode("||", $lenomEvenement);
    $lenomEvenement = substr($pieces[0],6); 
    $nbTours = chercherUnePropriete($lenomEvenement,"sonTemps");
    $celluleTableau = $pieces[1]; 
    $celluleEtEvenement = $celluleTableau."_".$lenomEvenement;
    $fichier = fopen($cheminTableau,"r+");
    array_push($contenuApres,"\n");
    if ($fichier){
        while (($buffer = fgets($fichier, 4096)) !== false) { //Recherche la cellule
            if(strpos($buffer, $celluleTableau) !== true) {
                $delimiteur += strlen($buffer);
                array_push($contenuAvant,$buffer."\n");
            }
            if(strpos($buffer, $celluleTableau) !== false)
                break;
        }
        fseek($fichier, $delimiteur);
        while (($buffer = fgets($fichier, 4096)) !== false) { //Va jusqu'au délimiteur (-----) en partant du nom de cellule
            if(strpos($buffer, "-----") !== false) {
                break;
            }else{
                if(strpos($buffer,trim($celluleEtEvenement)) !== false){
                    $erreur = true;
                    break;
                }
                $delimiteur += strlen($buffer);
                array_push($contenuAvant,$buffer."\n");
            }
        }
        if ($erreur == false){
            fseek($fichier, $delimiteur);
            while (($buffer = fgets($fichier, 4096)) !== false) { //Prend tout le reste du fichier
                array_push($contenuApres,$buffer."\n");
            }
            ftruncate($fichier,0);
            fseek($fichier, 0);
            foreach($contenuAvant as $unElement){
                fputs($fichier,$unElement);
            }
            fputs($fichier, trim($celluleEtEvenement)."_".$quand."_".$nbTours);
            foreach($contenuApres as $unElement){
                fputs($fichier,$unElement);
            }   
        }
    }
    fclose($fichier);
    return $erreur;
}

// --- suppressionProprietesDansCellule ---
// Parcourt le fichier (tableau.txt) à la recherche de la propriété à supprimer
// Demande 2 String (1 = code du tableau + code de la propriété (concaténé au préalable), 1 = le chemin du tableau voulu)
function suppressionProprietesDansCellule($codeCellulePropriete,$cheminTableau){
    $fichier = fopen($cheminTableau,"r+");
    $erreur = false;
    try{
        if ($fichier){
            while (($buffer = fgets($fichier, 4096)) !== false) {
                if(strpos($buffer, $codeCellulePropriete) !== false)
                    file_put_contents($cheminTableau, str_replace($buffer, "", file_get_contents($cheminTableau)));
            }
        }
        fclose($fichier);
    }catch(Exception $e){
        $erreur = true;
        print_r($e);
    }
    return $erreur;
}

function modifierEvementCellule($cheminTableau,$demarreQuand,$dureeEvenement,$codeCelluleEtPropriete){
    $contenuAvant = array(); $contenuApres = array();
    $delimiteur = 0;
    $existeDeja = false;
    $fichier = fopen($cheminTableau,"r+");
    if ($fichier){
        while (($buffer = fgets($fichier)) !== false) { //Recherche la cellule
            if(strpos($buffer, $codeCelluleEtPropriete) !== false){
                break;
            }
            if(strpos($buffer, $codeCelluleEtPropriete) !== true) {
                $delimiteur += strlen($buffer);
                array_push($contenuAvant,$buffer);
            }

        }
        fseek($fichier, $delimiteur);
        while (($buffer = fgets($fichier)) !== false) {
            $delimiteur += strlen($buffer);
            break;
        }
        fseek($fichier, $delimiteur);
        while (($buffer = fgets($fichier)) !== false) {
            array_push($contenuApres,$buffer);
        }
        ftruncate($fichier, 0);
        fseek($fichier, 0);
        foreach ($contenuAvant as $unElement){
            fputs($fichier,$unElement);
        }
        fputs($fichier,$codeCelluleEtPropriete."_".$demarreQuand."_".$dureeEvenement."\n");
        foreach ($contenuApres as $unElement){
            fputs($fichier,$unElement);
        }  
    }
}

// --- rechercheDansFichierCelluleProprietes ---
// Parcourt un fichier à la recherche des proprietes d'une cellule dans le fichier tableau.txt
// Demande 1 String (= proprietes de la cellule qu'on veut trouver)
// Retourne un Array contenant propriétés de la cellule voulue
function rechercheDansFichierCelluleProprietes($uneCellule,$cheminTableau){ 
    $resultat = array();
    $tabfich = file($cheminTableau); 
    foreach($tabfich as $buffer){ 
        if (strpos($buffer,$uneCellule) !== false){
            $rest = substr($buffer,6);
            $rest = mb_strimwidth($rest, 0, 2); //supprime le start event et la durée de l'event
            array_push($resultat, $rest);
        }     
    }
    return $resultat;
    
    
//    $resultat = array();
//    $fichier = fopen($cheminTableau,"r"); //(lecture/ecriture = r+)  
//    if ($fichier){
//        while ((!feof($fichier))){
//            $buffer = fgets($fichier);
//            if (strpos($buffer,$uneCellule) !== false){
//                $rest = substr($buffer,6);
//                $rest = mb_strimwidth($rest, 0, 2); //supprime le start event et la durée de l'event
//                array_push($resultat, $rest);
//            }
//        }
//        if (!feof($fichier))
//            echo "Erreur: fgets() de rechercheDansFichierCelluleProprietes() a échoué\n";
//    }
//    fclose($fichier);
//    return $resultat;
}

// --- connaitreTouteProprietes ---
// Sert à connaitre les proprietes d'une cellule d'un tableau
// Demande un Array
// Retourne un Array dans un array (un tableau de cellule contenant les proprietes par cellule)
function connaitreTouteProprietes($codeCellule,$cheminTableau){ 
    $lesProprietesDuTableau = array();
    $lesProprietes = array();
    $lescodeProprietes = array();
    foreach($codeCellule as $uneCellule){ //On va connaitre les codeProprietes d'une cellule
        array_push($lesProprietesDuTableau, rechercheDansFichierCelluleProprietes($uneCellule,$cheminTableau)); 
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
        case "combienTableau":
            closedir($dir);
            $dirname = "../aideMJ/ressources/Maps/".$nomTableau."/";
            $dir = opendir($dirname); 
            $lesFichiers = array(); 
            while($file = readdir($dir)) {
                if($file != '.' && $file != '..' && !is_dir($dirname.$file) && stristr($file, "tableau_") == true)
                    array_push($lesFichiers,$file);
            }
            closedir($dir);
            return $lesFichiers;
            break;
        case "connaitreNumeroTableau":
            closedir($dir);
            $numeroTableau = "";
            $dirname = "../aideMJ/ressources/Maps/".$nomTableau."/";
            if (file_exists($dirname)) {
                $dir = opendir($dirname); 
                $lesFichiers = array(); 
                while($file = readdir($dir)) {
                    if($file != '.' && $file != '..' && !is_dir($dirname.$file) && stristr($file, "tableau_") == true)
                        $numeroTableau = substr(stristr($file,"_"),1,-4)+1;
                }
                closedir($dir);
            }else{
                $numeroTableau = 0;
            }
            return $numeroTableau;
            break;
        case "tous":
            $lesFichiers = array(); 
            while($file = readdir($dir)) {
                if($file != '.' && $file != '..' && !is_dir($dirname.$file))
                    array_push($lesFichiers,str_replace("_", " ", $file));
            }
            closedir($dir);
            return $lesFichiers;
            break;
        default :
            echo "le choix sur la fonction connaitreTableau() n'existe pas.";
    }
}

// --- determinerDemarreQuand ---
// Permet de connaitre la date de départ d'un événement 
// Demande un String (1= le code de la cellule du tableau) et une Ressource (le chemin ou se situe le fichier compteurTours.txt))
// Retourne deux String (1= la date de commencement de l'événement et le nombre de tours qu'il es actif)
function determinerDemarreQuand($codeCellule,$cheminTableau){
    $check = false;
    $fichier = file($cheminTableau); 
    foreach($fichier as $buffer){
        if (strpos($buffer, $codeCellule) !== false){
            $demarreQuand = explode("_", $buffer);
            $resultat = $demarreQuand[3]."||"; //Démarre quand
            $resultat = $resultat.$demarreQuand[4]; //Durée en temps
        }
    }
    return $resultat;
}

// --- determinerTour ---
// Permet de connaitre le nombre de tours passés
// Demande un String (1= le chemin ou se situe le fichier compteurTours.txt)
function determinerTour($cheminTableau){
    $fichier = fopen($cheminTableau,"r"); 
    if ($fichier){
        while (($buffer = fgets($fichier)) !== false) {
            $nbTours = $buffer;
            break;
        }
    }
    fclose($fichier);
    return $nbTours;
}

// --- incrementerTours ---
// Permet d'incrémenter la valeur du tour pour un tableau (sa position dans le temps par rapport à une partie)
// Demande un Int (1= le tours (non incrémenté)) et un String (1= le chemin ou se situe le fichier compteurTours.txt)
function incrementerTours($nbTours,$chemin){
    $fichier = fopen($chemin,"r+");
    if ($fichier){
        ftruncate($fichier, 0);
        fputs($fichier,$nbTours + 1);
    }
    return $nbTours + 1;
}

// --- determinerEvenementCeTour ---
// Permet de connaitre les événements qui se déclenchent ce tours-ci
// Demande un Int (1= le tours (non incrémenté)) et un String (1= le chemin ou se situe le fichier compteurTours.txt)
function determinerEvenementCeTour($nbTours,$nomTableau){
    $evenementsActifs = array();
    try{
        foreach (connaitreTableau("combienTableau", $nomTableau) as $unTableau){
            $cheminTableau = "../aideMJ/ressources/Maps/".$nomTableau."/".$unTableau;        
            $i = 0;
            $fichier = file($cheminTableau); 
            foreach($fichier as $buffer){
                if ($buffer > 5 && $i > 0){
                    $temps = explode("_", $buffer);
                    $quandFinira = $temps[3] + $temps[4]; //Démarre quand + Durée en temps
                    echo "Démarre quand : ".$temps[3]."durée temps : ".$temps[3]."<br/>";
                    if ($quandFinira < $nbTours){
                        suppressionProprietesDansCellule($temps[1].$temps[2], $cheminTableau);
                    }else if ($temps[0] <= $nbTours && $quandFinira >= $nbTours){
                        $codePropriete = substr($buffer,6,-7);
                        array_push($evenementsActifs,$codePropriete);
                    }
                }
                if ($i == 0){
                    ++$i;
                }
            }
        }
        return $evenementsActifs;
    }catch(Exception $e){
        print_r($e);
    }
}

// --- getTousPlateaux ---
// Determiner quel plateau est disponible à l'utilisation
// Retour un Array contenant la liste des numéros des plateaux disponibles.
function getTousPlateaux(){    
    $lesPlateaux = array();
    $repertoire = '../aideMJ/ressources/Images/';
    $dossier = opendir($repertoire); 
    while($file = readdir($dossier)) {
        if($file != '.' && $file != '..' && !is_dir($repertoire.$file)){
            if (strpos($file, "complet") !== false){
                $file = explode(".",substr($file,8));
                array_push($lesPlateaux,"<option value='$file[0]'>$file[0]</option>");
            }
        }
    }
    closedir($dossier);
    return $lesPlateaux;
}

// --- ajoutElementDecor ---
// Ecrit dans le fichier elementsDecor.txt l'emplacement du décor (exemple : t1_a0_arbre)
// Retour un boolean (le résultat de l'opération)
function ajoutElementDecor($nomElement,$nomTableau,$cellule){
    $existeDeja = false;
    $cheminTableau = "../aideMJ/ressources/Maps/".$nomTableau."/elementsDecor.txt";
    $fichier = fopen($cheminTableau,"r+");
    if ($fichier){
        while(($buffer = fgets($fichier)) !== false) {
            if(stripos($buffer,$cellule."_".$nomElement) !== false){
                $existeDeja = true;
                break;
            }
        }
        if ($existeDeja == false)
            fputs($fichier,trim($cellule)."_".$nomElement."\r\n");
    }
    fclose($fichier);
    return $existeDeja;
}

// --- getLesDecors ---
// Donne tous les décors attribué au tableau
// Demande un String (le chemin qui mène au tableau
// Retour un Array des éléments du décors (arbre, pilier...)
function getLesDecors($nomTableau){
    $lesElements = array();
    $cheminTableau = "../aideMJ/ressources/Maps/".$nomTableau."/elementsDecor.txt";
    $fichier = fopen($cheminTableau,"r");
    if ($fichier){
        while(($buffer = fgets($fichier)) !== false) {
            $resultat = explode("_",$buffer);
            $lesElements[$resultat[0]."_".$resultat[1]] = $resultat[2];
        }
    }
    fclose($fichier);
    return $lesElements;
}

function determinerElementDecor($lesElementsDecors,$positionDansTableau){
    switch(trim($lesElementsDecors[$positionDansTableau])){
        case "arbre":
            return $decor = "<div id='premierPlan'><span class='glyphicon glyphicon-tree-deciduous'></div>";          
            break;
        case "pilier":
            return $decor = "<div id='premierPlan'><span class='glyphicon glyphicon-pawn'></div>";          
            break;
        case "porte":
            echo "Porte, pas encore codé !";
            break;
    }
}


function gestionErreur($natureAction,$erreur){
    $index = false;
    switch($natureAction){
        case "suppressionProprietesDansCellule":
            if ($erreur == false){
                echo "<div class='alert alert-success'><strong><span class='glyphicon glyphicon-ok'></span> Réussite !</strong><br/>La propriété a été effacée de la cellule.</div>";
            }else{
                echo "<div class='alert alert-danger'><span class='glyphicon glyphicon-remove'></span> <strong>Avertissement !</strong><br/>La propriété n'a pas été effacée de la cellule ou la propriété n'existe pas.</div>";
            }
        break;
        case "ajouterEvementCellule":
            if ($erreur == false){
                echo "<div class='alert alert-success'><span class='glyphicon glyphicon-ok'></span> <strong>Réussite !</strong><br/>Opération effectuée avec succès.</div>";
            }else{
                echo "<div class='alert alert-danger'><span class='glyphicon glyphicon-remove'></span> <strong>Echec !</strong><br/>L'opération n'a pas aboutie car la cellule comporte déjà cet attribut.</div>";
            }    
            break;
        case "":
            
            break;
        default :
            echo "Rien selectionné dans la fonction gestionErreur()";
    }
    header('Refresh:2;url=index.php?uc=genererTableau&action=genererTableau');
}

function getLesEvenements($nomTableau){
    $cheminTableau = "../aideMJ/ressources/Maps/".$nomTableau."/tableau_1.txt";
    $fichier = file($cheminTableau); 
    $lesEvenements = array();
    foreach($fichier as $buffer){
        if (isset($buffer[8])){ //equivalant à strlen($buffer) > 8
            array_push($lesEvenements,substr($buffer,0,5));
        }
    }
    return $lesEvenements;
}
?>
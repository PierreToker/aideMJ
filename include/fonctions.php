<?php
// --- actionFichier ---
// Effectue l'action sur le fichier en gérant les erreurs
// Demande 3 string (1 = l'action voulue sur le fichier, 1 = le chemin du fichier, 1 = le mode d'accès au fichier r+,r...)
// Retourne la ressource fichier et, un message d'erreur si cas échéant 
// r = Ouvre le fichier en lecture seule. Cela signifie que vous pourrez seulement lire le fichier.
// r+ = Ouvre le fichier en lecture et écriture. Vous pourrez non seulement lire le fichier, mais aussi y écrire (on l'utilisera assez souvent en pratique).
// a = Ouvre le fichier en écriture seule. Mais il y a un avantage : si le fichier n'existe pas, il est automatiquement créé.
// a+ = Ouvre le fichier en lecture et écriture. Si le fichier & n'existe pas, il est créé automatiquement. Attention : le répertoire doit avoir un CHMOD à 777 dans ce cas ! À noter que si le fichier existe déjà, le texte sera rajouté à la fin.
function actionFichier($action,$nomFichier,$modeAcces){
    switch ($action){
        case "ouvrir":
            if (!$fp = fopen($nomFichier,$modeAcces)) {
                echo "Echec de l'ouverture du fichier";
                return $fp;
            } else {
                return $fp;
            }
            break;
        case "fermer":
            if (!$fp = fclose($nomFichier)) {
                echo "Echec de fermeture du fichier";
                return $fp;
            } else {
                return $fp;
            }
            break;
    }
}

// --- constructionNouveauTableau ---
// Construit le tableau souhaité par l'utilisateur quand la map n'existe pas
// Demande 2 Int (1= la longueur du tableau ET 1 = largeur du tableau) et 1 String (1= le nom de la map)
// Retourne un Array (le tableau à reconstuire avec 1 Foreach)
function constructionNouveauTableau($colonne,$ligne,$nomTableau){
    $chemin = '../aideMJ/ressources/Maps/'.$nomTableau;
    if (!mkdir($chemin, 0777, true))
        die('Echec lors de la création du dossier.');
    $tableau = array();
    $lettre = 'a';
    $fichier = actionFichier("ouvrir","../aideMJ/ressources/Maps/$nomTableau/tableau_1.txt","a+");
    $cheminTableau = "../aideMJ/ressources/Maps/$nomTableau/";
    construireTableauFichier($colonne,$ligne,$cheminTableau);
    $lesProprietes = getToutesLesProprietes();
    $tableau[0] = "<div class='dropdown' style='position:relative'><table border='3'>";
    for($i=0;$i<$ligne;$i++){
        if($i != 0)
            $lettre++;
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
    actionFichier("fermer",$fichier,"");
    return $tableau;
}

// --- constructionTableau ---
// Construit le tableau souhaité par l'utilisateur
// Demande 2 Int (1= la longueur du tableau ET 1 = largeur du tableau)
// Retourne un Array (le tableau à reconstuire avec 1 Foreach)
function constructionTableau($colonne,$ligne,$cheminTableau){
    $tableau = array();
    $lettre = 'a';
    $lesProprietes = getToutesLesProprietes();
    $fichier = fopen($cheminTableau,"r"); 
    $tableau[0] = "<div class='dropdown' style='position:relative'><table border='3'>";
    for($i=0;$i<$ligne;$i++){
        if($i != 0)
            ++$lettre;
        $tableau[count($tableau)+1] = "<tr>";
        for($x=0;$x<$colonne;$x++){
            $position = "t1_".$lettre.$x;
            $celluleTableau = array();
            array_push($celluleTableau, $position);
            $tableau[count($tableau)+1] = "<td id=\"leTD\"><a href='#' class='btn btn-primary dropdown-toggle' data-toggle='dropdown'>Click here</a><ul class='dropdown-menu'>
                <li><a>$position</a></li>
                <li class='dropdown-header'>Evenement affectant la cellule</li>";
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
                                                    . "<input type='hidden' name='codeCelluleEtPropriete' value='$codeCelluleEtPropriete'><input type='hidden' name='cheminTableau' value='$cheminTableau'><input type='hidden' name='dureeEvenement' value='$lesDetails[2]'></form></ul>"
                                                . "</li>"
                                    . "<li><a href='#' class='btn btn-primary btn-lg active btn-sm' onclick=\"transfertSuppression('$codePropriete','$position','$cheminTableau')\">Supprimer attribut</a></li>"
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
                                    . "<li><a class='dropdown-header'>A quelle tour commencera l'événément</a></li>"
                                    . "<li><input type='number'number' name='quand' min=".$_SESSION['nbTours']." max='1000' required></li>"
                                    . "<li><button class='btn btn-primary btn-sm' type='submit'>Valider</button>"
                                . "<input type='hidden' name='cheminTableau' value='$cheminTableau'></form></ul>"
                            . "</li></ul></td>"; 
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
function construireTableauFichier($colonne,$ligne,$cheminTableau){
    $fichierTours = fopen($cheminTableau."/compteurTours.txt","a+");
    fputs($fichierTours, 0);
    fclose($fichierTours);
    $lettre = 'a';
    $cheminTableau = $cheminTableau."/tableau_1.txt";
    $fichier = fopen($cheminTableau,"r+"); 
    fputs($fichier,$colonne."_".$ligne."\r\n");
    fputs($fichier, "-----\r\n");
    for($i=0;$i<$ligne;$i++){
        if($i != 0)
            $lettre++;
        for($x=0;$x<$colonne;$x++){
            $position = "t1_".$lettre.$x;
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
    $resultat = false; $existeDeja = false;
    $pieces = explode("||", $lenomEvenement);
    $lenomEvenement = substr($pieces[0],6); 
    $lenomEvenement = substr($lenomEvenement,0,-2);
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
            fputs($fichier, $celluleTableau."_".$lenomEvenement."_".$quand."_".$nbTours);
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
// Demande 2 String (1 = code du tableau + code de la propriété (concaténé au préalable), 1 = le chemin du tableau voulu)
function suppressionProprietesDansCellule($codeCellulePropriete,$cheminTableau){
    $fichier = fopen($cheminTableau,"r+");
    $resultat = false;
    if ($fichier){
        while (($buffer = fgets($fichier, 4096)) !== false) {
            if(strpos($buffer, $codeCellulePropriete) !== false) {
                file_put_contents($cheminTableau, str_replace($buffer, "", file_get_contents($cheminTableau)));
                $resultat = true;
            }
        }
        if (!feof($fichier))
            echo "Erreur: fgets() de suppression a échoué\n";
    }
    fclose($fichier);
    if ($resultat == true){
        echo "<div class='alert alert-success'><strong><span class='glyphicon glyphicon-ok'></span> Réussite !</strong><br/>La propriété a été effacée de la cellule.</div>";
    }else{
        echo "<div class='alert alert-danger'><span class='glyphicon glyphicon-remove'></span> <strong>Avertissement !</strong><br/>La propriété n'a pas été effacée de la cellule ou la propriété n'existe pas.</div>";
    }
    header('Refresh:2;url=index.php?uc=genererTableau&action=genererTableau');
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
    $fichier = fopen($cheminTableau,"r"); //(lecture/ecriture = r+)  
    if ($fichier){
        while (($buffer = fgets($fichier, 4096)) !== false) {
            if (preg_match("#".$uneCellule."#", $buffer)){
                $rest = substr($buffer,6);
                $rest = mb_strimwidth($rest, 0, 2); //supprime le start event et la durée de l'event
                array_push($resultat, $rest);
            }
        }
        if (!feof($fichier))
            echo "Erreur: fgets() de rechercheDansFichierCelluleProprietes() a échoué\n";
    }
    fclose($fichier);
    return $resultat;
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
    $fichier = fopen($cheminTableau,"r");
    if ($fichier){
        while (($buffer = fgets($fichier, 4096)) !== false) {
            if (strpos($buffer, $codeCellule) !== false){
                $rest = substr($buffer,9);
                $demarreQuand = explode("_", $rest);
                $resultat = $demarreQuand[0]."||"; //Démarre quand
                $resultat = $resultat.$demarreQuand[1]; //Durée en temps
            }
        }
        if (!feof($fichier))
            echo "Erreur: fgets() de rechercheDansFichierCelluleProprietes() a échoué\n";
    }
    fclose($fichier);
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
}

function determinerEvenementCeTour($nbTours,$nomTableau){
    foreach (connaitreTableau("combienTableau", $nomTableau) as $unTableau){
        $cheminTableau = "../aideMJ/ressources/Maps/".$nomTableau."/".$unTableau;
        $fichier = fopen($cheminTableau,"r"); 
        if ($fichier){
            while (($buffer = fgets($fichier)) !== false) {
                
            }
        }
        fclose($fichier);
    }
}
?>
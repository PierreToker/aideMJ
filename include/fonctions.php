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
    //$tableau[0] = "<form method='POST' id='myForm' action='index.php?uc=genererTableau&action=voirDetailCellule'><div class='dropdown'><table border='1'>";
    $tableau[0] = "<div class='dropdown'><table border='3'>";
    for($i=0;$i<$ligne;$i++){
        if($i != 0){
            $lettre++;
        }
        $tableau[count($tableau)+1] = "<tr>";
        for($x=0;$x<$colonne;$x++){
            $position = "t1_".$lettre.$x;
            $celluleTableau = array();
            array_push($celluleTableau, $position);
           // $tableau[count($tableau)+1] = "<td id=\"leTD\" onclick=\"transfertCodeCellule('$position')\">Vaaaide</td>"; //code de la cellule
            $tableau[count($tableau)+1] = "<td id=\"leTD\"><button class='dropbtn' id='menu1' type='button' data-toggle='dropdown'>Vide</button><ul class='dropdown-menu' role='menu' aria-labelledby='menu1'>
                <li role='presentation'><a role='menuitem' tabindex='-1' href='#'>$position</a></li>
                <li role='presentation' class='divider'></li>";
                foreach (connaitreTouteProprietes($celluleTableau) as $unResultat){
                    foreach ($unResultat as $laPropriete){
                        $tableau[count($tableau)+1] = "<li role='introduction'><a role='menuitem' tabindex='-1' href='#'>$laPropriete</a></li>";
                    }
                }  
            $tableau[count($tableau)+1] = "</ul></td>"; //code de la cellule [TEST]
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
                array_push($resultat, $buffer);
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
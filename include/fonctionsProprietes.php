<?php
// --- chercherUnePropriete ---
// Sert à connaitre la durée d'un événement 
// Demande un String (1 = le code de la propriete)
// Retourne un String ((son temps = la durée de l'événément) ou (tous = tous les éléments d'une propriété))
function chercherUnePropriete($codePropriete,$action){
    $check = false;
    $i = 0;
    $resultat = "";
    $fichier = fopen("../aideMJ/ressources/Proprietes/proprietes.txt","r");
    if ($fichier){
        switch ($action){
            case "sonTemps":
                while (($buffer = fgets($fichier)) !== false) {
                    if ($check == true){
                        ++$i;
                        if ($i == 4){
                            $buffer = substr($buffer, 6);
                            $resultat = $buffer;
                            break;
                        }
                    }
                    if(strpos($buffer, "lenom=".$codePropriete) !== false) {
                        $check = true;
                    }
                }
                break;
            case "tous":
                while (($buffer = fgets($fichier)) !== false) {
                    if ($check == true){
                        ++$i;
                        $buffer = substr($buffer, 6);
                        $resultat = $resultat.$buffer."||";
                        if ($i == 4){
                            break;
                        }
                    }
                    if(strpos($buffer, "lenom=".$codePropriete) !== false) {
                        $check = true;
                    }
                }
                break;
            default:
                echo "Erreur lors de la selection du mode de lecture dans la fonction chercherUnePropriete()";
        }
    }
    fclose($fichier);  
    return $resultat;
}

// --- ajouterNouveauEvenement ---
// Ajoute l'événement voulue par l'utilisateur sur le fichier (proprietes.txt), gére l'encodage UTF-8
// Demande 3 String (1 = le titre de l'événement, 1 = la description de l'événement, 1 = le nombre de tour que dure l'événement)
function ajouterNouveauEvenement($titreEvenement,$descriptionEvenement,$nbTours,$effet){
    $fichier = fopen("../aideMJ/ressources/Proprietes/proprietes.txt","r+");
    $i = 0;
    if ($fichier){
        while (($buffer = fgets($fichier)) !== false) {
            if(strpos($buffer, "lenom=") !== false){
                ++$i;
            }
        }
        if (!feof($fichier)) {
            echo "Erreur: fgets() de ajouterNouveauEvenement() a échoué\n";
            $resulatat = true;
        }
        fseek($fichier, 0, SEEK_END);
        fputs($fichier,"\r\n");
        fputs($fichier,'lenom=p'.$i."\r\ntitre=".$titreEvenement."\r\ndescr=".$descriptionEvenement."\r\neffet=".$effet."\r\nnTour=".$nbTours."\r\n-----");
        $resultat = false;
    }
    fclose($fichier);
    if ($resultat == false){
        echo "<div class='alert alert-success'><span class='glyphicon glyphicon-ok'></span> <strong>Réussite !</strong><br/>L'événement a bien été créé.</div>";
        header('Refresh:2;url=index.php?uc=genererTableau&action=genererTableau');
    }else{
        echo "<div class='alert alert-danger'><span class='glyphicon glyphicon-remove'></span> <strong>Echec !</strong><br/>L'opération n'a pas aboutie car la cellule comporte déjà cet attribut.</div>";
        header('Refresh:2;url=index.php?uc=genererTableau&action=genererTableau');
    }
}

// --- getToutesLesProprietes ---
// Parcourt le fichier (proprietes.txt) à la recherche de toutes les propriétés
// Retourne un Array contenant toutes les proprietes présents dans le fichier proprietes.txt
function getToutesLesProprietes(){
    $lesProprietes = array();
    $fichier = fopen("../aideMJ/ressources/Proprietes/proprietes.txt","r");
    if ($fichier){
        while (($buffer = fgets($fichier, 4096)) !== false) {
            array_push($lesProprietes, $buffer);
            array_push($lesProprietes, "||");
        }
        if (!feof($fichier)) {
            echo "Erreur: fgets() de getToutesLesProprietes a échoué\n";
        }
    }
    fclose($fichier);
    return $lesProprietes;
}

// --- rechercheDansFichierProprietes ---
// Parcourt un fichier à la recherche des proprietes dans le fichier proprietes.txt
// Demande 1 String (= le code de la propriété)
// Retourne un Array retournant tous les titres des événements de la cellule
function rechercheDansFichierProprietes($codePropriete){ 
    $resultat = array();
    $check = false;
    $fichier = fopen("../aideMJ/ressources/Proprietes/proprietes.txt","r");
    if ($fichier){
        while (($buffer = fgets($fichier, 4096)) !== false) {
            if ($check == true){
                $buffer = substr($buffer, 6);
                array_push($resultat, $codePropriete."||".$buffer);
                $check = false;
            }else if (strpos($buffer, "lenom=".$codePropriete) !== false){
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

// --- supprimerPropriete ---
// Parcourt le fichier (propriete.txt) à la recherche de la propriété à supprimer
// Demande 1 String (1 = code du tableau + code de la propriété (concaténé au préalable)
function supprimerPropriete($numeroPropriete){
    $cheminFichier = "../aideMJ/ressources/Proprietes/proprietes.txt";
    $aSupprimer = false;
    $fichier = fopen($cheminFichier,"r+");
    $erreur = false;
    try{
        if ($fichier){
            while (($buffer = fgets($fichier, 4096)) !== false) {
                if ($aSupprimer == true){
                    if (stripos($buffer, "-----") !== false){
                        break;
                    }else{
                        file_put_contents($cheminFichier, str_replace($buffer, "", file_get_contents($cheminFichier)));
                    }
                } else if(strpos($buffer, $numeroPropriete) !== false){
                    file_put_contents($cheminFichier, str_replace($buffer, "", file_get_contents($cheminFichier)));
                    $aSupprimer = true;
                }         
            }
        }
        fclose($fichier);
    }catch(Exception $e){
        $erreur = true;
        print_r($e);
    }
    return $erreur;
}
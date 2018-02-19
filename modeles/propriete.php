<?php
class propriete {
    function __construct($code,$titre,$description,$effet,$duree,$nomImage,$axe_x,$axe_y){
        $this->codePropriete = $code;
        $this->titre = $titre;
        $this->description = $description;
        $this->effet = $effet;
        $this->duree = $duree;
        $this->nomImage = $nomImage;
        $this->axe_x = $axe_x;
        $this->axe_y = $axe_y;
    }

    function getCodePropriete() { return $this->codePropriete; }  // id = code de la propriete
    function getTitre() { return $this->titre; }	
    function getDescription() { return $this->description; }	
    function getDuree() { return $this->duree; }
    function getMultiCellule() { return $this->multiCellule; }

    function setCodePropriete($nouvelleValeur) { $this->codePropriete = $nouvelleValeur; }	
    function setTitre($nouvelleValeur) { $this->titre = $nouvelleValeur; }	
    function setDescription($nouvelleValeur) { $this->description = $nouvelleValeur; }	
    function setDuree($nouvelleValeur) { $this->duree = $nouvelleValeur; }
    function setMultiCellule($nouvelleValeur) { $this->multiCellule = $nouvelleValeur; }
    
    static function chercherPropriete($code){
        foreach ($GLOBALS['lesProprietes'] as $key => $val) { 
            if (strcmp($code,$val->codePropriete) == 0) {
                return $GLOBALS['lesProprietes'][$key];
            }
        }
    }
}
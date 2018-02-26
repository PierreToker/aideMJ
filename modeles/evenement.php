<?php
class evenement {
    function __construct($codeEvenement,$cellule,$demarreQuand,$axe_x,$axe_y){
        $this->codeEvenement = $codeEvenement;
        $this->cellule = $cellule;
        $this->demarreQuand = $demarreQuand;
        $this->axe_x = $axe_x;
        $this->axe_y = $axe_y;
    }

    function getDemarreQuand() { return $this->demarreQuand; }

    function setDemarreQuand($nouvelleValeur) { $this->demarreQuand = $nouvelleValeur; }
    
    static function chercherEvenement($cellule){
        foreach ($GLOBALS['lesEvenements'] as $key => $val) { 
            //echo "SI : ".$cellule." = ".$val->cellule."<br/>";
            if (strcmp($cellule,$val->cellule) == 0) {
                return $GLOBALS['lesEvenements'][$key];
            }
        }
    }
}
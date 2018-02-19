<?php
class evenement {
    function __construct($codeEvenement,$cellule,$demarreQuand){
        $this->codeEvenement = $codeEvenement;
        $this->cellule = $cellule;
        $this->demarreQuand = $demarreQuand;
    }

    function getDemarreQuand() { return $this->demarreQuand; }

    function setDemarreQuand($nouvelleValeur) { $this->demarreQuand = $nouvelleValeur; }
//    
//    static function chercherPropriete($lesProprietes,$codePropriete){
//        foreach($lesProprietes as $unePropriete) {
//            if ($codePropriete == $unePropriete->id) {
//                return $unePropriete;
//            }
//        }
//    }
}
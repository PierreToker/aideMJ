<?php
class tableau {
    function __construct($id,$numero,$sens,$colonne,$ligne){
        $this->id = $id;
        $this->numero = $numero;
        $this->sens = $sens;
        $this->colonne = $colonne;
        $this->ligne = $ligne;
    }

    function getId() { return $this->id; }	
    function getNumero() { return $this->numero; }	
    function getSens() { return $this->sens; }	
    function getColonne() { return $this->colonne; }
    function getLigne() { return $this->ligne; }

    function setId($nouvelleValeur) { $this->id = $nouvelleValeur; }	
    function setNumero($nouvelleValeur) { $this->numero = $nouvelleValeur; }	
    function setSens($nouvelleValeur) { $this->sens = $nouvelleValeur; }	
    function setColonne($nouvelleValeur) { $this->colonne = $nouvelleValeur; }
    function setLigne($nouvelleValeur) { $this->ligne = $nouvelleValeur; }
}
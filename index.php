<?php
require_once ("include/fonctions.php");
include("vues/v_entete.php") ;
if(!isset($_REQUEST['uc'])){
    $_REQUEST['uc'] = 'accueil';
}
$uc = $_REQUEST['uc'];
switch ($uc){
    case "accueil":
        include("vues/accueil.php");
        break;
    case "genererTableau":
        include("controleurs/c_actionTableau.php");
        break;
    case "actionSurEvenement":
        include("controleurs/c_actionEvenement.php");
        break;
    default:
        echo "Rien choisi dans l'index !";
}
include("vues/v_footer.php") ;
?>
    </body>
</html>

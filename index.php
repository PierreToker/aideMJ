<?php
require_once ("include/fonctions.php");
include("vues/v_entete.php") ;
if(!isset($_REQUEST['uc'])){
    $action = "genererTableau";
    $_REQUEST['uc'] = 'genererTableau';
}
$uc = $_REQUEST['uc'];
switch ($uc){
    case "genererTableau":
        include("controleurs/c_actionTableau.php");
        break;
    default:
        echo "Rien choisi dans l'index !";

}
include("vues/v_footer.php") ;
?>
    </body>
</html>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<?php
require_once ("include/fonctions.php");
require_once ("include/fonctionsProprietes.php");
include("vues/v_entete.php") ;
session_start();
if(!isset($_REQUEST['uc'])){
    $_REQUEST['uc'] = 'accueil';
}
$uc = $_REQUEST['uc'];
switch ($uc){
    case "accueil":
        $resultat = connaitreTableau("tous","");
        $_SESSION['lesPlateaux'] = getTousPlateaux();
        include("vues/accueil.php");
        break;
    case "genererTableauDepuisIndex":
        $nomTableau = isset($_POST['nomTableau']) ? $_POST['nomTableau'] : NULL;
        $_SESSION['nomTableau'] = str_replace(" ","_",$nomTableau);
        $_SESSION['combienTableau'] = connaitreTableau("combienTableau",$_SESSION['nomTableau']);
        include("controleurs/c_actionTableau.php");
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

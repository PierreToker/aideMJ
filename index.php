<?php
require_once ("include/fonctions.php");
include("vues/v_entete.php") ;
$action = "genererTableau";
switch ($action){
    case "genererTableau":
        include("controleurs/c_actionTableau.php");
        break;

}
include("vues/v_footer.php") ;
?>
    </body>
</html>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        $action = "genererTableau";
        switch ($action){
            case "genererTableau":
                include("controleurs/c_generationTableau.php");
                break;
            
        }
        ?>
    </body>
</html>

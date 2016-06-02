<script type="text/javascript">
function transfertCodeCellule(position) {
    var myForm = document.createElement("form");
    var cellule = "cellule";
    myForm.method = "post" ;
    myForm.action = "index.php?uc=genererTableau&action=voirDetailCellule" ;
    var myInput = document.createElement("input") ;
    myInput.setAttribute("name", cellule) ;
    myInput.setAttribute("value", position);
    myForm.appendChild(myInput) ;
  document.body.appendChild(myForm) ;
  myForm.submit() ;
  document.body.removeChild(myForm) ;
}
</script>
<style>
.dropbtn {
    background-color: #4CAF50;
    color: white;
    padding: 16px;
    font-size: 16px;
    border: none;
    cursor: pointer;
}
</style>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<form method='POST' action='index.php?uc=genererTableau&action=genererTableau'>
    Nb Colonne : <input type="text" name="colonne"><br>
    Nb Ligne : <input type="text" name="ligne"><br>
    <input type="submit" value="Valider">
</form>
<?php
foreach ($monTableau as $unElement){
    echo $unElement;  
}
?>
</form>
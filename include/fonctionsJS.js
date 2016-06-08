function transfertCodeCellule(codePropriete,action,position,cheminTableau) {
    var myForm = document.createElement("form");
    var cellule = "codeCellule";
    var cheminDuTableau = "cheminTableau";
    var nomInput = "codePropriete";
    myForm.method = "post";
    myForm.action = "index.php?uc=genererTableau&action=" + action;
    
    var codeProprieteInput = document.createElement("input");
    codeProprieteInput.setAttribute("name", nomInput);
    codeProprieteInput.setAttribute("value", codePropriete);
    myForm.appendChild(codeProprieteInput);
    
    var codeCelluleInput = document.createElement("input");
    codeCelluleInput.setAttribute("name", cellule);
    codeCelluleInput.setAttribute("value", position);
    myForm.appendChild(codeCelluleInput);
    
    var cheminTableauInput = document.createElement("input");
    cheminTableauInput.setAttribute("name", cheminDuTableau);
    cheminTableauInput.setAttribute("value", cheminTableau);
    myForm.appendChild(cheminTableauInput);
    
    document.body.appendChild(myForm);
    myForm.submit();
    document.body.removeChild(myForm);
}

function transfertSuppression(codePropriete,position,cheminTableau){
    var decision = confirm("La propriété de la cellule va étre effacée, voulez-vous continuer ?");
    if (decision == true){
        var action = "supprimerCellule";
        transfertCodeCellule(codePropriete,action,position,cheminTableau);
    }
}

function transfertModification(codePropriete,position){
    var action = "modifierCellule";
    transfertCodeCellule(codePropriete,action,position);
}

function transfertAjout(position){
    var codePropriete = "aucun";
    var action = "ajouterCellule";
    transfertCodeCellule(codePropriete,action,position);
}

$(function(){
    $(".dropdown-menu > li > a.trigger").on("click",function(e){
        var current=$(this).next();
        var grandparent=$(this).parent().parent();
        if($(this).hasClass('left-caret')||$(this).hasClass('right-caret'))
        $(this).toggleClass('right-caret left-caret');
        grandparent.find('.left-caret').not(this).toggleClass('right-caret left-caret');
        grandparent.find(".sub-menu:visible").not(current).hide();
        current.toggle();
        e.stopPropagation();
    });
    $(".dropdown-menu > li > a:not(.trigger)").on("click",function(){
        var root=$(this).closest('.dropdown');
        root.find('.left-caret').toggleClass('right-caret left-caret');
        root.find('.sub-menu:visible').hide();
    });
});  

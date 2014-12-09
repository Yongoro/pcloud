function OnFileGroup(){
    $.ajax({
    	datatype:'html', 
        url: $(this).attr("href"), // Récupère l'url du script qui reçoit la requête
        success: fileGroupPart // Callback qui récupère la réponse du serveur
    });
    return false; // Annule l'envoi classique du formulaire
}

function fileGroupPart(result){
    $("#partieMilieu").html(result); // Insère le résultat dans la balise d'id "result"
}
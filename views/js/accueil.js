function OnAccueil(){
    $.ajax({
        url: $(this).attr("href"), // Récupère l'url du script qui reçoit la r
        datatype : 'html',
        success: AccueilPart // Callback qui récupère la réponse du serveur
    });
    return false; // Annule l'envoi classique du formulaire
}

function AccueilPart(result){
      	
	 $("#partieMilieu").html(result);
}
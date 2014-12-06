function OnUser(){
    $.ajax({
        url: $(this).attr("href"), // Récupère l'url du script qui reçoit la requête
        success: UserPart // Callback qui récupère la réponse du serveur
    });
    return false; // Annule l'envoi classique du formulaire
}
function UserPart(result){
    $("#partieMilieu").html(result); 
}
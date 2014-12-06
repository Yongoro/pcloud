$(document).ready(OnReady); // Abonne le callback à exécuter lorsque tout le DOM est chargé
function OnReady(){
    $("form").submit(OnSubmit); // Abonne un callback à l'évènement "submit" du formulaire
}
function OnSubmit(){
    $.ajax({
        type: $(this).attr("method"), // Récupère la méthode d'envoi du formulaire, ici "POST"
        url: $(this).attr("action"), // Récupère l'url du script qui reçoit la requête, ici "add.php"
        data: $(this).serialize(), // Fabrique la "query string" contenant les deux nombres
        success: OnSuccess // Callback qui récupère la réponse du serveur
    });
    return false; // Annule l'envoi classique du formulaire
}
function OnSuccess(result){
    $("#messageAddGF").html(result); // Insère le résultat dans la balise d'id "result"
}
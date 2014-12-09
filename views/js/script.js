$(document).ready(OnReady); // Abonne le callback à exécuter lorsque tout le DOM est chargé
function OnReady(){
    $(".createView").submit(OncreateView); // Abonne un callback à l'évènement "submit" du formulaire
    $(".addGroup").submit(OnAddGroup); // Abonne un callback à l'évènement "submit" du formulaire
    $(".grantViewToGroup").submit(OnGrantViewToGroup); // Abonne un callback à l'évènement "submit" du formulaire
    $(".grantViewToUser").submit(OnGrantViewToUser); // Abonne un callback à l'évènement "submit" du formulaire
    $("#user").click(OnUser); 
    $("#file").click(OnFile); // Abonne un callback à l'évènement "submit" du formulaire
    $("#groupFile").click(OnFileGroup); // Abonne un callback à l'évènement "submit" du formulaire
    $("#groupUser").click(OnUserGroup); // Abonne un callback à l'évènement "submit" du formulaire
    $("#accueil").click(OnAccueil); // Abonne un callback à l'évènement "submit" du formulaire
    $("#stat").click(OnStat); // Abonne un callback à l'évènement "submit" du formulaire
    $("#dropUser").click(OnDropUser); // Abonne un callback à l'évènement "submit" du formulaire
    $("#search").click(OnSearch); // Abonne un callback à l'évènement "submit" du formulaire
    $("#accueil").click(Onaccueil); // Abonne un callback à l'évènement "submit" du formulair
}

function OncreateView(event){
    event.preventDefault();
    $.ajax({


        type: $(this).attr("method"), // Récupère la méthode d'envoi du formulaire, ici "POST"
        url: $(this).attr("action"), // Récupère l'url du script qui reçoit la requête, ici "add.php"
        datatype: 'text', 
        data: $(this).serialize(),// Fa
        success: OnSuccess // Callback qui récupère la réponse du serveur
    });
    return false; // Annule l'envoi classique du formulaire
}

function OnSuccess(result){
    $("#messageAddGF").html(result); // Insère le résultat dans la balise d'id "result"
}

function OnAddGroup(event){
    event.preventDefault();
    $.ajax({
        type: $(this).attr("method"), // Récupère la méthode d'envoi du formulaire, ici "POST"
        url: $(this).attr("action"), // Récupère l'url du script qui reçoit la requête, ici "add.php"
        datatype: 'text', 
        data: $(this).serialize(),
        success: OnSuccess1 // Callback qui récupère la réponse du serveur
    });
    return false; // Annule l'envoi classique du formulaire
}

function OnSuccess1(result){
    $("#messageAddGU").html(result); // Insère le résultat dans la balise d'id "result"
}



function OnGrantViewToGroup(event){
    event.preventDefault();
    $.ajax({
        type: $(this).attr("method"), // Récupère la méthode d'envoi du formulaire, ici "POST"
        url: $(this).attr("action"), // Récupère l'url du script qui reçoit la requête, ici "add.php"
        datatype: 'text', 
        data: $(this).serialize(),
        success: OnSuccess3 // Callback qui récupère la réponse du serveur
    });
    return false; // Annule l'envoi classique du formulaire
}

function OnSuccess3(result){
    $("#messageGrantViewToGroup").html(result); // Insère le résultat dans la balise d'id "result"
}


function OnGrantViewToUser(){
    event.preventDefault();
    $.ajax({
        type: $(this).attr("method"), // Récupère la méthode d'envoi du formulaire, ici "POST"
        url: $(this).attr("action"), // Récupère l'url du script qui reçoit la requête, ici "add.php"
        datatype: 'text', 
        data: $(this).serialize(),
        success: OnSuccess4 // Callback qui récupère la réponse du serveur
    });
    return false; // Annule l'envoi classique du formulaire
}

function OnSuccess4(result){
    $("#messageGrantViewToUser").html(result); // Insère le résultat dans la balise d'id "result"
}



 


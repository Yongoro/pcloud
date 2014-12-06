 $(document).ready(OnReadyPartFile);
 $(document).ready(OnLettreFichier);





function OnFile(){
    $.ajax({
        datatype: 'html',
        url: $(this).attr("href"), // Récupère l'url du script qui reçoit la requête
        success: filePart // Callback qui récupère la réponse du serveur
    });
    return false; // Annule l'envoi classique du formulaire
}

function filePart(result){
    $("#partieMilieu").html(result); // Insère le résultat dans la balise d'id "result"
}



//si la partie fichier est prete
//abonner le callback à l'évènement concerné


   function OnReadyPartFile(){
     $("#dialog").dialog({
        modal: true,
        bgiframe: true,
        width: 500,
        height: 200,
        autoOpen: false
    });
     //$(.lettre).click(Onfile)
    //$(".addFile").click(On); // abonner l'evenement au callback
    //$(".showFile").click(On); // abonner l'evenement au callback
    //$(".updateFile").click(On); // abonner l'evenement au callback
    $('.lettre').click(OnFile);
    $(".deleteFile").click(OnDeleteFile); // abonner l'evenement au callback
     }





function OnDeleteFile(e){

    e.preventDefault();
   //jConfirm('voulez vous vraiment supprimer?', 'suppression', function(r) {
   //
 if(myConfirm('voulez vous vraiment supprimer?', function () {
    return true;
  }, function () {
    return false;
  },
  'suppression'
))
    $.ajax({
    	datatype: 'text',
        url: $(this).attr("href"), // Récupère l'url du script qui reçoit la requête
        success: deleteFilePart // Callback qui récupère la réponse du serveur
    });
   else ;
     return false; // Annule l'envoi classique du formulaire
}

function deleteFilePart(result){

    alert(result); // Insère le résultat dans la balise d'id "result"
}


  
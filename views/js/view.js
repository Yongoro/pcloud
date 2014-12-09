$(document).ready(OnViewSelected);

   function OnViewSelected(){

  $(".oneView").click(viewSelected); 
 
  }

function viewSelected(event){
	  event.preventDefault();
  $.ajax({
        datatype: 'text',
        url: $(this).attr("href"), // Récupère l'url du script qui reçoit la requête
        success: viewSelectedSuccess // Callback qui récupère la réponse du serveur
    });
return false;
}

function viewSelectedSuccess(result){
        $("#partieMilieu").html(result);
}
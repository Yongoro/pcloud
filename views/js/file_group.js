$(document).ready(OnViewDetails);
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

function OnViewDetails(event){
    $(".view").hover(ViewDetails);   
}

//function ViewDetails(){

//    var rout = $(".view").attr("href");
  //  var params = rout.substring(1);
   // var sURLVariables = params.split('=');
   // var id = sURLVariables[1];
	 // alert(id);

	// $.ajax({
      //  type: 'get', // Récupère la méthode d'envoi du formulaire, ici "POST"
       // url: '/pcloud/index.php/admin/readOneViewInfos?id_file_group='+id, 
       // datatype: 'text', 
       // data: $(this).serialize(),// Fa
       // success: OnDetails // Callback qui récupère la réponse du serveur
    //});
    //return false; // Annule l'envoi classique du formulaire
//}

//function OnDetails(result){
	//alert(result);
//}



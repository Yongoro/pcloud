$(document).ready(function(){
			

				   		   
	//When you click on a link with class of poplight and the href starts with a # 
	//special this part deals with reading one user subscription
	$('a.poplight[href^=admin/readSubscription]').click(function() {


         $.ajax({
        datatype: 'html',
        url: $(this).attr("href"), // Récupère l'url du script qui reçoit la requête
        success: onDemandeEnCours, // Callback qui récupère la réponse du serveur
           });		

		var popID = $(this).attr('rel'); //Get Popup Name
		var popURL = $(this).attr('href'); //Get Popup href to define size
				
		//Pull Query & Variables from href URL
		var query= popURL.split('?');
		var dim= query[1].split('&');
		var popWidth = dim[0].split('=')[1]; //Gets the first query string value

		//Fade in the Popup and add close button
		$('#' + popID).fadeIn().css({ 'width': Number( popWidth ) }).prepend('<a href="#" class="close"><img src="close_pop.png" class="btn_close" title="Close Window" alt="Close" /></a>');
		
		//Define margin for center alignment (vertical + horizontal) - we add 80 to the height/width to accomodate for the padding + border width defined in the css
		var popMargTop = ($('#' + popID).height() + 80) / 2;
		var popMargLeft = ($('#' + popID).width() + 80) / 2;
		
		//Apply Margin to Popup
		$('#' + popID).css({ 
			'margin-top' : -popMargTop,
			'margin-left' : -popMargLeft
		});
		
		//Fade in Background
		$('body').append('<div id="fade"></div>'); //Add the fade layer to bottom of the body tag.
		$('#fade').css({'filter' : 'alpha(opacity=80)'}).fadeIn(); //Fade in the fade layer 
		
		return false;
	});
	
	
	//Close Popups and Fade Layer
	$('a.close, #fade').live('click', function() { //When clicking on the close or fade layer...
	  	$('#fade , .popup_block').fadeOut(function() {
			$('#fade, a.close').remove();  
	}); //fade them both out
		
		return false;
	});




	//When you click on a link with class of poplight and the href starts with a # 
	//special this part deals with reading one user subscription
	$('a.poplight[href^=admin/readOneUserInfo]').click(function() {


         $.ajax({
        datatype: 'html',
        url: $(this).attr("href"), // Récupère l'url du script qui reçoit la requête
        success: onUserInfo, // Callback qui récupère la réponse du serveur
           });		

		var popID = $(this).attr('rel'); //Get Popup Name
		var popURL = $(this).attr('href'); //Get Popup href to define size
				
		//Pull Query & Variables from href URL
		var query= popURL.split('?');
		var dim= query[1].split('&');
		var popWidth = dim[0].split('=')[1]; //Gets the first query string value

		//Fade in the Popup and add close button
		$('#' + popID).fadeIn().css({ 'width': Number( popWidth ) }).prepend('<a href="#" class="close"><img src="close_pop.png" class="btn_close" title="Close Window" alt="Close" /></a>');
		
		//Define margin for center alignment (vertical + horizontal) - we add 80 to the height/width to accomodate for the padding + border width defined in the css
		var popMargTop = ($('#' + popID).height() + 80) / 2;
		var popMargLeft = ($('#' + popID).width() + 80) / 2;
		
		//Apply Margin to Popup
		$('#' + popID).css({ 
			'margin-top' : -popMargTop,
			'margin-left' : -popMargLeft
		});
		
		//Fade in Background
		$('body').append('<div id="fade"></div>'); //Add the fade layer to bottom of the body tag.
		$('#fade').css({'filter' : 'alpha(opacity=80)'}).fadeIn(); //Fade in the fade layer 
		
		return false;
	});
	
	
	//Close Popups and Fade Layer
	$('a.close, #fade').live('click', function() { //When clicking on the close or fade layer...
	  	$('#fade , .popup_block').fadeOut(function() {
			$('#fade, a.close').remove();  
	}); //fade them both out
		
		return false;
	});









	
});


function onDemandeEnCours (result){
	  $("#dateDemande").html(result.DATE_SUBSCRIPTION_USER); // Insère le résultat dans la balise d'id "result"
	  $("#nomDemandeur").html(result.NAME_USER); // Insère le résultat dans la balise d'id "result"
	  $("#prenomDemandeur").html(result.SURNAME_USER); // Insère le résultat dans la balise d'id "result"
	  $("#emailDemandeur").html(result.EMAIL_USER); // Insère le résultat dans la balise d'id "result"
	  $("#sexDemandeur").html(result.SEX_USER); // Insère le résultat dans la balise d'id "result"
	  $("#pseudoDemandeur").html(result.PSEUDO_USER); // Insère le résultat dans la balise d'id "result"
}



function onUserInfo (result){
      $("#dateDemande").html(result.user.DATE_SUBSCRIPTION_USER); // Insère le résultat dans la balise d'id "result"
	  $("#prenomDemandeurShow").html(result.user.SURNAME_USER); // Insère le résultat dans la balise d'id "result"
	  $("#nomDemandeurShow").html(result.user.NAME_USER); // Insère le résultat dans la balise d'id "result"
	  $("#pseudoDemandeurShow").html(result.user.PSEUDO_USER); // Insère le résultat dans la balise d'id "result"
	  $("#emailDemandeurShow").html(result.user.EMAIL_USER); // Insère le résultat dans la balise d'id "result"
	  $("#sexDemandeurShow").html(result.user.SEX_USER); // Insère le résultat dans la balise d'id "result"

	  //permet de parcourir le tableau json contenant les noms des groupes et leurs identifiants
       
         $("#userGroups").html('<div id="userGroups"></div>');
        var i=0;
	    $.each(result.groups, function(group,infos)
	    {
	    	i++;

	    	//console.log(i);
	          // $each(infos,function(prop,val)
	          // {

	    		 	      $("#userGroups").append("<input type='checkbox' name='group'"+i+" value='"+infos.id_user_group+
	    		 	      	"'> "+infos.name_user_group+"<BR>");
	    		 	   
	    	//                                       });

	       });




   
         
	    $.each(result.allgroups, function(group,infos)
	    {
             $(".rights select").append('<option>'+infos.name_user_group+'</option>');
	    		 	   
	    });

       

 




	    

	    
  
	
	
}






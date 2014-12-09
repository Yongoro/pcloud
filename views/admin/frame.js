//to populate the select with existing file's groups in the DB
function populateFileGroups(){

	$.ajax({

  	  url      :  '/d',
	  type     :  'GET',
	  // équivalent de responseType
	  dataType :  'json',
	  // conversion et gestion automatique de GET et POST	
	  //data     : ,
	  // Callbacks pour les évènements
	  success  :  function(data) {

	  				var select = $("#group_select");
	  				var options = select.prop('options');
	  				$('option', select).remove();
	  				
	  				///*for(val in data){ console.log(data[val].name_file_group);}*/
	  				$.each(data,function(index,array){	  					
	  					options[options.length] = new Option(array['name_file_group']);	  					
	  				})
	  				
	  			  },
	  error    :  function(xhr, status, errorThrown){
	  				console.log( "Error: " + errorThrown );
			        console.log( "Status: " + status );
			        console.dir( xhr );
	  		    },
	  complete :  function() { }
	});
}


$(document).ready(function(){
	
	$('#upload').click(function(){
		populateFileGroups();
		$('#feed').fadeIn(400,"swing", function(){			
			$('#feed1').slideDown("slow");
		});
		
	});

	$('#feed').click(function(){

		$('#feed').hide();
		$('#feed1').hide();
	});

});
	
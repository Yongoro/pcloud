
	
	//premier appel de tous les chargements
	refreshFileGroups();


	function refreshAll(){

		setInterval(refreshFileGroups,60000);
		setInterval(refreshFiles,120000);
	}
	
	function refreshFileGroups(){
		
		$.ajax({

		  	  url      :  '/pcloud/index.php/api/fileGroups',
			  type     :  'GET',
			  // équivalent de responseType
			  dataType :  'json',
			  // conversion et gestion automatique de GET et POST data     : , Callbacks pour les évènements
			  success  :  function(data) {
			  				
			  				//ajout SumoSelect
			  				$('.SlectBox').SumoSelect();
			  				$('select.SlectBox')[0].sumo.unload();

			  				//vider les options en mode normal, sans SumoSelect
			  				var select = $("#group_select");
			  				var options = select.prop('options');
			  				$('option', select).remove();
			  				
			  				//remise du SumoSelect pour ajouter les options en multiple
			  				$('.SlectBox').SumoSelect({ okCancelInMulti: true });
 						
			  				for(val in data)
			  					{		  						
			  						$('select.SlectBox')[0].sumo.add(data[val].name_file_group);
			  						//console.log(data[val].name_file_group);
			  					}

			  				//$.each(data,function(index,array){	  					
			  				//	options[options.length] = new Option(array['name_file_group']);	  					
			  				//})
			  				
			  			  },
			  error    :  function(xhr, status, errorThrown){
			  				console.log( "Error: " + errorThrown );
					        console.log( "Status: " + status );
					        console.dir( xhr );
			  		    },
			  complete :  function() { }
		});
	}//end refreshFileGroups

	$('#upload').click(function(){
		
		$('#feed').fadeIn(400,"swing", function(){			
			$('#feed1').slideDown("slow");
		});
		
	});

	$('#feed').click(function(){

		$('#feed').hide();
		$('#feed1').hide();
	});


	
$(document).ready(function(){
   $("#add").click(function(e){               
   	e.preventDefault();
   	$("#feed").fadeIn(500,function(){
   		$("#feed1").slideDown(500);
   	});
   });
  	$("#feed").click(function(){
   	$("#feed").hide();
   	$("#feed1").hide();
   });

});
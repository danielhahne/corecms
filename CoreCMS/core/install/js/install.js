/*
 *  Ajax function for Loading corePHP
 */

/* CORE FUNCTIONS **********************************************************************/

function loadData(url) {
	var vars = "";
		
	$(":input[checked],:input[type!=checkbox]").each(function(){
		vars = vars + $(this).attr("id") + "=" + encodeURIComponent($(this).val()) + "&";
	});
	
	
	$("#loader").html(loaderElem);
	$('#content').fadeOut('slow', function(){
		
		$.ajax({
			type: "POST",
			url: url,
			data: vars,
			success: function(data){
				$('#content').html(data);
				$('#content').fadeIn('slow', function(){
					$("#loader").html(''); 
				}); 
				initEvents(); 
   			}
 		});
	});  
}     

function initEvents() {
	$(".help-ico").hover(
		function(){
			var pos = $(this).position();
			$(this).next().fadeIn('fast');
			$(this).next().css("top",pos.top + 15);
			$(this).next().css("left",pos.left + 15);
		},
		function() {
			$(this).next().fadeOut('fast');
		}
	); 
}

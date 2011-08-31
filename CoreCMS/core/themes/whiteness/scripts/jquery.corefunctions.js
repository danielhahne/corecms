jQuery.extend({
	
	//content
	firstLoad: function(id) {
		if(!location.hash && id) {
			$.sendRequest(id);
		}
	},
	
	contentTarget: undefined,
	
	latestLoad: undefined,
	
	sendRequest: function(id) {
		if(!id) return;
		id = decodeURIComponent(id);
		if(id == jQuery.latestLoad) return;
		if(id.search("tag-") < 0) jQuery.contentLoad(id);
		else jQuery.tagClick(id);
	},
	
	contentLoad: function(id) {
		$(".entry-link, .page-link").removeClass("link-active");
		$(".page-link a[href='#"+id+"']").parent().addClass("link-active");
		$("#list"+id+".entry-link").addClass("link-active");
		
		jQuery.contentTarget.animate({"opacity":0},200);
		
		$.ajax({
			type: "POST",
			url: "core/functions/get_entry.php",
			data: "id="+id,
			success: function(data){
				$("#load-content").show();
				$('html').animate({scrollTop:0}, 200);
				jQuery.latestLoad = id;
				jQuery.contentInsert(data);
			}
		});
	},
	
	contentInsert: function(data) {
		var id = jQuery.contentTarget.attr("id");
		jQuery.contentTarget.html(data);
		
		$("#close-entry").click(function(){
			$(".link-text, .page-link").removeClass("link-active");
			jQuery.latestLoad = "";
			$("#load-content").html("");
			$("#load-content").hide();
		});
		
		jQuery.contentTarget.animate({"opacity":1},200);
	},
	
	//tags
	tagArray: undefined,
	
	tagClick: function(id) {
		var tag,arr,ex;
		
		$(".tag-link.link-active").each(function(){
			$(this).removeClass("link-active");								 
		});
		
		$(".tag-link a[href='#"+id+"']").each(function(){
			$(this).parent().addClass("link-active");
			tag = $(this).attr("name");
		});
		
		arr = jQuery.tagArray[tag];
		if(!arr) arr = [];
		
		$(".entry-link").each(function() {
			ex = false;
			
			for (var i = 0;i<arr.length;i++){
				var e_id = $(this).attr("id").replace(/list/,'').replace(/thumb/,'');
				if( e_id == arr[i] ) ex = true;
			}
			
			if(ex) $(this).fadeTo(200,1.0);
			else $(this).fadeTo(200,0.25);
		});
	},
	
	tagAll: function() {
		$.latestLoad = "";
		$("#load-content").hide();
		$(".link-active").each(function() {
			$(this).removeClass("link-active");
		});
									 
		$(".entry-link").each(function() {
			$(this).fadeTo(200,1.0);
		});
	}
});


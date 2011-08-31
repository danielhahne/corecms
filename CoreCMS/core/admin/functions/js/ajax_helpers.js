var loaderElem = "<img src='ajax-loader.gif' />";

function submit_form(url, refresh_url, refresh_data) {
	$("#debug").html("");
	url = "functions/" + url + ".php";
	var vars = "";
	$(":input[checked],:input[type!=checkbox]").each(function(){
		vars = vars + $(this).attr("id") + "=" + encodeURIComponent($(this).val()) + "&";
	});
	
	$("#main").html(loaderElem);
	$.ajax({
	   type: "POST",
	   url: url,
	   data: vars,
	   success: function(data){
			$("#debug").html(data);
		   get_data(refresh_url, refresh_data);
	   }
	});
}

function get_data(url, data) {
	url = "functions/" + url + ".php";
	$("#main").html(loaderElem);
	$.ajax({
		type: "POST",
		url: url,
		data: data,
		success: function(response){
			$("#main").html(response);
			$(".debug-clear").click(function(){
				$(this).parent().slideUp(200);
			});
			update_help();
		}
	});
}

function delete_data(url, data, refresh_url, refresh_data) {
	$("#debug").html("");
	var conf = confirm("Are you sure?");
	if(conf) {
		url = "functions/" + url + ".php";
		$("#main").html(loaderElem);
		$.ajax({
			type: "POST",
			url: url,
			data: data,
			success: function(response){
				$("#debug").html(response);
				get_data(refresh_url, refresh_data);
			}
		});
	}
}

function show_data(data) {
	$("#main").html(data);
}

function toggleExtra(type, target) {
	if($(target).html() == "Hide extra") {
		$(".extra").slideUp(50);
		$(target).html("Show extra");
	} else {
		$(".extra").slideDown(50);
		$(target).html("Hide extra");
	}
}



function update_help() {
	$(".help-ico").hover(
		function(){
			var pos = $(this).position();
			$(this).next().css("display","block");
			$(this).next().css("top",pos.top + 15);
			$(this).next().css("left",pos.left + 15);
		},
		function() {
			$(this).next().css("display","none");
		}
	);
}

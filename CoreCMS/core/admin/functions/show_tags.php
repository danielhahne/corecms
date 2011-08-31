<?
error_reporting(0);
require("_headr.php");
if($_SESSION["password"] != $pass || $_SESSION["sessid"] != session_id()) die(logout());

debug("Password validated, logged in",0);
	//Show header
	?>
	<p class="title-head">Tags</p>
    
    Tags are basically categories, when you add a tag you can go into an entry and connect that entry with the tag you just created. All themes have different methods for displaying and using tags. Every entry can be connected to an unlimited amount of tags.
    
    <p class="margin"></p>
	<?
	
	$contents = mysql_query("SELECT t.* FROM core_tags t ORDER BY t.tag_position DESC") or die(mysql_error());
	$count = mysql_num_rows($contents);
	if(!$contents) {
		echo '<p>No tags fetched, none added yet?</p>';
	}
	
	//HITS
	?>  
    <fieldset>
	<legend>Tags&mdash; <? echo $count; ?> total</legend>
	<p class="btn add" onclick="get_data('get_add_tag')">Add new tag</p>
<p class="btn add" onclick="t_update()">Submit rearrange</p>
<p class="clear"></p>
<p class="margin"></p>
    <div id="tags">
    <?
	
    while($data = mysql_fetch_array($contents)) {
		$tag_id = $data["tag_id"];
		$tag_text = $data["tag_text"];
		
	
		$e2t = mysql_query("SELECT e2t.* FROM core_entry2tag e2t WHERE e2t.tag_id = $tag_id") or die(mysql_error());
		$e2t_c = mysql_num_rows($e2t);

		
		echo 	"<div class=\"list-item\" id=\"listItem_".$tag_id."\">
				<span class=\"arr\"><img src=\"arr.jpg\" /></span> <span class=\"list-link\" onclick=\"get_data('get_edit_tag',{'id':$tag_id})\"><b>$tag_text</b> #$tag_id &mdash;used by $e2t_c entries</span>
				</div>";
	}
	?>
	</div>
    </fieldset>
<script type="text/javascript">

function rearrange(order) {
	$.ajax({
		type: "GET",
		url: "functions/rearrange_tag.php",
		data: order
	});
}

function t_update() {
	var order = $('#tags').sortable('serialize'); 
	rearrange(order);
	get_data('show_tags');
}

$("#tags").sortable(); 
</script>
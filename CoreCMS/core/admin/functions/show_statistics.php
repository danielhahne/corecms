<?
require("_headr.php");
if($_SESSION["password"] != $pass || $_SESSION["sessid"] != session_id()) die(logout());

debug("Password validated, logged in",0);
?>
<p class="title-head"><b>OVERVIEW</b> &mdash; <? echo $_SERVER['HTTP_HOST']; ?></p>
<?
$db_passed = "";
$connection = @mysql_connect($db_server, $db_user, $db_pass) or $connection = false;
if($connection) {
	$db_return = "<span class=\"good\">Connection to database server is working</span> ";
	if(mysql_select_db($db_name, $connection)) {
	} else {
		$db_return = $db_return + "<span class=\"err\">but could not find the database.</span>";
	}
} else {
	$db_return = "<span class=\"err\">Connection to database server is not working</span>";
}

$entries = @mysql_query("SELECT e.* FROM core_entries e WHERE e.hits > 0");
$tags = @mysql_query("SELECT t.* FROM core_tags t");
$pages = @mysql_query("SELECT p.* FROM core_pages p");

$hits = 0;

$num_entries = mysql_num_rows($entries);
$num_tags = mysql_num_rows($tags);
$num_pages = mysql_num_rows($pages);

while($h = mysql_fetch_array($entries)) {
	$total += $h["hits"];
}

$entries = @mysql_query("SELECT e.* FROM core_entries e");
$tags = @mysql_query("SELECT t.* FROM core_tags t");
$pages = @mysql_query("SELECT p.* FROM core_pages p");

$num_entries_2 = mysql_num_rows($entries);
//HALFSIES
?>
<p class="title">&mdash;Database</p>
<p class="text">
<? echo $db_return; ?>
</p>

<p class="title">&mdash;Summary</p>
<p class="text">
Logged in as: <b><? echo $user; ?></b><br />
Using theme: <b><? echo $theme; ?></b><br />
<?
echo $num_entries_2 . " entries, ";
echo $num_tags . " tags, ";
echo $num_pages . " pages";
?>
</p>

<p class="title">&mdash;Updates (twitter)</p>
<div id="twitter_div">
<ul id="twitter_update_list"><img src="ajax-loader.gif" width="16" height="16" /></ul>
</div><br />
<a href="http://twitter.com/core_cms" id="twitter-link" style="display:block;">Core on Twitter</a>

<script type="text/javascript" src="http://twitter.com/javascripts/blogger.js"></script>
<script type="text/javascript" src="http://twitter.com/statuses/user_timeline/core_cms.json?callback=twitterCallback2&amp;count=10"></script>
<p class="text">
</p>
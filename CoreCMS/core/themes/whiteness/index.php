<script type="text/javascript">
loader = new Image(); 
loader.src="core/themes/soft/gfx/ajax-loader-w.gif"; 

$(function() {
	$('#thumbs img').preload({
		placeholder:'core/themes/soft/gfx/white.jpg',
		threshold: 3
	});
});
</script>

<div id="menu">
<div id="first">
	<p id="logotype">
		<img src="core/user/gfx/core_logo_small_black.png" alt="core logotype" />
    </p>
    <p id="core-loader">
   		<img src="<? echo $loadingImg; ?>" alt="loader" />
    </p>
</div>

<div id="entries">
	<? LOAD_MENU(); ?>
	<? LOAD_ENTRIES("LIST"); ?>
	<? LOAD_TAGS("LIST"); ?>
    <p class="tag-link tag-all"><a href="#">all</a></p>
</div>
</div>

<p class="clear-margin"></p>

<div id="load-content"></div>

<div id="thumbs"><? LOAD_ENTRIES("THUMBS"); ?></div>

<div id="footer">&mdash;<a href="http://weareastronauts.org/core-cms/" target="_blank">Core v1.2</a></div>

<? require($theme_path . "parts/scripts.php") ?>

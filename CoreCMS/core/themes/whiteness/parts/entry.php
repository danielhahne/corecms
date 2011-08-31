<div class="entry" id="entry-<? echo $entry_id; ?>">
    
    <p class="entry-title">
		<? echo $entry_title; ?>
        <span class="entry-extra">
        (<? echo $entry_hits; ?> hits)
        &mdash;
		<? echo $entry_tags; ?>
        </span>
    </p>
    
    <p class="media">
    	<? LOAD_MEDIA($entry_title); ?>
    </p>
    
    <div class="entry-text">
    <div class="entry-text-top">
    <p class="entry-extra1"><? echo $entry_extra1; ?></p>
    <p class="entry-extra2"><? echo $entry_extra2; ?></p>
    <p class="clear"></p>
    </div>
    	<? echo $entry_text; ?><br />
    </div>
    
    <p class="button"><a id="close-entry" href="#">close</a></p>
    <p class="button"><a id="close-entry" href="<? echo $entry_perma; ?>">permalink</a></p>
    
    <p class="entry-line"></p>
    
</div>
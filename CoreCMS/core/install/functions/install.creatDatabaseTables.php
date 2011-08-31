<?php

session_start(); 
error_reporting(0);

/*
 * INSTALLATION STEP 7 
 * > Creats all Tables in Database for CoreCMS
 */


$fatal = false; 

$root = $_SESSION['root'];
$http = $_SESSION['http'];
require_once($root. '/functions/install.session.php' ); 
$root = set_root();  
require_once($root. 'user/configuration.php' );
 
function s($str) {
	return addslashes($str);
}

$connection = mysql_connect($db_server, $db_user, $db_pass);
if(!$connection){
	debug("<span class=\"err\">ERROR</span>: There was an error connecting to the database server");
	debug(mysql_error());
	$fatal=true;
}else{
	debug("Connected to database server.");
	if(!mysql_select_db($db_name, $connection)){
		debug("<span class=\"err\">ERROR</span>: There was an error selecting the database");
		debug(mysql_error());
		$fatal=true;
	}
}


if($fatal) {
	debug("There was a fatal error. Exiting.");
	echo debug_echo();
	?>
    <p class="btn add" onclick="loadData('get_db_input.php')">Back</p>
    <?
	die();
}



/**
 * core_entries table
 *
 *
 * @since 1.2.1 
 * @param Boolean 
 *
 * @usage Store all entrys and their attributes like id, title, text..   
 *
 * @changelog 	entry_visit_link  
 *  			@since Core 1.3.3
 */ 

if(!check_table("core_entries")){
	debug("Creating table \"core_entries\"");
	if(!mysql_query("
	CREATE TABLE `core_entries` (
	`entry_id` int(10) unsigned NOT NULL auto_increment,
	`entry_position` int(11) NOT NULL default '0',
	`entry_date` date NOT NULL default '0000-00-00',
	`entry_title` varchar(255) NOT NULL default '',
	`entry_text` text NOT NULL,
	`entry_client` varchar(255) NOT NULL default '',
	`entry_extra1` text NOT NULL,
	`entry_extra2` text NOT NULL,
	`entry_visit_link` text NOT NULL,
	`entry_new` smallint(6) NOT NULL default '1',
	`entry_show` smallint(6) NOT NULL default '1',
	`hits` int(10) unsigned NOT NULL default '0',
	PRIMARY KEY  (`entry_id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
	")){
		debug("<span class=\"err\">ERROR</span>: There was an error creating table \"core_entries\"");
		debug(mysql_error());
		$fatal = true;
	}
}else{
	debug("Table core_entries does already exist, skipping creation");
}    




 
/**
 * Upgrade Core Database
 *
 *
 * @since 1.2.1   
 * @param Boolean
 *
 * @usage Transferring data to new Core installation with upgraded version
 */

if($upgrade) {
	debug("Transferring from old tables");
	if(check_table("data")) {
		$entries = mysql_query("SELECT * FROM `data`");
		$i = 0;
		
		$new = mysql_query("SELECT * FROM `core_entries`");
		if(mysql_num_rows($new) < 1) {
			while($e = mysql_fetch_array($entries)) {
				$entry_date = s($e['DATE']);
				$entry_title = s($e['TITLE']);
				$entry_text = s($e['TEXT']);
				$entry_client = s($e['CLIENT']);
				$entry_extra1 = s($e['EXTRA']);
				$entry_extra2 = s($e['EXTRA2']);
				$entry_new = $e['NEW'];
				
				if(!mysql_query("
					INSERT INTO `core_entries` (
					`entry_position`,
					`entry_date` ,
					`entry_new`,
					`entry_title` ,
					`entry_text` ,
					`entry_client` ,
					`entry_extra1` ,
					`entry_extra2` , 
					`entry_visit_link`
					) VALUES (
					$i, \"$entry_date\", $entry_new, \"$entry_title\", \"$entry_text\", \"$entry_client\", \"$entry_extra1\", \"$entry_extra2\", \"$entry_visit_link\"
					);")){
						debug(mysql_error()); 
				   }
				
				$i++;
			}	
		} else {
			debug("There is already content in core_entries. No transfer of old content made");
		}
	} else {
		debug("No table with the name \"data\" found, no transfer of old content made");
	}
}   





/**
 * core_entry2tag table
 *
 *
 * @since 1.2.1
 *
 * @usage Combine entry and their tag affiliation
 */

if(!check_table("core_entry2tag")){
	debug("Creating table \"core_entry2tag\"");
	if(!mysql_query("
CREATE TABLE `core_entry2tag` (
  `entry_id` int(10) unsigned NOT NULL default '0',
  `tag_id` int(10) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
")){
		debug("<span class=\"err\">ERROR</span>: There was an error creating table \"core_entries\"");
		debug(mysql_error());
		$fatal = true;
	}
}else{
	debug("Table core_entry2tag does already exist, skipping creation");
}
     
  




/**
 * core_pages table
 *
 *
 * @since 1.0.0
 *
 * @usage Store page and their attributes like title, url, id..
 */

if(!check_table("core_pages")){
	debug("Creating table \"core_pages\"");
	if(!mysql_query("
CREATE TABLE `core_pages` (
  `page_id` smallint(5) unsigned NOT NULL auto_increment,
  `page_title` varchar(255) NOT NULL default '',
  `page_url` varchar(30) NOT NULL default '',
  `page_position` smallint(5) unsigned NOT NULL default '0',
  `hits` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`page_id`),
  UNIQUE KEY `page_title` (`page_title`,`page_url`,`page_position`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
")){
		
		debug("<span class=\"err\">ERROR</span>: There was an error creating table \"core_entries\"");
		debug(mysql_error());
		$fatal = true;
	}
}else{
	debug("Table core_pages does already exist, skipping creation");
}   





/**
 * core_tags table
 *
 *
 * @since 1.2.1
 *
 * @usage Store tags and their attributes like name, id..
 */

if(!check_table("core_tags")){
	debug("Creating table \"core_tags\"");
	if(!mysql_query("
CREATE TABLE `core_tags` (
  `tag_id` int(10) unsigned NOT NULL auto_increment,
  `tag_text` varchar(255) character set latin1 NOT NULL default '',
  `tag_position` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`tag_id`),
  UNIQUE KEY `tag_text` (`tag_text`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
")){
		
		debug("<span class=\"err\">ERROR</span>: There was an error creating table \"core_entries\"");
		debug(mysql_error());
		$fatal = true;
	}
}else{
	debug("Table core_tags does already exist, skipping creation");
}     
    



/**
 * core_user table
 *
 *
 * @since 1.2.1
 *
 * @usage Combine entry and their tag affiliation
 */

if(!check_table("core_user")){
	debug("Creating table \"core_user\"");
	if(!mysql_query("
CREATE TABLE `core_user` (
  `username` varchar(30) NOT NULL default '',
  `pass` varchar(30) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
")){
		debug("<span class=\"err\">ERROR</span>: There was an error creating table \"core_user\"");
		debug(mysql_error());
		$fatal = true;
	}
}else{
	debug("Table core_user does already exist, skipping creation");
}








if($fatal) {
	debug("There was a fatal error. Exiting.");
	echo debug_echo();
	?>
    <p class="btn add" onclick="loadData('<?php echo $http; ?>install/functions/install.setDatabase.php')">Back</p>
    <?
	die();
} else {
	debug("Database tables created.");
	if($debug) {
		debug("End of PHP, displaying html.");
		debug_echo();
	}
}
?>

<p class="title-head">Tables created!</p>

You are all done, please continue to the summary.

<p class="margin"></p>
<p class="btn add" onclick="loadData('<?php echo $http; ?>install/functions/install.summary.php')">Continue</p>
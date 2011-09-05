# core.cms

## Core is a flexible ajax driven CMS devolped by Simon Jakobsson and further developed by Andreas Klein. 
After one year stopped development i want expand this beautiful ajax and php driven Content-Management System. 
Currently i work on a new deployed version of core (1.31) with an revised installer, a new feature which allows 
the user to install/deinstall themes. In further steps i want to implement a extension handler. 

If you find issues or want to help me develop core, fork this project on git.
thx andr3ws 


## Install CoreCMS on your Webserver
To begin installation, copy the contents of the folder "CoreCMS" (or the whole folder itself if you'd like) to your server.

(NOTE: There has been reported errors when putting the directory named "core" in another directory named "core". 
Try avoid this; putting it in a directory named "Core" (capitalized first letter) works fine.)

Then navigate to example.com/whatever/directory/core/install/ and start the installation. 
Or since v1.34 you can navigate to example.com/whatever/directory or if you want to install Core on a TLD 
call just example.com and Core automatically runs the Installer for you.


##UPGRADE TO HIGHER VERSION
There exists no upgrade script from < 1.21 to 1.34 at the moment. You need to go the hard way and use the new 
Installer and then manually copy you Database content. Sorry for that, i work on it!

If you are upgrading from version 1.0 - 1.21, just replace everything except you user folder (core/user) and 
upload to your server. (You can save your theme).

I've supplied a htaccess file with this package, just put a dot infront of "htaccess" (.htaccess) and put it 
on your server in the same folder as the core index file and folder is and you can enable "nice permalinks" 
in the configuration later on.

HINT: If you're upgrading from v06 or v05 and want to keep your old Core version intact that is absolutely 
no problem. 
Just put this core folder somewhere where it doesn't replace your old one. For example, if your current website 
is www.example.com, your current core folder is at www.example.com/core, put the new one at something like 
www.example.com/new-core/core.
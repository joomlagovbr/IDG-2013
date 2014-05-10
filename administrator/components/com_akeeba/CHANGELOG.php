<?php die();?>
Akeeba Backup 3.11.0
================================================================================
+ Show update information in the component's control panel page
+ Allow the user to forcibly reload the update information from the component's control panel page
+ Backup progress also shown on the browser tab's icon using Piecon
+ You can now change the date format of the Start column of the Manage Backups page
+ Database table installation, uninstallation and update is now handled by our own code instead of the unreliable JInstaller code
+ Work around against certain browsers (e.g. Safari) and password managers auto-filling fields marked with autocomplete="off"
+ Display a warning when you set up an ANGIE password during backup
+ [PRO] Added CLI script to check for new versions and automatically update Akeeba Backup
~ Working around Safari browser ignoring web standards: it always auto-completes a password field even when web developers explicitly specify autocomplete="off"
# [HIGH] Accessing the component fails on Joomla! 3.2.x if the core database has not been updated
# [MEDIUM] Blank page or 400 error message after updating from 3.10.1 or lower using Live Update
# [MEDIUM] WebDAV upload fails depending on whether you have a trailing slash in the base URL and a leading slash in the directory name
# [MEDIUM] ANGIE: Sometimes a fatal error is thrown on redirection
# [MEDIUM] The CLI check script was not working under Joomla 2.5
# [LOW] Some languages do not include a translation for the new PLG_SYSTEM_AKEEBAUPDATECHECK_MSG string
# [LOW] A tilde in the PHP version could cause a cosmetic error (a warning printed out) during restoration. Functionality was not affected by this issue.

Akeeba Backup 3.10.2
================================================================================
+ [PRO] Special ANGIE versions for WordPress, phpBB and miscellaneous PHP scripts allow you to backup and restore even more sites and scripts!
+ [PRO] CRON script to check for failed backups
~ Now using the built-in Joomla! extensions updater instead of Live Update to deliver updates
~ Live Update now uses the same Bootstrap style as the rest of the component
~ Live Update now uses database storage to solve issues with it getting stuck on some sites
~ Instruct browsers to NOT auto-complete password fields in the configuration page. Some browsers will STILL perform auto-completion.
~ Update backup duration after post-processing is finished, giving more accurate information about backups uploading their archives to remote storage
~ The size of the failed backup is stored when the backup record is being cleaned up automatically
- Removing the obsolete "Akeeba Backup Notification Module"
# [MEDIUM] WebDAV fails when the base URL does not contain a trailing slash (e.g. Box.com's base URL)
# [MEDIUM] WebDAV over SSL (HTTPS) fails on hosts without a system-wide certificate authority root
# [LOW] The backup archive size was reported incorrectly when the "Process each part immediately" option is enabled
# [LOW] Step and substep not shown during database backup

Akeeba Backup 3.10.1
================================================================================
! [PRO] The Hybrid file write engine in the restoration page resulted to an error
! [PRO] The Windows Azure BLOB Storage post-processing engine was broken
! [PRO] The iDriveSync post-processing engine was broken
+ [PRO] CloudMe integration.
+ [PRO] WebDAV integration. Allows us to support more than 40 new cloud storage providers including the frequently requested OwnCloud, Box.com and Copy.com!
~ Store last plugin notification timestamp inside the component table
~ Update notification emails do not include an automatic log in URL by default

Akeeba Backup 3.10.0
================================================================================
! [PRO] System Restore Points for components didn't work
+ [PRO] You can now tell Akeeba Backup to upload kickstart.php to remote storage (e.g. when using FTP or SFTP) to facilitate site transfers
+ You can specify the large file threshold (it was previously hard-coded to 10Mb)
+ "Large site scanner" engine for sites with directories containing hundreds or even several thousands of files
+ [PRO] System Restore Points now support extension types: package, library, file
~ Ignoring filters and engines with non-alphanumeric characters in their names, preventing crashes on hosts which automatically create copies of files e.g. folders.php to filders.1.php without asking the user
~ Warn about bad (S)FTP hostnames
# ANGIE doesn't reset the secret key in configuration.php
# [MEDIUM] Lite Mode was broken
# [LOW] gh-460 Layout display error in database exclusion page when you cannot connect to the database
# [LOW] gh-463 All items in Manage Backups page shown under Joomla! 3.2
# [LOW] [PRO] ghpro-7 Pagination alignment
# [LOW] [PRO] ALICE would report PHP 5.5 as too new, even though it's currently fully supported

Akeeba Backup 3.9.2
================================================================================
+ SFTP authentication with key files in the "Upload to Remote SFTP (SSH) Server" post-processing engine
+ SFTP connection testing for the "Upload to Remote SFTP (SSH) Server" post-processing engine
+ SFTP directory browser for the "Upload to Remote SFTP (SSH) Server" post-processing engine
+ Hybrid file writing engine for the integrated restoration feature
~ Forbit the browsers to autocomplete the fields in the Backup Now page. Many browsers auto-filled the ANGIE password (usually with the Super User's password), catching users by surprise when restoring their sites.
~ Changed the wording of the update email to make it abundantly clear that it's not sent by Akeeba Ltd
~ Improved detection of administrator email addresses
~ Warn users of the Core release that entering the Download ID doesn't magically "unlock" the Professional features
~ Better layout for the Component Parameters in Joomla! 3
# [MEDIUM] The Extension Manager: Update page displays wrong when the System Restore Points feature is enabled on Joomla! 2.5, using Firefox (other Joomla! versions / browsers are not affected)
# [MEDIUM] The alternate CRON backup script (akeeba-altbackup.php) would go on forever
# [LOW] Untranslated strings when using the SRP feature
# [LOW] Dropbox chunked upload would kick in for files less than 150Mb, causing problems in some low quality, yet popular, hosts.
# [LOW] Link to retry uploading of failed remote transfer not shown in Manage Backups page

Akeeba Backup 3.9.1
================================================================================
! [PRO] System Restore Points causing a fatal error when the (broken!) Joomla! cache is enabled.
! [CORE] The Core version would throw a white page

Akeeba Backup 3.9.0
================================================================================
+ [PRO] Massively updated System Restore Points feature, now works without replacing Joomla! extensions installer *and* it also works with extension updates
+ [PRO] You can enter a custom S3 endpoint URL, allowing you to use third party storage services with an S3-compatible API
+ [PRO] ANGIE is now embedded in "All configured databases" backup archives to let you restore them easily on your server.
+ [PRO] Easily troubleshoot your backup issues using Akeeba Log Inspection and Cause Elimination (ALICE) feature. It's like asking the developers for support, only faster.
- [PRO] Removing Box.com integration. PLEASE READ THE DOCUMENTATION FOR THE EXPLANATION.
- The old lazy scheduling plugin is now completely removed (for the last year it would only show an error message to remind you to disable it)
~ S3: Make sure that the directory uses forward slashes instead of backslashes; remove trailing slashes from the directory name
~ Better layout in back-end views under Joomla! 3
# [LOW] Incremental files only backup was broken
# [LOW] J3 Help toolbar modal window was not shown correctly

Akeeba Backup 3.8.2
================================================================================
+ [PRO] SFTP post-processing engine, allows you to upload backup archives to an SFTP (SSH) server
# [HIGH] CLI backup script: the connection is not set to UTF-8, causing backup issues on some servers
# [HIGH] System Restore Points: Upgrading Akeeba Backup leads to a huge restore point or a failure if you are using the component's default output directory for your backups
# [LOW] A non-existent JS file is attempted to be loaded in the Core release (the file only exists in the Pro release)

Akeeba Backup 3.8.1
================================================================================
! An annoying –but harmless– fatal error was shown on upgrade from older versions

Akeeba Backup 3.8.0
================================================================================
+ [PRO] DreamObjects support (an S3-compatible cloud storage solution by DreamHost)
+ Backup on update plugin: allows you to automatically backup your site before applying an update using the Joomla! Update component built into Joomla! itself
+ [PRO] Experimental support for PostgreSQL, SQL Server and Microsoft Azure SQL database technologies (installation and basic backups)
- Removed JMonitoring integration plugin as this software is no longer available
# [LOW] [PRO] CLI CRON scripts not fully compatible with PHP CGI

Akeeba Backup 3.7.10
================================================================================
~ No change compared to version 3.7.9. This is released due to what appears to be a CDN caching issue preventing some users from seeing the correct package. Please read the release notes for more information.

Akeeba Backup 3.7.9
================================================================================
! Wrong ANGIE package included in 3.7.8

Akeeba Backup 3.7.8
================================================================================
+ Option for full site backup with incremental files (kudos to Brian Teeman for the suggestion)
~ ANGIE: Improved database error reporting
~ ANGIE: Removing absolute URLs in favour of relative URLs, trying to make it compatible with temporary URLs (which are still not encouraged as they'll break Joomla's SEF URLs)
+ Box.net: you can now use remote quotas with this post-processing method
+ Box.net: you can now download backup archives directly to your browser with this post-processing method
# [MEDIUM] ANGIE: Restoration would fail if the original site's database password contains certain special characters
# [MEDIUM] Could not exclude symlinks to directories when dereference symlinks was disabled
# [MEDIUM] ANGIE: Default log and tmp path would still not work properly on Windows hosts. Now tested against WAMPServer, XAMPP, EasyPHP and IIS.
# [LOW] PHP Notice thrown in Backup Now page of Core release
# [LOW] Compatibility issue with FOF 2.1
# [LOW] ANGIE: Throw the correct error message when the database key is invalid
# [LOW] Pre-installation script would run the wrong SQL files on upgrade (normally not causing a problem, but still it's not the intended behaviour)
# [LOW] System Restore Points wouldn't work with plugins
# [LOW] ANGIE: Relative paths in template may result to restoration failure on certain hosts with similar named files in the default PHP include path (thank you, Damian)
# [LOW] Box.net: backups would appear as Obsolete instead of Remote

Akeeba Backup 3.7.7
================================================================================
+ Added "Robert button" (accept the mandatory information in the post-setup page without ticking each box)
~ Making MySQLi the default database engine in ANGIE
# [LOW] ANGIE: Division by zero on dead slow servers
# [LOW] ANGIE: The FTP layer's status sticks to the value of the backed up site

Akeeba Backup 3.7.6
================================================================================
! [HIGH] 403, memory outage errors (all Joomla! versions) and lack of system messages (Joomla! 3.x)
# [MEDIUM] RackSpace CloudFiles authentication broken; apparently the updated documentation in RackSpace's site isn't accurate?!

Akeeba Backup 3.7.5
================================================================================
+ ANGIE: Warn users of IE9 or earlier that they need to upgrade or switch to another browser
+ ANGIE: Reset some configuration.php settings when restoring to a different location, hopefully preventing broken sites due to server incompatibilities
+ ANGIE: Better session handling, making restoration more resilient to accidental information disclosure (when used with the password protection feature)
+ iDriveSync integration (Pro only)
+ PRO: Adding the possibility to password-protect the ANGIE installer
+ PRO: Allow editing the virtual directory name of external folders; if it's empty, the files will end up inside the archive's root
~ ANGIE: Expect web.config to have been renamed to web.config.bak during extraction (req. Kickstart 3.7.0)
~ ANGIE: Always use a file to store session data, as the PHP session storage seems to be causing db restoration issues
# [MEDIUM] ANGIE: Default log and tmp path wouldn't work properly on Windows hosts
# [LOW] ANGIE: State wasn't reset after the end of restoration, causing a subsequent restoration on the same site / local server and browser session to fail
# [LOW] ANGIE: Wrong database information put in configuration.php when restoring multiple databases (thanks Spring)

Akeeba Backup 3.7.4
================================================================================
! [HIGH] ANGIE: Database settings not saved when restoring sites
! [HIGH] ANGIE: Failed Joomla! 3 restoration due to typo in default configuration

Akeeba Backup 3.7.3
================================================================================
+ New backup installation script (ANGIE)
~ The Lazy Scheduling plugin which is deprecated since 2011 is now reduced to printing a warning which reminds you to disable it on your site
# [HIGH] Internal server error on ancient versions of PHP 5.3 with broken late static binding implementations
# [LOW] #445 Switching profiles in Backup Now page does not update the form properly

Akeeba Backup 3.7.2
================================================================================
# [HIGH] The component's menu item was removed if the installation couldn't proceed (too low PHP or Joomla! version)
# [HIGH] Joomla! doesn't run the database upgrade scripts when upgrading from a very old version or when the #__schemas entry is somehow missing
# [HIGH] After a failed installation, even if the subsequent installation is reported successful Joomla! does not install the database tables causing a broken installation
# [MEDIUM] The reason why the installation was aborted is not shown due to a Joomla! bug; worked around

Akeeba Backup 3.7.1
================================================================================
! Performance issue (too much RAM required) the first time Akeeba Backup would run

Akeeba Backup 3.7.0
================================================================================
~ UX improvement: When the Discover & Import archives finishes redirect to Manage Backups instead of Control Panel
~ Automatically detect and fix missing database tables
# [HIGH] The directory browser for off-site folders didn't work properly
# [MEDIUM] Wrong End of Central Directory record in multi-part ZIP archives didn't allow anything except Kickstart to extract them
# [MEDIUM] Import S3 would fail on multipart archives
# [MEDIUM] Using "Discover and Import Archives" with multipart archives would end up not showing the last part in the Manage Backups page
# [MEDIUM] The .htaccess in the backups directory would cause the server to return an error page instead of just limit access
# [MEDIUM] The "Manage remotely stored files" didn't appear in a popup as it is supposed to on Joomla! 3.0.
# [LOW] Specifying Super Administrator email for update notifications wasn't working
# [LOW] Very rare issue killing the database backup with a message like "fwrite(): XYZ is not a valid stream resource"
# [LOW] Very rare performance issue when your PHP memory limit is expressed in Kilobytes, e.g. 131072K instead of 128M
# [LOW] Giving akeeba.backup ACL privilege without also giving the global Edit Content privilege would prevent you from backing up
# [LOW] Backups downloaded from S3 would not be imported to the Manage Backups page, but only downloaded on the server
# [LOW] MySQL 5.6 compatibility
# [LOW] Help tooltips wouldn't display correctly on Joomla! 3.0.3

Akeeba Backup 3.6.12
================================================================================
# [LOW] Backup doesn't run on IE because it lacks console.debug support (ugh!)

Akeeba Backup 3.6.11
================================================================================
+ Allow you to specify only one Super Administrator to receive emails for Akeeba Backup updates
+ The users have to acknowledge a warning against untested backups before using the component
# [HIGH] Restoring backups with multiple databases doesn't allow you to modify the super administrator password
# [LOW] Backup wouldn't work on some brain-dead browsers like Mobile Safari due to XHR caching issues
# [LOW] ABI would warn against using PHP 5.3 with E_STRICT and display_errors enabled. This was a leftover from the Joomla! 1.5 days and had to be removed.
# [LOW] Working around Gantry bugs which make it incompatible with the SRP plugin (Pro version)
# [LOW] JSON API doesn't respect the from and limit parameters in the listBackups method

Akeeba Backup 3.6.10
================================================================================
~ Add more part size checks in the Configuration Wizard
~ Improved post-setup error messages if you have not selected both license & support checkboxes
~ Make sure we are no longer using Joomla!'s integrated extension update (it doesn't support stability and Joomla! version compatibility checks)
# [HIGH] The Akeeba Backup Update Check plugin would crash the site under Joomla! 3.0
# [HIGH] CLI backup broken under Joomla! 3.0

Akeeba Backup 3.6.9
================================================================================
+ The users have to accept the license and support policy before using the component
+ You can now exclude the username and password of your database connection from the backup
# [MEDIUM] ABI: Passwords with dollar signs will cause the configuration.php to throw parse errors
# [LOW] The Akeeba Backup update notification plugin didn't work
# [LOW] Notice thrown by the System Restore Points feature
# [LOW] JClientFtp::getInstance requires an empty array, not null, as its third parameter

Akeeba Backup 3.6.8
================================================================================
# [LOW] Sometimes the interface renders strangely (e.g. when a plugin sets format="")

Akeeba Backup 3.6.7
================================================================================
# [HIGH] Front-end backup doesn't work

Akeeba Backup 3.6.6
================================================================================
+ Warning when your tmp or log folder are equal to the site root which would result in incomplete backup
+ Profile import/export (including all profile settings, of course!)
~ Small visual improvements in the Configuration page
~ Improved integrated help rendering
# [HIGH] CLI backups of databases containing data or tables with non-ASCII characters would fail
# [HIGH] J3 Enabling System Restore Points made it impossible to install extensions on the site
# [MEDIUM] Sometimes no profiles are shown in the Profiles Management page
# [MEDIUM] Profile settings would be reset when turning encryption on/off
# [MEDIUM] ABI Using passwords with double quotes would result in inability to use the restored site (thanks @nternetinspired)
# [LOW] J3 Tooltips in Configuration page are surrounded by a smaller white box
# [LOW] J3 Component Parameters icon opened a modal box instead of simply redirecting to com_config
# [LOW] J3 Incomaptibility of jQuery UI with Joomla!'s jQuery version leading to broken Multiple Databases and Off-Site Folders inclusion filters
* All items marked with "J3" only apply to Joomla! 3. Items marked with ABI apply to Akeeba Backup Installer

Akeeba Backup 3.6.6.rc1 RELEASE CANDIDATE
================================================================================
+ Support for Joomla! 3.0 stable
+ Configuration tooltips now remain sticky (as per @brianteeman's suggestion)
+ Now using a Quick Icon plugin to show the backup status instead of the legacy module method
# [MEDIUM] Front-end backup wouldn't work in some cases
# [MEDIUM] Pagination not shown in Profiles view, sometimes making profiles disappear until you logged out and back in
# [LOW] Backup failure on MySQL versions which do not support the SQL_BIG_SELECTS option

Akeeba Backup 3.6.5
================================================================================
# [HIGH] Display bug with bundled FOF version

Akeeba Backup 3.6.4
================================================================================
~ Better handling of default log directory path when restoring, based on which path exists and which is writable
# [HIGH] The Akeeba Backup Update Check plugin would occasionally crash
# [MEDIUM] Opening MySQL and MySQLi connections could sometimes fail
# [MEDIUM] System Restore Points would not work on Windows
# [MEDIUM] Old PHP version warning was shown in extensions installation page when using System Restore Points
# [LOW] Main Database Only wouldn't run from the command-line
# [LOW] Adding a RegEx folder skip filter like !#subdirectory# would result in no backup being taken
# [LOW] Missing ID in checkbox could interfere with FTP connection test
# [LOW] Scheduling information page would crash

Akeeba Backup 3.6.3
================================================================================
~ Experimental support for Joomla! 3.0.a2; please use Joomla! 3.0-alpha2 only on TEST sites and report any bugs you find.
~ Recycling the MySQL connection in the CRON script to help with certain servers losing the db connection on uploading very large backups
~ Explain that the PHP 5.2 warning is a WARNING, not an error, and allow users to disable it.
# [MEDIUM] Some hosts report the file and folders permissions in a strange way, causing the permissions warning to always pop up in Akeeba Backup's Control Panel page
# [LOW] Notice thrown in the quota code

Akeeba Backup 3.6.2
================================================================================
~ Make it abundantly clear to PHP 5.2 users that they are using an outdated PHP version, without the option to turn off the huge, blinding message.
~ Improve styling of the Configuration page
# [LOW] warning in lines 48 and 248 of the mod_akadmin module
# [LOW] Fatal error in command-line CRON script when the email to administrator fails
# [LOW] The restoration script would always display in English
# [MEDIUM] Integrated restoration would fail with a Javascript error
# [MEDIUM] Copying the Dropbox tokens wouldn't work as we were missing the necessary User ID

Akeeba Backup 3.6.1
================================================================================
# [LOW] Update icon sometimes floating under the right-hand column
# [HIGH] System Restore Points not compatible with PHP 5.2

Akeeba Backup 3.6.0
================================================================================
! THIS VERSION IS ONLY COMPATIBLE WITH JOOMLA! 2.5.1 OR LATER
+ #434 Let the email report the post-processing status
+ Expose AKEEBAPRO status in JSON API's getVersion
+ Scheduling information page
~ Removing leftover jquery.js and jquery-ui.js from media/com_akeeba/js directory
# [MEDIUM] Fatal error in the native CLI akeeba-backup.php script when there are warnings
# [LOW] Notice thrown by S3 processing engine
# [LOW] Division by zero when restoring an SRP backup with a zero-length database dump
# [LOW] Clicking on Changelog for a second time would result in a Javascript error

Akeeba Backup 3.5.2
================================================================================
# [HIGH] Site Overrides' site root path was ignored
# [HIGH] Restoring a backup with multiple database definitions would result in the restoration getting stuck in a loop showing the main site db restoration page
# [MEDIUM] File filters for files in the site roto not respected when using the akeeba-backup.php CLI script
# [LOW] Import Archives did not parse directory variables like [DEFAULT_OUTPUT] and [SITEROOT]
# [LOW] You could no longer delete backup files or records through the JSON API
# [LOW] Error thrown in the component's control panel when Debug System is enabled
# [LOW] Wrong label "Database driver" instead of "Database hostname" in Site Overrides configuration (Professional release)
# [LOW] Backup notification icon would be permanently deactivated if a user without backup privileges (e.g. Administrator) tried to display it
# [LOW] Deleting a remotely stored backup could result in an error if there were too many parts
# [LOW] Strict notice in Administer Backup Files page

Akeeba Backup 3.5.1
================================================================================
+ Plugin for JMonitoring, notifies you on failed and stale backups
# [HIGH] Blank pages when accessing various Akeeba Backup views on some hosts which tried to load a certain PHP file twice, despite that making no sense whatsoever :s
# [HIGH] Obsolete files from very old releases (3.3.x and earlier) were not being removed, causing fatal errors.
# [MEDIUM] Restore points lacked the "Roll back" button
# [LOW] Akeeba Backup Core tries to install plugins existing only in the Professional release
# [LOW] Akeeba Backup Core would try to uninstall an inexistent module
# [LOW] The akeeba-backup.php CLI script wouldn't load the language files, causing the backup confirmation email to fail
# [LOW] A Javascript popup was shown when saving the Post-Configuration Wizard settings in Akeeba Backup Core

Akeeba Backup 3.5.0
================================================================================
~ Default minimum update stability is now set to stable
~ Akeeba Backup Core always updates to Stable only, irrespective of the user's preferences
~ The Post-Installation page now allows you to select the minimum update stability level
~ Auto-disable the Akeeba Backup icon module if a crash is detected, making sure that failed updates won't bring down your site
# Accessing Live Update threw a Not Authorized error
# Forgot to include the frontend dispatcher in the XML manifest. Thank you,	Daniele!
# The CLI scripts weren't copied when installing the Professional release
# The SRP and Update Check plugins wouldn't get installed in the Professional release
# The remote API for the SRP backup was broken. Thank you Daniele!
# FOF wasn't loaded by mod_akadmin, causing a fatal error to be thrown in the Joomla! control panel

Akeeba Backup 3.5.a2
================================================================================
! THIS IS AN ALPHA (TESTING) RELEASE. IT SHOULD NOT BE INSTALLED ON PRODUCTION (LIVE) SITES.
+ Allow Dropbox tokens to be copied across sites, allowing you to authenticate many sites to the same Dropbox account
# Live Update wouldn't work in Akeeba Backup 3.5.a1
# The warning about the Download ID would never go away
# Content-Disposition should wrap the filename in double quotes when downloading files

Akeeba Backup 3.5.a1
================================================================================
! THIS IS AN ALPHA (TESTING) RELEASE. IT SHOULD NOT BE INSTALLED ON PRODUCTION (LIVE) SITES.
+ Overhauled backup engine
+ Multi-db support (work in progress)
+ You can now add the Joomla! version in the archive's name using the [PLATFORM_VERSION] tag
+ Making sure you won't forget to enter your Download ID
+ Configure button next to profile's name in Profiles view
+ You can now override the site's root and/or database connection details, allowing you to backup any accessible on the server
+ #368 SugarSync integration
+ #396 Google Storage integration
+ New backup archive naming variable: [SITENAME]
+ #326 Added SRP support to the JSON API
~ Automatically update the XML update stream in Joomla! 2.5 to make use of the Download ID in the Pro release
~ ABI: Remove J! 1.5 support
# Accessing Akeeba Backup without adequate ACL priileges would cause an infinite redirection loop
# ABI: No progress bar shown in database restoration
# Functions, procedures and triggers were not being backed up correctly when their definition contained comments

Akeeba Backup 3.4.3
================================================================================
# Call time pass by reference removed in PHP 5.4.0, causing a fatal error when using Akeeba Backup on PHP 5.4
# The Box.net integration would copy the backup archive to the administrator directory and not delete it
# Regression: using the email after front-end backup causes a backup failure under Joomla! 2.5
# No emails after front-end backup sent under Joomla! 2.5

Akeeba Backup 3.4.2
================================================================================
# Some servers didn't create a correct OAuth signature for Dropbox' POST requests
# The Dropbox integration didn't work with PHP Safe Mode

Akeeba Backup 3.4.1
================================================================================
# Immediate crash in Joomla! 1.5 when the System - Legacy plugin is activated
# Dropbox authorisation step 1 would fail on some servers

Akeeba Backup 3.4.0
================================================================================
+ ABI: Updated jQuery and jQuery UI
+ ABI: Remember the preference to clear the database connection information when navigating between steps
+ #120 ABI: Ability to skip restoring select databases, except the one of the site (Skip instead of Next)
+ #121 Import arbitrary archives from S3
+ #248 Integrate with box.net cloud storage provider
+ The backup.php CRON script now supports a --debug switch so that it will dump any PHP errors
+ Finder tables (which can be rebuilt anytime) are now skipped by default, leading in MAJOR performance boost when backing up Joomla! 2.5 sites
~ Reimplementing Dropbox integration with their official API
# The update notification plugin could fire repeatedly if it wasn't able to update its last run timestamp
# ABI doesn't support MySQLi connection to non-standard (non-3306) ports
# Regression: multiple db backup didn't work
# The backup.php CRON script would not support send-by-email features under Joomla! 2.5.x

Akeeba Backup 3.3.13
================================================================================
+ You can now update Akeeba Backup Professional using the Joomla! extensions update (you still have to supply your Download ID to the component)
+ System Restore Points: Allow skipping table data with the <skiptables> element
# System Restore Points threw an error when updating a component
# The extension post-installation message would not show when System Restore Points was enabled
# Configuration overrides weren't being applied (affecting backup.php CRON script and System Restore Points)
# Language strings not showing on installation

Akeeba Backup 3.3.12
================================================================================
+ You can now force the language the update emails will be sent in
# Cancelling the edit of a backup record results in the display being filtered (thanks @brianteeman)
# The backup notification icon did not show on Joomla! 2.5.0 due to differences in Joomla's HTML markup
# backup.php stopped working with Joomla! 2.5.0 Stable

Akeeba Backup 3.3.11
================================================================================
+ Support for Amazon S3 RRS (Reduced Redundancy Storage)
~ Updating the CLI platform with the new location of Joomla! 2.5's version.php file
~ ABI: Warning message when Javascript is disabled during restoration
! Joomla! 2.5 requires passing the table prefix in getTable()
# The header in Administer Backup Files would change when using the pagination controls (thank you @brianteeman)
# A "Transfer Archive ()" link would appear even when there was no post-processing engine available (thank you @brianteeman)
# DropBox file operations (delete, download) were not working
# ABI: The configuration.php would show broken characters when non-ASCII characters were used in any field, e.g. site name or off-line message (kudos to Alexandros S. for the heads up)
# 311: Intermediate backup files are not removed after backup failure

Akeeba Backup 3.3.10
================================================================================
~ ABI: Bumped the minimum required PHP versions (5.1.6 for J! 1.5, 5.2.7 for J! 1.6+)
! Regression: No quotas would be applied

Akeeba Backup 3.3.9
================================================================================
! The SRP quotas would remove the latest system restore point, rendering the feature useless
~ Improved readability of update notification email (thank you Brian Teeman!)
# Suppressed warnings and notices would be reported in the log file, but they are expected to happen (they are file existence/read tests)
# Notice thrown in engine/abstract/archiver.php
# Suppressed warning thrown in engine/abstract/dump.php
# Suppressed warning thrown in AEConfiguration::reset()
# Suppressed warning thrown in AEUtilTempvars::reset()
# Suppressed warning thrown in AEDumpNative::createNewPartIfRequired()
# Suppressed warning thrown in AECoreDomainPack::pack_files()
# Suppressed warning thrown in AEAbstractArchiver::_addFile()

Akeeba Backup 3.3.8
================================================================================
+ UX: Remotely stored backups are now identified as "Remote" instead of "Obsolete" (kudos to Brian Teeman for proposing this)
+ UX: Renamed the Normal and Tabular views in Files and Directories Exclusion; added a more prominent link to the tabular view (kudos to Brian Teeman for proposing this)
+ ABI: You can now set the Cookie Domain and Cookie Path during restoration
# The pagination bar in the Restore Points would take you to the Backups page when clicked
# Joomla! 1.7 layout fixes
! System Restore Points were broken

Akeeba Backup 3.3.7
================================================================================
+ Add delete support for CloudFiles so that remote quotas are possible with it
+ Add download support for CloudFiles so that you can fetch archives back to your server
+ Add delete support for DropBox so that remote quotas are possible with it
+ Add download support for DropBox so that you can fetch archives back to your server
+ If Admin Tools Professional is installed, the update notification email link will include your administrator secret word.
~ Updated bundled JavaScript libraries to jQuery 1.7 and jQuery UI 1.8.16
# ABI: Invalid use of DS
# System Restore Point backups would appear in the "latest backup" view
# Sending backup completion emails was impossible from the CLI scripts (backup.php, altbackup.php)
# If you did not specify an email address, Akeeba Backup would fail fetching the Super Administator email addressed under Joomla! 1.7
# "Transfer Archive" would appear for obsolete backup records (Pro release)
# Regression: could not upload archives from the Administer Backup Files page
# Non-standard administrator templates could have a problem with the backup status icon module hiding everything on the page

Akeeba Backup 3.3.6
================================================================================
~ #247 Propose a fix for non-existent/unwritable backup output directory
# Cosmetic: #253 CHANGELOG would display an page showing [object] Object on Firefox
# Cosmetic: Tooltips on exclusion views would float out of view
# Make sure that we have at least PHP 5.2.7 before enabling the administrator module and the system plugins
# Backing up Thumbs.db and .DS_Store files cause extraction to fail on Windows and Mac OS X respectively; as of now, we don't back them up
! Command-line backup would not run on Joomla! 1.7 due to missing DS constant, still required by Joomla!

Akeeba Backup 3.3.5
================================================================================
+ Showing backup record ID in Administer Backup Files page
+ The Akeeba Backup admin icon now inlines itself to the Joomla! control panel's Quick Icons module
+ Display the CHANGELOG inside the component's Control Panel page
- Akeeba Backup Core feature moved to Professional: Update notification emails
- Akeeba Backup Core feature moved to Professional: System Restore Points
- Akeeba Backup Core feature moved to Professional: Site Transfer Wizard
- Akeeba Backup Core feature moved to Professional: All archiver engines except JPA
- Akeeba Backup Core feature moved to Professional: Most log level options
- Akeeba Backup Core feature moved to Professional: All backup types except full site and db-only backup
- Akeeba Backup Core feature moved to Professional: Date conditional filter
- Akeeba Backup Core feature moved to Professional: All quota settings except the basic ones (obsolete count, size, count)
- Akeeba Backup Core feature moved to Professional: Fine tuning settings except min/max execution time and runtime bias
# Regression: Count quotas would remove the latest, not the oldest, backup files
# Installing Akeeba Backup would remove the menu item link for Akeeba Subscriptions
# Akeeba Backup notification module: "Enable warnings" had no effect
# Use of AKEEBAPRO instead of AKEEBA_PRO in administrator/components/com_akeeba/views/backup/tmpl/default.php (fixed by doorknob)
# Untranslated string JGLOBAL_BATCH_COPY (this lang string was replaced in Joomla! 1.7)
# Would not show development version only updates
# GMT timezone used for the backup time stamp in Joomla! 1.7 instead of the user's timezone.
# ABI: It would leave behind akeeba_connection_test.png in the site's root, created by Akeeba Backup's Site Transfer Wizard
# Notice thrown by the native dump engine (cosmetic issue)

Akeeba Backup 3.3.4
================================================================================
+ Explanatory text regarding archive restoration in the Core release, when you visit the Administer Backup Files page
+ Improved message in the case of an error which will help you solve the issue yourself
+ ACL checks in the backup status administrator module
+ Row-level filtering is now possible (by creating a PHP filter file)
+ ABI is now self-documenting; no excuses for not reading the fine manual any more!
+ Added warning about UNC paths when used as the site's root, since PHP is very often buggy with respect to UNC paths
~ Modified the success message after taking a System Restore Point, backing up before upgrading Joomla! or otherwise take a backup as part of an automated process with a return URL
~ backup.php now returns exit code 1 if there were warnings or 2 if the backup failed on an error
~ Changed Akeeba Backup's installation page to better lead you to your next steps.
~ Renamed jquery.js to akeebajq.js due to some system plugins removing all instances of jquery.js from the page source (whichever idiot wrote them!)
~ Renamed jquery-ui.js to akeebajqui.js due to some system plugins removing all instances of jquery-ui.js from the page source (whichever idiot wrote them!)
~ Updated SRP definitions for Akeeba Backup, Admin Tools, Akeeba Subscriptions and Akeeba Release System
# tmp directory would not be cleaned up after extensions installation when SRP is used
# The Configuration page would not render on IE7, IE8 and IE9 (the latter only when using Compatibility mode)
# Backup age quotas caused the backup finalization to crash on PHP 5.2
# --quiet option added to backup.phpto suppress output except warnings and errors
# When using a developer's release, Live Update would always report that a newer version is available
# When a Windows server is using a UNC path for the site's root, the backup always failed
# When jQuery wasn't loaded, the message urged you to use Google AJAX API, which is no longer an option.
# ABI: Would always report that the storage isn't working when the session save path is unwritable but ABI could create a storage file to store session information.
# Quotas would delete the most recent files, not the oldest files

Akeeba Backup 3.3.3
================================================================================
~ Live Update and Joomla! XML update feed now tuned to look for updates through our CloudFront CDN distribution for faster results
~ Huge text describing what the update link does, what it doesn't and how to disable it added to update notification emails
# Akeeba Backup Update Check: version.php not loaded, causing it to believe that there is always an update available
# Restoration of a System Restore Point would not restore database content if it was very big (thanks to the guys at Migur for discovering it)
# During a System Restore Point restoration the database restoration progress would not be printed out
# Timezone was reset to UTC on Joomla! 1.6 and later when the server default timezone wasn't set, instead of letting Joomla! define the timezone based on Global Configuration preferences
# System Restore Points wouldn't run for component installations on Joomla! 1.5 running on Linux servers

Akeeba Backup 3.3.2
================================================================================
~ UI fixes for Joomla! 1.7
~ Improved Control Panel layout to better give at-a-glance information
~ Replaced remaining $mainframe references with JFactory::getApplication()
# The "Post-installation" view would load repeatedly on some servers
# Workaround for Joomla! 1.6+ bug resulting in "Can not build admin menus" and "DB function reports no error" messages when trying to install the component after a failed installation/update
# Site crash if somehow the component directories were removed but the module or plugins were not uninstalled
# Improved installation and uninstallation under Joomla! 1.6/1.7, working around Joomla! bugs which would prevent installation
# CLI backup would not run on sites running on Joomla! 1.7
# Akeeba Backup Update Check would send out emails even when no update information was retrieved from the updates server

Akeeba Backup 3.3.1
================================================================================
! The new quota implementation wasn't compatible with PHP 5.2 and threw a fatal error

Akeeba Backup 3.3.0
================================================================================
+ Maximum age quota limits; allows for, say, only monthly and last 30 day backups to be kept
+ #175 Post-installation view should "remember" user's settings
+ You can now define the maximum size System Restore Points will occupy on your server
# Obsolete count quota would only keep the last 10 records when enabled, no matter the user's choice
# #162 Force reload update information in the post-installation view
# Live Update would not refresh the version status after applying an update
# The "back to Control Panel" button in Step 3 of the Site Transfer Wizard would do nothing at all
# Some buggy servers report file lengths as floats, misleading Akeeba Backup into believing that it is unable to backup correctly the site's files
# Inversion of logic on AEPlatform::get_site_root() would cause issues on servers which report "/" as the site's root
# The ACL view in Joomla! 1.5 wouldn't allow you to change the permissions for individual users
# Post-installation setup would always ignore user's preference about automatic update emails and never enable the feature
# The post-installation page could appear over and over again on a new installation
# #176 System Restore Points interfere with the backup notification module
# ABI: Invisible newlines could cause PHP sessions to go haywire, not allowing the restoration to proceed
# Joomla! 1.6 wouldn't run the SQL file on update, causing potential update failure
# Quotas and post-processing would be applied to System Restore Points
# System Restore Points would have a download link
# System Restore Points would have an edit link which, of course, does nothing

Akeeba Backup 3.3.b1
================================================================================
+ Post-setup configuration wizard
+ #97 Akeeba Backup can now detect if its tables are broken and attempt to fix them, or warn you that it can't work properly
+ #29 Upload already taken, locally stored backup archives to remote storage any time
+ You are now able to select if you will be notified by Live Update for alpha, beta and RC versions.
~ Changed the page title to "System Restore Point in progress" when taking a System Restore Point
~ Changed all button to native button elements instead of Joomla!-styled input elements so that buttons DO LOOK like buttons
~ UI fix to cater for RocketTheme's MissionControl back-end template
~ UI fix for Nooku Server Alpha 3 (the flex-box was killing our layout)
~ Improved tooltips for clarity and usability
~ Changed label of Backup Now view to "Site Transfer Wizard" when it's called from the Site Transfer Wizard
~ Changed lable of "Backing up files" step to read "Transferring files to remote server" when a Site Transfer Wizard backup is running
~ Added a message before Site Transfer Wizard transfers you to the new site to complete the restoration
# Update check plugin: it would run all the time, without checking its last run time
# Live Update: The caching never worked, resulting in repeated hits to the update server
# Live Update: Current version number wouldn't always show up with svn releases
# The administrator backup notification icon module wasn't removed on component uninstall
# Large files in ZIP archives would always have a wrong uncompressed size of 4Gb
# Remote files quotas would not be applied
# Invalid ZIP central directory record generated meant the archive wouldn't be extracted by thirdparty unarchivers
# Invalid file size stored for large files when the ZIP format was being used
# If no files were being backed up from a root directory, an empty directory entry would be created, potentially making extraction impossible
# The remote API wouldn't return progress information
# Live Update: crashes on Joomla! 1.6 after caching fix
# Installation of a component using SRP in Joomla! 1.6 would cause the output of the install.component.php to never be displayed, causing potential installation issues
# ABI: "No database definitions found" error on some misbehaving servers
# One Click Actions: MySQL query error when auto-expiring old entries
# 156 Temporary directory not pre-populated in enhanced installer
# Files inside media/com_akeeba changed to 0755 permissions instead of the intended 0644

Akeeba Backup 3.3.a2
================================================================================
~ Remove alternate jQuery sources. The included jQuery library can be loaded on all sites (unless the permissions are broken, but you receive a warnign about it).
~ Renamed "Back" button to "Control Panel" (thank you, Brian Teeman, for the UX hint!)
~ Forward compatibility with 1.7 based on BC notes in http://docs.joomla.org/Potential_backward_compatibility_issues_in_Joomla_1.7_and_Joomla_Platform_11.1
# Directory browser: Using the "Go" button results in an error message.
# Can not override some configuration variables from the CLI
# S3: could not upload (it never took the bucket name)
# Fatal error coming from Live Update when accessing the component under Joomla! 1.6
# cacert.pem missing from package
# Update check emails could be sent en masse, instead of once every day to each Super Administrator
# Update check emails would severely malfunction on Joomla! 1.6
# "System - Akeeba Backup Update Check" plugin had the wrong name in the XML file

Akeeba Backup 3.3.a1
================================================================================

+ DirectSFTP engine, allows directly transferring your site to a remote SFTP server
+ Alternate directory scanning method, working around some servers not listing all files, leading to missing files from the backups
+ Site Transfer Wizard so that you can easily transfer your sites between hosts
+ web.config file in the backup output directory to prevent direct web access to the directory on IIS-based hosts
+ System Restore Points: extension developers can instruct Akeeba Backup to take SRPs before upgrades for that extra peace of mind
+ #125 Allow per-extension system restoration point overrides
+ Akeeba Backup now uninstalls its modules and plugins when you uninstall the component (how did I miss that?!)
+ #111 Add warning for low memory limit
+ #107 Optional storage of temporary data to the database instead of files
+ #131 One click update emails to Super Administrators
+ Live Update now takes a System Restore Point before upgrade when this feature is enabled
# RackSpace CloudFiles: Did not support UK-based accounts; added an option for that (thanks Dean!)
# #92 Restoring an 1.6 site results to configuration.php artifacts
# #95 Live site URL not stored in component configuration
# #96 JS/CSS files out of date after upgrade, due to browser caching
# #93 Using HTTPS with cURL fails on some servers
# #123 S3: Automatically remove / from the bucket name
# #124 S3: Do not lowercase the bucket name
# #112 Live Update doesn't work on hosts with open_basedir restrictions
# Can not backup a site on some broken hosts which report an empty string as the site's root *and* do not parse relative directories correctly

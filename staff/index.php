<?php /* 
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
include('../bootstrap.php');

util::prepend_title('Staff');

include(BASE_PATH.'doc_top.php');

include(BASE_PATH.'menu/main.php');

define('HIDELOGIN',1);
include(BASE_PATH.'doc_mid.php');
				// MAIN PAGE INFO
?>

				<div class="Title">SquadWar Staff</div>
				<br />
				<div class="copy">
					<b>Cliff 'chief1983' Gordon</b> - Administrator, Moderator, SquadWar Support<br />
					<b>taylor</b>	 - FS2NetD Support/FreeSpace 2 Multiplayer Support<br />
				<p>
				If you have a problem with single-player FreeSpace please contact Interplay support at <a href="http://www.interplay.com">http://www.interplay.com</a></p>
				<p>
				If you have a problem with FS2NetD, connecting to FS2NetD, or hosting a SquadWar match please contact FS2NetD Support at: <a href="mailto:<?php echo SUPPORT_EMAIL; ?>"><?php echo SUPPORT_EMAIL; ?></a></p>
				<p>
				If you have a problem with the SquadWar web application, your SquadWar information, Squadron, or to dispute or report information about individuals detrimental to SquadWar, please write the circumstances in full and complete detail to: <a href="mailto:squadwar@volition-inc.com">squadwar@volition-inc.com</a></p>
				</div>

				<?php // END MAIN PAGE INFO ?>
<?php include(BASE_PATH.'doc_bot.php'); ?>

<?php /* 
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
include('../bootstrap.php');

$document_title = 'SquadWar - Staff';

include(BASE_PATH.'doc_top.php');

include(BASE_PATH.'menu/main.php');

define('HIDELOGIN',1);
include(BASE_PATH.'doc_mid.php');
				// MAIN PAGE INFO
?>

				<div class="Title">SquadWar Staff</div>
				<br />
				<div class="copy">
					<b>Nathan Camarillo</b> - Administrator, Moderator, SquadWar Support<br />
					<b>Eric Keyser</b>	 - PXO Support/FreeSpace 2 Multiplayer Support<br />
					<b>James Tsai</b> - PXO Support/FreeSpace 2 Multiplayer Support<br />

				<p>
				If you have a problem with single-player FreeSpace please contact Interplay support at <a href="http://www.interplay.com">http://www.interplay.com</a></p>
				<p>
				If you have a problem with PXO, connecting to PXO, or hosting a SquadWar match please contact PXO Support at: <a href="mailto:support@pxo.net">support@pxo.net</a></p>
				<p>
				If you have a problem with the SquadWar web application, your SquadWar information, Squadron, or to dispute or report information about individuals detrimental to SquadWar, please write the circumstances in full and complete detail to: <a href="mailto:squadwar@volition-inc.com">squadwar@volition-inc.com</a></p>
				</div>

				<?php // END MAIN PAGE INFO ?>
<?php include(BASE_PATH.'doc_bot.php'); ?>

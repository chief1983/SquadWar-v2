<?php /*
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
define('SECURE',1);
include('../bootstrap.php');

$document_title = 'SquadWar - Enlist';

include(BASE_PATH.'doc_top.php');

define('ENLIST',1);
include(BASE_PATH.'menu/main.php');

include(BASE_PATH.'doc_mid.php');
				// MAIN PAGE INFO
?>
				<div class="title">Recruitment Center</div>
				<br />
				<div class="copy">
					Welcome, <?php echo $_SESSION['login']; ?>, to the SquadWar Recruitment Center.  
					The recruitment center allows you to <a href="enlist.php?action=add">Enlist a FreeSpace 2 pilot for recruitment</a> on the <a href="<?php echo RELATIVEPATH; ?>recruits/">Recruits Board</a>, <a href="enlist.php?action=add">edit a pilot already on the board</a>, or <a href="enlist.php?action=deactivate">deactivate a pilot from the board.</a>
					<p>
					The purpose of the Recruitment Center is to provide pilots with a way to show off their FreeSpace 2 stats to potential squadrons.  Squad Leaders will check out the Recruits Board for information on active pilots who want to join a Squad.</p>
				
				</div>
								
				<?php // END MAIN PAGE INFO ?>
<?php include(BASE_PATH.'doc_bot.php'); ?>

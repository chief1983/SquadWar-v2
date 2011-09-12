<?php /* 
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
include('bootstrap.php');

$document_title = 'SquadWar - League';

include(BASE_PATH.'doc_top.php');

define('LEAGUE',1);
include(BASE_PATH.'menu/main.php');

define('HIDELOGIN',1);
include(BASE_PATH.'doc_mid.php');
				// MAIN PAGE INFO

if(!empty($_GET['leagueid'])):
	$leagueid = $_GET['leagueid'];
?>
	<center>
		<?php include('leagues/map.php'); ?>
		<br />
		<?php include('leagues/ticker_pending_view.php'); ?>
	</center>			
<?php endif; ?>

				<?php // END MAIN PAGE INFO ?>
<?php include(BASE_PATH.'doc_bot.php'); ?>

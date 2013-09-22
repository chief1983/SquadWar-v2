<?php /*
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
define('SECURE',1);
include('../bootstrap.php');

$rec = squad_api::new_search_record();
$rec->set_SquadID($_SESSION['squadid']);
$ret = squad_api::search($rec);
$ret = squad_api::populate_info($ret);
$get_squad = reset($ret->get_results());
$get_squad_info = $get_squad->get_info();

$get_squad_info->set_Squad_Email($_POST['squad_email']);
if(!empty($_POST['squad_leader_icq']))
{
	$get_squad_info->set_Squad_Leader_ICQ($_POST['squad_leader_icq']);
}
if(!empty($_POST['squad_irc']))
{
	$get_squad_info->set_Squad_IRC($_POST['squad_irc']);
}
if(!empty($_POST['squad_web_link']))
{
	$get_squad_info->set_Squad_Web_Link($_POST['squad_web_link']);
}
if(!empty($_POST['squad_leader_icq']))
{
	$get_squad_info->set_Squad_Leader_ICQ($_POST['squad_leader_icq']);
}

$status = $get_squad_info->save();

util::prepend_title('Edit Squad');

include(BASE_PATH.'doc_top.php');

include(BASE_PATH.'menu/main.php');

include(BASE_PATH.'doc_mid.php');

				// MAIN PAGE INFO
?>

		<div class="copy">
			<?php if($status): ?>
				<b>Your changes have been accepted</b>
			<?php else: ?>
				<b>There was an error.  Please try again or contact the administrator.</b>
			<?php endif; ?>
		</div>

				<?php // END MAIN PAGE INFO ?>
<?php include(BASE_PATH.'doc_bot.php'); ?>

<?php /*
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
define('SECURE',1);
include('../bootstrap.php');

if(empty($_SESSION['squadid']))
{
	util::location('admin.php');
}

$rec = squad_api::new_search_record();
$rec->set_SquadID($_SESSION['squadid']);
$ret = squad_api::search($rec);
$ret = squad_api::populate_info($ret);
$get_squad = reset($ret->get_results());
$get_squad_info = $get_squad->get_info();

$document_title = 'SquadWar - Edit Squad';

include(BASE_PATH.'doc_top.php');

include(BASE_PATH.'menu/main.php');

include(BASE_PATH.'doc_mid.php');

				// MAIN PAGE INFO
?>

					<div class="title">Edit Your Squad's Information</div>
					<p>
					<form action="_edit_squad.php" method="post" class="validate">
						<table>
						<tr>
							<td align="right"><div class="copy"><b>Email Address:</b></div></td>
							<td><input type="text" name="squad_email" size="25" maxlength="50" class="required" value="<?php echo $get_squad_info->get_Squad_Email(); ?>"  title="You must enter your email address." /></td>
						</tr>
						<tr>
							<td align="right"><div class="copy"><b>Squad ICQ:</b></div></td>
							<td><input type="text" name="squad_leader_icq" size="25" maxlength="25" value="<?php echo $get_squad_info->get_Squad_Leader_ICQ(); ?>" /></td>
						</tr>
						<tr>
							<td align="right"><div class="copy"><b>Squad IRC:</b></div></td>
							<td><input type="text" name="squad_irc" size="25" maxlength="25" value="<?php echo $get_squad_info->get_Squad_IRC(); ?>" /></td>
						</tr>
						<tr>
							<td align="right" valign="top"><div class="copy"><b>Squad Web Link:</b><br />(include http://)</div></td>
							<td valign="top"><input type="text" name="squad_web_link" size="25" maxlength="55" value="<?php echo $get_squad_info->get_Squad_Web_Link(); ?>" /></td>
						</tr>
						<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
						<tr><td>&nbsp;</td><td><input type="submit" value="Submit Change" /></td></tr>
						</table>

						</form>

				<?php // END MAIN PAGE INFO ?>
<?php include(BASE_PATH.'doc_bot.php'); ?>

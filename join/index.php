<?php /*
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
define('SECURE',1);
include('../bootstrap.php');

$rec = squad_api::new_search_record();
$rec->set_sort_by('SquadName');
$ret = squad_api::search($rec);
$ret = squad_api::populate_info($ret);
$get_squads = $ret->get_results();

util::prepend_title('Join a Squad');

include(BASE_PATH.'doc_top.php');

include(BASE_PATH.'menu/main.php');

include(BASE_PATH.'doc_mid.php');
				// MAIN PAGE INFO
?>
				<?php if($_SESSION['totalfsopilots'] > 0): ?>
					<div class="copy">
						To join a Squad, the Squad Leader must give you the Squad's "join" password.  If you have the Squad's join password, select the Squad's name from the list below.  When you reach the next screen, enter the password where prompted on the form.
						<p>
						<b>Currently Pre-registered Squad Names:</b></p>
						<table>
							<tr>
								<td><div class="squadtable"><b>Name</b></div></td>
								<td><div class="squadtable"><b>Web Link</b></div></td>
								<td><div class="squadtable"><b>Squad ICQ</b></div></td>
								<td><div class="squadtable"><b>Squad Email</b></div></td>
							</tr>
							<?php $coloredrow = 0;
							foreach($get_squads as $squad):
								$squad_info = $squad->get_info();
								?>
								<tr<?php if(++$coloredrow % 2): ?> bgcolor="#0B160D"<?php endif; ?>>
								<td><div class="squadtable"><a href="join_squad.php?id=<?php echo $squad->get_SquadID(); ?>"><?php echo $squad->get_SquadName(); ?></a></div></td>
								<td><div class="squadtable"><?php if($squad_info->get_Squad_Web_Link() != ''): ?><a href="<?php echo $squad_info->get_Squad_Web_Link(); ?>">yes</a><?php else: ?>&nbsp;<?php endif; ?></div></td>
								<td><div class="squadtable"><?php if($squad_info->get_Squad_Leader_ICQ() != ''): echo $squad_info->get_Squad_Leader_ICQ(); ?><?php endif; ?></div></td>
								<td><div class="squadtable"><?php if($squad_info->get_Squad_Email() != ''): ?><a href="mailto:<?php echo $squad_info->get_Squad_Email(); ?>"><?php endif; echo $squad_info->get_Squad_Email(); if($squad_info->get_Squad_Email() != ''): ?></a><?php endif; ?></div></td>
								</tr>
							<?php endforeach; ?>
						</table>
					</div>

				<?php else: ?>
					<div calss="copy">
						No pilots exist for this account.  You must log into FS2NetD using FreeSpace 2 to create a multiplayer pilot in the FS2NetD database before attempting to join a squad.<br />
					</div>
				<?php endif; ?>

				<?php // END MAIN PAGE INFO ?>
<?php include(BASE_PATH.'doc_bot.php'); ?>

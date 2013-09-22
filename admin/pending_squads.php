<?php /*
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
include('../bootstrap.php');

util::prepend_title('Pending Squads Admin');

$rec = squad_api::new_search_record();
$rec->set_Active(0);
$rec->set_suspended(0);
$ret = squad_api::search($rec);
$ret = squad_api::populate_info($ret);
$get_squads_not_approved = $ret->get_results();

include(BASE_PATH.'doc_top.php');

include(BASE_PATH.'admin/menu/main.php');

include(BASE_PATH.'doc_mid.php');
				// MAIN PAGE INFO
?>
				<div class="title">Squads Pending Activation</div>
				<br />
					<table width="90%" border="1" style="border:1px solid #5A63F7;" cellspacing="0">
						<tr>
							<th>ID</th><th>Name</th><th>ICQ</th><th>IRC</th><th>Web</th><th>Email</th><th>&nbsp;</th>
						</tr>
						<?php foreach($get_squads_not_approved as $squad): ?>
						<tr>
							<td><div class="admin_table"><a href="<?php echo RELATIVEPATH; ?>admin/_pending_squads.php?squadid=<?php echo $squad->get_SquadID(); ?>"><?php echo $squad->get_SquadID(); ?></a>&nbsp;</div></td>
							<td><div class="admin_table"><?php echo $squad->get_SquadName(); ?>&nbsp;</div></td>
							<td><div class="admin_table"><?php echo $squad->get_info()->get_Squad_Leader_ICQ(); ?>&nbsp;</div></td>
							<td><div class="admin_table"><?php echo $squad->get_info()->get_Squad_IRC(); ?>&nbsp;</div></td>
							<td><div class="admin_table"><?php if($squad->get_info()->get_Squad_Web_Link() != ''): ?><a href="<?php echo $squad->get_info()->get_Squad_Web_Link(); ?>"><?php echo $squad->get_info()->get_Squad_Web_Link(); ?></a><?php endif; ?>&nbsp;</div></td>
							<td><div class="admin_table"><a href="mailto:<?php echo $squad->get_info()->get_Squad_Email(); ?>"><?php echo $squad->get_info()->get_Squad_Email(); ?></a>&nbsp;</div></td>							
							<td><div class="admin_table"><a href="<?php echo RELATIVEPATH; ?>admin/_delete_squad.php?squadid=<?php echo $squad->get_SquadID(); ?>">delete</a>&nbsp;</div></td>
						</tr>
						<?php endforeach; ?>
					</table>
					
				<?php // END MAIN PAGE INFO ?>
<?php include(BASE_PATH.'doc_bot.php'); ?>

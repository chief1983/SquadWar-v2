<?php /* 
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
include('../bootstrap.php');

$rec = mission_api::new_search_record();
$ret = mission_api::search($rec);
$get_missions = $ret->get_results();

util::prepend_title('Missions');

include(BASE_PATH.'doc_top.php');

include(BASE_PATH.'menu/main.php');

define('HIDELOGIN',1);
include(BASE_PATH.'doc_mid.php');
				// MAIN PAGE INFO
?>

				<div class="Title"><b>SquadWar Missions</b></div>
				<br />
				<div class="copy">

				You must download the most recent Multi Missions to play. Extract the contents into your FreeSpace2\ directory. The missions are available for download here:
				<a href="http://www.fubar.org/FS2/multi.rar">Multi Missions</a>
				</div>
				<br />

				<div class="copy">
					<?php foreach($get_missions as $mission): ?>
						<div class="title"><?php echo $mission->get_filename(); ?>: <?php echo $mission->get_name(); ?></div>
						<table width="95%" class="center">
							<tr>
								<td><div class="copy"><b>Description:</b></div></td>
								<td rowspan="4">
									<a href="../images/missions/<?php echo $mission->get_filename(); ?>_full.png">
										<img src="../images/missions/<?php echo $mission->get_filename(); ?>.png" width="240" height="180" alt="<?php echo $mission->get_name(); ?> screenshot" border="0" align="right" />
									</a>
								</td>
							</tr>
							<tr>
								<td><div class="copy">
								<?php echo $mission->get_description(); ?>
								</div></td>
							</tr>
							<tr>
								<td><div class="copy"><b>Specifics:</b>
								</div></td>
							</tr>
							<tr>
								<td><div class="copy">
								<?php echo $mission->get_specifics(); ?>
								<br /><br />Max Respawns: <?php echo $mission->get_respawns(); ?>
								<br />Max Players: <?php echo $mission->get_players(); ?>
								</div></td>
							</tr>
						</table>
						<br />
						<br />

					<?php endforeach; ?>
				</div>
		
				<?php // END MAIN PAGE INFO ?>
<?php include(BASE_PATH.'doc_bot.php'); ?>

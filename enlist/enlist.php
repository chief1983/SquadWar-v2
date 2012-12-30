<?php /*
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
define('SECURE',1);
include('../bootstrap.php');

$rec = fsopilot_api::new_search_record();
$rec->set_user_id($_SESSION['user_id']);
if(!empty($_GET['action']) && $_GET['action'] == 'deactivate')
{
	$rec->set_Recruitme(1);
}
$ret = fsopilot_api::search($rec);
$ret = fsopilot_api::populate_swpilots($ret);
$fs2_search_pilots = $ret->get_results();

$document_title = 'SquadWar - Enlist a Pilot';

include(BASE_PATH.'doc_top.php');

define('ENLIST',1);
include(BASE_PATH.'menu/main.php');

include(BASE_PATH.'doc_mid.php');
				// MAIN PAGE INFO
?>

				<?php if(!empty($_GET['action'])): ?>
					<?php if($_GET['action'] == 'add'): ?>
						<div class="title"><font color="white"><b><?php echo $_SESSION['login']; ?>'s FreeSpace 2 Pilots</b></font></div>								
						<?php if(count($fs2_search_pilots) == 0): ?>
							<div class="copy">
								No pilots exist for this account.  You must log into FS2NetD using FreeSpace 2 to create a multiplayer pilot in the FS2NetD database.<br />
							</div>
						<?php else: ?>
							<br />
							<div class="copy">
							Choose a pilot to enlist or edit.
							<ol>
							<?php foreach($fs2_search_pilots as $pilot):
								$swpilot = $pilot->get_swpilot(); ?>
									<li><b>Pilot:</b> <?php echo $pilot->get_pilot_name(); ?><br />
										<b>Score:</b> <?php echo $pilot->get_score(); ?><br />
										<b>Rank:</b> <?php echo util::str_rank($pilot->get_rank()); ?><br />
										<b>Kills:</b> <?php echo $pilot->get_kill_count_ok(); ?><br />
										<b>Missions Flown:</b> <?php echo $pilot->get_missions_flown(); ?><br />
										<b>Flight Time:</b> <?php echo util::str_time($pilot->get_flight_time()); ?><br />
										<form action="_enlist.php" method="post" name="addpilot">
											<input type="hidden" name="refer" value="<?php echo $_SERVER['PHP_SELF']; ?>" />
											<input type="hidden" name="pilotid" value="<?php echo $pilot->get_id(); ?>" />
										<?php if(empty($swpilot)): ?>
											<input type="hidden" name="action" value="add" />
											<input type="submit" value="Enlist this pilot" />
										<?php else: ?>																																				
											<b>ICQ:</b> <?php echo $swpilot->get_ICQ(); ?><br />
											<?php if($swpilot->get_show_email()): ?>
												<b>Email:</b> <?php echo $swpilot->get_email(); ?><br />
											<?php endif; ?>
											<b>Time Zone:</b> <?php echo htmlspecialchars($swpilot->get_fetch_time_zone()); ?><br />
											<b>Connection Type:</b> <?php echo $swpilot->get_fetch_connection_type(); ?><br />
											<input type="hidden" name="action" value="update" />
											<p>
											<?php if($swpilot->get_Recruitme()): ?>
												<input type="submit" value="Update this pilot" />
											<?php else: ?>
												<input type="submit" value="Re-Enlist this pilot" />
											<?php endif; ?>
											</p>
										<?php endif; ?>			
										</form>								
										<br />
									</li>
							<?php endforeach; ?>
							</ol>
							</div>
						<?php endif; ?>
					<?php elseif($_GET['action'] == 'deactivate'): ?>
						<div class="newsitemtitle"><font color="white"><b><?php echo $_SESSION['login']; ?>'s FreeSpace 2 Pilots</b></font></div>								
						<br />
						<div class="copy">
							Choose a pilot to remove from the Recruit Board.
						</div>
						<br />
						<?php if(count($fs2_search_pilots) == 0): ?>
							<div class="copy">
								No pilots exist for this account.<br />
							</div>
						<?php else: ?>
							<div class="copy">
							<ol>
							<?php foreach($fs2_search_pilots as $pilot):
								$swpilot = $pilot->get_swpilot(); ?>
									<?php if(!empty($swpilot)): ?>
										<li><b>Pilot:</b> <?php echo $pilot->get_pilot_name(); ?><br />
											<b>Score:</b> <?php echo $pilot->get_score(); ?><br />
											<b>Rank:</b> <?php echo util::str_rank($pilot->get_rank()); ?><br />
											<b>Kills:</b> <?php echo $pilot->get_kill_count_ok(); ?><br />
											<b>Missions Flown:</b> <?php echo $pilot->get_missions_flown(); ?><br />
											<b>Flight Time:</b> <?php echo util::str_time($pilot->get_flight_time()); ?><br />
											<form action="_enlist_deactivate.php" method="post" name="addpilot">
												<input type="hidden" name="refer" value="<?php echo $_SERVER['PHP_SELF']; ?>" />
												<input type="hidden" name="pilotid" value="<?php echo $pilot->get_id(); ?>" />
												<b>ICQ:</b> <?php echo $swpilot->get_ICQ(); ?><br />
												<b>Time Zone:</b> <?php echo htmlspecialchars($swpilot->get_fetch_time_zone()); ?><br />
												<b>Connection Type:</b> <?php echo $swpilot->get_fetch_connection_type(); ?><br />
												<p>
												<input type="hidden" name="action" value="update" />
												<input type="submit" value="Deactivate this pilot" />
												</p>
											</form>													
											<br />
										</li>
									<?php endif; ?>
							<?php endforeach; ?>
							</ol>
							</div>
						<?php endif; ?>
					<?php endif; ?>
			
				<?php else: ?>
					<div class="copy">
						You have reached this page in error.
					</div>
				<?php endif; ?>
		
				<?php // END MAIN PAGE INFO ?>
<?php include(BASE_PATH.'doc_bot.php'); ?>

<?php /* 
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
include('../bootstrap.php');

$document_title = 'SquadWar - Pending Matches';

$rec = match_api::new_search_record();
$rec->set_either_squad($_SESSION['squadid']);
$ret = match_api::search($rec);
$ret = match_api::populate_info($ret);
$ret = match_api::populate_sectors($ret);
$ret = match_api::populate_league($ret);
$ret = match_api::populate_squads($ret, true);
$get_matches = $ret->get_results();

include(BASE_PATH.'doc_top.php');

include(BASE_PATH.'menu/main.php');

include(BASE_PATH.'doc_mid.php');
				// MAIN PAGE INFO
?>
						<table width="95%" cellpadding="0" cellspacing="0" border="0">
						<?php if(count($get_matches)):
							$coloredrow = 0;
							$thisdate = ''; ?>
								<tr><td colspan="6" align="center"><div class="title">Pending Matches</div></td></tr>
								<tr><td colspan="6" align="center"><div class="copy">Click on the "<font color="red">match code</font>" to access scheduling options for these matches.</div></td></tr>
								<tr>
									<td><div class="copy">Date</div></td>
									<td><div class="copy">Code</div></td>
									<td><div class="copy">League</div></td>
									<td><div class="copy">Description</div></td>
									<td><div class="copy">Opponent's Email</div></td>
									<td><div class="copy">Status</div></td>
								</tr>
							<?php foreach($get_matches as $match): ?>
								<tr<?php if(++$coloredrow %2): ?> bgcolor="#0B160D"<?php endif; ?>>
									<td valign="top">
										<div class="copy"<?php if(strtotime($match->get_info()->get_time_created()) < time() - 60*60*24*7): ?> style="color:red;"<?php endif;?>>
											<b><?php echo date('n.j.y', strtotime($match->get_info()->get_time_created())); ?></b>
										</div>
									</td>
									<td valign="top"><div class="copy">&nbsp;<a href="squad_match_info.php?matchid=<?php echo $match->get_SWCode(); ?>"><font color="red"><?php echo $match->get_SWCode(); ?></font></a>&nbsp;</div></td>
									<td valign="top"><div class="copy"><?php echo $match->get_league()->get_Title(); ?>&nbsp;</div></td>
									<td valign="top">
										<div class="copy">
											<?php if($match->get_SWSquad1() != $_SESSION['squadid']): ?><a href="squadinfo.php?id=<?php echo $match->get_SWSquad1(); ?>"><?php endif; ?>
											<?php echo $match->get_Challenger()->get_SquadName(); ?>
											<?php if($match->get_SWSquad1() != $_SESSION['squadid']): ?></a><?php endif; ?>
											Challenged
											<?php if($match->get_SWSquad2() != $_SESSION['squadid']): ?><a href="squadinfo.php?id=<?php echo $match->get_SWSquad2(); ?>"><?php endif; ?>
											<?php echo $match->get_Challenged()->get_SquadName(); ?>
											<?php if($match->get_SWSquad2() != $_SESSION['squadid']): ?></a><?php endif; ?>
											for control of Sector
											<?php echo $match->get_SWSector_ID(); ?>
											<?php echo $match->get_SWSector()->get_SectorName(); ?>
										</div>
									</td>
																				
									<td valign="top">
										<div class="copy">
										<?php if($match->get_SWSquad1() != $_SESSION['squadid']): ?>
											<a href="mailto:<?php echo $match->get_Challenger()->get_info()->get_Squad_Email(); ?>"><?php echo $match->get_Challenger()->get_info()->get_Squad_Email(); ?></a>
										<?php else: ?>
											<a href="mailto:<?php echo $match->get_Challenged()->get_info()->get_Squad_Email(); ?>"><?php echo $match->get_Challenged()->get_info()->get_Squad_Email(); ?></a>
										<?php endif; ?>
										</div>
									</td>
									<td align="left" valign="top">
										<?php $info = $match->get_info();
											$current_phase = $info->get_current_phase();
										?>
										<div class="copy">
											<?php if($current_phase < 4): ?>
												Scheduling
												<?php if($current_phase < 2): ?> - Created
												<?php else: ?> - Phase <?php echo $current_phase; ?>
												<?php endif; ?>
											<?php else: ?>
												<span<?php if(time() > strtotime($info->get_final_match_time())): ?> style="color:red;"<?php endif; ?>><?php echo date('F j, Y g:m A', strtotime($info->get_final_match_time())); ?></span>
											<?php endif; ?>
										</div>
									</td>
								</tr>
							<?php endforeach; ?>
							<tr<?php if(++$coloredrow %2): ?> bgcolor="#0B160D"<?php endif; ?>>
								<td colspan="6" align="center">
									<div class="copy">There are <?php echo count($get_matches); ?> matches pending for this squad.</div>
								</td>
							</tr>
						<?php else: ?>
							<tr><td colspan="6"><div class="title">Your squad has no pending matches in this league.</div></td></tr>
						<?php endif; ?>
						</table>

				<?php // END MAIN PAGE INFO ?>
<?php include(BASE_PATH.'doc_bot.php'); ?>

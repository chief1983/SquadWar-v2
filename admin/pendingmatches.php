<?php /*
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
include('../bootstrap.php');

util::prepend_title('Pending Matches Admin');

$rec = match_api::new_search_record();
$ret = match_api::search($rec);
$ret = match_api::populate_info($ret);
$ret = match_api::populate_sectors($ret);
$ret = match_api::populate_squads($ret, true);
$get_matches = $ret->get_results();

include(BASE_PATH.'doc_top.php');

include(BASE_PATH.'admin/menu/main.php');

define('HIDELOGIN',1);
include(BASE_PATH.'doc_mid.php');
				// MAIN PAGE INFO
?>
<div class="copy">
Current game time is: <?php echo time(); ?>
</div>
						<table width="98%" cellpadding="1" cellspacing="0" border="0">
							<tr><td colspan="8" align="center"><div class="title">Pending Matches</div></td></tr>
						<?php if(count($get_matches)):
							$coloredrow = 0; ?>
								<?php foreach($get_matches as $match): ?>
									<tr<?php if(++$coloredrow % 2): ?> bgcolor="#0B160D"<?php endif; ?>>
										<td>
											<div class="copy">
												<span<?php if(strtotime($match->get_info()->get_time_created()) < time() - 7 * 24 * 60 * 60): ?> style="color:red;"<?php endif; ?>>
												<b><?php echo date('n.j.y', strtotime($match->get_info()->get_time_created())); ?></b>
												</span>
											</div>
										</td>
										<td><div class="copy">&nbsp;<?php echo $match->get_SWCode(); ?>&nbsp;</div></td>
										<td>
											<div class="copy">
												<a href="_award_match.php?code=<?php echo $match->get_SWCode(); ?>&amp;sector=<?php echo $match->get_SWSector_ID(); ?>&amp;league=<?php echo $match->get_League_ID(); ?>&amp;first=<?php echo $match->get_SWSquad1(); ?>&amp;second=<?php echo $match->get_SWSquad2(); ?>&amp;winner=<?php echo $match->get_SWSquad1(); ?>&amp;loser=<?php echo $match->get_SWSquad2(); ?>"><?php echo $match->get_Challenger()->get_SquadName(); ?></a>
												Challenged
												<a href="_award_match.php?code=<?php echo $match->get_SWCode(); ?>&amp;sector=<?php echo $match->get_SWSector_ID(); ?>&amp;league=<?php echo $match->get_League_ID(); ?>&amp;first=<?php echo $match->get_SWSquad1(); ?>&amp;second=<?php echo $match->get_SWSquad2(); ?>&amp;winner=<?php echo $match->get_SWSquad2(); ?>&amp;loser=<?php echo $match->get_SWSquad1(); ?>"><?php echo $match->get_Challenged()->get_SquadName(); ?></a>
												for control of
												<?php if(!$match->get_SWSector()->get_SectorSquad()): ?><b>Unclaimed</b><?php endif; ?>
												Sector
												<?php echo $match->get_SWSector_ID(); ?>
												<?php echo $match->get_SWSector()->get_SectorName(); ?>
											</div>
										</td>
										<td>
											<div class="copy">&nbsp;<?php echo $match->get_SWSquad1(); ?>&nbsp;</div>
										</td>
										<td>
											<div class="copy">
												<a href="mailto:<?php echo $match->get_Challenger()->get_info()->get_Squad_Email(); ?>"><?php echo $match->get_Challenger()->get_info()->get_Squad_Email(); ?></a>
											</div>
										</td>
										<td>
											<div class="copy">&nbsp;<?php echo $match->get_SWSquad2(); ?>&nbsp;</div>
										</td>												
										<td>
											<div class="copy">
												<a href="mailto:<?php echo $match->get_Challenged()->get_info()->get_Squad_Email(); ?>"><?php echo $match->get_Challenged()->get_info()->get_Squad_Email(); ?></a>
											</div>
										</td>		
										<td><div class="copy"><a href="delete_match.php?id=<?php echo $match->get_SWCode(); ?>">delete</a></div></td>										
									</tr>
								<?php endforeach; ?>
								<tr<?php if(++$coloredrow % 2): ?> bgcolor="#0B160D"<?php endif; ?>>
									<td colspan="8" align="center">
										<div class="copy">There are <?php echo count($get_matches); ?> matches pending in this league</div>
									</td>
								</tr>
						<?php endif; ?>
						</table>
					
				<?php // END MAIN PAGE INFO ?>
<?php include(BASE_PATH.'doc_bot.php'); ?>

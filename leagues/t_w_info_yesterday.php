<?php /*
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
$rec = squad_api::new_matchhistory_search_record();
$rec->set_League_ID($leagueid);
$rec->set_match_time_oldest(time() - 172800); // Only get matches newer than 48 hours ago
$rec->set_sort_by('match_time');
$rec->set_sort_dir('DESC');
$ret = squad_api::search($rec);
$ret = squad_api::populate_matchhistory_squads($ret);
$ret = squad_api::populate_matchhistory_sectors($ret);
$get_matches = $ret->get_results();
?>

						<table width="90%" cellpadding="0" cellspacing="0" border="0">
						<?php if(count($get_matches)):
							$coloredrow = 0;
							$thisdate = ''; ?>
							<tr><td colspan="3" align="center"><div class="title">Results from the Last 48 Hours</div></td></tr>
							<?php foreach($get_matches as $match): ?>
								<tr<?php if(++$coloredrow % 2): ?> bgcolor="#0B160D"<?php endif; ?>>
									<td>
										<div class="copy">
											<?php if($thisdate != date('m.d.Y', $match->get_match_time() - 21600)): ?>
												<b><?php echo date('m.d.Y', $match->get_match_time() - 21600); ?></b>
											<?php else: ?>
												&nbsp;
											<?php endif; ?>
											<?php $thisdate = date('m.d.Y', $match->get_match_time() - 21600); ?>
										</div>
									</td>
									<td>&nbsp;&nbsp;</td>
									<td>
										<div class="copy">
											<?php if($_SESSION['loggedin']): ?><a href="squads/squadinfo.php?id=<?php echo $match->get_match_victor(); ?>&amp;leagueid=<?php echo $leagueid; ?>"><?php endif; echo $match->get_Squad1()->get_SquadName(); if($_SESSION['loggedin']): ?></a><?php endif; ?>
											Defeated
											<?php if($_SESSION['loggedin']): ?><a href="squads/squadinfo.php?id=<?php echo $match->get_match_loser(); ?>&amp;leagueid=<?php echo $leagueid; ?>"><?php endif; echo $match->get_Squad2()->get_SquadName(); if($_SESSION['loggedin']): ?></a><?php endif; ?>
											for control of sector
											<?php echo $match->get_SWSector_ID(); ?>
											<?php echo $match->get_SWSector()->get_SectorName(); ?>
											<?php if($match->get_special() == 1): ?>(forfeit)<?php endif; ?>
										</div>
									</td>
								</tr>
							<?php endforeach; ?>		
						<?php endif; ?>
						</table>

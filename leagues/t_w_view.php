<?php /*
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
$rec = squad_api::new_matchhistory_search_record();
$rec->set_League_ID($leagueid);
$ret = squad_api::search($rec);
$ret = squad_api::populate_matchhistory_squads($ret);
$ret = squad_api::populate_matchhistory_sectors($ret);
$ret = squad_api::populate_matchhistory_info($ret);
$get_matches = $ret->get_results();
?>
	<table width="90%" cellpadding="0" cellspacing="0" border="0" class="center">
	<?php if(count($get_matches)):
		$coloredrow = 0;
		$thisdate = '';
	?>
		<tr><td colspan="3" align="center"><div class="title">League History</div></td></tr>
		<?php foreach($get_matches as $match): ?>
			<tr<?php if(++$coloredrow %2): ?> bgcolor="#0B160D"<?php endif; ?>>
				<td>
					<div class="copy">
						<?php if($thisdate != date('m.d.Y', $match->get_match_time())): ?>
							<b><?php echo date('m.d.Y', $match->get_match_time()); ?></b>
						<?php else: ?>
							&nbsp;
						<?php endif; ?>
						<?php $thisdate = date('m.d.Y', $match->get_match_time()); ?>
					</div>
				</td>
				<td>&nbsp;&nbsp;</td>
				<td>
					<div class="copy">
	
						<?php echo $match->get_Squad1()->get_SquadName(); ?>
						Defeated
						<?php echo $match->get_Squad2()->get_SquadName(); ?>
						for control of Sector 
						<?php echo $match->get_SWSector_ID(); ?>
						<?php echo $match->get_SWSector()->get_SectorName(); ?>
						
						<?php if($match->get_special()): ?><b>(forfeit)</b><?php endif; ?>
					</div>
				</td>
			</tr>
		<?php endforeach; ?>
			<tr<?php if(++$coloredrow %2): ?> bgcolor="#0B160D"<?php endif; ?>>
				<td colspan="3" align="center">
					<div class="copy">There have been <?php echo count($get_matches); ?> matches played in this league.</div>
				</td>
			</tr>
	<?php endif; ?>
	</table>

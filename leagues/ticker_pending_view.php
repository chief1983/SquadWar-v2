<?php /* 
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
$rec = match_api::new_search_record();
$rec->set_League_ID($leagueid);
$ret = match_api::search($rec);
$ret = match_api::populate_squads($ret);
$ret = match_api::populate_sectors($ret);
$ret = match_api::populate_info($ret);
// $ret = match_api::sort_by_Time_Created($ret);
$get_matches = $ret->get_results();
?>
	<table width="90%" cellpadding="0" cellspacing="0" border="0" class="center">
	<?php if(count($get_matches)):
		$coloredrow = 0;
		$thisdate = ''; ?>
		<tr><td colspan="3" align="center"><div class="title">Pending Matches</div></td></tr>
		<?php foreach($get_matches as $match): ?>
		<tr<?php if(++$coloredrow % 2): ?> bgcolor="#0B160D"<?php endif; ?>>
			<td>
				<div class="copy"<?php if(strtotime($match->get_info()->get_Time_Created()) < time() - 60*60*24*7): ?> style="color:red;"<?php endif; ?>>
					<b><?php echo date('n.j.y', strtotime($match->get_info()->get_Time_Created())); ?></b>
				</div>
			</td>
			<td>&nbsp;&nbsp;</td>
			<td>
				<div class="copy">

					<?php echo $match->get_Challenger()->get_SquadName(); ?>
					Challenged
					<?php echo $match->get_Challenged()->get_SquadName(); ?>
					for control of Sector
					<?php echo $match->get_SWSector_ID(); ?>
					<?php echo $match->get_SWSector()->get_SectorName(); ?>
				</div>
			</td>
			<td align="left">
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
						<?php echo date('F j, Y g:m A', $info->get_final_match_time()); ?>
					<?php endif; ?>
				</div>
			</td>
		</tr>
		<?php endforeach; ?>
		<tr<?php if(++$coloredrow % 2): ?> bgcolor="#0B160D"<?php endif; ?>>
			<td colspan="3" align="center">
				<div class="copy">There are <?php echo count($get_matches); ?> matches pending in this league</div>
			</td>
		</tr>
	<?php endif; ?>
	</table>

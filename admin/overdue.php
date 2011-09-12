<?php /*
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
include('../bootstrap.php');

$rec = match_api::new_search_record();
$rec->set_SWCode($_GET['id']);
$ret = match_api::search($rec);
$ret = match_api::populate_info($ret);
$ret = util::sort_results_on_child_data($ret, 'info', 'time_created', array(SORT_STRING));
$get_pending_matches = $ret->get_results();


?>
<table border="1" cellspacing="0">
	<tr>
		<td>SWCode</td>
		<td>time_created</td>
		<td>SWSquad1</td>
		<td>SWSquad2</td>
		<td>SWSector_ID</td>
		<td>League_ID</td>
		<td>match_time1</td>
		<td>match_time2</td>
		<td>proposed_final_time</td>
		<td>proposed_alternate_time</td>
		<td>squad_last_proposed</td>
		<td>final_match_time</td>
		
		<td>dispute</td>
		<td>status_last_changed</td>
		<td>phase</td>
	</tr>
<?php foreach($get_pending_matches as $match): ?>
		<tr>
			<td><?php echo $match->get_SWCode(); ?>&nbsp;</td>
			<td><?php echo $match->get_info()->get_time_created(); ?>&nbsp;</td>
			<td><?php echo $match->get_SWSquad1(); ?>&nbsp;</td>
			<td><?php echo $match->get_SWSquad2(); ?>&nbsp;</td>
			<td><?php echo $match->get_SWSector_ID(); ?>&nbsp;</td>
			<td><?php echo $match->get_League_ID(); ?>&nbsp;</td>
			<td><?php echo $match->get_info()->get_match_time1(); ?>&nbsp;</td>
			<td><?php echo $match->get_info()->get_match_time2(); ?>&nbsp;</td>
			<td><?php echo $match->get_info()->get_proposed_final_time(); ?>&nbsp;</td>
			<td><?php echo $match->get_info()->get_proposed_alternate_time(); ?>&nbsp;</td>
			<td><?php echo $match->get_info()->get_squad_last_proposed(); ?>&nbsp;</td>
			<td><?php echo $match->get_info()->get_final_match_time(); ?>&nbsp;</td>

			<td><?php echo $match->get_info()->get_dispute(); ?>&nbsp;</td>
			<td><?php echo $match->get_info()->get_status_last_changed(); ?>&nbsp;</td>
			<td>
				<?php $current_phase = $match->get_info()->get_current_phase(); ?>
				<?php if($current_phase < 4): ?>
					<?php if($current_phase == 1): ?> Created
					<?php elseif($current_phase < 4): ?> Phase <?php echo $current_phase; ?>
					<?php endif; ?>
				<?php else: ?>
					<?php echo date('F j, Y g:i A', strtotime($match->get_info()->get_final_match_time())); ?>
				<?php endif; ?>
			</td>
		</tr>
<?php endforeach; ?>
</table>

<table border="0">
<?php foreach($get_pending_matches as $match): ?>
	<tr>
		<td><?php echo $match->get_info()->get_time_created(); ?></td>
		<td><?php echo $match->get_SWCode(); ?></td>
		<td>
				<?php if($current_phase < 4): ?>
					<?php if($current_phase == 1): ?> Created
					<?php elseif($current_phase < 4): ?> Phase <?php echo $current_phase; ?>
					<?php endif; ?>
				<?php else: ?>
					Phase 4
					<?php echo date('F j, Y g:i A', strtotime($match->get_info()->get_final_match_time())); ?>
				<?php endif; ?>

				<?php
					$overdue = 0;
					$pastdue = 0;
					if($current_phase == 2 && strtotime($match->get_info()->get_status_last_changed()) < time() - 2 * 24 * 60 * 60): $overdue = 1; endif;
					if($current_phase >= 4 && strtotime($match->get_info()->get_final_match_time()) < time() - 2 * 24 * 60 * 60): $overdue = 1; $pastdue = 1; endif;
					if($match->get_info()->get_status_last_changed() != '0000-00-00 00:00:00'):
						if($current_phase < 4 && strtotime($match->get_info()->get_status_last_changed()) < time() - 2 * 24 * 60 * 60):
							$overdue = 1;
						endif;
				?>hooo
				<?php
					else:
				?>whee!
				<?php
						if(strtotime($match->get_info()->get_time_created()) <= time() - 24 * 60 * 60): $overdue = 1; endif;
					endif;
				?>
			</td>
			<td>
				<?php if($overdue): ?>
					<?php if($pastdue): ?>pastdue<?php else: ?>overdue<?php endif; ?>
				<?php endif; ?>
			</td>
	</tr>
<?php endforeach; ?>
</table>	

<?php
$winner = '';
$loser = '';
?>
	<br />
<?php if($overdue): ?>
<?php foreach($get_pending_matches as $match): ?>
	<?php
	switch($current_phase)
	{
		case 1: ?>
		This match has only been created.<br />
		This match should be forfeit in favor of the defender.<br />
		<?php
		$winner = $match->get_SWSquad2();
		$loser = $match->get_SWSquad1();
		break;
		case 2: ?>
		This match is in phase 2.<br />
		This match should be forfeit in favor of the challenger.<br />
		<?php
		$winner = $match->get_SWSquad1();
		$loser = $match->get_SWSquad2();
		break;
		case 3: ?>
		This match is in phase 3.<br />
		This match should be forfeit in favor of the defender.<br />
		<?php
		$winner = $match->get_SWSquad2();
		$loser = $match->get_SWSquad1();
		break;
		case 4: ?>
		This match is in phase 4.<br />
		Either team has 48 hours to report a no-show or request a reschedule.<br />
		<?php
		if($pastdue):
			$winner = $match->get_SWSquad1();
			$loser= $match->get_SWSquad2();
		endif;
		break;
	}
	?>

	<?php if(!empty($winner)): ?>
		<br />
		This match requires an update.
	<?php endif; ?>
	<hr />
<?php endforeach; ?>
<?php endif; ?>

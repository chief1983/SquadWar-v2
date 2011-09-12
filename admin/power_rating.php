<?php /*
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
include('../bootstrap.php');

$document_title = 'SquadWar - Power Rating';

$inc = 200;

$last = (!empty($_GET['last'])) ? $_GET['last'] : 0;

$rec = squad_api::new_search_record();
$rec->set_Active(1);
//$rec->set_min_SquadID($last);
//$rec->set_max_SquadID($last + $inc);
$rec->set_sort_by('SquadName');
$rec->set_sort_dir('ASC');
$ret = squad_api::search($rec);
$ret = squad_api::populate_match_history($ret);
$ret = squad_api::populate_sectors($ret);
$ret = squad_api::populate_info($ret);
$ret = util::sort_results_on_child_count($ret, 'sectors', array(SORT_DESC));
$rank_count = $ret->get_results();

$opponent_list = array();
$R2_sum = array();
$r2 = array();
foreach($rank_count as $key => $squad)
{
	$wins = count($squad->get_matches_won());
	$lost = count($squad->get_matches_lost());
	$totalmatches[$key] = $wins + $lost;

	if($totalmatches[$key])
	{
		$opponents = array();
		foreach(array_merge($squad->get_matches_won(), $squad->get_matches_lost()) as $match)
		{
			$opponent_list[$key][] = ($match->get_match_victor() == $squad->get_SquadID()) ? $match->get_match_loser() : $match->get_match_victor();
		}

		$rec = squad_api::new_search_record();
		$rec->set_SquadID($opponent_list[$key]);
		$ret = squad_api::search($rec);
		$ret = squad_api::populate_info($ret);
		$get_opponents = $ret->get_results();

		$R2_sum[$key] = 0;
		foreach($get_opponents as $opponent)
		{
			$R2_sum[$key] += $opponent->get_info()->get_power_rating();
		}

		$r2[$key] = $R2_sum[$key] / count($opponent_list[$key]);

		$power_rating2[$key] = number_format( $squad->get_info()->get_win_loss() + $r2[$key] / 2, 4 );
		$power_rating[$key] = number_format( ($squad->get_info()->get_win_loss() + $r2[$key] / 2) * sqrt($totalmatches[$key]), 4 );
	}
	else
	{
		$power_rating2[$key] = 0;
		$power_rating[$key] = 0;
	}

	$info = $squad->get_info();
	$info->set_power_rating($power_rating[$key]);
	$status = $info->save();
	$squad->set_info($info);
}

// util::location($_SERVER['PHP_SELF'].'?last='.($last + $inc));

include(BASE_PATH.'doc_top.php');

include(BASE_PATH.'menu/main.php');

define('HIDELOGIN',1);
include(BASE_PATH.'doc_mid.php');
				// MAIN PAGE INFO
?>
<br />
<br />

<div class="copy">
Current game time is: <?php echo time(); ?>
</div>

<?php if(count($rank_count)): ?>
<br />
<table border="1">
	<tr><td>Squad ID</td><td>Squad Name</td><td>Win/Loss</td><td>R2_sum</td><td>Num Opponents</td><td>R2</td><td>Num sectors</td><td>Total Matches</td><td>Power Rating 2</td><td>Power Rating</td></tr>
<?php foreach($rank_count as $key => $squad): ?>
	<tr>
		<td><div class="copy"><?php echo $squad->get_SquadID(); ?></div></td>
		<td><div class="copy"><?php echo $squad->get_SquadName(); ?></div></td>

			<?php if($totalmatches[$key]): ?>
					<td><div class="copy"><?php echo $squad->get_info()->get_win_loss(); ?></div></td>
					<td><div class="copy"><?php echo $R2_sum[$key]; ?></div></td>
					<td><div class="copy"><?php echo count($opponent_list[$key]); ?></div></td>
					<td><div class="copy"><?php echo $r2[$key]; ?></div></td>
					<td><div class="copy"><?php echo count($squad->get_sectors()); ?></div></td>
			<?php endif; ?>
			<td><div class="copy"><?php echo $totalmatches[$key]; ?></div></td>
			<td><div class="copy"><?php echo $power_rating2[$key]; ?></div></td>
			<td><div class="copy"><?php echo $power_rating[$key]; ?></div></td>
	</tr>
<?php endforeach; ?>
</table>
<?php
/* ?>
<div class="copy"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?last=<?php echo $last + $inc; ?>">Next <?php echo $inc; ?></a></div>
<?php else: ?>
<div class="copy">done.</div>
<?php */ endif; ?>

				<?php // END MAIN PAGE INFO ?>
<?php include(BASE_PATH.'doc_bot.php'); ?>

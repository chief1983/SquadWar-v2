<?php /*
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
include('../bootstrap.php');

$document_title = 'SquadWar - Win-Loss';

$rec = squad_api::new_search_record();
$rec->set_Active(1);
$rec->set_sort_by('SquadName');
$rec->set_sort_dir('ASC');
$ret = squad_api::search($rec);
$ret = squad_api::populate_match_history($ret);
$ret = squad_api::populate_sectors($ret);
$ret = squad_api::populate_info($ret);
$ret = util::sort_results_on_child_count($ret, 'sectors', array(SORT_DESC));
$rank_count = $ret->get_results();

foreach($rank_count as $squad)
{
	$wins = count($squad->get_matches_won());
	$lost = count($squad->get_matches_lost());
	$totalmatches = $wins + $lost;
	$win_loss = ($totalmatches) ? number_format($wins / $totalmatches, 4) : 0;

	$info = $squad->get_info();
	$info->set_win_loss($win_loss);
	$status = $info->save();
}

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
<br />
<table>
<?php foreach($rank_count as $squad):
	$wins = count($squad->get_matches_won());
	$lost = count($squad->get_matches_lost());
	$totalmatches = $wins + $lost;
	$win_loss = ($totalmatches) ? number_format($wins / $totalmatches, 4) : 0; ?>
	<tr>
		<td><div class="copy"><?php echo $squad->get_SquadName(); ?></div></td>
		<td><div class="copy"><?php echo $wins; ?></div></td>
		<td><div class="copy"><?php echo $totalmatches; ?></div></td>
		<td><div class="copy"><?php echo $win_loss; ?></div></td>
	</tr>
<?php endforeach; ?>
</table>

				<?php // END MAIN PAGE INFO ?>
<?php include(BASE_PATH.'doc_bot.php'); ?>

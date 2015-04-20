<?php /* 
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
include('bootstrap.php');

$title = '';
if(!empty($_GET['leagueid']))
{
	$leagueid = $_GET['leagueid'];
	$rec = squad_api::new_search_record();
	$rec->set_league($leagueid);
	$ret = squad_api::search($rec);
	$get_league_squads = $ret->get_results();
	if(!empty($get_league_squads))
	{
		$get_league = league_api::get($leagueid);
		$title = $get_league->get_Title().' ';

		$rec = squad_api::new_search_record();
		$rec->set_league($leagueid);
		$rec->set_active(1);
		$rec->set_has_sectors(1);
		$ret = squad_api::search($rec);
		$ret = squad_api::populate_info($ret);
		$ret = squad_api::populate_match_history($ret);
		$ret = squad_api::populate_league_match_history($ret, $leagueid);
		$ret = squad_api::populate_league_sectors($ret, $leagueid);
		$ret = squad_api::populate_matches($ret);
		$ret = squad_api::populate_league_matches($ret, $leagueid);
		$squadgroup['Squadrons hold Systems in this League'] = $ret->get_results();

		$rec->set_has_sectors(0);
		$ret = squad_api::search($rec);
		$ret = squad_api::populate_info($ret);
		$ret = squad_api::populate_match_history($ret);
		$ret = squad_api::populate_league_match_history($ret, $leagueid);
		$ret = squad_api::populate_league_sectors($ret, $leagueid);
		$ret = squad_api::populate_matches($ret);
		$ret = squad_api::populate_league_matches($ret, $leagueid);
		$squadgroup['Squadrons hold no Systems in this League'] = $ret->get_results();
	}
}

util::prepend_title("{$title}League");

include(BASE_PATH.'doc_top.php');

define('LEAGUE',1);
include(BASE_PATH.'menu/main.php');

define('HIDELOGIN',1);
include(BASE_PATH.'doc_mid.php');
				// MAIN PAGE INFO
?>

<?php if(!empty($leagueid)): ?>
		<?php if(!empty($get_league_squads)): ?>

			<?php include('leagues/t_w.php');
			include('leagues/map.php');
			include('leagues/ticker_pending.php'); ?>
			<br />
			<?php include('leagues/t_w_info_yesterday.php'); ?>

			<br />

			<?php
			$looprank = 1;
			$lastranktotal = 1;
			$actualnumber = 0;
			$top_ranking_display = 10;
			?>
			<?php foreach($squadgroup as $group => $rank_count): ?>
				<?php if(count($rank_count)): ?>
				<br />
				<table width="90%" cellpadding="0" cellspacing="0" border="0" class="center">
					<tr><td align="center" colspan="9"><div class="title"><?php echo count($rank_count); ?> <?php echo $group; ?></div></td></tr>
					<tr>
						<td align="left"><div class="copy">&nbsp;</div></td>
						<td align="left"><div class="copy"><b>&nbsp;Rank&nbsp;</b>&nbsp;</div></td>
						<td><div class="copy" align="center">&nbsp;<b>Squad Name</b>&nbsp;</div></td>
						<td><div class="copy" align="center">&nbsp;<b>Power</b>&nbsp;</div></td>
						<td><div class="copy" align="center">&nbsp;<b>Systems</b>&nbsp;</div></td>
						<td><div class="copy" align="center">&nbsp;<b>League Record</b>&nbsp;</div></td>
						<td><div class="copy" align="center">&nbsp;<b>Overall Record</b>&nbsp;</div></td>	
						<td><div class="copy" align="center">&nbsp;<b>Members</b>&nbsp;</div></td>
						<td><div class="copy" align="center">&nbsp;<b>Chall.</b>&nbsp;</div></td>	
						<td><div class="copy" align="center">&nbsp;<b>Defend</b>&nbsp;</div></td>
					</tr>
					<?php
						$coloredrow = 0;
						foreach($rank_count as $squad):
						$actualnumber++;
						$wins = count($squad->get_matches_won());
						$lost = count($squad->get_matches_lost());
						$totalmatches = $wins + $lost;
						$l_wins = count($squad->get_l_matches_won());
						$l_lost = count($squad->get_l_matches_lost());
						$l_totalmatches = $l_wins + $l_lost;
						$info = $squad->get_info();
						$teamcolor = $info->get_teamcolor();
						$count_of_sw_code_challenger = count($squad->get_l_matches_challenger());
						$count_of_sw_code_defender = count($squad->get_l_matches_defender());
						$count_of_sw_code = count($squad->get_matches_challenger()) + count($squad->get_matches_defender());
						if(++$coloredrow % 2):
							if($looprank <= $top_ranking_display):
								?><tr bgcolor="#0B160D"><?php
							else:
								?><tr bgcolor="#161616"><?php
							endif;
						else:
							?><tr><?php
						endif; ?>
							<td width="5" bgcolor="#<?php echo $teamcolor; ?>" align="right"><img src="images/spacer.gif" width="5" height="3" alt=" " border="0" /></td>
							<td align="right"><div class="copy"><b><?php echo $looprank++; ?></b>&nbsp;</div></td>
							<td align="left"><div class="copy">&nbsp;<?php if($_SESSION['loggedin']): ?><a href="squads/squadinfo.php?id=<?php echo $squad->get_SquadID(); ?>&amp;leagueid=<?php echo $leagueid; ?>"><?php endif; echo $squad->get_SquadName(); if($_SESSION['loggedin']): ?></a><?php endif; ?></div></td>
							<td align="center"><div class="copy"><?php if($totalmatches): echo number_format($info->get_power_rating(),4); else: ?>&nbsp;<?php endif; ?></div></td>
							<td align="center"><div class="copy"><?php if(count($squad->get_l_sectors()) > 0): echo count($squad->get_l_sectors()); else: ?>&nbsp;<?php endif; ?></div></td>
							<td align="center"><div class="copy"><?php if($totalmatches): echo $l_wins; ?>  - <?php echo $l_lost; else: ?>&nbsp;<?php endif; ?></div></td>
							<td align="center"><div class="copy"><?php if($totalmatches): echo $wins; ?> - <?php echo $lost; else: ?>&nbsp;<?php endif; ?></div></td>												
							<td align="center"><div class="copy"><?php echo count($squad->get_SquadMembers()); ?></div></td>
							<td align="right"><div class="copy"><?php if(!$count_of_sw_code): ?>&nbsp;<?php else: echo $count_of_sw_code_challenger; endif; ?></div></td>
							<td align="right"><div class="copy"><?php if(!$count_of_sw_code): ?>&nbsp;<?php else: echo $count_of_sw_code_defender; endif; ?></div></td>
						</tr>
						<?php $lastranktotal = count($squad->get_sectors()); ?>
					<?php endforeach; // rank_count ?>
				</table>
				<?php endif; ?>
			<?php endforeach; // squadgroup?>
		<?php else: ?>
			<div class="title">No Squads have registered for this league.</div>
		<?php endif; ?>

<?php endif; ?>

				<?php // END MAIN PAGE INFO ?>
<?php include(BASE_PATH.'doc_bot.php'); ?>

<?php /*
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell
   or otherwise commercially exploit the source or things you created based on the
   source.
*/
define('SECURE',1);
include('../bootstrap.php');

if(!empty($_GET['id']))
{
	$id = $_GET['id'];
}
else
{
	// No ID, what to do?
}

$rec = squad_api::new_search_record();
$rec->set_SquadID($id);
$rec->set_Active(1);
$ret = squad_api::search($rec);
$ret = squad_api::populate_info($ret);
$ret = squad_api::populate_match_history($ret);
$get_squad = reset($ret->get_results());
$get_squad_info = $get_squad->get_info();

$validdata = 0;
if($get_squad_info)
{
	$get_squad_leader = user_api::get($get_squad_info->get_Squad_Leader_ID());
	if($get_squad_leader)
	{
		$validdata = 1;
	}
}

$rec = user_api::new_search_record();
$rec->set_id($get_squad->get_squadmembers());
$ret = user_api::search($rec);
$get_pilots = $ret->get_results();

if(!empty($_GET['leagueid']))
{
	$leagueid = $_GET['leagueid'];

	$rec = match_api::new_search_record();
	$rec->set_either_squad($id);
	$rec->set_League_ID($leagueid);
	$ret = match_api::search($rec);
	$ret = match_api::populate_sectors($ret);
	$ret = match_api::populate_squads($ret);
	$get_matches = $ret->get_results();

	$rec = squad_api::new_sector_search_record();
	$rec->set_SectorSquad($id);
	$rec->set_League_ID($leagueid);
	$ret = squad_api::search($rec);
	$get_sectors = $ret->get_results();

	$rec = squad_api::new_matchhistory_search_record();
	$rec->set_League_ID($leagueid);
	$rec->set_either_squad($id);
	$ret = squad_api::search($rec);
	$ret = squad_api::populate_matchhistory_sectors($ret);
	$ret = squad_api::populate_matchhistory_squads($ret);
	$match_history = $ret->get_results();
}

util::prepend_title($get_squad->get_SquadName());
util::prepend_title('Squad Info');

include(BASE_PATH.'doc_top.php');

include(BASE_PATH.'menu/main.php');

include(BASE_PATH.'doc_mid.php');
				// MAIN PAGE INFO
?>

					<div class="title">Squad Information</div>

					 	<table>
					 		<tr>
					 			<td valign="top" align="right">
					 				<div class="copy">Team Name:</div>
					 			</td>
					 			<td valign="top" align="left">
					 				<div class="copy"><a href="squadlogin.php?id=<?php echo $id; ?><?php if(!empty($leagueid)): ?>&amp;leagueid=<?php echo $leagueid; endif; ?>"><?php echo $get_squad->get_SquadName(); ?></a></div>
					 			</td>
								</tr>
					 		<tr>
					 			<td valign="top" align="right">
					 				<div class="copy">Squad Leader:</div>
					 			</td>
					 			<td valign="top" align="left">
					 				<div class="copy"><?php if(!empty($get_squad_leader)): echo $get_squad_leader->get_user_name(); else: ?>*yet to be determined<?php endif; ?></div>
					 			</td>
					 		</tr>
					 		<tr>
					 			<td valign="top" align="right">
					 				<div class="copy">Squad Email:</div>
					 			</td>
					 			<td valign="top" align="left">
					 				<div class="copy"><a href="mailto:<?php echo $get_squad_info->get_Squad_Email(); ?>"><?php echo $get_squad_info->get_Squad_Email(); ?></a></div>
					 			</td>
					 		</tr>
					 		<tr>
					 			<td valign="top" align="right">
					 				<div class="copy">Squad Leader ICQ:</div>
					 			</td>
					 			<td valign="top" align="left">
					 				<div class="copy"><?php if($validdata && $get_squad_info->get_Squad_Leader_ICQ() != ''): echo $get_squad_info->get_Squad_Leader_ICQ(); else: ?>*yet to be determined<?php endif; ?></div>
					 			</td>
					 		</tr>
					 		<tr>
					 			<td valign="top" align="right">
					 				<div class="copy">Squad IRC:</div>
					 			</td>
					 			<td valign="top" align="left">
					 				<div class="copy"><?php if($validdata && $get_squad_info->get_Squad_IRC() != ''): echo $get_squad_info->get_Squad_IRC(); else: ?>*yet to be determined<?php endif; ?></div>
					 			</td>
					 		</tr>
					 		<tr>
					 			<td valign="top" align="right">
					 				<div class="copy">Web Link:</div>
					 			</td>
					 			<td valign="top" align="left">
					 				<div class="copy"><?php if($validdata && $get_squad_info->get_Squad_Web_Link() != ''): ?><a href="<?php echo $get_squad_info->get_Squad_Web_Link(); ?>"><?php echo $get_squad_info->get_Squad_Web_Link(); ?></a><?php else: ?>*yet to be determined<?php endif; ?></div>
					 			</td>
					 		</tr>
							<tr>
					 			<td valign="top" align="right">
					 				<div class="copy">Roster:</div>
					 			</td>
					 			<td align="left">
					 				<?php if(!empty($get_pilots)): ?>
					 					<div class="copy"><?php
					 						foreach($get_pilots as $pilot):
					 							?><a href="mailto:<?php echo $pilot->get_email(); ?>"><?php echo $pilot->get_user_name(); ?></a><br /><?php
					 						endforeach; ?></div>
					 				<?php else: ?>
					 					<div class="copy">*yet to be determined</div>
					 				<?php endif; ?>
					 			</td>
					 		</tr>
					 	</table>
					<br />
					<div class="title">Ribbons and Medals:</div>
					<br />
					<?php if($get_squad_info->get_ribbon_1()): ?><img src="<?php echo RELATIVEPATH; ?>images/ribbons/swr_1.gif" width="80" height="20" alt="SquadWar Pre-Registered Squadron" border="0" /><?php endif; ?>
					<?php if($get_squad_info->get_ribbon_2()): ?><img src="<?php echo RELATIVEPATH; ?>images/ribbons/swr_2.gif" width="80" height="20" alt="SquadWar Founding Member" border="0" /><?php endif; ?>
					<?php if($get_squad_info->get_ribbon_3()): ?><img src="<?php echo RELATIVEPATH; ?>images/ribbons/swr_3.gif" width="80" height="20" alt="SquadWar First Match Participant" border="0" /><?php endif; ?>
					<?php if($get_squad_info->get_ribbon_4()): ?><img src="<?php echo RELATIVEPATH; ?>images/ribbons/swr_4.gif" width="80" height="20" alt="SquadWar First Match Victor" border="0" /><?php endif; ?>
					<?php if($get_squad_info->get_ribbon_5()): ?><img src="<?php echo RELATIVEPATH; ?>images/ribbons/swr_5.gif" width="80" height="20" alt="Defeated V_Summoner (Volition Squad)" border="0" /><?php endif; ?>
					<?php if($get_squad_info->get_ribbon_6()): ?><img src="<?php echo RELATIVEPATH; ?>images/ribbons/swr_6.gif" width="80" height="20" alt="Defeated escentDay ourFay (Volition Squad)" border="0" /><?php endif; ?>

					<br /><?php if($get_squad_info->get_medal_1()): ?><img src="<?php echo RELATIVEPATH; ?>images/ribbons/swm_1.jpg" width="101" height="214" alt="SquadWar Medal for Exceptional Conduct" border="0" /><?php endif; ?><br />

					<?php // GET MATCHES ?>
					<?php if(!empty($leagueid)): ?>
					 						<br />
					 						<div class="title">Pending Matches in This League:</div>
					 						<br />
					 						<?php if(!count($get_matches)): ?>
					 							<div class="copy">No matches at this time.</div>
					 							<?php else: ?>
					 								<ol>
					 								<?php foreach($get_matches as $match): ?>
					 									<li><div class="copy">Squad
					 										<?php if($match->get_SWSquad1() != $id): ?><a
					 										href="squadinfo.php?id=<?php echo $match->get_SWSquad1(); ?>&amp;leagueid=<?php
					 										echo $leagueid; ?>"><?php
					 										echo $match->get_Challenger()->get_SquadName(); ?></a><?php else:
					 										echo $match->get_Challenger()->get_SquadName(); endif; ?> has challenged
					 										<?php if($match->get_SWSquad2() != $id): ?><a
					 										href="squadinfo.php?id=<?php echo $match->get_SWSquad2(); ?>&amp;leagueid=<?php
															echo $leagueid; ?>"><?php
					 										echo $match->get_Challenged()->get_SquadName(); ?></a><?php else:
					 										echo $match->get_Challenged()->get_SquadName(); endif; ?> for control of <?php
					 										echo $match->get_SWSector()->get_SectorName(); ?>.</div></li>
					 								<?php endforeach; ?>
					 								</ol>
					 						<?php endif; ?>

					 							<br clear="all" />

					 				<?php // GET SECTORS ?>

					 						<div class="title">This Squad Holds the Following Systems in This League:</div>
					 						<br />
					 							<?php if(count($get_sectors)): ?>
						 							<table border="1" style="border:1px solid #5A63F7;" cellspacing="0">
						 							<tr>
						 									<td align="left"><div class="copy"><b>Sector ID</b></div></td>
						 									<td align="left"><div class="copy"><b>System Name</b></div></td>
						 									<td><div class="copy"><b>Held Since</b></div></td>
						 							</tr>
						 							<?php foreach($get_sectors as $sector): ?>
						 								<tr>
						 									<td align="left"><div class="copy">&nbsp;<?php echo $sector->get_SWSectors_ID(); ?></div></td>
						 									<td align="left"><div class="copy">&nbsp;<?php echo $sector->get_SectorName(); ?></div></td>
						 									<td><div class="copy">&nbsp;<?php if($sector->get_SectorTime() == '0'): ?>Creation<?php else: echo date('h:i A m.d.Y T', $sector->get_SectorTime()); endif; ?></div></td>
						 								</tr>
						 							<?php endforeach; ?>
						 							</table>
												<?php else: ?>
													<div class="copy">This team holds no sectors in this league.</div>
												<?php endif; ?>

					 				<?php // END GET MATCHES ?>

					 						<br />
					 						<div class="title">Match History in This League</div>
					 						<br />

											<?php if(!count($match_history)): ?>
					 						<div class="copy">
					 							This squad has no match history yet.
					 						</div>
					 						<?php else: ?>
				 							<table width="90%" border="1" style="border:1px solid #5A63F7;" cellspacing="0" class="center">
				 							<tr>
				 									<td align="left"><div class="copy"><b>System Name</b></div></td>
				 									<td><div class="copy"><b>Victor</b></div></td>
				 									<td><div class="copy"><b>Loser</b></div></td>
				 									<td><div class="copy"><b>Match Time</b></div></td>
				 							</tr>
				 							<?php foreach($match_history as $match): ?>
				 								<tr>
				 									<td align="left"><div class="copy">&nbsp;<?php echo $match->get_SWSector()->get_SectorName(); ?></div></td>
				 									<td><div class="copy">&nbsp;<?php if($match->get_match_victor() == $id): ?><b><?php echo $match->get_Squad1()->get_SquadName(); ?></b><?php else: echo $match->get_Squad1()->get_SquadName(); endif; ?></div></td>
				 									<td><div class="copy">&nbsp;<?php if($match->get_match_loser() == $id): ?><b><?php echo $match->get_Squad2()->get_SquadName(); ?></b><?php else: echo $match->get_Squad2()->get_SquadName(); endif; ?></div></td>
				 									<td><div class="copy">&nbsp;<?php if($match->get_match_time() == '0'): ?>Creation<?php else: echo date('h:i A m.d.Y T', $match->get_match_time()); endif; ?></div></td>
				 								</tr>
				 							<?php endforeach; ?>
				 							</table>
					 						<?php endif; ?>


					 				<?php // END NEW STUFF ?>

					 				<?php // End page info ?>
					<?php endif; ?>

				<?php // END MAIN PAGE INFO ?>
<?php include(BASE_PATH.'doc_bot.php'); ?>

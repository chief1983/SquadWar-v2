<?php /*
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
define('SECURE',1);
include('../bootstrap.php');

$document_title = 'SquadWar - Admin Squad';

// preprocess login stuff
if(!empty($_POST['id']))
{
	$id = $_POST['id'];
}
elseif(!empty($_GET['id']))
{
	$id = $_GET['id'];
}
elseif(!empty($_SESSION['squadid']))
{
	$id = $_SESSION['squadid'];
}
else
{
	util::location('admin.php');
}

if(!empty($_GET['leagueid']))
{
	$leagueid = $_GET['leagueid'];
}

$rec = squad_api::new_search_record();
$rec->set_SquadID($id);
$rec->set_Active(1);
$ret = squad_api::search($rec);
if(count($ret->get_results()))
{
	$ret = squad_api::populate_info($ret);
	if(!empty($leagueid))
	{
		$ret = squad_api::populate_match_history($ret, $leagueid);
	}
	$get_squad = reset($ret->get_results());
	$get_squad_info = $get_squad->get_info();
	$_SESSION['squadid'] = $id;
}
else
{
	unset($_SESSION['squadid']);
}

if(empty($_SESSION['squadid']))
{
	$message = urlencode('You have tried to access a squad which does not exist');
	util::location(RELATIVEPATH.'error/error.php?message='.$message);
}

$validdata = 0;
$get_squad_leader = user_api::get($get_squad_info->get_Squad_Leader_ID());
if(!empty($get_squad_leader))
{
	$validdata = 1;
}

$rec = user_api::new_search_record();
$rec->set_id($get_squad->get_squadmembers());
$ret = user_api::search($rec);
$get_pilots = $ret->get_results();

$valid_login = 0;
if(!empty($_POST['password']))
{
	$_SESSION['squadpassword'] = $_POST['password'];
}
if(!empty($_SESSION['squadpassword']) && $_SESSION['squadpassword'] == $get_squad->get_SquadPassword())
{
	$valid_login = 1;
}

if(!$valid_login)
{
	$message = urlencode('Invalid squad password!');
	util::location(RELATIVEPATH.'error/error.php?message='.$message);
}

$_SESSION['adminlastchosen'] = $id;

if(empty($leagueid))
{
	$rec = league_api::new_search_record();
	$rec->set_squad($id);
	$rec->set_Archived(0);
	$ret = league_api::search($rec);
	$get_leagues = $ret->get_results();
}
else
{
	$get_league = league_api::get($leagueid);
	if(empty($get_league))
	{
		$message = urlencode('Invalid league ID!');
		util::location(RELATIVEPATH.'error/error.php?message='.$message);
	}

	$rec = squad_api::new_search_record();
	$rec->set_league($leagueid);
	$rec->set_Active(1);
	$rec->set_sort_by('SquadName');
	$ret = squad_api::search($rec);
	$ret = squad_api::populate_info($ret);
	$ret = squad_api::populate_league_sectors($ret, $leagueid);
	$ret = squad_api::populate_match_history($ret);
	$ret = squad_api::populate_matches($ret);
	$ret = squad_api::populate_league_match_history($ret, $leagueid);
	$ret = util::sort_results_on_child_data($ret, 'info', 'power_rating', array(SORT_DESC, SORT_NUMERIC));
	$rank_count = $ret->get_results();

	$rec = match_api::new_search_record();
	$rec->set_either_squad($id);
	$rec->set_League_ID($leagueid);
	$ret = match_api::search($rec);
	$ret = match_api::populate_squads($ret);
	$ret = match_api::populate_sectors($ret);
	$get_matches = $ret->get_results();

	$rec = squad_api::new_sector_search_record();
	$rec->set_SectorSquad($id);
	$rec->set_League_ID($leagueid);
	$ret = squad_api::search($rec);
	$get_sectors = $ret->get_results();

	$rec = squad_api::new_matchhistory_search_record();
	$rec->set_either_squad($id);
	$rec->set_League_ID($leagueid);
	$ret = squad_api::search($rec);
	$ret = squad_api::populate_matchhistory_squads($ret);
	$ret = squad_api::populate_matchhistory_sectors($ret);
	$match_history = $ret->get_results();

	// For side menu
	define('LEAGUEADMIN',1);
	$rec = squad_api::new_sector_search_record();
	$rec->set_SectorSquad($_SESSION['squadid']);
	$rec->set_League_ID($_GET['leagueid']);
	$ret = squad_api::search($rec);
	$get_thissquadsectorcount = $ret->get_results();
	if(!count($get_thissquadsectorcount))
	{
		$rec->set_Entry_Node(1);
		$rec->set_SectorSquad(0);
		$ret = squad_api::search($rec);
		$check_entry_nodes = $ret->get_results();
		if(!count($check_entry_nodes))
		{
			$rec = match_api::new_search_record();
			$rec->set_SWSquad1($_SESSION['squadid']);
			$rec->set_League_ID($_GET['leagueid']);
			$ret = match_api::search($rec);
			$check_entry_nodes_challenge = $ret->get_results();
		}
	}
}

// end preprocess login stuff

include(BASE_PATH.'doc_top.php');

include(BASE_PATH.'menu/main.php');

include(BASE_PATH.'doc_mid.php');

				// MAIN PAGE INFO
?>

<div class="title"><?php echo $get_squad->get_SquadName(); ?> Squad Management</div>	
<br />
<?php if(!empty($_GET['message'])): ?>
	<div class="copy"><?php echo $_GET['message']; ?></div>
	<br />
<?php endif; ?>

<?php if(empty($leagueid)): ?>

	<?php if(!empty($get_leagues)): ?>
		<div class="copy"><b>This Squad has signed up for the following leagues:</b></div>
		<div class="copy">
			<p>
			<i>Click on the league name(s) listed below to issue challenges and reach Admin options for that league.</i></p>
			<ul>
				<?php foreach($get_leagues as $league): ?>
					<li><a href="squadlogin_validate.php?id=<?php echo $id; ?>&amp;leagueid=<?php echo $league->get_id(); ?>"><?php echo $league->get_Title(); ?></a></li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>

	<div class="title">Administrative Options:</div>
	<div class="copy">
		<ul>
			<li><a href="join_league.php">Sign up for a league</a></li>
			<li><a href="squad_pending_matches.php">View pending matches for this squad and schedule matches</a></li>
			<li><a href="set_color.php">Set your squad color</a></li>
			<li><a href="edit_squad.php">Edit your squad's information</a></li>
		</ul>
	</div>
	<?php // START PASTE ?>
	<hr noshade="noshade" style="color:#2E5537;" />

	<div class="title">Squad Information</div>

	 	<table>
	 		<tr>
	 			<td valign="top" align="right">
	 				<div class="copy">Team Name:</div>
	 			</td>
	 			<td valign="top" align="left">
	 				<div class="copy"><a href="squadlogin.php?id=<?php echo $id; ?><?php if(!empty($leagueid)): ?>&leagueid=<?php echo $leagueid; endif; ?>"><?php echo $get_squad->get_SquadName(); ?></a></div>
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
						<table>
							<?php foreach($get_pilots as $pilot): ?>
							<tr>
								<td>
								<div class="copy"><a href="mailto:<?php echo $pilot->get_email(); ?>"><?php echo $pilot->get_user_name(); ?></a><br /></div>
								</td> 
								<td>
								<div class="copy"><a href="_remove_member_roster.php?memberid=<?php echo $pilot->get_id(); ?>"><font color="red">remove</font></a></div>
								</td>
							</tr>
							<?php endforeach; ?>
						</table>
 					<?php else: ?>
						<div class="copy">This roster is empty</div>
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

	<br /><?php if($get_squad_info->get_medal_1()): ?><img src="<?php echo RELATIVEPATH; ?>images/ribbons/swm_1.jpg" width="101" height="214" alt="SquadWar Medal for Exceptional Conduct" border="0" /><?php endif; ?>

	<?php // END PASTE ?>

<?php else: // leagueid not empty ?>

	<div class="title">
		SquadWar <?php echo $get_league->get_Title(); ?> League
	</div>

	<center>
		<?php include(BASE_PATH."leagues/map.php"); ?>
	</center>

				<?php
					$squadrank = '0';
					$notfoundsquadrank = 1;
					foreach($rank_count as $squad):
						$squadrank++;
						if($id == $squad->get_id())
						{
							break;
						}
					endforeach; ?>
				<div class="copy">
				Your Squad is currently ranked <?php echo $squadrank; ?> of <?php echo count($rank_count); ?>
				</div>
				<br />
				
				
					<table width="90%" border="1" style="border:1px solid #5A63F7;" cellspacing="0">
						<tr><th align="center" colspan="9">The 10 opponents closest to your rank</th></tr>
						<tr>
							<td align="left"><div class="copy">&nbsp;</div></td>
							<td><div class="copy"><b>Rank</b></div></td>
							<td><div class="copy"><b>Squad Name</b></div></td>
							<td><div class="copy"><b>League</b></div></td>
							<td><div class="copy"><b>Overall</b></div></td>
							<td><div class="copy"><b>Systems</b></div></td>								
							<td><div class="copy"><b>Status</b></div></td>	
							<td><div class="copy"><b>Challengers</b></div></td>	
							<td><div class="copy"><b>Challenges</b></div></td>	
						</tr>

					<?php 
						$looprank = 1;
						$startrow = $squadrank - 5;
						if($startrow < 1)
						{
							$startrow = 1;
						}
						$endrow = $startrow + 10;
						if($endrow > count($rank_count))
						{
							$endrow = count($rank_count);
							$startrow = $endrow - 10;
							if($startrow < 1)
							{
								$startrow = 1;
							}
						}
						$displayrank = $startrow;
					?>

					<?php if(count($rank_count)): ?>
						<?php for($i = $startrow - 1; $i < $endrow; $i++):
							$squad = $rank_count[$i];
							$teamcolor = $squad->get_info()->get_teamcolor();
							$wins = count($squad->get_matches_won());
							$lost = count($squad->get_matches_lost());
							$total_matches = $wins + $lost;
							$l_wins = count($squad->get_l_matches_won());
							$l_lost = count($squad->get_l_matches_lost());
							$l_total_matches = $l_wins + $l_lost;
							$num_league_sectors = count($squad->get_l_sectors());
							$CountOfSWCode_challenged = count($squad->get_matches_defender());
							$CountOfSWCode_challenger = count($squad->get_matches_challenger()); ?>
								<tr>
									<td width="5" bgcolor="#<?php echo $teamcolor; ?>" align="right"><img src="images/spacer.gif" width="5" height="3" alt=" " border="0" /><br /></td>
									<td align="right"><div class="copy"><?php echo $displayrank; ?></div></td>
									<td align="left"><div class="copy">&nbsp;<?php if($squad->get_SquadID() == $id): ?><b><?php echo $squad->get_SquadName(); ?></b><?php else: ?><a href="squadinfo.php?id=<?php echo $squad->get_SquadID(); ?>&amp;leagueid=<?php echo $leagueid; ?>"><?php echo $squad->get_SquadName(); ?></a><?php endif; ?></div></td>
									<td align="center"><div class="copy"><?php if(!empty($l_wins)): echo $l_wins; else: ?>0<?php endif; ?>/<?php if(!empty($l_total_matches)): echo $l_total_matches; else: ?>0<?php endif; ?></div></td>
									<td align="center"><div class="copy"><?php if(!empty($wins)): echo $wins; else: ?>0<?php endif; ?>/<?php if(!empty($total_matches)): echo $total_matches; else: ?>0<?php endif; ?></div></td>
									<td align="right"><div class="copy"><?php if($squad->get_SquadID() == $id): $thissquadsectorcount = $num_league_sectors; endif; echo $num_league_sectors; ?></div></td>
									<td align="right"><div class="copy"><?php if($CountOfSWCode_challenged < 2): ?>Available<?php else: ?>Challenged<?php endif; ?></div></td>
									<td align="right"><div class="copy"><?php echo $CountOfSWCode_challenged; ?></div></td>
									<td align="right"><div class="copy"><?php echo $CountOfSWCode_challenger; ?></div></td>
								</tr>
								<?php $displayrank++; ?>
						<?php endfor; ?>
					<?php endif; ?>
			</table>			
			<?php // GET MATCHES ?>

					<br />
					<div class="title">Pending Matches in This League:</div>
					<br />
					<?php if(!count($get_matches)): ?>
						<div class="copy">No matches at this time.</div>
					<?php else: ?>
						<?php foreach($get_matches as $match): ?>
							<div class="copy">
								<?php if($match->get_SWSquad1() == $id): ?>
									Your squad has challenged <?php echo $match->get_Challenged()->get_SquadName(); ?> for control of sector <?php echo $match->get_SWSector_ID().' '.$match->get_SWSector()->get_SectorName(); ?>, match code: <b><?php echo $match->get_SWCode(); ?></b>.<br />
								<?php else: ?>
									Squad <?php echo $match->get_Challenger()->get_SquadName(); ?> has challenged your squad for control of <?php echo $match->get_SWSector()->get_SectorName(); ?>, match code: <b><?php echo $match->get_SWCode(); ?></b>.<br />
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>
					<br clear="all" />

			<?php // GET SECTORS ?>

					<?php if(count($get_sectors)): ?>
					<div class="title">Your Squad Holds the Following Systems in This League:</div>	
					<br />

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
					<div class="title">Your squad holds no sectors in this league.</div>	
					<br />
					<?php endif; ?>

			<?php // MATCH HISTORY ?>
					<?php if(!count($match_history)): ?>
					<div class="title">
						This squad has no match history yet.
					</div>
					<br />
					<?php else: ?>
					<div class="title">Match History in This League</div>	
					<br />
					<center>
						<table width="90%" border="1" style="border:1px solid #5A63F7;" cellspacing="0">
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
					</center>		
					<?php endif; ?>

<?php endif; ?>

				<?php // END MAIN PAGE INFO ?>
<?php include(BASE_PATH.'doc_bot.php'); ?>

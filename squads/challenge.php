<?php /*
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
include('../bootstrap.php');

if(!empty($_SESSION['squadid']))
{
	$get_squad = squad_api::get($_SESSION['squadid']);
	util::prepend_title($get_squad->get_SquadName());
}

util::prepend_title('Issue Challenge');

$get_league = league_api::get($_GET['leagueid']);

$league_proceed = true;
if($get_league->get_archived())
{
	$league_proceed = false;
}
else
{
	$valid_login = 0;
	if(!empty($_SESSION['squadpassword']) && !empty($get_squad) && $_SESSION['squadpassword'] == $get_squad->get_SquadPassword())
	{
		$valid_login = 1;
		$challenged_max = $get_league->get_challenged_max();
		$current_team = 8;

		$rec = squad_api::new_sector_search_record();
		$rec->set_SectorSquad($_SESSION['squadid']);
		$rec->set_League_ID($_GET['leagueid']);
		$ret = squad_api::search($rec);
		$ret = squad_api::populate_sector_squad($ret);
		$ret = squad_api::populate_sector_sectorgraph($ret);
		$challenge_test = $ret->get_results();

		$dontchecklist = array();
		$test_sectors_array_to_check = array();
		$test_sectors_list_to_check = array();

		foreach($challenge_test as $sector)
		{
			$dontchecklist[] = $sector->get_SWSectors_ID();
			for($i = 1; $i <= MAX_SECTOR_PATHS; $i++)
			{
				$path_call = 'get_path_'.$i;
				if(!method_exists($sector->get_sectorgraph(), $path_call))
				{
					continue;
				}

				if($sector->get_sectorgraph()->$path_call())
				{
					$test_sectors_array_to_check[] = $sector->get_sectorgraph()->$path_call();
					if(!in_array($sector->get_sectorgraph()->$path_call(), $test_sectors_list_to_check))
					{
						$test_sectors_list_to_check[] = $sector->get_sectorgraph()->$path_call();
					}
				}
			}
		}

		$pos = 0;

		foreach($challenge_test as $sector)
		{
			if(in_array($sector->get_SWSectors_ID(), $test_sectors_list_to_check))
			{
				unset($test_sectors_list_to_check[array_search($sector->get_SWSectors_ID(), $test_sectors_list_to_check)]);
			}
		}

		$testList = $test_sectors_array_to_check;

		$final_sectors_owned_can_challenge = array();

		$rec = squad_api::new_sector_search_record();
		$rec->set_SectorSquad_not(0);
		$rec->set_SWSectors_ID($test_sectors_list_to_check);
		$rec->set_Entry_Node(0);
		$rec->set_League_ID($_GET['leagueid']);
		$rec->set_sort_by('SWSectors_ID');
		$rec->set_sort_dir('asc');
		$ret = squad_api::search($rec);
		$ret = squad_api::populate_sector_squad($ret);
		$ret = squad_api::populate_sector_sectorgraph($ret);
		$adjacent_test_owned = $ret->get_results();

		foreach($adjacent_test_owned as $sector)
		{
			$final_sectors_owned_can_challenge[] = $sector->get_SWSectors_ID();
		}

		$rec = squad_api::new_sector_search_record();
		$rec->set_SectorSquad(array(0));
		$rec->set_SWSectors_ID($test_sectors_list_to_check);
		$rec->set_Entry_Node(0);
		$rec->set_League_ID($_GET['leagueid']);
		$rec->set_sort_by('SWSectors_ID');
		$rec->set_sort_dir('asc');
		$ret = squad_api::search($rec);
		$ret = squad_api::populate_sector_squad($ret);
		$ret = squad_api::populate_sector_sectorgraph($ret);
		$adjacent_test_unowned = $ret->get_results();

		foreach($adjacent_test_unowned as $sector)
		{
			$dontchecklist[] = $sector->get_SWSectors_ID();
		}

		$dontchecklist = array(); // ???
		$final_sectors_unowned_can_challenge = array();
		$test_sectors_list_adjacent_to_no_check = array();

		foreach($adjacent_test_unowned as $sector)
		{
			for($i = 1; $i <= MAX_SECTOR_PATHS; $i++)
			{
				$path_call = 'get_path_'.$i;
				if(!method_exists($sector->get_sectorgraph(), $path_call))
				{
					continue;
				}

				if($sector->get_sectorgraph()->$path_call())
				{
					if(!in_array($sector->get_sectorgraph()->$path_call(), $test_sectors_list_adjacent_to_no_check))
					{
						$rec = squad_api::new_sector_search_record();
						$rec->set_SWSectors_ID($sector->get_sectorgraph()->$path_call());
						$rec->set_SectorSquad_not(array(0,$current_team));
						$ret = squad_api::search($rec);
						$test_this_link = $ret->get_results();
						if(empty($test_this_link))
						{
							$test_sectors_list_adjacent_to_no_check[] = $sector->get_sectorgraph()->$path_call();
						}
						elseif(!in_array($sector->get_SWSectors_ID(), $final_sectors_unowned_can_challenge))
						{
							$final_sectors_unowned_can_challenge[] = $sector->get_SWSectors_ID();
						}
					}
				}
			}
		}

		$final_sectors_can_challenge = array_merge($final_sectors_owned_can_challenge, $final_sectors_unowned_can_challenge);
		if(!empty($final_sectors_can_challenge))
		{
			$rec = squad_api::new_sector_search_record();
			$rec->set_SWSectors_ID($final_sectors_can_challenge);
			$rec->set_League_ID($_GET['leagueid']);
			$ret = squad_api::search($rec);
			$ret = squad_api::populate_sector_sectorgraph($ret);
			$ret = squad_api::populate_sector_squad($ret);
			$final_challenge = $ret->get_results();
		}
		else
		{
			$final_challenge = array();
		}

		$rec = match_api::new_search_record();
		$rec->set_SWSquad1($_SESSION['squadid']);
		$rec->set_League_ID($_GET['leagueid']);
		$ret = match_api::search($rec);
		$ret = match_api::populate_sectors($ret);
		$count_challenger = $ret->get_results();

		$canchallenge = 1;
		$canchallengeunclaimed = 1;
		$canchallengeowned = 1;

		if(count($count_challenger))
		{
			foreach($count_challenger as $match)
			{
				$check_sector_squad = $match->get_SWSector()->get_SectorSquad();
				if(empty($check_sector_squad))
				{
					$canchallengeunclaimed = 0;
				}
				else
				{
					$canchallengeowned = 0;
				}
			}
		}

		$optionstring = '';
		$ownedstring = '';
		$unownedstring = '';
		$validchallenge = 0;

		if($canchallenge)
		{
			foreach($final_challenge as $key => $sector)
			{
				if($sector->get_SectorSquad() && $canchallengeowned)
				{
					$rec = match_api::new_search_record();
					$rec->set_SWSquad2($sector->get_SectorSquad());
					$rec->set_League_ID($_GET['leagueid']);
					$ret = match_api::search($rec);
					$get_currentpath_matchcount[$key] = $ret->get_results();

					if(count($get_currentpath_matchcount[$key]) < $challenged_max)
					{
						$rec = match_api::new_search_record();
						$rec->set_SWSector_ID($sector->get_SWSectors_ID());
						$rec->set_League_ID($_GET['leagueid']);
						$ret = match_api::search($rec);
						$check_for_already_challenged[$key] = $ret->get_results();

						if(!count($check_for_already_challenged[$key]))
						{
							$optionstring .= '<option value="'.$sector->get_SWSectors_ID().','.$sector->get_SectorSquad().'">'.$sector->get_squad()->get_SquadName().' for '.$sector->get_SectorName().' - '.$sector->get_SWSectors_ID().'</option>';
							if(!empty($ownedstring))
							{
								$ownedstring .= ',';
							}
							$ownedstring .= $sector->get_SWSectors_ID();
							$validchallenge = 1;
						}
					}
				}
				if(!$sector->get_SectorSquad() && $canchallengeunclaimed)
				{
					$rec = match_api::new_search_record();
					$rec->set_SWSector_ID($sector->get_SWSectors_ID());
					$rec->set_League_ID($_GET['leagueid']);
					$ret = match_api::search($rec);
					$check_for_already_challenged[$key] = $ret->get_results();

					if(!count($check_for_already_challenged[$key]))
					{
						$rec = squad_api::new_sectorgraph_search_record();
						$rec->set_SWSectors_ID($sector->get_SWSectors_ID());
						$ret = squad_api::search($rec);
						$ret = squad_api::populate_sectorgraph_pathsectors($ret);
						$final_challenge_graph[$key] = reset($ret->get_results());
						$canchallengeunclaimed_adjacent_test_unowned[$key] = array();

						for($i = 1; $i <= MAX_SECTOR_PATHS; $i++)
						{
							$func = 'get_sector_'.$i;
							if($final_challenge_graph[$key]->$func())
							{
								$squad = $final_challenge_graph[$key]->$func()->get_SectorSquad();
								if($squad && $squad != $_SESSION['squadid'])
								{
									$canchallengeunclaimed_adjacent_test_unowned[$key][] = $final_challenge_graph[$key]->$func();
								}
								else
								{
									// This one is the user's squad
									$func = 'set_sector_'.$i;
									$final_challenge_graph[$key]->$func(null);
									$func = 'set_path_'.$i;
									$final_challenge_graph[$key]->$func(0);
								}
							}
						}

						if(!empty($canchallengeunclaimed_adjacent_test_unowned[$key]))
						{
							// There is at least one unclaimed node adjacent to this one that has an adjacent squad, that is not this one.
							$ret = new base_return();
							$ret->set_result($canchallengeunclaimed_adjacent_test_unowned[$key]);
							$ret = squad_api::populate_sector_squad($ret);
							$canchallengeunclaimed_adjacent_test_unowned[$key] = $ret->get_results();

							$pathcheck = 0;
							foreach($canchallengeunclaimed_adjacent_test_unowned[$key] as $path)
							{
								$rec = match_api::new_search_record();
								$rec->set_SWSquad2($path->get_SectorSquad());
								$rec->set_League_ID($_GET['leagueid']);
								$ret = match_api::search($rec);
								$pending_matches[$key][$path->get_SectorSquad()] = $ret->get_results();
							}
						}
					}
				}
			}
		}

		$rec = match_api::new_search_record();
		$rec->set_either_squad($_SESSION['squadid']);
		$rec->set_League_ID($_GET['leagueid']);
		$ret = match_api::search($rec);
		$ret = match_api::populate_squads($ret);
		$ret = match_api::populate_sectors($ret);
		$get_matches = $ret->get_results();

		$rec = squad_api::new_sector_search_record();
		$rec->set_SectorSquad($_SESSION['squadid']);
		$rec->set_League_ID($_GET['leagueid']);
		$ret = squad_api::search($rec);
		$get_sectors = $ret->get_results();

		/* <!--- <b>#challenge_test.SWSectors_ID#</b> -<cfif challenge_test.Entry_Node IS 1><b>E</b></cfif> #challenge_test.SectorName#- (<cfif challenge_test.path_1 IS NOT 0>#challenge_test.path_1#</cfif><cfif challenge_test.path_2 IS NOT 0>,#challenge_test.path_2#</cfif><cfif challenge_test.path_3 IS NOT 0>,#challenge_test.path_3#</cfif><cfif challenge_test.path_4 IS NOT 0>,#challenge_test.path_4#</cfif><cfif challenge_test.path_5 IS NOT 0>,#challenge_test.path_5#</cfif>) - #challenge_test.SquadName#<br> ---> */
	}
}

include(BASE_PATH.'doc_top.php');

include(BASE_PATH.'menu/main.php');

include(BASE_PATH.'doc_mid.php');
				// MAIN PAGE INFO
?>

<div class="title">Squad <?php echo $get_squad->get_SquadName(); ?> Challenge Mode</div>
<br />

<?php if(!$league_proceed): ?>
	<div class="title" align="center">This league is over.</div>
<?php else: ?>
	<?php if($valid_login): ?>
		<?php // START CODE FOR CHALLENGE GENERATION ?>
		<?php if($canchallenge): ?>
		<div class="title">Map Status:</div>
		<br />
		<div class="copy">
			<?php foreach($final_challenge as $key => $sector): ?>
				<?php if($sector->get_SectorSquad() && $canchallengeowned): ?>
					<?php if(!count($get_currentpath_matchcount[$key]) < $challenged_max): ?>
						You cannot challenge <?php echo $sector->get_squad()->get_SquadName(); ?> for Claimed sector <?php echo $sector->get_SectorName(); ?> - <?php echo $sector->get_SWSectors_ID(); ?> because <?php echo $sector->get_squad()->get_SquadName(); ?> has already been challenged the maximum of <?php echo $challenged_max; ?> times.<br />
					<?php endif; ?>
				<?php endif; ?>
				<?php if(!$sector->get_SectorSquad() && $canchallengeunclaimed): ?>
					<?php if(count($check_for_already_challenged[$key])): ?>
						You cannot challenge for Sector: <?php echo $sector->get_SWSectors_ID(); ?> - <?php echo $sector->get_SectorName(); ?> because it has currently been challenged.<br />
					<?php else: 
						$pathcheck = 0;
						foreach($canchallengeunclaimed_adjacent_test_unowned[$key] as $path):
							if(count($pending_matches[$key][$path->get_SectorSquad()]) < $challenged_max):
								$pathcheck = 1;
								$validchallenge = 1;
								$optionstring .= '<option value="'.$sector->get_SWSectors_ID().','.$path->get_SectorSquad().'">Unclamined sector '.$sector->get_SectorName().' - '.$sector->get_SWSectors_ID().' - ';
								$optionstring .= $path->get_squad()->get_SquadName().'</option>';
								if(!empty($unownedstring)):
									$unownedstring .= '&';
								endif;
								$unownedstring .= $path->get_SWSectors_ID();
							else:
								?>You cannot challenge <?php echo $path->get_squad()->get_SquadName(); ?> for Unclamined sector <?php echo $sector->get_SectorName(); ?> - <?php echo $sector->get_SWSectors_ID(); ?> because <?php echo $path->get_squad()->get_SquadName(); ?> has already been challenged the maximum of <?php echo $challenged_max; ?> times.<br />
								<?php 
							endif;  ?>
						<?php endforeach; ?>
						<?php if(!$pathcheck): ?>
							You cannot challenge for Sector: <?php echo $sector->get_SWSectors_ID(); ?> - <?php echo $sector->get_SectorName(); ?> because there are no available teams adjacent to this sector.<br />
						<?php endif; ?>
					<?php endif; ?>
				<?php endif; ?>
			<?php endforeach; ?>
			<br />
			<?php if(!$validchallenge): ?>There are no sectors you can challenge at this time.<br />
				<?php if(!$canchallengeunclaimed): ?>You have already challenged an unclaimed sector.<br /><?php endif; ?>
				<?php if(!$canchallengeowned): ?>You have already challenged a claimed sector.<br /><?php endif; ?>
			<?php endif; ?>
		</div>
		<?php else: // canchallenge ?>
		You have filled your available challenge slots.
		<?php endif; ?>

		<?php
			$_SESSION['owned'] = $ownedstring;
			$_SESSION['unclaimed'] = $unownedstring;
			$_SESSION['show_challenge'] = 1;
			include(BASE_PATH.'leagues/map.php');
			$_SESSION['show_challenge'] = 0;
		?>

		<?php if($validchallenge): ?>
			<form action="_challengeack.php" method="post">
			Challenge 
			<select name="challenge_sector">
				<?php echo $optionstring; ?>	
			</select>	
			<input type="hidden" name="leagueid" value="<?php echo $_GET['leagueid']; ?>" />
			<input type="submit" value="Generate Match Code" />
			</form>
		<?php endif; ?>

		<?php // show current matches ?>
		<br />
		<div class="title">Pending Matches In This League:</div>
		<br />
		<?php if(!count($get_matches)): ?>
			<div class="copy">No matches at this time.</div>
		<?php else: ?>
			<?php foreach($get_matches as $match): ?>
				<div class="copy">
					<?php if($match->get_SWSquad1() == $_SESSION['squadid']): ?>
						Your squad has challenged <?php echo $match->get_Challenged()->get_SquadName(); ?> for control of Sector <?php echo $match->get_SWSector_ID(); ?> <?php echo $match->get_SWSector()->get_SectorName(); ?>, match code: <b><?php echo $match->get_SWCode(); ?></b>.<br />
					<?php else: ?>
						Squad <?php echo $match->get_Challenger()->get_SquadName(); ?> has challenged your squad for control of Sector <?php echo $match->get_SWSector_ID(); ?> <?php echo $match->get_SWSector()->get_SectorName(); ?>, match code: <b><?php echo $match->get_SWCode(); ?></b>.<br />
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
		<br />	
		<?php // show current matches ?>						

		<?php // GET SECTORS ?>
		<br />
		<br />
		<div class="title">Your Squad Holds the Following Systems In This League:</div>	
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
				<td><div class="copy">&nbsp;<?php if($sector->get_SectorTime() == 0): ?>Creation<?php else: echo date('h:i A m.d.Y T', $sector->get_SectorTime()); endif; ?></div></td>
			</tr>
		<?php endforeach; ?>
		</table>	
	<?php else: // validlogin ?>
		Invalid Squad Name or Password.  You need to log back in to your squad to reset your session variables.
	<?php endif; ?>

<?php endif; ?>
<?php $_SESSION['challenging'] = 1; ?>

				<?php // END MAIN PAGE INFO ?>
<?php include(BASE_PATH.'doc_bot.php'); ?>

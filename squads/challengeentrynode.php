<?php /*
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
include('../bootstrap.php');

$get_squad = squad_api::get($_SESSION['squadid']);
util::prepend_title($get_squad->get_SquadName());
util::prepend_title('Challenge an Entry Node');

$_SESSION['challenging'] = 1;

$leagueid = (!empty($_GET['leagueid'])) ? $_GET['leagueid'] : '';
$league_proceed = 1;

$check_league = league_api::get($leagueid);

if($check_league->get_Archived())
{
	$league_proceed = 0;
}
else
{
	$rec = squad_api::new_sector_search_record();
	$rec->set_Entry_Node(1);
	$rec->set_SectorSquad_not(array(0,$_SESSION['squadid']));
	$rec->set_League_ID($leagueid);
	$ret = squad_api::search($rec);
	$ret = squad_api::populate_sector_pending_matches($ret);
	$ret = squad_api::populate_sector_squad($ret);
	$check_entry_nodes = $ret->get_results();

	$rec = squad_api::new_matchhistory_search_record();
	$rec->set_SWSquad1($_SESSION['squadid']);
	$rec->set_League_ID($leagueid);
	$rec->set_sort_by('match_time');
	$rec->set_sort_dir('DESC');
	$ret = squad_api::search($rec);
	$get_sector_last_challenged = $ret->get_results();

	$entrynodemessage = 0;
	$canchallenge = 0;

	if(count($check_entry_nodes))
	{
		foreach($check_entry_nodes as $sector)
		{
			if(count($get_sector_last_challenged) > 0)
			{
				if(!$sector->get_pending_matches() && $sector->get_SWSectors_ID() != reset($get_sector_last_challenged)->get_SWSector_ID())
				{
					$canchallenge = 1;
				}
				else
				{
					$entrynodemessage = 1;
				}
			}
			elseif(!count($sector->get_pending_matches()))
			{
				$canchallenge = 1;
			}
		}
	}
}



include(BASE_PATH.'doc_top.php');

include(BASE_PATH.'menu/main.php');

include(BASE_PATH.'doc_mid.php');
				// MAIN PAGE INFO
?>
<?php include(BASE_PATH.'leagues/map.php'); ?>
<?php if(!$league_proceed): ?>
	<div class="title" align="center">This league is over.</div>	
<?php else: ?>
	<?php if($canchallenge): ?>
		<form action="_challengeentrynode.php" method="post">
		<div class="copy">
			Choose the entry node you wish to challenge from the dropdown box:<br />
			<select name="challenge_sector">
				<?php foreach($check_entry_nodes as $sector): ?>
					<?php if(!count($sector->get_pending_matches())): ?>
						<option value="<?php echo $sector->get_SWSectors_ID(); ?>,<?php echo $sector->get_SectorSquad(); ?>">Versus <?php echo $sector->get_squad()->get_SquadName(); ?> for Sector <?php echo $sector->get_SWSectors_ID(); ?> - <?php echo $sector->get_SectorName(); ?></option> 
					<?php endif; ?>
				<?php endforeach; ?>
			</select>	
			<input type="hidden" name="leagueid" value="<?php echo $leagueid; ?>" /><br />
			<input type="submit" value="Click to Challenge This Entry Node" />
		</div>
		</form>
	<?php else: ?>
	<div class="copy">
		<?php if(!$canchallenge && $entrynodemessage): ?>You cannot challenge the same entry node twice in a row.<br /><?php endif; ?>
		All <?php if(!$canchallenge && $entrynodemessage): ?>other <?php endif; ?>entry nodes have been challenged.
	</div>
	<?php endif; ?>
<?php endif; ?>
				<?php // END MAIN PAGE INFO ?>
<?php include(BASE_PATH.'doc_bot.php'); ?>

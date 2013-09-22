<?php /*
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
include('../bootstrap.php');

$get_squad = squad_api::get($_SESSION['squadid']);
util::prepend_title($get_squad->get_SquadName());
util::prepend_title('Enter Map');

$_SESSION['challenging'] = 1;

$leagueid = util::get($_GET, 'leagueid');

$rec = squad_api::new_sector_search_record();
$rec->set_SectorSquad($_SESSION['squadid']);
$rec->set_League_ID($leagueid);
$ret = squad_api::search($rec);
$check_all_nodes = $ret->get_results();
if(!count($check_all_nodes))
{
	$rec->set_Entry_Node(1);
	$rec->set_SectorSquad(0);
	$ret = squad_api::search($rec);
	$ret = squad_api::populate_sector_pending_matches($ret);
	$check_entry_nodes = $ret->get_results();
}

include(BASE_PATH.'doc_top.php');

include(BASE_PATH.'menu/main.php');

include(BASE_PATH.'doc_mid.php');
				// MAIN PAGE INFO
?>
	<center>
		<?php include(BASE_PATH.'leagues/map.php'); ?>
	</center>
	<?php if(!count($check_all_nodes)): ?>
	<form action="_entermap.php" method="post">
		<label for="sector">Challenge</label>
		<select name="sector">
			<?php foreach($check_entry_nodes as $sector):
				if(!$sector->get_pending_matches()):?>
					<option value="<?php echo $sector->get_SWSectors_ID(); ?>"><?php echo $sector->get_SWSectors_ID().' '.$sector->get_SectorName(); ?></option>
				<?php endif;
			endforeach; ?>
		</select>
		<input type="hidden" name="leagueid" value="<?php echo $leagueid; ?>" />
		<input type="submit" value="Enter the Map" />
	</form>
	<?php endif; ?>

	<?php // END MAIN PAGE INFO ?>
<?php include(BASE_PATH.'doc_bot.php'); ?>

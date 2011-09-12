<?php /*
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
include('../bootstrap.php');

$rec = squad_api::new_search_record();
$rec->set_sort_by('SquadID');
$ret = squad_api::search($rec);
$ret = squad_api::populate_match_history($ret);
$ret = squad_api::populate_info($ret);
$ret = squad_api::populate_league($ret);
$get_squads = $ret->get_results();

$lame_squads = array();
$played_squads = array();
foreach($get_squads as $key => $squad)
{
	if(!$squad->get_matches_won() && !$squad->get_matches_lost())
	{
		$lame_squads[] = $squad->get_SquadID();
		$squad->get_info()->delete();
		$squad->get_league()->delete();
		$squad->delete();
		unset($get_squads[$key]);
	}
	else
	{
		$played_squads[] = $squad->get_SquadID();
	}
}

?>

Squads that have played matches: <b><?php echo count($get_squads); ?></b><br />

<hr />
<br />

<?php echo implode(', ', $played_squads); ?><br />

<hr />
<?php echo count($lame_squads); ?>

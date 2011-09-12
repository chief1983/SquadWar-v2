<?php /*
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
include('../bootstrap.php');

$match = match_api::get_by_SWCode($_GET['code']);

if(!$match)
{
	$message = 'Could not find match.';
	util::location(RELATIVEPATH.'admin/pendingmatches.php?message='.urlencode($message));
}

$rec = squad_api::new_sector_search_record();
$rec->set_SWSectors_ID($_GET['sector']);
$ret = squad_api::search($rec);
$sector = reset($ret->get_results());
$sector->set_SectorSquad($_GET['winner']);
$sector->set_SectorTime($_GET['time']);
$sector->save();

$rec = squad_api::new_matchhistory_detail_record();
$rec->set_MatchID($match->get_MatchID());
$rec->set_SWCode($_GET['code']);
$rec->set_SWSquad1($_GET['first']);
$rec->set_SWSquad2($_GET['second']);
$rec->set_SWSector_ID($_GET['sector']);
$rec->set_match_victor($_GET['winner']);
$rec->set_match_loser($_GET['loser']);
$rec->set_match_time($_GET['time']);
$rec->set_League_ID($_GET['league']);
$rec->set_special(1);
$rec->save();

$match->delete();

util::location(RELATIVEPATH.'admin/pendingmatches.php');
?>

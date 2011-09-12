<?php /*
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
include('../bootstrap.php');
if(empty($_SESSION['squadid']))
{
	util::location(RELATIVEPATH.'index.php');
}

$rec = match_api::new_search_record();
$rec->set_SWCode($_POST['SWCode']);
$rec->set_either_squad($_SESSION['squadid']);
$ret = match_api::search($rec);
$ret = match_api::populate_info($ret);
$ret = match_api::populate_squads($ret, true);
$match = reset($ret->get_results());

if(empty($match))
{
	util::location(RELATIVEPATH.'index.php');
}

$info = $match->get_info();

if($match->get_SWSquad1() == $_SESSION['squadid'])
{
	$info->set_swsquad1_reports_noshow(1);
	$info->set_swsquad1_noshow_datetime(date('Y-m-d H:i:s'));
}
if($match->get_SWSquad2() == $_SESSION['squadid'])
{
	$info->set_swsquad2_reports_noshow(1);
	$info->set_swsquad2_noshow_datetime(date('Y-m-d H:i:s'));
}
$status = $info->save();

util::location(RELATIVEPATH.'squads/squad_pending_matches.php');
?>

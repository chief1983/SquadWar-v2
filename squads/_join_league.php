<?php /*
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
define('SECURE',1);
include('../bootstrap.php');

if(empty($_SESSION['squadid']))
{
	$message = urlencode('You must log into your squad');
	util::location(RELATIVEPATH.'error/error.php?message='.$message);
}

$rec = squad_api::new_search_record();
$rec->set_SquadID($_SESSION['squadid']);
$ret = squad_api::search($rec);
$ret = squad_api::populate_league($ret);
$get_squad = reset($ret->get_results());
$check_league = $get_squad->get_league();

$league_ids = array();
foreach($check_league as $league)
{
	$league_ids[] = $league->get_Leagues();
}

if(!$get_squad || !$get_squad->get_Active() || !($get_squad->get_SquadPassword() == $_SESSION['squadpassword']))
{
	$message = urlencode('You must properly log in as your squad.');
	util::location(RELATIVEPATH.'error/error.php?message='.$message);
}

if(count($get_squad->get_SquadMembers()) < 2)
{
	$message = urlencode('You must have at least two members on your Squad before attempting to join a league.');
	util::location(RELATIVEPATH.'error/error.php?message='.$message);
}

if(in_array($_POST['leagueid'], $league_ids))
{
	$message = urlencode('You have already joined this league.');
	util::location(RELATIVEPATH.'error/error.php?message='.$message);
}

$rec = squad_api::new_league_detail_record();
$rec->set_SWSquad_SquadID($_SESSION['squadid']);
$rec->set_Leagues($_POST['leagueid']);
$status = $rec->save();
util::location('squadlogin_validate.php');

?>

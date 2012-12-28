<?php /*
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
define('SECURE',1);
include('../bootstrap.php');

$rec = squad_api::new_search_record();
$rec->set_SquadID($_POST['squadid']);
$ret = squad_api::search($rec);
$ret = squad_api::populate_info($ret);
$get_squad = $ret->get_results();
if(!empty($get_squad))
{
	$squad = reset($get_squad);
	$info = $squad->get_info();
}

$check_user = user_api::check_password($_POST['pxo_login'], $_POST['pxo_password']);

if(empty($get_squad))
{
	// No squad matching that ID in Squad table
	util::location(RELATIVEPATH.'error/error.php?message=This%20Squad%20no%20longer%20exists.');
}
elseif(empty($info))
{
	// Join PW not yet set
	util::location(RELATIVEPATH.'error/error.php?message=The%20squad%20administrator%20has%20not%20set%20the%20join password.');
}
elseif($_POST['join_password'] != $info->get_Squad_Join_PW())
{
	// Join password incorrect
	util::location(RELATIVEPATH.'error/error.php?message=Your%20Squad%20Join%20Password%20is%20incorrect.');
}
elseif($check_user !== 1)
{
	// FS2NetD login info incorrect
	util::location(RELATIVEPATH.'error/error.php?message=Your%20FS2NetD%20login%20and%20password%20are%20incorrect.');
}

$members = $squad->get_SquadMembers();
if(!in_array($_SESSION['user_id'], $members))
{
	$members[] = $_SESSION['user_id'];
	$squad->set_SquadMembers($members);
	$squad->save();
	util::location(RELATIVEPATH.'squads/squadinfo.php?id='.$_POST['squadid']);
}
else
{
	util::location(RELATIVEPATH.'error/error.php?message=This%20person%20is%20already%20a%20member%20of%20this%20squad.');
}
?>

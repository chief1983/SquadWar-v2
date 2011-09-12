<?php /*
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
include('../bootstrap.php');

$rec = squad_api::new_search_record();
$rec->set_SquadID($_SESSION['squadid']);
$ret = squad_api::search($rec);
$get_squad_members = reset($ret->get_results());

$members = $get_squad_members->get_SquadMembers();
foreach($members as $key => $member)
{
	if($member == $_GET['memberid'])
	{
		unset($members[$key]);
	}
}
$get_squad_members->set_SquadMembers($members);
$get_squad_members->save();
util::location(RELATIVEPATH.'squads/squadlogin_validate.php');
?>

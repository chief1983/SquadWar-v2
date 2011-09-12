<?php /*
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
include('../bootstrap.php');

$rec = squad_api::new_sector_search_record();
$rec->set_SectorSquad($_SESSION['squadid']);
$rec->set_League_ID($_POST['leagueid']);
$ret = squad_api::search($rec);
$check_entry_nodes = $ret->get_results();

if(!count($check_entry_nodes) && !empty($_SESSION['challenging']))
{
	$rec = squad_api::new_sector_search_record();
	$rec->set_SWSectors_ID($_POST['sector']);
	$rec->set_SectorSquad(0);
	$ret = squad_api::search($rec);
	$check_sector = $ret->get_results();

	if(!count($check_sector))
	{
		$message = 'Someone has already taken this sector.';
	}
	else
	{
		$check_sector = reset($check_sector);
		$check_sector->set_SectorSquad($_SESSION['squadid']);
		$check_sector->set_SectorTime(time());
		$status = $check_sector->save();

		if($status)
		{
			$message = 'You have successfully gained control of this sector.';
		}
		else
		{
			$message = 'Could not save, please try again or contact admins.';
		}
	}
}
else
{
	$message = 'Dont be lame and try to take more than one sector.';
}

$_SESSION['challenging'] = 0;
util::location('squadlogin_validate.php?leagueid='.$_POST['leagueid'].'&message='.urlencode($message));
?>

<?php /*
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
define('SECURE',1);
include('../bootstrap.php');

if(!empty($_POST['pilotid']))
{
	$ret = fsopilot_api::get_swpilot($_POST['pilotid']);
	$rec = reset($ret->get_results());
	if($rec->get_TrackerID() != $_SESSION['trackerid'])
	{
		// Houston, we have a problem.
		echo "You don't have permission to edit this pilot.";
		exit;
	}

	$rec->set_Recruitme(0);
	$status = $rec->save();
	util::location('enlist.php?action=add');
}
else
{
	echo "No pilot id.";
}

?>

<?php /*
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
require('../bootstrap.php');

$result = match_api::award_match(
	$_GET['code'],
	$_GET['winner'],
	1
);

if(!$result)
{
	$message = 'Could not find match.';
	util::location(RELATIVEPATH.'admin/pendingmatches.php?message='.urlencode($message));
}

util::location(RELATIVEPATH.'admin/pendingmatches.php');

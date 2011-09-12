<?php /*
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
include('../bootstrap.php');

$rec = match_api::new_search_record();
$rec->set_SWCode($_GET['id']);
$ret = match_api::search($rec);
$ret = match_api::populate_info($ret);
$match = reset($ret->get_results());
$info = $match->get_info();

$match->delete();
$info->delete();

util::location(RELATIVEPATH.'admin/pendingmatches.php');
?>

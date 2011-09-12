<?php /*
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
include('../bootstrap.php');

$rec = squad_api::new_search_record();
$rec->set_SquadID($_GET['squadid']);
$ret = squad_api::search($rec);
$ret = squad_api::populate_info($ret);
$squad = reset($ret->get_results());
$info = $squad->get_info();

$squad->delete();
$info->delete();

util::location(RELATIVEPATH.'admin/pending_squads.php');
?>

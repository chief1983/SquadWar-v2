<?php /*
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
include('bootstrap.php');

$document_title = 'SquadWar - News Archive';

$cutoff_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . " -1 month"));
$rec = news_api::new_search_record();
$rec->set_SquadWar(1);
$rec->set_older_than($cutoff_date);
$rec->set_sort_by('date_posted');
$rec->set_sort_dir('DESC');
$res = news_api::search($rec);
$main_news = $res->get_results();

include(BASE_PATH.'doc_top.php');

include(BASE_PATH.'menu/main.php');

include(BASE_PATH.'doc_mid.php');

include(BASE_PATH.'doc_bot.php');
?>

<?php
// We'll use output buffering because the frontend code of this site is ported
// from an ancient system that doesn't separate logic from markup. 
ob_start();
session_start();
define('BASE_PATH',dirname(__FILE__).'/');
require_once(BASE_PATH.'inc/autoload.php');

// Include the config file if we can find it, or die gracefully.
if(file_exists(BASE_PATH.'conf/config.php'))
{
	require_once(BASE_PATH.'conf/config.php');
}
else
{
	util::custom_die('Could not load config!  Copy conf/config.php.sample to conf/config.php and fill it out.');
}

if(empty($_SESSION['loggedin']))
{
	$_SESSION['loggedin'] = false;
}

// Page requires login
if(defined('SECURE') && SECURE)
{
	if(!$_SESSION['loggedin'])
	{
		util::location(RELATIVEPATH.'login.php');
	}
}

// Page requires you to not be logged in
if(defined('SECURE') && !SECURE)
{
	if($_SESSION['loggedin'])
	{
		util::location(RELATIVEPATH.'index.php');
	}
}

if(!$_SESSION['loggedin'])
{
	// Get number of validated FS2NetD users
	$rec = user_api::new_search_record();
	$rec->set_Validated(1);
	$count_pxo_users = user_api::get_count($rec);

	// Get number of total pilots in FS2NetD
	$rec = fsopilot_api::new_search_record();
	$res = fsopilot_api::search($rec);
	$get_fs2_pilots = $res->get_results();
	$count_fs2_pilots = count($get_fs2_pilots);

	// Get number of unique players that have created a pilot in FS2NetD
	$players = array();
	foreach($get_fs2_pilots as $pilot)
	{
		if(!in_array($pilot->get_TrackerID(), $players))
		{
			$players[] = $pilot->get_TrackerID();
		}
	}
	$count_fs2_players = count($players);

	// Get number of total squads in FS2NetD
	$rec = squad_api::new_search_record();
	$count_sw_squads = squad_api::get_count($rec);

	$script = <<<EOT
	<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery("form.validate").validate();
		});
	</script>
EOT;
	util::push_head($script);
}
else
{
	// Get number of pilots the current user has in FS2NetD
	$rec = fsopilot_api::new_search_record();
	$rec->set_TrackerID($_SESSION['trackerid']);
	$count_fs2_pilots = fsopilot_api::get_count($rec);
	$_SESSION['totalfsopilots'] = $count_fs2_pilots;
}

$rec = new league_record_search();
$rec->set_Active(1);
$rec->set_sort_by('League_ID');
$res = league_api::search($rec);
$get_swleagues = $res->get_results();

$rec = new league_record_search();
$rec->set_Archived(1);
$rec->set_sort_by('League_ID');
$res = league_api::search($rec);
$get_old_swleagues = $res->get_results();

?>

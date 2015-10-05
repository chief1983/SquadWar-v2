<?php /*
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell
   or otherwise commercially exploit the source or things you created based on the
   source.
*/
include('../bootstrap.php');
if(empty($_SESSION['squadid']))
{
	util::location(RELATIVEPATH.'index.php');
}

$rec = match_api::new_search_record();
$rec->set_SWCode($_POST['SWCode']);
$rec->set_SWSquad2($_SESSION['squadid']);
$ret = match_api::search($rec);
$ret = match_api::populate_info($ret);
$ret = match_api::populate_squads($ret, true);
$match = reset($ret->get_results());

if(empty($match))
{
	util::location(RELATIVEPATH.'index.php');
}

$parts = explode(',', $_POST['proposed_final_time']);
for($i = 1; $i <= 2; $i++)
{
	if($parts[$i] < 10)
	{
		$parts[$i] = '0'.$parts[$i];
	}
}
$proposed_final_time = "{$parts[0]}-{$parts[1]}-{$parts[2]} {$parts[3]}:{$parts[4]}:{$parts[5]}";
$parts = explode(',', $_POST['date1']);
for($i = 1; $i <= 2; $i++)
{
	if($parts[$i] < 10)
	{
		$parts[$i] = '0'.$parts[$i];
	}
}
$hour = $_POST['hour1'];
if($hour < 10)
{
	$hour = '0'.$hour;
}
$minute = $_POST['minute1'];
if($minute < 10)
{
	$minute = '0'.$minute;
}
$proposed_alternate_time = "{$parts[0]}-{$parts[1]}-{$parts[2]} {$hour}:{$minute}:00";
$info = $match->get_info();
$info->set_Mission($_POST['mission']);
$info->set_Pilots($_POST['pilots']);
$info->set_AI($_POST['ai']);
$info->set_proposed_final_time($proposed_final_time);
$info->set_proposed_alternate_time($proposed_alternate_time);
$info->set_squad_last_proposed($_SESSION['squadid']);
$info->set_status_last_changed(date('Y-m-d H:i:s'));
$status = $info->save();

$date = date('F j, Y \a\t g:m A T');
$to = $match->get_Challenger()->get_info()->get_Squad_Email();
$from = SUPPORT_EMAIL;
$headers = 'From: SquadWar <' . $from . ">\r\n" .
    'Reply-To: ' . $from . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
$subject = "SquadWar:Challenge Update";
$message = <<<EOT
There has been an update regarding match {$_POST['SWCode']}.

This phase was completed on {$date}.  You have 48 hours to complete this phase.

You may now pick the final match time.

EOT;

mail($to, $subject, $message, $headers);
util::location(RELATIVEPATH.'squads/squad_pending_matches.php');

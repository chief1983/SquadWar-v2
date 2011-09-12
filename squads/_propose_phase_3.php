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
$rec->set_SWSquad1($_SESSION['squadid']);
$ret = match_api::search($rec);
$ret = match_api::populate_info($ret);
$ret = match_api::populate_squads($ret, true);
$match = reset($ret->get_results());

if(empty($match))
{
	util::location(RELATIVEPATH.'index.php');
}

$parts = explode(',', $_POST['final_time']);
for($i = 1; $i <= 2; $i++)
{
	if($parts[$i] < 10)
	{
		$parts[$i] = '0'.$parts[$i];
	}
}
$final_time = "{$parts[0]}-{$parts[1]}-{$parts[2]} {$parts[3]}:{$parts[4]}:{$parts[5]}";
$info = $match->get_info();
$info->set_final_match_time($final_time);
$info->set_squad_last_proposed($_SESSION['squadid']);
$info->set_status_last_changed(date('Y-m-d H:i:s'));
$status = $info->save();

$date = date('F j, Y \a\t g:m A T');
$to = $match->get_Challenged()->get_info()->get_Squad_Email();
$from = SUPPORT_EMAIL;
$headers = 'From: SquadWar <' . $from . ">\r\n" .
    'Reply-To: ' . $from . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
$subject = "SquadWar:Challenge Update";
$message = <<<EOT
There has been an update regarding match {$_POST['SWCode']}.

This phase was completed on {$date}.  Your opponent has picked the final match time.

Please visit the scheduling page available on your Squad Management page.  You can reach your Squad Management page by clicking on the link in the menu on the left side of the page called "Admin Squad".  You can reach the scheduling page by clicking on the link titled "View pending matches for this squad and schedule matches" available on the Squad Management page.

EOT;

mail($to, $subject, $message, $headers);
util::location(RELATIVEPATH.'squads/squad_pending_matches.php');
?>

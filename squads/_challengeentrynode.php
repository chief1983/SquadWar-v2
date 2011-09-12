<?php /*
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
include('../bootstrap.php');

$sector_id = reset(explode(',', $_POST['challenge_sector']));
$challenged_squad = trim(end(explode(',', $_POST['challenge_sector'])));

$rec = match_api::new_search_record();
$rec->set_SWSquad1($_SESSION['squadid']);
$rec->set_League_ID($_POST['leagueid']);
$ret = match_api::search($rec);
$quick_check_entry_nodes_challenge = $ret->get_results();

if(!count($quick_check_entry_nodes_challenge) && $_SESSION['challenging'])
{
	$rec = match_api::new_search_record();
	$ret = match_api::search($rec);
	$matches = $ret->get_results();

	$rec = squad_api::new_sector_search_record();
	$rec->set_SWSectors_ID($sector_id);
	$ret = squad_api::search($rec);
	$sector = reset($ret->get_results());

	mt_srand(time());
	$swcode = $_SESSION['squadid'].mt_rand(0,99999);

	$rec = match_api::new_search_record();
	$rec->set_SWSector_ID($sector_id);
	$ret = match_api::search($rec);
	$checkforsectoralreadyintable = $ret->get_results();

	if(!count($checkforsectoralreadyintable))
	{
		$rec = match_api::new_detail_record();
		$rec->set_SWCode($swcode);
		$rec->set_SWSquad1($_SESSION['squadid']);
		$rec->set_SWSquad2($challenged_squad);
		$rec->set_SWSector_ID($sector_id);
		$rec->set_League_ID($_POST['leagueid']);
		$status = $rec->save();
		$match_id = $rec->get_MatchID();

		if($status)
		{
			$rec = match_api::new_info_detail_record();
			$rec->set_MatchID($match_id);
			$rec->set_SWCode($swcode);
			$rec->set_time_created(date('Y-m-d H:i:s'));
			$status = $rec->save();
		}
	}
	else
	{
		$message = 'Someone has already challenged for this sector.';
		$_SESSION['challenging'] = 0;
		util::location(RELATIVEPATH.'squads/squadlogin_validate?leagueid='.$_POST['leagueid'].'&message='.urlencode($message));
	}
}
else
{
	$message = "Do not be lame.";
	$_SESSION['challenging'] = 0;
	util::location(RELATIVEPATH.'squads/squadlogin_validate.php?leagueid='.$_POST['leagueid'].'&message='.urlencode($message));
}

$rec = squad_api::new_search_record();
$rec->set_SquadID($_SESSION['squadid']);
$ret = squad_api::search($rec);
$ret = squad_api::populate_info($ret);
$get_challenger = reset($ret->get_results());

$rec->set_SquadID($challenged_squad);
$ret = squad_api::search($rec);
$ret = squad_api::populate_info($ret);
$get_challenged = reset($ret->get_results());

$to = $get_challenger->get_info()->get_Squad_Email();
$from = SUPPORT_EMAIL;
$admin = ADMIN_NAME;
$headers = 'From: SquadWar <' . $from . ">\r\n" .
    'Reply-To: ' . $from . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
$subject = "SquadWar:Challenge - Your Squad has challenged.";
$message = <<<EOT
Your Squad has issued a challenge for sector {$sector_id}.

Please visit the scheduling page available on your Squad Management page to propose times for the match.  

You have 24 hours to complete the first phase of this match.

You can reach your Squad Management page by clicking on the link in the menu on the left side of the page called "Admin Squad".  You can reach the scheduling page by clicking on the link titled "View pending matches for this squad and schedule matches" available on the Squad Management page.

After your Squad proposes times, your opponent will choose one, offer an alternative time, and set battle conditions.

Your squad will then pick the final match time.

Your match code is: {$swcode}
Your opponent is: {$get_challenged->get_SquadName()}
Your opponent's email is: {$get_challenged->get_info()->get_Squad_Email()}

Please contact me if you have any difficulties or questions.


- {$admin}
- SquadWar Administrator
- {$from}

EOT;

mail($to, $subject, $message, $headers);

$to = $get_challenged->get_info()->get_Squad_Email();
$subject = "SquadWar:Challenge - Your Squad has been challenged.";
$message = <<<EOT
Your Squad has been challenged for sector {$sector_id}.

You will be contacted when your opponent has proposed times on the scheduling page. 

Your opponent has 24 hours to complete Phase 1.

After this has been completed you may set the battle conditions and offer an alternative match time.  Please visit the scheduling page available on your Squad Management page.  You can reach your Squad Management page by clicking on the link in the menu on the left side of the page called "Admin Squad".  You can reach the scheduling page by clicking on the link titled "View pending matches for this squad and schedule matches" available on the Squad Management page.

Your opponent's squad will pick the final match time. 

Your match code is: {$swcode}
Your opponent is: {$get_challenger->get_SquadName()}
Your opponent's email is: {$get_challenger->get_info()->get_Squad_Email()}

Please contact me if you have any difficulties or questions.


- {$admin}
- SquadWar Administrator
- {$from}

EOT;

mail($to, $subject, $message, $headers);

$to = SUPPORT_EMAIL;
$subject = "SquadWar:Challenge - Match {$swcode} created";
$message = <<<EOT
Match code is: {$swcode} 
Challenger: {$get_challenger->get_SquadName()}
Challenger's Email: {$get_challenger->get_info()->get_Squad_Email()}
Defender: {$get_challenged->get_SquadName()}
Defender's Email: {$get_challenged->get_info()->get_Squad_Email()}

Battle is for dispute of Sector {$sector_id}
EOT;

mail($to, $subject, $message, $headers);

$message = "You have just created a challenge.  Your match code is: {$swcode}";
$_SESSION['challenging'] = 0;
util::location(RELATIVEPATH.'squads/squadlogin_validate.php?leagueid='.$_POST['leagueid'].'&message='.urlencode($message));
?>

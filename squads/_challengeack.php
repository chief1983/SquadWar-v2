<?php /*
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
include('../bootstrap.php');

$rec = match_api::new_search_record();
$rec->set_SWSquad1($_SESSION['squadid']);
$rec->set_League_ID($_POST['leagueid']);
$ret = match_api::search($rec);
$ret = match_api::populate_sectors($ret);
$count_challenger = $ret->get_results();

$canchallenge = 1;
$canchallengeunclaimed = 1;
$canchallengeowned = 1;

if(count($count_challenger) != 0)
{
	foreach($count_challenger as $match)
	{
		if($match->get_SWSector()->get_SectorSquad() == 0 || $match->get_SWSector()->get_SectorSquad() == '')
		{
			$canchallengeunclaimed = 0;
		}
		else
		{
			$canchallengeowned = 0;
		}
	}
}

if(($canchallengeowned || $canchallengeunclaimed) && $_SESSION['challenging'] == 1)
{
	$rec = match_api::new_search_record();
	$ret = match_api::search($rec);
	$matches = $ret->get_results();

	$sectorparts = explode(',', $_POST['challenge_sector']);
	$sector_id = $sectorparts[0];
	$challenged = $sectorparts[1];
	$rec = squad_api::new_sector_search_record();
	$rec->set_SWSectors_ID($sector_id);
	$ret = squad_api::search($rec);
	$sector = reset($ret->get_results());

	$seed = time();
	mt_srand($seed);
	$code = $_SESSION['squadid'].mt_rand(0,99999);

	$rec = match_api::new_search_record();
	$rec->set_SWSector_ID($sector_id);
	$ret = match_api::search($rec);
	$checkforsectoralreadyintable = $ret->get_results();

	if(count($checkforsectoralreadyintable))
	{
		$message = 'Someone has already challenged for this sector.';
		$_SESSION['challenging'] = 0;
		util::location(RELATIVEPATH.'squads/squadlogin_validate.php?leagueid='.$_POST['leagueid'].'&message='.urlencode($message));
	}

	$rec = match_api::new_detail_record();
	$rec->set_SWCode($code);
	$rec->set_SWSquad1($_SESSION['squadid']);
	$rec->set_SWSquad2($challenged);
	$rec->set_SWSector_ID($sector_id);
	$rec->set_League_ID($_POST['leagueid']);
	$status = $rec->save();
	if($status)
	{
		$match_id = $rec->get_MatchID();
	}
	else
	{
		$message = 'Could not create match.';
		$_SESSION['challenging'] = 0;
		util::location(RELATIVEPATH.'squads/squadlogin_validate.php?leagueid='.$_POST['leagueid'].'&message='.urlencode($message));
	}

	$rec = match_api::new_info_detail_record();
	$rec->set_MatchID($match_id);
	$rec->set_SWCode($code);
	$rec->set_time_created(date('Y-m-d H:i:s'));
	$status = $rec->save();

	$rec = squad_api::new_search_record();
	$rec->set_SquadID($_SESSION['squadid']);
	$ret = squad_api::search($rec);
	$ret = squad_api::populate_info($ret);
	$get_challenger = reset($ret->get_results());
	$rec->set_SquadID($challenged);
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

Your match code is: {$code}
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

Your match code is: {$code}
Your opponent is: {$get_challenger->get_SquadName()}
Your opponent's email is: {$get_challenger->get_info()->get_Squad_Email()}

Please contact me if you have any difficulties or questions.


- {$admin}
- SquadWar Administrator
- {$from}

EOT;

	mail($to, $subject, $message, $headers);

	$to = SUPPORT_EMAIL;
	$subject = "SquadWar:Challenge - Match {$code} created";
	$message = <<<EOT
Match code is: {$code} 
Challenger: {$get_challenger->get_SquadName()}
Challenger's Email: {$get_challenger->get_info()->get_Squad_Email()}
Defender: {$get_challenged->get_SquadName()}
Defender's Email: {$get_challenged->get_info()->get_Squad_Email()}

Battle is for dispute of Sector {$sector_id}
EOT;

	mail($to, $subject, $message, $headers);

	$message = "Your match code is: {$code}";
	$_SESSION['challenging'] = 0;
	util::location(RELATIVEPATH.'squads/squadlogin_validate.php?leagueid='.$_POST['leagueid'].'&message='.urlencode($message));
}
else
{
	$message = 'You can not flood the challenge system by resubmitting the form!';
	$_SESSION['challenging'] = 0;
	util::location(RELATIVEPATH.'squads/squadlogin_validate.php?leagueid='.$_POST['leagueid'].'&message='.urlencode($message));
}

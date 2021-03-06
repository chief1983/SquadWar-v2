<?php /*
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
define('SECURE',1);
include('../bootstrap.php');

$rec = squad_api::new_search_record();
$rec->set_SquadName($_POST['squad_name']);
$ret = squad_api::search($rec);
$check_squads = $ret->get_results();

$rec = user_api::new_search_record();
$rec->set_user_name($_POST['pxo_login']);
$ret = user_api::search($rec);
$get_pxo_stuff = reset($ret->get_results());
if(user_api::check_password($_POST['pxo_login'], $_POST['pxo_password']) !== 1)
{
	$get_pxo_stuff = array();
}

$messages = array();
if(count($check_squads) != 0)
{
	// Squad name already taken
	$messages[] = "That squad name is already taken. Please try another name.";
}
if(empty($get_pxo_stuff))
{
	// No user credentials found
	$messages[] = "Invalid FS2NetD Login or password.";
}
if(empty($_POST['join_password']))
{
	$messages[] = "Empty join password.";
}
if($_POST['join_password'] != $_POST['join_password2'])
{
	$messages[] = "Your Squad join password and confirmation do not match.";
}
if(empty($_POST['admin_password']))
{
	$messages[] = "Empty admin password.";
}
if($_POST['admin_password'] != $_POST['admin_password2'])
{
	$messages[] = "Your Squad admin password and confirmation do not match.";
}
if(!filter_var($_POST['squad_email'], FILTER_VALIDATE_EMAIL))
{
	$messages[] = "Invalid email address.";
}

if(!empty($messages))
{
	util::location('index.php?message='.urlencode(implode("<br />", $messages)));
}

$icq = '';
$link = '';

$rec = squad_api::new_detail_record();
$rec->set_SquadName($_POST['squad_name']);
$rec->set_SquadPassword($_POST['admin_password']);
$rec->set_SquadMembers($get_pxo_stuff->get_id());
$status = $rec->save();
$squad_id = $rec->get_id();

$rec = squad_api::new_info_detail_record();
$rec->set_SquadID($squad_id);
$rec->set_Squad_Leader_ID($get_pxo_stuff->get_id());
$rec->set_Squad_Email($_POST['squad_email']);
$rec->set_Squad_Join_PW($_POST['join_password']);
$rec->set_Squad_Time_Zone($_POST['squad_time_zone']);
if(!empty($_POST['squad_leader_icq']))
{
	$rec->set_Squad_Leader_ICQ($_POST['squad_leader_icq']);
	$icq = $_POST['squad_leader_icq'];
}
if(!empty($_POST['squad_web_link']))
{
	$rec->set_Squad_Web_Link($_POST['squad_web_link']);
	$link = $_POST['squad_web_link'];
}
$status = $rec->save();

$to = SUPPORT_EMAIL;
$from = SUPPORT_EMAIL;
$subject = "SQUADWAR:REGISTRATION:{$_POST['squad_name']}";
$url = 'http://'.$_SERVER['SERVER_NAME'].RELATIVEPATH;
$message = <<<EOT
{$url}admin/_pending_squads.php?squadid={$squad_id}

	{$_POST['squad_name']}
	{$_POST['squad_email']}
	{$_POST['pxo_login']}
	{$get_pxo_stuff->get_id()}
	{$_POST['squad_time_zone']}
	{$icq}
	{$link}
EOT;

// Send the email

$to = $_POST['squad_email'];
$from = SUPPORT_EMAIL;
$from_name = ADMIN_NAME;
$subject = "Thank you for Registering a SquadWar Squadron";
$message = <<<EOT
Thank you for signing up for SquadWar. Your team will be contacted via email when your Squad has been approved by the Administrator. Please note that all Squad Names are subject to approval from the SquadWar administrator. 

Squad Name: {$_POST['squad_name']}
Squad ID Number: {$squad_id}
Squad Admin Password: {$_POST['admin_password']}
Squad Join Password: {$_POST['join_password']}

Please keep this email for reference purposes.


- {$from_name}
- SquadWar Administrator
- {$from}
EOT;

// Send the other email

util::location('index.php?message=Thank%20you%20for%20signing%20up%20for%20Squad%20War.%20%20Your%20team%20will%20be%20contacted%20via%20email%20when%20we%20are%20ready%20for%20the%20next%20phase.');
?>

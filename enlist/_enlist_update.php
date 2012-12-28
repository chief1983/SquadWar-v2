<?php /*
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
define('SECURE',1);
include('../bootstrap.php');

$message = array();
if(!empty($_POST['pilotid']))
{
	$pilot = fsopilot_api::get($_POST['pilotid']);
	if(!empty($pilot))
	{
		$user_id = $pilot->get_user_id();
		if($user_id != $_SESSION['user_id'])
		{
			// This user shouldn't be modifying this pilot, wrong user_id.
			$message[] = "You don't have permission to edit this pilot.";
		}
	}
}
else
{
	$message[] = "No pilot ID.";
}

if(empty($_POST['action']) || !in_array($_POST['action'], array('update','add')))
{
	$message[] = "Invalid action.";
}
if(empty($_POST['email']) || !util::check_email_address($_POST['email']))
{
	$message[] = "Invalid email.";
}

if(empty($message))
{
	if($_POST['action'] == 'update')
	{
		$ret = fsopilot_api::get_swpilot($_POST['pilotid']);
		$rec = reset($ret->get_results());
		$rec->set_ICQ($_POST['ICQ']);
	}
	elseif($_POST['action'] == 'add')
	{
		$rec = fsopilot_api::new_swpilot_detail_record();
		$rec->set_PilotID($_POST['pilotid']);
		$rec->set_user_id($pilot->get_user_id());
		$rec->set_Pilot_Name($pilot->get_pilot_name());
		if(!empty($_POST['ICQ']))
		{
			$rec->set_ICQ($_POST['ICQ']);
		}
		$rec->set_Active(1);
	}
	$rec->set_time_zone($_POST['time_zone']);
	$rec->set_connection_type($_POST['connection_type']);
	$rec->set_Recruitme(1);
	$rec->set_email($_POST['email']);
	$rec->set_show_email(($_POST['show_email'] == 'on') ? 1 : 0);
	$status = $rec->save();
	util::location('enlist.php?action=add');
}

$document_title = 'SquadWar - Enlist a Pilot';

include(BASE_PATH.'doc_top.php');

define('ENLIST',1);
include(BASE_PATH.'menu/main.php');

include(BASE_PATH.'doc_mid.php');
				// MAIN PAGE INFO
?>

					<p>
					<font face="arial" color="FF0000"><b><?php echo implode("<br />\n", $message); ?></b></font></p>
					<p>
					Please try again, an error has been detected in your submission.<br /> 								
					If you have any further questions, feel free to contact <a href="mailto:<?php echo SUPPORT_EMAIL; ?>"><?php echo SUPPORT_EMAIL; ?></a>.</p>			

				<?php // END MAIN PAGE INFO ?>
<?php include(BASE_PATH.'doc_bot.php'); ?>

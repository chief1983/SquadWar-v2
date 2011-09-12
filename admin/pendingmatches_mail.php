<?php /*
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
include('../bootstrap.php');

$document_title = 'SquadWar - Pending Matches Mail';

$rec = match_api::new_search_record();
$ret = match_api::search($rec);
$ret = match_api::populate_info($ret);
$ret = match_api::populate_sectors($ret);
$ret = match_api::populate_squads($ret, true);
$get_matches = $ret->get_results();



include(BASE_PATH.'doc_top.php');

include(BASE_PATH.'menu/main.php');

define('HIDELOGIN',1);
include(BASE_PATH.'doc_mid.php');
				// MAIN PAGE INFO
?>
<div class="copy">
Current game time is: <?php echo time(); ?>
</div>

				<table width="98%" cellpadding="0" cellspacing="0" border="0">
				<?php if(count($get_matches)):
					$coloredrow = 0;
					$thisdate = ''; ?>
						<tr><td colspan="7" align="center"><div class="title">Pending Matches</div></td></tr>
						<?php foreach($get_matches as $match): ?>
							<tr<?php if(++$coloredrow % 2): ?> bgcolor="#0B160D"<?php endif; ?>>
										<td>
											<div class="copy">
												<span<?php if($match->get_info()->get_time_created() < time() - 7 * 24 * 60 * 60): ?> style="color:red;"<?php endif; ?>>
												<b><?php echo date('n.j.y', strtotime($match->get_info()->get_time_created())); ?></b>
												</span>
											</div>
										</td>
										<td><div class="copy">&nbsp;<?php echo $match->get_SWCode(); ?>&nbsp;</div></td>
										<td>
											<div class="copy">
												<?php echo $match->get_Challenger()->get_SquadName(); ?>
												Challenged
												<?php echo $match->get_Challenged()->get_SquadName(); ?>
												for control of Sector 
												<?php echo $match->get_SWSector_ID(); ?>
												<?php echo $match->get_SWSector()->get_SectorName(); ?>
											</div>
										</td>
										<td>
											<div class="copy">&nbsp;<?php echo $match->get_SWSquad1(); ?>&nbsp;</div>
										</td>
										<td>
											<div class="copy">
												<a href="mailto:<?php echo $match->get_Challenger()->get_info()->get_Squad_Email(); ?>"><?php echo $match->get_Challenger()->get_info()->get_Squad_Email(); ?></a>
											</div>
										</td>
										<td>
											<div class="copy">&nbsp;<?php echo $match->get_SWSquad2(); ?>&nbsp;</div>
										</td>												
										<td>
											<div class="copy">
												<a href="mailto:<?php echo $match->get_Challenged()->get_info()->get_Squad_Email(); ?>"><?php echo $match->get_Challenged()->get_info()->get_Squad_Email(); ?></a>
											</div>
										</td>		
									</tr>
<?php

if($match->get_info()->get_time_created() < time() - 7 * 24 * 60 * 60)
{
	$to = $match->get_Challenger()->get_info()->get_Squad_Email();
	$date = date('n.j.y', strtotime($match->get_info()->get_time_created()));
	$from = SUPPORT_EMAIL;
	$admin = ADMIN_NAME;
	$headers = 'From: SquadWar <' . $from . ">\r\n" .
	    'Reply-To: ' . $from . "\r\n" .
	    'X-Mailer: PHP/' . phpversion();
	$subject = "SquadWar:Challenge:Delinquent Match - ".$match->get_SWCode();
	$message = <<<EOT

Respond directly to this email!  If your squad receives multiple emails regarding outstanding matches, reply to each one individually so the subject stays intact!
										
Your squad has an outstanding SquadWar match.  Please respond with the current status and situation of the match listed below.  Please respond within 24 hours or the Administrator may force a forfeit of your match.  If you have already sent email to the Administrator regarding the status of this match, please do so again as this is a bulk emailing.

Match Code: {$match->get_SWCode()}
Created On: {$date}
{$match->get_Challenger()->get_SquadName()} Challenged {$match->get_Challenged()->get_SquadName()} for control of Sector {$match->get_SWSector_ID()} {$match->get_SWSector()->get_SectorName()}

Please contact me if you have any difficulties or questions.


- {$admin}
- SquadWar Administrator
- {$from}

EOT;

	mail($to, $subject, $message, $headers);

	$to = $match->get_Challenged()->get_info()->get_Squad_Email();
	mail($to, $subject, $message, $headers);
}
?>
						<?php endforeach; ?>
						<tr<?php if(++$coloredrow % 2): ?> bgcolor="#0B160D"<?php endif; ?>>
							<td colspan="7" align="center">
								<div class="copy">There are <?php echo count($get_matches); ?> matches pending in this league</div>
							</td>
						</tr>
				<?php endif; // at least one match ?>
				</table>	
					
				<?php // END MAIN PAGE INFO ?>
<?php include(BASE_PATH.'doc_bot.php'); ?>

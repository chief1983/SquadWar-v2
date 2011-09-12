<?php /*
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
include('../bootstrap.php');

$rec = match_api::new_search_record();
$ret = match_api::search($rec);
$ret = match_api::populate_info($ret);
$ret = match_api::populate_squads($ret, true);
$ret = util::sort_results_on_child_data($ret, 'info', 'time_created', array(SORT_STRING));
$get_pending_matches = $ret->get_results();

/* start comment */
?>
<table border="1" cellspacing="0">
	<tr>
		<td>SWCode</td>
		<td>time_created</td>
		<td>SWSquad1</td>
		<td>SWSquad2</td>
		<td>SWSector_ID</td>
		<td>League_ID</td>
		<td>match_time1</td>
		<td>match_time2</td>
		<td>proposed_final_time</td>
		<td>proposed_alternate_time</td>
		<td>squad_last_proposed</td>
		<td>final_match_time</td>
		
		<td>dispute</td>
		<td>status_last_changed</td>
		<td>phase</td>
	</tr>
<?php foreach($get_pending_matches as $match): ?>
		<tr>
			<td><?php echo $match->get_SWCode(); ?>&nbsp;</td>
			<td><?php echo $match->get_info()->get_time_created(); ?>&nbsp;</td>
			<td><?php echo $match->get_SWSquad1(); ?>&nbsp;</td>
			<td><?php echo $match->get_SWSquad2(); ?>&nbsp;</td>
			<td><?php echo $match->get_SWSector_ID(); ?>&nbsp;</td>
			<td><?php echo $match->get_League_ID(); ?>&nbsp;</td>
			<td><?php echo $match->get_info()->get_match_time1(); ?>&nbsp;</td>
			<td><?php echo $match->get_info()->get_match_time2(); ?>&nbsp;</td>
			<td><?php echo $match->get_info()->get_proposed_final_time(); ?>&nbsp;</td>
			<td><?php echo $match->get_info()->get_proposed_alternate_time(); ?>&nbsp;</td>
			<td><?php echo $match->get_info()->get_squad_last_proposed(); ?>&nbsp;</td>
			<td><?php echo $match->get_info()->get_final_match_time(); ?>&nbsp;</td>
			<td><?php echo $match->get_info()->get_dispute(); ?>&nbsp;</td>
			<td><?php echo $match->get_info()->get_status_last_changed(); ?>&nbsp;</td>	
			<td><?php $current_phase = $match->get_info()->get_current_phase(); ?>
				<?php if($current_phase < 4): ?>
					<?php if($current_phase == 1): ?> Created
					<?php else: ?> Phase <?php echo $current_phase; ?>
					<?php endif; ?>
				<?php else: ?>
					<?php echo date('F j, Y g:i A', strtotime($match->get_info()->get_final_match_time())); ?>
				<?php endif; ?>
			</td>	
		</tr>	
<?php endforeach; ?>
</table>
<?php /* end comment */ ?>
<table border="0">
<?php foreach($get_pending_matches as $match): ?>
	<tr>
		<td><?php echo $match->get_SWCode(); ?></td>
		<td><?php echo $match->get_info()->get_time_created(); ?></td>
		<td><?php echo $match->get_info()->get_status_last_changed(); ?></td>
		<td><?php $current_phase = $match->get_info()->get_current_phase(); ?>
				<?php if($current_phase < 4): ?>
					<?php if($current_phase == 1): ?> Created
					<?php else: ?> Phase <?php echo $current_phase; ?>
					<?php endif; ?>
				<?php else: ?>
					Phase 4
					<?php echo date('F j, Y g:i A', strtotime($match->get_info()->get_final_match_time())); ?>
				<?php endif; ?>
				<?php
					$overdue = 0;
					$pastdue = 0;
					if($current_phase == 2 && strtotime($match->get_info()->get_status_last_changed()) < time() - 2 * 24 * 60 * 60): $overdue = 1; endif;
					if($current_phase >= 4 && strtotime($match->get_info()->get_final_match_time()) < time() - 2 * 24 * 60 * 60): $overdue = 1; $pastdue = 1; endif;
					if($match->get_info()->get_status_last_changed() != '0000-00-00 00:00:00'):
						if($current_phase < 4 && strtotime($match->get_info()->get_status_last_changed()) < time() - 2 * 24 * 60 * 60):
							$overdue = 1;
						endif;
				?>hooo
				<?php
					else:
				?>whee!
				<?php
						if(strtotime($match->get_info()->get_time_created()) <= time() - 24 * 60 * 60): $overdue = 1; endif;
					endif;
				?>
		</td>
		<td>
			<?php if($overdue): ?>
				<a href="overdue.php?id=<?php echo $match->get_SWCode(); ?>"><?php if($pastdue): ?>pastdue<?php else: ?>overdue<?php endif; ?></a>
			<?php endif; ?>
		</td>
		<td>
			<cfset winner=''>
			<cfset loser=''>
				<br />
			<?php if($overdue): ?>
				<?php
				switch($current_phase)
				{
					case 1: ?>
					This match has only been created.<br />
					This match should be forfeit in favor of the defender.<br />
					<?php
					$winner = $match->get_SWSquad2();
					$match_winner = $match->get_Challenged();
					$loser = $match->get_SWSquad1();
					$match_loser = $match->get_Challenger();
					break;
					case 2: ?>
					This match is in phase 2.<br />
					This match should be forfeit in favor of the challenger.<br />
					<?php
					$winner = $match->get_SWSquad1();
					$match_winner = $match->get_Challenger();
					$loser = $match->get_SWSquad2();
					$match_loser = $match->get_Challenged();
					break;
					case 3: ?>
					This match is in phase 3.<br />
					This match should be forfeit in favor of the defender.<br />
					<?php
					$winner = $match->get_SWSquad2();
					$match_winner = $match->get_Challenged();
					$loser = $match->get_SWSquad1();
					$match_loser = $match->get_Challenger();
					break;
					case 4: ?>
					This match is in phase 4.<br />
					Either team has 48 hours to report a no-show or request a reschedule.<br />
					<?php
					if($pastdue):
						$winner = $match->get_SWSquad1();
						$match_winner = $match->get_Challenger();
						$loser= $match->get_SWSquad2();
						$match_loser = $match->get_Challenged();
					endif;
					break;
				}
				?>

				<?php $ignore = 0; ?>
				<?php if($match->get_info()->get_swsquad1_protest() || $match->get_info()->get_swsquad2_protest()): $ignore = 1; endif; ?>
				<?php if(!empty($winner) && !$ignore): ?>
					<br />
					This match requires an update.

					<?php if($current_phase < 4): ?>
					database interaction<br />
					SWSquad1: <?php echo $match->get_SWSquad1(); ?><br />
					SWSquad2: <?php echo $match->get_SWSquad2(); ?><br />

					winner: <?php echo $winner; ?> - <?php echo $match_winner->get_SquadName(); ?> - <?php echo $match_winner->get_info()->get_Squad_Email(); ?><br />
					loser: <?php echo $loser; ?> - <?php echo $match_loser->get_SquadName(); ?> - <?php echo $match_loser->get_info()->get_Squad_Email(); ?><br />
					<?php echo $match->get_SWCode() .' '. $match->get_SWSquad1() .' '. $match->get_SWSquad2() .' '. $match->get_SWSector_ID() .' '. $winner .' '. $loser .' '. time() .' '. $match->get_League_ID(); ?><br />


					<?php
					$rec = squad_api::new_matchhistory_detail_record();
					$rec->set_MatchID($match->get_MatchID());
					$rec->set_SWCode($match->get_SWCode());
					$rec->set_SWSquad1($match->get_SWSquad1());
					$rec->set_SWSquad2($match->get_SWSquad2());
					$rec->set_SWSector_ID($match->get_SWSector_ID());
					$rec->set_match_victor($winner);
					$rec->set_match_loser($loser);
					$rec->set_match_time(time());
					$rec->set_League_ID($match->get_League_ID());
					$rec->set_special(1);
					$rec->save();

					$rec = squad_api::new_sector_search_record();
					$rec->set_SWSectors_ID($match->get_SWSector_ID());
					$ret = squad_api::search($rec);
					$sector = reset($ret->get_results());
					$sector->set_SectorSquad($winner);
					$sector->set_SectorTime(time());
					$sector->save();

					// email to winner
					$to = $match_winner->get_info()->get_Squad_Email();
					$from = SUPPORT_EMAIL;
					$admin = ADMIN_NAME;
					$headers = 'From: SquadWar <' . $from . ">\r\n" .
					    'Reply-To: ' . $from . "\r\n" .
					    'X-Mailer: PHP/' . phpversion();
					$subject = $match->get_SWCode()." - Auto-Forfeit System Notification";
					$message = <<<EOT
The Auto-Forfeit system has updated match {$match->get_SWCode()}.

The match was stuck in phase {$current_phase}.  Therefore, your squad was awarded the forfeit for this match versus {$match_loser->get_SquadName()}.

For more information on the SquadWar rules, visit the rules page at:
http://www.squadwar.com/rules/

Thanks,

- {$admin}
- SquadWar Administrator
- {$from}

Message delivered via the auto-forfeit system.

**Please leave all correspondence intact in your reply**
EOT;
					mail($to, $subject, $message, $headers);

					// email to loser
					$to = $match_loser->get_info()->get_Squad_Email();
					$message = <<<EOT
The Auto-Forfeit system has updated match {$match->get_SWCode()}.

The match was stuck in phase {$current_phase} for longer than the allowed time.  Therefore, your squad forfeits this match versus {$match_winner->get_SquadName()}.

For more information on the SquadWar rules, visit the rules page at:
http://www.squadwar.com/rules/

Thanks,

- {$admin}
- SquadWar Administrator
- {$from}

Message delivered via the auto-forfeit system.

**Please leave all correspondence intact in your reply**
EOT;
					mail($to, $subject, $message, $headers);

					$match->delete();
					?>

					<?php else: // Not phase 4 ?>
					<?php // Phase 4 ?>
					<?php $winner = ''; ?>
					<?php $loser = ''; ?>
					<?php if($pastdue): ?>
					<?php if(!$match->get_info()->get_mail_sent()): ?>
						<?php if($match->get_info()->get_swsquad1_reports_noshow() && $match->get_info()->get_swsquad2_reports_noshow()): ?>
							<?php
							// don't update but send mail
							$to = $match->get_Challenger()->get_info()->get_Squad_Email();
							$from = SUPPORT_EMAIL;
							$admin = ADMIN_NAME;
							$headers = 'From: SquadWar <' . $from . ">\r\n" .
							    'Reply-To: ' . $from . "\r\n" .
							    'X-Mailer: PHP/' . phpversion();
							$subject = $match->get_SWCode()." - Auto-Forfeit System Notification";
							$message = <<<EOT
The Auto-Forfeit system is reporting the status of match {$match->get_SWCode()}.

The match did not occur in the scheduled time and both squads reported a no-show.  You will be contacted by an administrator regarding the status of this match.

For more information on the SquadWar rules, visit the rules page at:
http://www.squadwar.com/rules/

Thanks,

- {$admin}
- SquadWar Administrator
- {$from}

Message delivered via the auto-forfeit system.

**Please leave all correspondence intact in your reply**
EOT;
							mail($to, $subject, $message, $headers);
							$to = $match->get_Challenged()->get_info()->get_Squad_Email();
							mail($to, $subject, $message, $headers);

							$info = $match->get_info();
							$info->set_mail_sent(1);
							$info->save();
							$match->set_info($info);
							?>

						<?php elseif($match->get_info()->get_swsquad1_reports_noshow()): ?>
							<?php $winner = $match->get_SWSquad1(); ?>
							<?php $match_winner = $match->get_Challenger(); ?>
							<?php $loser = $match->get_SWSquad2(); ?>
							<?php $match_loser = $match->get_Challenged(); ?>
						<?php elseif($match->get_info()->get_swsquad2_reports_noshow()): ?>
							<?php $winner = $match->get_SWSquad2(); ?>
							<?php $match_winner = $match->get_Challenged(); ?>
							<?php $loser = $match->get_SWSquad1(); ?>
							<?php $match_loser = $match->get_Challenger(); ?>
						<?php else:
							$info = $match->get_info();
							$info->delete();
							$match->delete();
						endif; ?>

						<?php if(!empty($winner)):
							$rec = squad_api::new_matchhistory_detail_record();
							$rec->set_MatchID($match->get_MatchID());
							$rec->set_SWCode($match->get_SWCode());
							$rec->set_SWSquad1($match->get_SWSquad1());
							$rec->set_SWSquad2($match->get_SWSquad2());
							$rec->set_SWSector_ID($match->get_SWSector_ID());
							$rec->set_match_victor($winner);
							$rec->set_match_loser($loser);
							$rec->set_match_time(time());
							$rec->set_League_ID($match->get_League_ID());
							$rec->set_special(1);
							$rec->save();
		
							$rec = squad_api::new_sector_search_record();
							$rec->set_SWSectors_ID($match->get_SWSector_ID());
							$ret = squad_api::search($rec);
							$sector = reset($ret->get_results());
							$sector->set_SectorSquad($winner);
							$sector->set_SectorTime(time());
							$sector->save();

							// email to winner
							$to = $match_winner->get_info()->get_Squad_Email();
							$from = SUPPORT_EMAIL;
							$admin = ADMIN_NAME;
							$headers = 'From: SquadWar <' . $from . ">\r\n" .
							    'Reply-To: ' . $from . "\r\n" .
							    'X-Mailer: PHP/' . phpversion();
							$subject = $match->get_SWCode()." - Auto-Forfeit System Notification";
							$message = <<<EOT
The Auto-Forfeit system has updated match {$match->get_SWCode()}.

The match was overdue and only your squad reported a no-show.  Therefore, your squad was awarded the forfeit for this match versus {$match_loser->get_SquadName()}.

For more information on the SquadWar rules, visit the rules page at:
http://www.squadwar.com/rules/

Thanks,

- {$admin}
- SquadWar Administrator
- {$from}

Message delivered via the auto-forfeit system.

**Please leave all correspondence intact in your reply**
EOT;
							mail($to, $subject, $message, $headers);

							// email to loser
							$to = $match_loser->get_info()->get_Squad_Email();
							$message = <<<EOT
The Auto-Forfeit system has updated match {$match->get_SWCode()}.

The match was overdue and your squad did not report a no-show.  Therefore, your squad forfeits this match versus {$match_winner->get_SquadName()}.

For more information on the SquadWar rules, visit the rules page at:
http://www.squadwar.com/rules/

Thanks,

- {$admin}
- SquadWar Administrator
- {$from}

Message delivered via the auto-forfeit system.

**Please leave all correspondence intact in your reply**
EOT;
							mail($to, $subject, $message, $headers);
							$match->delete();
						?>
						<?php endif; // winner not empty ?>
												
					<?php endif; // !mail_sent ?>
					
					
					<?php endif; // pastdue ?>
					<?php endif; // Phase 4 ?>
				<?php endif; // needed an update ?>
				<hr />
			<?php endif; // overdue ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>

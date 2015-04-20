<?php /* 
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
include('../bootstrap.php');

$get_squad = squad_api::get($_SESSION['squadid']);
util::prepend_title($get_squad->get_SquadName());
util::prepend_title('Scheduling System');

$rec = match_api::new_search_record();
$rec->set_either_squad($_SESSION['squadid']);
$rec->set_SWCode($_GET['matchid']);
$ret = match_api::search($rec);
$ret = match_api::populate_info($ret);
$ret = match_api::populate_sectors($ret);
$ret = match_api::populate_squads($ret, true);
$ret = util::sort_results_on_child_data($ret, 'info', 'time_created', array(SORT_DESC));
$get_match = reset($ret->get_results());

if($get_match)
{
	$info = $get_match->get_info();
	$current_phase = $info->get_current_phase();
}

if($current_phase == 2)
{
	// Get mission list, instead of using form_missions.txt.
	$rec = mission_api::new_search_record();
	$rec->set_status(1);
	$ret = mission_api::search($rec);
	$missions = $ret->get_results();
}

$script = <<<EOT
<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery("#match_claim_form").validate(
		{
			submitHandler: function(f){
				jQuery('#match_claim_form input[type=submit]').attr('disabled', 'disabled');
				jQuery('#pleasewait').toggle()
				form.submit();
			}
		});
	});
</script>
EOT;
util::push_head($script);

include(BASE_PATH.'doc_top.php');

include(BASE_PATH.'menu/main.php');

include(BASE_PATH.'doc_mid.php');
				// MAIN PAGE INFO
?>
<table width="90%" cellpadding="0" cellspacing="0" border="0">
<?php if($get_match):
	$coloredrow = 0;
	$thisdate = ''; ?>
		<tr><td colspan="4" align="center"><div class="title">Pending Match <?php echo $_GET['matchid']; ?></div></td></tr>
		<tr>
			<td><div class="copy">Date</div></td>
			<td><div class="copy">Code</div></td>
			<td><div class="copy">Description</div></td>
			<td><div class="copy">Opponent's Email</div></td>
		</tr>
	<?php if($get_match): ?>
		<tr<?php if(++$coloredrow % 2): ?> bgcolor="#0B160D"<?php endif; ?>>
			<td>
				<div class="copy">
					<span<?php if(strtotime($info->get_time_created()) < time() - 7 * 24 * 60 * 60): ?> style="color:red;"<?php endif; ?>>
					<b><?php echo date('n.j.y', strtotime($info->get_time_created())); ?></b>
					</span>
				</div>
			</td>
			<td><div class="copy">&nbsp;<?php echo $get_match->get_SWCode(); ?>&nbsp;</div></td>
			<td>
				<div class="copy">
					<?php if($get_match->get_SWSquad1() != $_SESSION['squadid']): ?>
					<a href="squadinfo.php?id=<?php echo $get_match->get_SWSquad1(); ?>"><?php endif; echo $get_match->get_Challenger()->get_SquadName(); if($get_match->get_SWSquad1() != $_SESSION['squadid']): ?></a>
					<?php endif; ?>
					Challenged
					<?php if($get_match->get_SWSquad2() != $_SESSION['squadid']): ?>
					<a href="squadinfo.php?id=<?php echo $get_match->get_SWSquad2(); ?>"><?php endif; echo $get_match->get_Challenged()->get_SquadName(); if($get_match->get_SWSquad2() != $_SESSION['squadid']): ?></a>
					<?php endif; ?>
					for control of Sector 
					<?php echo $get_match->get_SWSector_ID(); ?>
					<?php echo $get_match->get_SWSector()->get_SectorName(); ?>
				</div>
			</td>
			<td>
				<div class="copy">
				<?php if($get_match->get_SWSquad1() != $_SESSION['squadid']): ?>
					<a href="mailto:<?php echo $get_match->get_Challenger()->get_info()->get_Squad_Email(); ?>"><?php echo $get_match->get_Challenger()->get_info()->get_Squad_Email(); ?></a>
				<?php else: ?>
					<a href="mailto:<?php echo $get_match->get_Challenged()->get_info()->get_Squad_Email(); ?>"><?php echo $get_match->get_Challenged()->get_info()->get_Squad_Email(); ?></a>
				<?php endif; ?>
				</div>
			</td>
		</tr>
	<?php endif; ?>
<?php else: ?>
	<tr><td colspan="3"><div class="title">Your squad is not authorized to view the information for this match.</div></td></tr>
<?php endif; ?>
</table>

<br />

<?php if($get_match): ?>
<?php // START PHASE 1 ?>
<?php if($current_phase == 1): ?>
	<hr />
	<br />
	<?php if($get_match->get_SWSquad1() == $_SESSION['squadid']): ?>
		<div class="title">
			Phase 1: Propose two times to play this match.
		</div>
		<br />

		<form action="_propose_phase_1.php" method="post">
		<table class="center">
			<tr>
				<th><font color="white">&nbsp;</font></th>
				<th><font color="white">Date</font></th>
				<th colspan="3"><font color="white">Time</font></th>
				<th><font color="white">Time Zone</font></th>
			</tr>
			<?php for($i = 1; $i <= 2; $i++): ?>
			<tr>
				<td style="vertical-align:top;"><div class="copy"><nobr>Option <?php echo $i; ?>:</nobr></div></td>
				<td style="vertical-align:top;">
					<div class="copy">
						<select name="date<?php echo $i; ?>">
						<?php for($day_add = 6; $day_add <= 8; $day_add++): ?>
							<option value='<?php echo $day_add; ?>'><?php echo date('F j Y', strtotime($info->get_time_created()) + $day_add * 24 * 60 * 60); ?></option>
						<?php endfor; ?>
						</select>																
					</div>										
				</td>											
				<td style="vertical-align:top;">
					<div class="copy">											
						<select name="hour<?php echo $i; ?>">
						<?php for($hour = 0; $hour <= 23; $hour++): ?>
							<option value='<?php echo $hour; ?>'><?php if($hour < 10): echo '0'; endif; echo $hour; ?></option>
						<?php endfor; ?>
						</select>
					</div>
				<td><div class="copy"><font color="white"><b>:</b></font></div></td>
				<td style="vertical-align:top;">
					<div class="copy">													
						<select name="minute<?php echo $i; ?>">
						<?php for($minute = 0; $minute <= 45; $minute = $minute + 15): ?>
							<cfoutput><option value='<?php echo $minute; ?>'><?php if($minute < 10): echo '0'; endif; echo $minute; ?></cfoutput>
						<?php endfor; ?>
						</select>																
					</div>										
				</td>
				<td style="vertical-align:top;">
					<div class="copy">																		
						All times are <?php echo date('T'); ?>.														
					</div>
				</td>	
			</tr>
			<?php endfor; ?>
		</table>
		<br />
		<input type="hidden" name="SWCode" value="<?php echo $get_match->get_SWCode(); ?>" />
		<center><input type="submit" value="Propose these times" /></center>
		</form>
		
	<?php else: ?>
		<div class="title">
			Phase 1: Waiting for challenging squad to propose a time to play this match.
		</div>								
	<?php endif; ?>
<?php else: ?>
	<hr />
	<br />
	<div class="title">
		Phase 1 (completed): The initial proposed times for the match are...
	</div>
	<br />
	<div class="copy">
		<b>Option 1:</b> <?php echo date('F j, Y g:i A', strtotime($info->get_match_time1())); ?><br />
		<b>Option 2:</b> <?php echo date('F j, Y g:i A', strtotime($info->get_match_time2())); ?><br />
	</div>
		
<?php endif; ?>
<?php // END PHASE 1 ?>


<?php // START PHASE 2 ?>
<?php if($current_phase == 2): ?>
	<hr />
	<br />
	<?php if($get_match->get_SWSquad2() == $_SESSION['squadid']): ?>
		<div class="title">
			Phase 2: Set the battle parameters of this match.
		</div>
		<br />

		<form action="_propose_phase_2.php" method="post">
		<table class="center">
			<tr>
				<td align="right"><div class="copy"><b>Mission:</b></div></td>
				<td>&nbsp;&nbsp;</td>
				<td>
					<select name="mission">
						<?php foreach($missions as $mission): ?>
						<option value="<?php echo $mission->get_filename(); ?>"><?php echo $mission->get_filename(); ?> <?php echo $mission->get_name(); ?></option>
						<?php endforeach; ?>
					</select>										
				</td>
			</tr>
			<tr>
				<td align="right"><div class="copy"><b>Number of Pilots:</b></div></td>
				<td>&nbsp;&nbsp;</td>
				<td>
					<select name="pilots">
						<?php include(BASE_PATH.'forms/form_pilots.txt'); ?>
					</select>										
				</td>										
			</tr>
			<tr>
				<td align="right"><div class="copy"><b>AI Pilots:</b></div></td>
				<td>&nbsp;&nbsp;</td>
				<td>
					<select name="ai">
						<?php include(BASE_PATH.'forms/form_ai.txt'); ?>
					</select>										
				</td>										
			</tr>		
			<tr>
				<td align="right"><div class="copy"><b>Time Option:</b></div></td>
				<td>&nbsp;&nbsp;</td>
				<td>
					<select name="proposed_final_time">
						<option value="<?php echo date('Y,n,j,H,i,s', strtotime($info->get_match_time1())); ?>"><?php echo date('F j, Y g:i A', strtotime($info->get_match_time1())); ?></option>
						<option value="<?php echo date('Y,n,j,H,i,s', strtotime($info->get_match_time2())); ?>"><?php echo date('F j, Y g:i A', strtotime($info->get_match_time2())); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right"><div class="copy"><b>Alternate Time:</b></div></td>
				<td>&nbsp;&nbsp;</td>
				<td>
					<table cellpadding="0" cellspacing="0">
						<tr>
							<td style="vertical-align:top;">
								<div class="copy">
									<select name="date1">
										<option value='<?php echo date('Y,n,j', strtotime($info->get_match_time1())); ?>'><?php echo date('F j, Y', strtotime($info->get_match_time1())); ?></option>
										<option value='<?php echo date('Y,n,j', strtotime($info->get_match_time2())); ?>'><?php echo date('F j, Y', strtotime($info->get_match_time2())); ?></option>
									</select>
								</div>
							</td>
							<td><div class="copy">&nbsp;at&nbsp;</div></td>
							<td style="vertical-align:top;">
								<div class="copy">
									<select name="hour1">
									<?php for($hour = 0; $hour <= 23; $hour++): ?>
										<option value='<?php echo $hour; ?>'><?php if($hour < 10): echo '0'; endif; echo $hour; ?></option>
									<?php endfor; ?>
									</select>
								</div>
							</td>
							<td><div class="copy"><font color="white"><b>:</b></font></div></td>
							<td style="vertical-align:top;">
								<div class="copy">													
									<select name="minute1">
									<?php for($minute = 0; $minute <= 45; $minute += 15): ?>
										<option value='<?php echo $minute; ?>'><?php if($minute < 10): echo '0'; endif; echo $minute; ?></option>
									<?php endfor; ?>
									</select>
								</div>
							</td>
							<td style="vertical-align:top;">
								<div class="copy">
									&nbsp;All times are <?php echo date('T'); ?>.
								</div>
							</td>	
						</tr>
					</table>									
				</td>										
			</tr>																								
		</table>

		<input type="hidden" name="SWCode" value="<?php echo $get_match->get_SWCode(); ?>" />
		<center><input type='submit' value='Set Battle Parameters' /></center>
		</form>

	<?php else: ?>
		<div class="title">
			Phase 2: Waiting for challenged squad to set battle parameters this match.
		</div>								
	<?php endif; ?>
<?php elseif($current_phase > 2): ?>
	<hr />
	<br />
	<div class="title">
		Phase 2 (completed): The battle paramaters for the match are...
	</div>
	<br />
	<table>
		<tr>
			<td align="right"><div class="copy"><b>Mission:</b></div></td>
			<td>&nbsp;&nbsp;</td>
			<td>
				<div class="copy">
					<?php echo $info->get_Mission(); ?>
				</div>								
			</td>
		</tr>
		<tr>
			<td align="right"><div class="copy"><b>Number of Pilots:</b></div></td>
			<td>&nbsp;&nbsp;</td>
			<td>
				<div class="copy">
					<?php echo $info->get_Pilots(); ?>
				</div>											
			</td>										
		</tr>
		<tr>
			<td align="right"><div class="copy"><b>AI Pilots:</b></div></td>
			<td>&nbsp;&nbsp;</td>
			<td>
				<div class="copy">
					<?php echo $info->get_AI(); ?>
				</div>										
			</td>										
		</tr>																																	
	</table>
<?php endif; ?>
<?php // END PHASE 2 ?>


<?php // START PHASE 3 ?>
<?php if($current_phase == 3): ?>
	<hr />
	<br />
	<?php if($get_match->get_SWSquad1() == $_SESSION['squadid']): ?>
		<div class="title">
			Phase 3: Set the final time for this match.
		</div>
		<br />
		<form action="_propose_phase_3.php" method="post">
		<table>
			<tr>
				<td align="right"><div class="copy"><b>Time Option:</b></div></td>
				<td>&nbsp;&nbsp;</td>
				<td>
					<select name="final_time">
						<option value="<?php echo date('Y,n,j,H,i,s', strtotime($info->get_proposed_final_time())); ?>"><?php echo date('F j, Y g:i A', strtotime($info->get_proposed_final_time())); ?></option>
						<option value="<?php echo date('Y,n,j,H,i,s', strtotime($info->get_proposed_alternate_time())); ?>"><?php echo date('F j, Y g:i A', strtotime($info->get_proposed_alternate_time())); ?></option>
					</select>										
				</td>										
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>
					<input type="hidden" name="SWCode" value="<?php echo $get_match->get_SWCode(); ?>" />
					<input type='submit' value='Set Final Time' />
				</td>
			</tr>
		</table>
		</form>
		
	<?php else: ?>
		<div class="title">
			Phase 3: Waiting for challenging squad to set the final time for this match.
		</div>								
	<?php endif; ?>
<?php elseif($current_phase > 3): ?>
		<hr />
		<br />
		<div class="title">
			Phase 3 (completed): The final time for this match is:
		</div>
		<br />
		<table>
			<tr>
				<td align="right"><div class="copy"><b>Final match time:</b></div></td>
				<td>&nbsp;&nbsp;</td>
				<td>
					<div class="copy">
						<?php echo date('F j, Y g:i A', strtotime($info->get_final_match_time())); ?>
					</div>								
				</td>
			</tr>																																
		</table>
<?php endif; ?>
<?php // END PHASE 3 ?>
<?php if($current_phase == 4): ?>
	<hr />
	<br />
	<div class="title">
		Phase 4: Play the match or report a No-Show
	</div>
	<br />
	
	<?php
	$this_reported = 0;
	$other_reported = 0;
	if($get_match->get_SWSquad1() == $_SESSION['squadid'] && $info->get_swsquad1_reports_noshow()): $this_reported = 1; endif;
	if($get_match->get_SWSquad2() == $_SESSION['squadid'] && $info->get_swsquad2_reports_noshow()): $this_reported = 1; endif;
	if($get_match->get_SWSquad1() == $_SESSION['squadid'] && $info->get_swsquad2_reports_noshow()): $other_reported = 1; endif;
	if($get_match->get_SWSquad2() == $_SESSION['squadid'] && $info->get_swsquad1_reports_noshow()): $other_reported = 1; endif;
	?>
	<table>
		<tr>
			<td align="right">&nbsp;</td>
			<td>&nbsp;&nbsp;</td>
			<td>
				<?php if($other_reported): ?><div class="copy">The other Squad has declared a No-Show or requests a reschedule.</div><?php endif; ?>
				<?php if(!$this_reported): ?>
				<div class="copy">
					<p>To claim a match victory after it is played, take a screenshot (<a href="<?php echo RELATIVEPATH; ?>images/example.png">example</a>) of the results screen and upload it here.</p>
					<form enctype="multipart/form-data" id="match_claim_form" action="_manual_award.php" method="post" class="validate">
						<input type="hidden" name="MAX_FILE_SIZE" value="18000000" />
						<input type="hidden" name="code" value="<?php echo $get_match->get_SWCode(); ?>" />
						Upload screenshot: <input onchange="jQuery('label[for=\'uploadedfile\']').hide();" title="Screenshot is required." class="required" id="uploadedfile" name="uploadedfile" type="file" />
						<input type="submit" value="Claim Match" />
						<span id="pleasewait" style="display:none;">Please wait for processing to complete...</span>
					</form>
					<p>Or, to report a no-show or request a reschedule:</p>
					<form action="noshow.php" method="post">
						<input type="hidden" name="SWCode" value="<?php echo $get_match->get_SWCode(); ?>" />
						<input type="submit" value="Report the other squad was a No-Show or request a Reschedule" />
					</form>
				</div>
				<?php else: ?>
				<div class="copy">
					Your squad has declared a No-Show or requests a reschedule.
				</div>
				<?php endif; ?>
			</td>
		</tr>																																
	</table>
	<?php if(strtotime($info->get_final_match_time()) < time()): ?>
	<?php endif; ?>
<?php endif; ?>
<?php // END PHASE 4 ?>
<br />
<div class="title">Protest This Match:
<br />
<div class="copy">
	Protesting a match removes the match from the auto-forfeit system.  You can use this feature to dispute poorly chosen match times, etc.  You can still play this match code even when it has been protested.  An administrator will contact both squads if a protest has been filed.						
</div>

<?php
$this_protested = 0;
$other_protested = 0;
if($get_match->get_SWSquad1() == $_SESSION['squadid'] && $info->get_SWSquad1_protest()):
$this_protested = 1;
endif;
if($get_match->get_SWSquad2() == $_SESSION['squadid'] && $info->get_SWSquad2_protest()):
$this_protested = 1;
endif;
if($get_match->get_SWSquad1() == $_SESSION['squadid'] && $info->get_SWSquad2_protest()):
$other_protested = 1;
endif;
if($get_match->get_SWSquad2() == $_SESSION['squadid'] && $info->get_SWSquad1_protest()):
$other_protested = 1;
endif;

if($other_protested):
?><div class="copy">The other Squad has protested this match and has removed it from the auto-forfeit system.</div>
<?php endif; ?>
<?php if(!$this_protested): ?>
	<form action="protest.php" method="post">
	<input type="hidden" name="SWCode" value="<?php echo $get_match->get_SWCode(); ?>" />
	<input type="submit" value="Protest" />
	</form>
	</div>
<?php else: ?>
<div class="copy">
	Your squad has protested this match and has removed it from the auto-forfeit system.
</div>
<?php endif; ?>					
<?php endif; // get_match ?>
				<?php // END MAIN PAGE INFO ?>
<?php include(BASE_PATH.'doc_bot.php'); ?>

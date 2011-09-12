<?php /*
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
define('SECURE',1);
include('../bootstrap.php');

if(!empty($_POST['action']) && $_POST['action'] == 'update')
{
	$ret = fsopilot_api::get_swpilot($_POST['pilotid']);
	$check_pilot = reset($ret->get_results());
}
elseif(!empty($_POST['action']) && $_POST['action'] == 'add')
{
	$pilot = fsopilot_api::get($_POST['pilotid']);
}
$get_form_time_zones = fsopilot_api::get_form_time_zones();
$get_form_connection_type = fsopilot_api::get_form_connection_types();

$document_title = 'SquadWar - Enlist a Pilot';

include(BASE_PATH.'doc_top.php');

define('ENLIST',1);
include(BASE_PATH.'menu/main.php');

include(BASE_PATH.'doc_mid.php');
				// MAIN PAGE INFO
?>
	<?php if(!empty($_POST['action']) && $_POST['action'] == 'update'): ?>
		<div class="title">Edit Pilot:</div>
		<br />
			<form action="_enlist_update.php" method="post" name="enlist_update">
				<input type="hidden" name="action" value="update" />
				<input type="hidden" name="pilotid" value="<?php echo $check_pilot->get_PilotID(); ?>" />
				<table>
					<tr>
						<td align="right"><div class="copy"><b>TrackerID:</b></div></td>
						<td><div class="copy"><?php echo $check_pilot->get_TrackerID(); ?></div></td>
					</tr>
					<tr>
						<td align="right"><div class="copy"><b>Pilot:</b></div></td>
						<td><div class="copy"><?php echo $check_pilot->get_Pilot_Name(); ?></div></td>
					</tr>
					<tr>
						<td align="right"><div class="copy"><b>ICQ:</b></div></td>
						<td><input type="text" name="ICQ" size="15" value="<?php if($check_pilot->get_ICQ() != ''): echo $check_pilot->get_ICQ(); endif; ?>" /></td>
					</tr>			
					<tr>
						<td align="right"><div class="copy"><b>Time Zone:</b></div></td>
						<td>
							<select name="time_zone" size="1">
								<?php foreach($get_form_time_zones as $time_zone): ?>
									<option value="<?php echo $time_zone['ID']; ?>"<?php if($check_pilot->get_time_zone() == $time_zone['ID']): ?> selected="selected"<?php endif; ?>> [GMT <?php echo $time_zone['value_hours']; ?>:<?php if($time_zone['value_minutes'] == 0): ?>00<?php else: echo $time_zone['value_minutes']; endif; ?>] <?php echo htmlspecialchars($time_zone['Description']); ?></option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>						
					<tr>
						<td align="right"><div class="copy"><b>Connection Type:</b></div></td>
						<td>
							<select name="connection_type" size="1">
								<?php foreach($get_form_connection_type as $contype): ?>
									<option value="<?php echo $contype['ID']; ?>"<?php if($check_pilot->get_connection_type() == $contype['ID']): ?> selected="selected"<?php endif; ?>> <?php echo $contype['type']; ?></option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>	
					<tr>
						<td align="right"><div class="copy"><b>Show Email:</b></div></td>
						<td>
							<input type="checkbox" name="show_email"<?php if($check_pilot->get_show_email()): ?> checked="checked"<?php endif; ?> />
						</td>
					</tr>		
					<tr>
						<td align="right"><div class="copy"><b>Email:</b></div></td>
						<td>
							<input type="text" size="25" name="email" value="<?php if($check_pilot->get_email() != ''): echo $check_pilot->get_email(); endif; ?>" />
						</td>
					</tr>																					
				</table>
				<?php if($check_pilot->get_Recruitme() == 0): ?>
					<input type="submit" value="Re-Enlist this Pilot" />
				<?php else: ?>
					<input type="submit" value="Update this Pilot" />
				<?php endif; ?>
			</form>
	<?php elseif(!empty($_POST['action']) && $_POST['action'] == 'add'): ?>
		<div class="title">
			Enlist a Pilot
		</div>
		<br />
		<div class="copy">
			This pilot has not been signed up for recruitment.  Please enter this pilot's data:
		</div>
			<form action="_enlist_update.php" method="post" name="enlist_update">
				<input type="hidden" name="action" value="add" />
				<input type="hidden" name="refer" value="<?php echo $_SERVER['PHP_SELF']; ?>" />
				<input type="hidden" name="pilotid" value="<?php echo $pilot->get_PilotID(); ?>" />
				<table>
					<tr>
						<td align="right"><div class="copy"><b>TrackerID:</b></div></td>
						<td><div class="copy"><?php echo $_SESSION['trackerid']; ?></div></td>
					</tr>
					<tr>
						<td align="right"><div class="copy"><b>Pilot:</b></div></td>
						<td><div class="copy"><?php echo $pilot->get_Pilot(); ?></div></td>
					</tr>
					<tr>
						<td align="right"><div class="copy"><b>ICQ:</b></div></td>
						<td><input type="text" name="ICQ" size="15"></td>
					</tr>			
					<tr>
						<td align="right"><div class="copy"><b>Time Zone:</b></div></td>
						<td>
							<select name="time_zone" size="1">
								<?php foreach($get_form_time_zones as $time_zone): ?>
									<option value="<?php echo $time_zone['ID']; ?>"<?php if(22 == $time_zone['ID']): ?> selected="selected"<?php endif; ?>> [GMT <?php echo $time_zone['value_hours']; ?>:<?php if($time_zone['value_minutes'] == 0): ?>00<?php else: echo $time_zone['value_minutes']; endif; ?>] <?php echo htmlspecialchars($time_zone['Description']); ?></option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>		
					<tr>
						<td align="right"><div class="copy"><b>Connection Type:</b></div></td>
						<td>
							<select name="connection_type" size="1">
								<?php foreach($get_form_connection_type as $contype): ?>
									<option value="<?php echo $contype['ID']; ?>"> <?php echo $contype['type']; ?></option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>	
					<tr>
						<td align="right"><div class="copy"><b>Show Email:</b></div></td>
						<td>
							<input type="checkbox" name="show_email" />
						</td>
					</tr>		
					<tr>
						<td align="right"><div class="copy"><b>Email:</b></div></td>
						<td>
							<input type="text" size="25" name="email" value="" />
						</td>
					</tr>																					
				</table>

				<p>
				<input type="submit" value="Enlist this Pilot" /></p>
			</form>
	<?php endif; ?>

				<?php // END MAIN PAGE INFO ?>
<?php include(BASE_PATH.'doc_bot.php'); ?>

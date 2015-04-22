<?php /*
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
define('SECURE',1);
include('../bootstrap.php');

$get_form_time_zones = fsopilot_api::get_form_time_zones();

util::prepend_title('Create a Squad');

include(BASE_PATH.'doc_top.php');

include(BASE_PATH.'menu/main.php');

include(BASE_PATH.'doc_mid.php');
				// MAIN PAGE INFO
?>

<?php if(!empty($_GET['message'])): ?>
	<div class="copy">
		<p><b><?php echo $_GET['message']; ?></b></p>
		<p>Please try again, an error has been detected in your submission.<br />
		If you have any further questions, feel free to contact <a href="mailto:<?php echo SUPPORT_EMAIL; ?>"><?php echo SUPPORT_EMAIL; ?></a>.</p>
		<p><a href="index.php">Return to sign-up page</a></p>
	</div>
<?php else: ?>
	<div class="copy">
	<p>We are currently taking registrations for SquadWar Squads.  Please fill out the form below.  You need a valid email address and a <a href="http://<?php echo FS2NETD_HOME; ?>/">FS2NetD</a> account.  You will be contacted as soon as your Squad name has been approved for use in SquadWar.</p>
	<p>
	Below the form you will find a list of squads which have Registered.  All Squad Names are subject to approval from the SquadWar administrator.</p>
	<p>
	Your "admin" password should only be known by your squad leader and trusted members of the squad.  Your "join" password is the password you will give out to others when you want them to join your squad.</p>
	<p>
	If you have problems with this form, please write email to <a href="mailto:<?php echo SUPPORT_EMAIL; ?>"><?php echo SUPPORT_EMAIL; ?></a>.</p>

				<form action="_signup.php" method="post" name="login" class="validate">
					<table>
						<tr>
							<td colspan="2"><div class="form_required"><p>&nbsp;</p><p><b>Required Info</b></p></div></td>
						</tr>
						<tr>
							<td align="right"><div class="form_required"><b><label for="squad_name">Squad Name:</label></b></div></td>
							<td><input type="text" name="squad_name" id="squad_name" title="You must enter your Squad Name." class="required name" size="20" maxlength="50" /></td>
						</tr>
						<tr>
							<td align="right"><div class="form_required"><b>Squad Email:</b></div></td>
							<td><input type="text" name="squad_email" title="You must enter your Squad Email." class="required" size="20" maxlength="50" /></td>
						</tr>
						<tr>
							<td align="right"><div class="form_required"><b>Your FS2NetD Login:</b></div></td>
							<td><input type="text" name="pxo_login" title="You must enter your FS2NetD login." class="required" size="20" maxlength="50" /></td>
						</tr>
						<tr>
							<td align="right"><div class="form_required"><b>Your FS2NetD Password:</b></div></td>
							<td><input type="password" name="pxo_password" title="You must enter your FS2NetD password." class="required" size="20" maxlength="50" /></td>
						</tr>
						<tr>
							<td align="right"><div class="form_required"><b>Squad Join Password:</b></div></td>
							<td><input type="password" name="join_password" title="You must enter your squad join password." class="required" size="20" maxlength="50" /></td>
						</tr>
						<tr>
							<td align="right"><div class="form_required"><b>Confirm Squad Join Password:</b></div></td>
							<td><input type="password" name="join_password2" title="You must confirm your squad join password." class="required" size="20" maxlength="50" /></td>
						</tr>
						<tr>
							<td align="right"><div class="form_required"><b>Squad Admin Password:</b></div></td>
							<td><input type="password" name="admin_password" title="You must enter your squad admin password." class="required" size="20" maxlength="50" /></td>
						</tr>
						<tr>
							<td align="right"><div class="form_required"><b>Confirm Squad Admin Password:</b></div></td>
							<td><input type="password" name="admin_password2" title="You must confirm your squad admin password." class="required" size="20" maxlength="50" /></td>
						</tr>
						<tr>
							<td align="right"><div class="form_required"><b>Time Zone/Country:</b></div></td>
							<td>
								<select name="squad_time_zone" size="1">
									<?php foreach($get_form_time_zones as $time_zone): ?>
										<option value="<?php echo $time_zone['ID']; ?>"<?php if($time_zone['ID'] == 22): ?> selected="selected"<?php endif; ?>> [GMT <?php echo $time_zone['value_hours']; ?>:<?php if($time_zone['value_minutes'] == 0): ?>00<?php else: echo $time_zone['value_minutes']; endif; ?>] <?php echo htmlspecialchars($time_zone['Description']); ?></option>
									<?php endforeach; ?>
								</select>
							</td>
						</tr>
						<tr>
							<td><div class="form"><b>Optional Info</b></div></td>
							<td></td>
						</tr>
						<tr>
							<td align="right"><div class="form">Squad Leader ICQ:</div></td>
							<td><input type="text" name="squad_leader_icq" title="" size="20" maxlength="50" /></td>
						</tr>
						<tr>
							<td align="right"><div class="form">Squad Leader Web Page:</div></td>
							<td><input type="text" name="squad_web_link" title="" size="20" maxlength="50" /></td>
						</tr>
						<tr>
							<td align="right"><div class="form"></div></td>
							<td></td>
						</tr>
						<tr>
							<td></td><td align="left"><input type="submit" value="Submit This Squad!" /></td>
						</tr>
					</table>

				</form>

	</div>
<?php endif; ?>

				<?php // END MAIN PAGE INFO ?>
<?php include(BASE_PATH.'doc_bot.php'); ?>

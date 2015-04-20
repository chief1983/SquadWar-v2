<?php /*
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
---> */ ?>

				</td>
				<td valign="top">	

		<br />

		<?php if(!defined('HIDELOGIN')): ?>
			<table width="95%" class="center" cellpadding="0" cellspacing="0" border="0"><tr><td>
			<?php if(!$_SESSION['loggedin']): ?>
				<?php // NOT LOGGED IN ?>
				<table><tr><td valign="top">
					<form action="<?php echo RELATIVEPATH; ?>_login.php" method="post" class="validate">
					<div class="title">SquadWar Log In</div>
					<table>
						<tr>
							<td><div class="copy"><label for="login">Username:</label></div></td>
							<td><input type="text" class="username required" name="login" id="login" title="You must enter your login." size="15" maxlength="50" /><br />
							<span class='validation' id="login_validation"></span></td>
						</tr>
						<tr>
							<td><div class="copy"><label for="password">Password:</label></div></td>
							<td><input type="password" class="password required" name="password" id="password" title="You must enter your password." size="15" maxlength="50" /><br />
							<span class='validation' id="password_validation"></span></td>
						</tr>
						<tr>
							<td></td>
							<td>
								<input type="hidden" name="refer" value="index.php" />
								<input type="image" src="images/forms/login.gif" value="Login" />
							</td>
						</tr>
					</table>
					</form>
				</td>
				<td><img src="images/spacer.gif" width="40" height="3" alt="" border="0" /></td>
				<td valign="top">
					<div class="title">FS2NetD at a glance:</div>
					<table><tr><td>
					<div class="copy">
						<b><?php echo $count_pxo_users; ?></b> Validated Users<br />
						<b><?php echo $count_fs2_pilots; ?></b> FreeSpace 2 Pilots<br />
						<b><?php echo $count_fs2_players; ?></b> FreeSpace 2 Players<br />
						<b><?php echo $count_sw_squads; ?></b> SquadWar Squadrons<br />
					</div>
					</td></tr></table>
				</td></tr></table>
				<?php // END NOT LOGGED IN ?>
			<?php else: ?>
				<div class="title">FS2NetD Account <?php echo $_SESSION['login']; ?></div>
				<table>
					<tr>
						<td align="right"><div class="copy">FS2NetD Account Email:</div></td>
						<td><div class="copy"><?php echo $_SESSION['email']; ?></div></td>
					</tr>
					<tr>
						<td align="right"><div class="copy">FS2NetD User ID:</div></td>
						<td><div class="copy"><?php echo $_SESSION['user_id']; ?></div></td>
					</tr>
					<tr>
						<td align="right"><div class="copy">FreeSpace 2 Pilots:</div></td>
						<td><div class="copy"><?php echo $_SESSION['totalfsopilots']; ?></div></td>
					</tr>
				</table>
			<?php endif; ?>
			</td></tr></table>

		<hr noshade="noshade" style="color:#2E5537;" />
		<?php endif; ?>

			<table width="95%" class="center" cellpadding="0" cellspacing="0" border="0"><tr><td>

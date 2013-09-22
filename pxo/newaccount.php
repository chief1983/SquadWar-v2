<?php /*
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
include('../bootstrap.php');

if($_SESSION['loggedin'])
{
	util::location(RELATIVEPATH.'index.php');
}

util::prepend_title('Register');

$script = <<<EOT
	<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery("#registerForm").validate();
		});
	</script>
EOT;
util::push_head($script);

include(BASE_PATH.'doc_top.php');

include(BASE_PATH.'menu/main.php');

define('HIDELOGIN',1);
include(BASE_PATH.'doc_mid.php');
				// MAIN PAGE INFO
?>

<div class="copy">
<b>IMPORTANT NOTE:</b><br />
	<b>A working email address is required for FS2NetD registration.</b><p>
	Without it a working email address, you will be unable to complete your registration.
	Your email address will <b>not</b> be given to solicitors, bulk emailers or anyone else.</p>
	<p>
	You may receive email from FS2NetD announcing game updates, contests, or special events.</p>
	<p>
	To improve your Internet play, please make sure you're using the latest version of your game.  To do so, hit the
	"Update" button in your Launcher.</p>
	<p>
	<b>NOTE:</b>  FS2NetD is not a standalone gaming service.  It is designed to work with Volition/SCP
	games like 
	<a href="http://www.hard-light.net/">FreeSpace 2</a>,
	<a href="http://babylon.hard-light.net/">The Babylon Project</a>,
	<a href="http://www.beyondtheredline.net/">Beyond the Red Line</a>,
	and <a href="http://www.diaspora-game.com/">Diaspora</a>
	for your PC.
	If you created a FS2NetD account for one
	of these games, you do not need to register again to play the other.</p>

	<p>Your login name is simply a name used to identify your FS2NetD account. It does not need to be the same
	as your pilot's callsign in FreeSpace 2.</p>
	<p>If you receive an error that says that your requested login already exists
	and you've never registered before, it means that someone else has already chosen that login.  To fix this, select
	a different login.</p>

	<div class="title">Fill out the form below:</div>

	<form action="_accounts.php" method="post" id="registerForm" class="validate">
	<br />
	
	<table>
		<tr>
			<td align="right">
				<div class="copy"><b><label for="login">Choose a login name:</label></b></div>
			</td>
			<td align="left">
				<input type="text" size="19" name="login" id="login" maxlength="32" class="required" title="You must enter your desired Login name" />
			</td>
		</tr>		
		<tr>
			<td align="right">
				<div class="copy"><b><label for="password">Choose a password:</label></b></div>
			</td>
			<td align="left">
				<input type="password" size="16" name="password" id="password" maxlength="32" class="password required field_value_match" title="You must enter your password" />
			</td>
		</tr>			
		<tr>
			<td align="right">
				<div class="copy"><b><label for="confirm_password">Re-Enter your password:</label></b></div>
			</td>
			<td align="left">
				<input type="password" size="16" name="confirm_password" id="confirm_password" maxlength="32" class="password required field_value_match" title="You must confirm your password" />
			</td>
		</tr>			
		<tr>
			<td align="right">
				<div class="copy"><b><label for="email">Email Address:</label></b></div>
			</td>
			<td align="left">
				<input type="text" size="40" name="email" id="email" maxlength="100" class="email required" title="You must enter a valid email address." />
			</td>
		</tr>
	</table>
	<input type="submit" value="Submit Registration" />
	<br />
	</form>
	</div>			

				<?php // END MAIN PAGE INFO ?>
<?php include(BASE_PATH.'doc_bot.php'); ?>

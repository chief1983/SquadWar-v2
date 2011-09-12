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

$document_title = 'SquadWar - Register';

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
	<b>A working email address is required for PXO registration.</b><p>
	Without it a working email address, you will be unable to complete your registration.
	Your email address will <b>not</b> be given to solicitors, bulk emailers or anyone else.</p>
	<p>
	You may receive email from PXO announcing game updates, contests, or special events.</p>
	<p>
	To improve your Internet play, please make sure you're using the latest version of your game.  To do so, hit the
	"Update" button in your Launcher.</p>
	<p>
	<b>NOTE:</b>  Parallax Online is not a standalone gaming service.  It is designed to work with Volition/Outrage
	games like 
	<a href="http://www.volition-inc.com/fs/">Descent: FreeSpace - The Great War</a>,
	<a href="http://www.volition-inc.com/st">Descent: FreeSpace - Silent Threat</a>,
	<a href="http://www.outrage.com/descent3.htm">Descent 3 Demo</a>,
	<a href="http://www.outrage.com/descent3.htm">Descent 3</a>,
	<a href="http://www.freespace2.com">FreeSpace 2 Demo</a>,
	and <a href="http://www.freespace2.com">FreeSpace 2</a>
	for your PC.
	If you created a PXO account for one
	of these games, you do not need to register again to play the other.</p>

	<p>Your login name is simply a name used to identify your PXO account. It does not need to be the same
	as your pilot's callsign in FreeSpace/Descent 3.</p>
	<p>If you receive an error that says that your requested login already exists
	and you've never registered before, it means that someone else has already chosen that login.  To fix this, select
	a different login.</p>

	<div class="title">Fill out the form below:</div>

	<form action="_accounts.php" method="post" id="registerForm" class="validate">
	<br />
	
	<table>
		<tr>
			<td align="right">
				<div class="copy"><b><label for="firstname">First Name:</label></b></div>
			</td>
			<td align="left">
				<div class="copy"><input type="text" name="firstname" id="firstname" size="25" maxlength="25" class="name required" title="You must enter your First Name" /></div>
			</td>
		</tr>
		<tr>
			<td align="right">
				<div class="copy"><b><label for="lastname">Last Name:</label></b></div>
			</td>
			<td align="left"><input type="text" name="lastname" id="lastname" size="25" maxlength="25" class="name required"  title="You must enter your Last Name" />
			</td>
		</tr>
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

<?php /*
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
define('SECURE',1);
include('../bootstrap.php');

$squads = squad_api::get_active_squads();

$document_title = 'SquadWar - Admin Squad';

include(BASE_PATH.'doc_top.php');

include(BASE_PATH.'menu/main.php');

include(BASE_PATH.'doc_mid.php');
				// MAIN PAGE INFO
?>

				<div class="title">Squad Login</div>
			
						<div class="copy">
							<form action="squadlogin_validate.php" method="post" name="login" class="validate">
								<b>Squad:</b><br />
								<select name="id">
									<?php foreach($squads as $squad): ?>
										<option value="<?php echo $squad->get_SquadID(); ?>"<?php if(!empty($_SESSION['adminlastchosen']) && $_SESSION['adminlastchosen'] == $squad->get_SquadID()): ?> selected="selected"<?php endif; ?>><?php echo $squad->get_SquadName(); ?></option>
									<?php endforeach; ?>
								</select>
								<p>
								<b>Admin Password:</b><br />
								<input type="password" name="password" title="You must enter your password." class="required" size="20" maxlength="50" />
								</p>
								<input type="submit" value="Login" />
							</form>
						</div>

				<?php // END MAIN PAGE INFO ?>
<?php include(BASE_PATH.'doc_bot.php'); ?>

<?php /*
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
define('SECURE',1);
include('../bootstrap.php');

if(!empty($_GET['id']))
{
	$get_squad = squad_api::get($_GET['id']);
}

util::prepend_title('Join '.$get_squad->get_SquadName());

include(BASE_PATH.'doc_top.php');

include(BASE_PATH.'menu/main.php');

include(BASE_PATH.'doc_mid.php');
				// MAIN PAGE INFO
?>
				<?php if(empty($get_squad)): ?>
					This squad does not exist.
				<?php else: ?>
					<div class="title">Join Squad: <?php echo $get_squad->get_SquadName(); ?></div>
					<br />
					<div class="copy">
					To join a Squad, the Squad Leader must give you the Squad's "join" password.  If you have the Squad's join password enter the join password, your FS2NetD login, and your FS2NetD password where prompted on the form.
					</div>
					
					<div class="copy">
						<form action="_join_squad.php" method="post" name="login" class="validate">
							<b>Squad's Join Password:</b><br />
							<input type="password" name="join_password" title="You must enter the squad's join password." class="required password" size="20" maxlength="50" /><br />
							<b>FS2NetD Login:</b><br />
							<input type="text" name="pxo_login" value="<?php echo $_SESSION['login']; ?>" title="You must enter your FS2NetD login." class="required" size="20" maxlength="50" /><br />
							<b>FS2NetD Password:</b><br />
							<input type="password" name="pxo_password" value="" title="You must enter your FS2NetD password." class="required password" size="20" maxlength="50" /><br />
							<br />
							<input type="hidden" name="squadid" value="<?php echo $_GET['id']; ?>" />
							<input type="hidden" name="refer" value="<?php echo $_SERVER['PHP_SELF']; ?>" />
							<input type="submit" value="Join This Squad" />
						</form>
					</div>
				<?php endif; ?>

				<?php // END MAIN PAGE INFO ?>
<?php include(BASE_PATH.'doc_bot.php'); ?>

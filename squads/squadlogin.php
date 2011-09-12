<?php /*
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
define('SECURE',1);
include('../bootstrap.php');

$get_squad = squad_api::get($_GET['id']);

$script = <<<EOT
<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery("#squadLoginForm").validate();
	});
</script>
EOT;
util::push_head($script);

$document_title = 'SquadWar - Admin Squad';

include(BASE_PATH.'doc_top.php');

include(BASE_PATH.'menu/main.php');

include(BASE_PATH.'doc_mid.php');
				// MAIN PAGE INFO
?>

				<div class="title"><?php echo $get_squad->get_SquadName(); ?> Squad Login</div>

						<div class="copy">
							<form action="squadlogin_validate.php" method="post" name="squadLoginForm" id="squadLoginForm" class="validate">
								<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" />
								<b>Admin Password:</b><br />
								<input type="password" name="password" title="You must enter your password." class="required" size="20" maxlength="50" /><br />
								<br />
								<input type="submit" value="Login" />
							</form>
						</div>

				<?php // END MAIN PAGE INFO ?>
<?php include(BASE_PATH.'doc_bot.php'); ?>

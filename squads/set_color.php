<?php /*
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
define('SECURE',1);
include('../bootstrap.php');

if(empty($_SESSION['squadid']))
{
	util::location('admin.php');
}

$rec = squad_api::new_search_record();
$rec->set_SquadID($_SESSION['squadid']);
$ret = squad_api::search($rec);
$ret = squad_api::populate_info($ret);
$get_squad = reset($ret->get_results());
$get_squad_info = $get_squad->get_info();

// Do the update if the values check out
if(!empty($_POST['update']) &&
	96 <= $_POST['red'] + $_POST['green'] + $_POST['blue'] &&
	$_POST['red'] + $_POST['green'] + $_POST['blue'] <= 707)
{
	$get_squad_info->set_Squad_Red($_POST['red']);
	$get_squad_info->set_Squad_Green($_POST['green']);
	$get_squad_info->set_Squad_Blue($_POST['blue']);
	$get_squad_info->save();
}

$red = $get_squad_info->get_Squad_Red();
$green = $get_squad_info->get_Squad_Green();
$blue = $get_squad_info->get_Squad_Blue();
$teamcolor = $fontcolor = $get_squad_info->get_teamcolor();
if($red == '' || $green == '' || $blue == '' || $red + $green + $blue < 96 || $red + $green + $blue > 707)
{
	$fontcolor = 'ffffff';
}

$document_title = 'SquadWar - Set Color';

include(BASE_PATH.'doc_top.php');

include(BASE_PATH.'menu/main.php');

include(BASE_PATH.'doc_mid.php');
				// MAIN PAGE INFO
?>
					<div class="title">Squad <span style="color:#<?php echo $fontcolor; ?>;"><?php echo $get_squad->get_SquadName(); ?>'s</span> Color Admin. Page</div>
					<br />
					<div class="copy">Your Squad can choose a standard RGB Color for its Squad color.  The sum of the three numbers should be greater than 95 and lest than 707.  This will prevent colors from appearing too dark or too light on the map.</div>
					<br />
					<?php if(!empty($_POST['update'])): ?>
						<div class="title">Your Squad Color has been updated.</div>
						<br />
					<?php endif; ?>
					<div class="copy"><b>Your current Squad color is:</b> Red - <?php echo $get_squad_info->get_Squad_Red(); ?>, Green - <?php echo $get_squad_info->get_Squad_Green(); ?>, Blue - <?php echo $get_squad_info->get_Squad_Blue(); ?></div>
					<br />


					<?php if(!empty($_POST['try'])):
						$tablecolor = '000000'; ?>
						<?php if(!isset($_POST['red']) || !isset($_POST['green']) || !isset($_POST['blue'])
							|| !is_numeric($_POST['red']) || !is_numeric($_POST['green']) || !is_numeric($_POST['blue'])): ?>
							<div class="copy"><b>You must enter values between 0 and 255.</b></div>

						<?php elseif($_POST['red'] > 255 || $_POST['green'] > 255 || $_POST['blue'] > 255): ?>
							<div class="copy"><b>You must enter values between 0 and 255.</b></div>
						<?php elseif($_POST['red'] < 0 || $_POST['green'] < 0 || $_POST['blue'] < 0): ?>
							<div class="copy"><b>You must enter values between 0 and 255.</b></div>									

						<?php elseif($_POST['red'] + $_POST['green'] + $_POST['blue'] < 96): ?>
							<div class="copy"><b>Your color is too dark. The Sum of values must be greater than 95.</b></div>
						<?php elseif($_POST['red'] + $_POST['green'] + $_POST['blue'] > 706): ?>
							<div class="copy"><b>Your color is too light. The Sum of values must be less than 707.</b></div>
						<?php else:
							$tablecolor = util::colorstring($_POST['red'], $_POST['green'], $_POST['blue']);
						endif; ?>
					<?php else:
						$tablecolor = $teamcolor;
					endif; ?>

					<table>
						<tr>
							<td valign="top">
								<table style="background-color:#<?php echo $tablecolor; ?>;" border="1"><tr><td width="95" height="95">&nbsp;</td></tr></table>					
							</td>
							<td valign="top">
								<div class="copy">
									
									<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
										<table>	
											<tr>	
												<td align="right"><div class="copy"><b>Red:</b></div></td>
												<td><input type="text" name="red" size="3" maxlength="3" value="<?php echo (!empty($_POST['try']) ? $_POST['red'] : $get_squad_info->get_Squad_Red()); ?>" /></td>
											</tr>
											<tr>	
												<td align="right"><div class="copy"><b>Green:</b></div></td>
												<td><input type="text" name="green" size="3" maxlength="3" value="<?php echo (!empty($_POST['try']) ? $_POST['green'] : $get_squad_info->get_Squad_Green()); ?>" /></td>
											</tr>
											<tr>	
												<td align="right"><div class="copy"><b>Blue:</b></div></td>
												<td><input type="text" name="blue" size="3" maxlength="3" value="<?php echo (!empty($_POST['try']) ? $_POST['blue'] : $get_squad_info->get_Squad_Blue()); ?>" /></td>
											</tr>	
										</table>
										<input type="hidden" name="try" value="1" />
										<input type="submit" value="Test This Color" />
									</form>
									<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">	
										<input type="hidden" name="red" value="<?php echo (isset($_POST['red']) ? $_POST['red'] : $get_squad_info->get_Squad_Red()); ?>" />
										<input type="hidden" name="green" value="<?php echo (isset($_POST['green']) ? $_POST['green'] : $get_squad_info->get_Squad_Green()); ?>" />
										<input type="hidden" name="blue" value="<?php echo (isset($_POST['blue']) ? $_POST['blue'] : $get_squad_info->get_Squad_blue()); ?>" />
										<input type="hidden" name="update" value="1" />
										<input type="submit" value="Accept This Color" />
									</form>
								</div>
							</td>
						</tr>
					</table>
					<br />

				<?php // END MAIN PAGE INFO ?>
<?php include(BASE_PATH.'doc_bot.php'); ?>

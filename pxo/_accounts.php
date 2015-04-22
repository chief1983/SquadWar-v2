<?php /*
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
include('../bootstrap.php');

// Do some lookups
$rec = user_api::new_search_record();
$rec->set_Login($_POST['login']);
$check_login = user_api::get_count($rec);

$check_email = user_api::get_user_by_email($_POST['email']);

util::prepend_title('Register');

include(BASE_PATH.'doc_top.php');

include(BASE_PATH.'menu/main.php');

define('HIDELOGIN',1);
include(BASE_PATH.'doc_mid.php');
				// MAIN PAGE INFO
?>

	<div class="title">Account Creation:</div>
<br />
	<div class="copy">			
						
							<?php if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)): ?>
															
									<?php if ($check_login != 0): ?>
										That login already exists in the <b>FS2NetD</b> database.
										<p>
										Please return to the form and try again.</p>										
									<?php endif; ?>
									
									<?php if (!empty($check_email)): ?>
										That email address already exists in the <b>FS2NetD</b> database.
										<p>
										Please return to the form and try again.</p>
									<?php endif; ?>
									
									<?php if (empty($_POST['password']) || $_POST['password'] != $_POST['confirm_password']):
										$password_fail = true; ?>
										Your passwords do not match.
										<p>
										Please return to the form and try again.</p>
									<?php endif; ?>


									<?php
									if ($check_login == 0 && empty($check_email) && empty($password_fail))
									{
										$rec = user_api::new_detail_record();
										$rec->set_email($_POST['email']);
										$rec->set_Login($_POST['login']);
										$rec->set_Password($_POST['password']);
										$rec->set_Validated(1); // For now, skip email validation.
										if(isset($_POST['privmail']) && $_POST['privmail'] != '')
										{
											$rec->set_ShowEmail($_POST['privmail']);
										}
										if(isset($_POST['privname']) && $_POST['privname'] != '')
										{
											$rec->set_ShowRealName($POST['privname']);
										}
										
										$rec->save();
										?>
										Step 1 complete!<br />
<?php /*
										<p>
										<b>Check your email.</b></p>
										<p>
										It may take several minutes for the email to arrive.  Follow the instructions in the email to complete your
										FS2NetD Registration!</p>
										<p>
										If you do not receive the email within a few hours, please contact us at:<br />
										<a href="mailto:<?php echo SUPPORT_EMAIL; ?>"><?php echo SUPPORT_EMAIL; ?></a></p>
*/?>
										<p>You can now <a href="<?php echo RELATIVEPATH; ?>login.php">log in</a>.</p>
<?php
$mailto = $_POST['email'];
$from = SUPPORT_EMAIL;
$subject = "Your FS2NetD Account";
$message = <<<EOT
Your FS2NetD Registration has been received.

=======================================
Your FS2NetD Tracker ID number is: {$rec->get_id()}
Your FS2NetD Login is: {$_POST['login']}
Your password is : {$_POST['password']}
=======================================

You must now validate your FS2NetD Account, which you can do by clicking on this URL:

http://www.pxo.net/validate.php?id={$rec->get_id()}
EOT;

// Send the email, or not.
?>

<?php /*
Or you can do it manually by going to:
http://www.parallaxonline.com/validate.html
If you have any questions or problems, please email help@parallaxonline.com
*/ ?>
									
									<?php
									}
									else
									{
									?>
						
										<p>
										<font face="arial" color="#FF0000"><b>Invalid email.</b></font></p>
										<p>
										You have entered an invalid email address.  Please try again.<br />
										If you have any further questions, feel free to contact <a href="<?php echo SUPPORT_EMAIL; ?>"><?php echo SUPPORT_EMAIL; ?></a>.</p>
								
									<?php
									}
									?>
							<?php endif; ?>
	</div>			

				<?php // END MAIN PAGE INFO ?>
<?php include(BASE_PATH.'doc_bot.php'); ?>

<?php
include('../bootstrap.php');

util::prepend_title('Error');

include(BASE_PATH.'doc_top.php');

include(BASE_PATH.'menu/main.php');

include(BASE_PATH.'doc_mid.php');
		// MAIN PAGE INFO
?>

					<div class="title">
						There has been an error.
					</div>
					<p></p>

					<div class="copy">
						<?php echo $_GET['message']; ?><p>
							<?php if(!empty($_GET['refer'])): ?><a href="<?php echo $_GET['refer']; ?>">Return to previous page</a><?php endif; ?>
							</p>
					</div>

				<?php // END MAIN PAGE INFO ?>
<?php include(BASE_PATH.'doc_bot.php'); ?>

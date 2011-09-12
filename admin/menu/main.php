<?php /* 
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/

							// start menu main
							?>
		
							<img src="<?php echo RELATIVEPATH; ?>images/main/header.jpg" width="110" height="207" alt="" border="0" /><br />
						
							<img src="<?php echo RELATIVEPATH; ?>images/menu/actions.gif" width="110" height="25" alt="" border="0" /><br />
							<table width="100%" cellpadding="0" cellspacing="0" border="0">
								<tr>
									<td valign="top">
										<img src="<?php echo RELATIVEPATH; ?>images/spacer.gif" width="5" height="1" alt="" border="0" /><br />
									</td>
									<td valign="top">
										<div class="copy">
											<a href="<?php echo RELATIVEPATH; ?>admin/pending_squads.php">Pending Squads</a><br />
											<a href="<?php echo RELATIVEPATH; ?>admin/pendingmatches.php">Pending Matches</a>
										</div>				
										&nbsp;<br />											
									</td>								
								</tr>
							</table>

							<img src="<?php echo RELATIVEPATH; ?>images/menu/info.gif" width="110" height="25" alt="" border="0" /><br />
							<table width="100%" cellpadding="0" cellspacing="0" border="0">
								<tr>
									<td valign="top">
										<img src="<?php echo RELATIVEPATH; ?>images/spacer.gif" width="5" height="1" alt="" border="0" /><br />
									</td>
									<td valign="top">
										<div class="copy">
											<a href="<?php echo RELATIVEPATH; ?>index.php">News</a><br />
											<a href="<?php echo RELATIVEPATH; ?>oldnews.php">News Archive</a><br />
											<a href="<?php echo RELATIVEPATH; ?>staff/">Contact Staff</a><br />
										</div>
										&nbsp;<br />
									</td>
								</tr>
							</table>

							<img src="<?php echo RELATIVEPATH; ?>images/menu/info.gif" width="110" height="25" alt="" border="0" /><br />
							<table width="100%" cellpadding="0" cellspacing="0" border="0">
								<tr>
									<td valign="top">
										<img src="<?php echo RELATIVEPATH; ?>images/spacer.gif" width="5" height="1" alt="" border="0" /><br />
									</td>
									<td valign="top">
										<div class="copy">
											<?php foreach($get_swleagues as $league): ?>
												<a href="<?php echo RELATIVEPATH; ?>league.php?leagueid=<?php echo $league->get_League_ID(); ?>"><?php echo $league->get_Title(); ?></a><br />
											<?php endforeach; ?>
										</div>
										&nbsp;<br />
									</td>
								</tr>
							</table>
							<img src="<?php echo RELATIVEPATH; ?>images/spacer.gif" width="110" height="1" alt="" border="0" /><br />
						<?php // end menu main ?>

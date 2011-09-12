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
											<?php if((defined('LEAGUEADMIN') && LEAGUEADMIN) || (defined('LEAGUE') && LEAGUE && !empty($_GET['leagueid']))): ?>
												<font color="white"><b>General:</b></font><br />
											<?php endif; ?>
											<?php if($_SESSION['loggedin']): ?>
											<a href="<?php echo RELATIVEPATH; ?>_logout.php">Log Out</a><br />
											<a href="<?php echo RELATIVEPATH; ?>enlist/">Enlist</a><br />
												<?php if(defined('ENLIST') && ENLIST): ?>
													&nbsp;&nbsp;<a href="<?php echo RELATIVEPATH; ?>enlist/enlist.php?action=add">Enlist/Edit Pilots</a><br />
													&nbsp;&nbsp;<a href="<?php echo RELATIVEPATH; ?>enlist/enlist.php?action=deactivate">Remove a Pilot</a><br />
												<?php endif; ?>
											<a href="<?php echo RELATIVEPATH; ?>recruits/">Recruit Board</a><br />
												<?php if(defined('RECRUITS') && RECRUITS): ?>
													&nbsp;&nbsp;<a href="<?php echo RELATIVEPATH; ?>recruits/?type=scores">By Score</a><br />
													&nbsp;&nbsp;<a href="<?php echo RELATIVEPATH; ?>recruits/?type=kills">By Kills</a><br />
													&nbsp;&nbsp;<a href="<?php echo RELATIVEPATH; ?>recruits/?type=msns">By Missions</a><br />
													&nbsp;&nbsp;<a href="<?php echo RELATIVEPATH; ?>recruits/?type=time">By Time</a><br />
												<?php endif; ?>
											<a href="<?php echo RELATIVEPATH; ?>create/">Create a Squad</a><br />
											<a href="<?php echo RELATIVEPATH; ?>join/">Join a Squad</a><br />
											<a href="<?php echo RELATIVEPATH; ?>squads/admin.php">Admin Squad</a><br />
											<?php else: ?>
											<a href="<?php echo RELATIVEPATH; ?>new.php">New Pilots</a><br />
											<a href="<?php echo RELATIVEPATH; ?>login.php">Log In</a><br />
											<?php endif; ?>
											<?php if(defined('LEAGUE') && LEAGUE && !empty($_GET['leagueid'])): ?>
												<br />
												<font color="white"><b>League:</b></font><br />
												<a href="<?php echo RELATIVEPATH; ?>league.php?leagueid=<?php echo $_GET['leagueid']; ?>">League Map</a><br />
												<a href="<?php echo RELATIVEPATH; ?>leaguehistory.php?leagueid=<?php echo $_GET['leagueid']; ?>">League History</a><br />
												<a href="<?php echo RELATIVEPATH; ?>leaguepending.php?leagueid=<?php echo $_GET['leagueid']; ?>">Pending Matches</a><br />
											<?php endif; ?>
											<?php if(defined('LEAGUEADMIN') && LEAGUEADMIN): ?>
												<br />
												<font color="white"><b>Challenges:</b></font><br />

												<?php if(count($get_thissquadsectorcount)): ?>
													<a href="<?php echo RELATIVEPATH; ?>squads/challenge.php?leagueid=<?php echo $_GET['leagueid']; ?>">Make Challenge</a>
												<?php else: ?>

													<?php if(count($check_entry_nodes)): ?>
															<a href="<?php echo RELATIVEPATH; ?>squads/entermap.php?leagueid=<?php echo $_GET['leagueid']; ?>">Enter Map</a>
													<?php else: ?>
														<?php if(!count($check_entry_nodes_challenge)): ?>
															<a href="<?php echo RELATIVEPATH; ?>squads/challengeentrynode.php?leagueid=<?php echo $_GET['leagueid']; ?>">Entry Node</a>
														<?php else: ?>
															Entry Challenge Pending
														<?php endif; ?>
													<?php endif; ?>
												<?php endif; ?>
												<br />
												<font color="white"><b>League:</b></font><br />
												<?php if(!empty($_GET['leagueid'])): ?>
												<a href="<?php echo RELATIVEPATH; ?>league.php?leagueid=<?php echo $_GET['leagueid']; ?>">League Map</a><br />
												<a href="<?php echo RELATIVEPATH; ?>leaguehistory.php?leagueid=<?php echo $_GET['leagueid']; ?>">League History</a><br />
												<a href="<?php echo RELATIVEPATH; ?>leaguepending.php?leagueid=<?php echo $_GET['leagueid']; ?>">Pending Matches</a>
												<?php endif; ?>
											<?php endif; ?>
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
											<a href="<?php echo RELATIVEPATH; ?>missions/">Mission List</a><br />
											<?php if(!$_SESSION['loggedin']): ?>
											<a href="<?php echo RELATIVEPATH; ?>faq/">FAQ</a><br />
											<a href="<?php echo RELATIVEPATH; ?>terms/">Terms</a><br />
											<?php endif; ?>
											<a href="<?php echo RELATIVEPATH; ?>staff/">Contact Staff</a><br />
										</div>
										&nbsp;<br />														
									</td>
								</tr>
							</table>
							<img src="<?php echo RELATIVEPATH; ?>images/menu/leages.gif" width="110" height="25" alt="" border="0" /><br />
			
							<table width="100%" cellpadding="0" cellspacing="0" border="0">
								<tr>
									<td valign="top">
										<img src="<?php echo RELATIVEPATH; ?>images/spacer.gif" width="5" height="1" alt="" border="0" /><br />
									</td>
									<td valign="top">
										<b>Active</b><br />
										<div class="copy">
											<?php foreach($get_swleagues as $league): ?>
												<a href="<?php echo RELATIVEPATH; ?>league.php?leagueid=<?php echo $league->get_League_ID(); ?>"><?php echo $league->get_Title(); ?></a><br />
											<?php endforeach; ?>
										</div>
										&nbsp;<br />
										<b>Closed</b><br />
										<div class="copy">
											<?php foreach($get_old_swleagues as $league): ?>
												<a href="<?php echo RELATIVEPATH; ?>league.php?leagueid=<?php echo $league->get_League_ID(); ?>"><?php echo $league->get_Title(); ?></a><br />
											<?php endforeach; ?>
										</div>
									</td>
								</tr>
							</table>
							<img src="<?php echo RELATIVEPATH; ?>images/spacer.gif" width="110" height="1" alt="" border="0" /><br />
						<?php // end menu main ?>

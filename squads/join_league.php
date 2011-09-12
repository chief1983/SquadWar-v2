<?php /*
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
define('SECURE',1);
include('../bootstrap.php');

$document_title = 'SquadWar - Join League';

if(empty($_SESSION['squadid']))
{
	$message = urlencode('You must log into your squad');
	util::location(RELATIVEPATH.'error/error.php?message='.$message);
}

$rec = league_api::new_search_record();
$rec->set_Active(1);
$rec->set_sort_by('Title');
$ret = league_api::search($rec);
$get_leagues = $ret->get_results();

include(BASE_PATH.'doc_top.php');

include(BASE_PATH.'menu/main.php');

include(BASE_PATH.'doc_mid.php');
				// MAIN PAGE INFO
?>

				<div class="title">Join a League</div>

				<div class="copy">
					<form action="_join_league.php" method="post">
						<b>League:</b><br />
						<select name="leagueid">
							<?php foreach($get_leagues as $league): ?>
								<option value="<?php echo $league->get_League_ID(); ?>"><?php echo $league->get_Title(); ?></option>
							<?php endforeach; ?>
						</select>
						<p>
						<input type="submit" value="Join League" /></p>
					</form>
				</div>

				<?php // END MAIN PAGE INFO ?>
<?php include(BASE_PATH.'doc_bot.php'); ?>

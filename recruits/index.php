<?php /*
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
define('SECURE',1);
include('../bootstrap.php');

$type = empty($_GET['type']) ? '' : $_GET['type'];

$rec = fsopilot_api::new_search_record();
$rec->set_Recruitme(1);
switch($type)
{
	case 'kills':
		$rec->set_sort_by('kill_count_ok');
		$rec->set_sort_dir('DESC');
		break;
	case 'msns':
		$rec->set_sort_by('missions_flown');
		$rec->set_sort_dir('DESC');
		break;
	case 'time':
		$rec->set_sort_by('flight_time');
		$rec->set_sort_dir('DESC');
		break;
	case 'scores':
	default:
		$type = 'scores';
		$rec->set_sort_by('score');
		$rec->set_sort_dir('DESC');
		break;
}
$res = fsopilot_api::search($rec);
$res = fsopilot_api::populate_swpilots($res);
$recruits = $res->get_results();

$maxscore = 1000;
$incrementer = 100;
$thispage = 1;
if(!empty($_GET['next']))
{
	$thispage = $_GET['next'];
}
if(!empty($_GET['previous']))
{
	$thispage = $_GET['previous'];
}
$next = $thispage + $incrementer;
$previous = $thispage - $incrementer;
if($next >= $maxscore)
{
	$stop = $maxscore;
}
else
{
	$stop = $next - 1;
}
$current = $thispage;
$i = $thispage;

util::prepend_title('Recruit Board');

include(BASE_PATH.'doc_top.php');

define('RECRUITS',1);
include(BASE_PATH.'menu/main.php');

include(BASE_PATH.'doc_mid.php');
				// MAIN PAGE INFO
?>

							<div class="title">Recruitment Board</div>
							<div class="copy">
							<?php if($previous > 0): ?>
								<p><a href="index.php?previous=<?php echo $previous; ?>">Previous 100</a></p>
							<?php endif; ?>
							<?php if(count($recruits) > $next): ?>
								<p><a href="index.php?next=<?php echo $next; ?>">Next 100</a></p>
							<?php endif; ?>
							</div>
							<br />
							
						<table width="100%" border="1" cellspacing="0">
						<tr>
							<th><div class="recruit">Name</div></th><th><div class="recruit">Score</div></th><th><div class="recruit">Kills</div></th><th><div class="recruit">Missions</div></th><th><div class="recruit">Flight Time</div></th>
							<th><div class="recruit">ICQ</div></th><th><div class="recruit">Conn.</div></th><th><div class="recruit">Time Zone</div></th><th><div class="recruit">Joined</div></th>
						</tr>
					<?php
					for($start = $thispage; $start <= min(count($recruits),$stop); $start++):
						$recruit = $recruits[$start-1];
						$swpilot = $recruit->get_swpilot();
						$str_rank = util::str_rank($recruit->get_rank());
						$str_time = util::str_time($recruit->get_flight_time());

						?>
							<tr>
							<td align="left"><div class="recruit" style="white-space:nowrap"> <?php echo $i; ?>. <?php echo $str_rank; ?> <?php if($swpilot->get_show_email()): ?><a href="mailto:<?php echo $swpilot->get_email(); ?>"><?php echo substr($recruit->get_pilot_name(), 0, 12); ?></a><?php else:  echo substr($recruit->get_pilot_name(), 0, 12);  endif; ?></div></td>
						<?php
						$score = ($type == "scores") ? "<b>".$recruit->get_score()."</b>" : $recruit->get_score();
						$killcount = ($type == "kills") ? "<b>".$recruit->get_kill_count_ok()."</b>" : $recruit->get_kill_count_ok();
						$missions = ($type == "msns") ? "<b>".$recruit->get_missions_flown()."</b>" : $recruit->get_missions_flown();
						$time = ($type == "time") ? "<b>".$str_time."</b>" : $str_time;
						?>
							<td align="right"><div class="recruit"><?php echo $score; ?></div></td>
								<td align="right"><div class="recruit"><?php echo $killcount; ?></div></td>
								<td align="right"><div class="recruit"><?php echo $missions; ?></div></td>
								<td align="right"><div class="recruit"><?php echo $time; ?></div></td>
								<td><div class="recruit">&nbsp;<?php echo $swpilot->get_ICQ(); ?>&nbsp;</div></td>
								<td><div class="recruit"><?php echo $swpilot->get_fetch_connection_type(); ?></div></td>
								<td><div class="recruit"><?php echo $swpilot->get_fetch_time_zone_hours(); ?><?php if($swpilot->get_fetch_time_zone_minutes()): ?>.5<?php endif; ?> GMT</div></td>
								<td><div class="recruit"><?php echo date('n.j.Y', strtotime($swpilot->get_Member_Since())); ?></div></td>
							</tr>
						<?php

						$i++;
					endfor;
					?>
						</table>


				<?php // END MAIN PAGE INFO ?>
<?php include(BASE_PATH.'doc_bot.php'); ?>

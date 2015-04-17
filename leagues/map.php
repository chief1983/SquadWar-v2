<?php /* 
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
if(!empty($_GET['isfullscreen']))
{
	include('../bootstrap.php');
}

$leagueid = '';
if(!empty($_GET['leagueid']))
{
	$leagueid = $_GET['leagueid'];
	$get_league = league_api::get($leagueid);
}

$fullscreenhref = RELATIVEPATH.'leagues/map.php?isfullscreen=1&amp;leagueid='.$leagueid;

if(empty($get_league)):
?>
League not found.
<?php
else:
	$template = '<iframe class="mapimage" frameborder="0" scrolling="no" src="'.RELATIVEPATH.'leagues/mapimage.php?leagueid='.$leagueid.'">Your browser does not support iframes.</iframe>';
	if(!empty($_GET['isfullscreen'])):
		define('BODYONLY',true);
		util::prepend_title($get_league->get_Title() . ' League Map');
		include(BASE_PATH.'doc_top.php');
		?><div class="mapimage_wrapper">
		<?=$template;?>
		</div>
		<?php
		include(BASE_PATH.'doc_bot.php');
	else: ?>
		<map name="fullscreen" id="fullscreen">
			<area shape="rect" coords="400,0,449,68" href="<?php echo $fullscreenhref; ?>" target="map" alt="fullscreen" />
		</map>		
		<map name="fullscreen2" id="fullscreen2">
			<area shape="rect" coords="400,0,449,68" href="<?php echo $fullscreenhref; ?>" target="map" alt="fullscreen2" />
		</map>				

		<table width="490" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td valign="top" rowspan="3"><img src="<?php echo RELATIVEPATH; ?>images/leagues/3/left.gif" width="12" height="435" alt="" border="0" /><br /></td>
				<td valign="top"><img src="<?php echo RELATIVEPATH; ?>images/leagues/3/top.gif" width="450" height="28" alt="" border="0" /><br /></td>
				<td valign="top" rowspan="3"><img src="<?php echo RELATIVEPATH; ?>images/leagues/3/right.gif" width="28" height="435" alt="" border="0" usemap="#fullscreen2" /><br /></td>
			</tr>
			<tr>
				<td width="450" height="338" align="center">
					<?=$template;?>
				</td>
			</tr>
			<tr>
				<td valign="top"><img src="<?php echo RELATIVEPATH; ?>images/leagues/3/bottom.gif" width="450" height="69" alt=" " border="0" usemap="#fullscreen" /><br /></td>
			</tr>
		</table>
<?php endif;
endif; ?>

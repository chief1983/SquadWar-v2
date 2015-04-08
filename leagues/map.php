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
if(!isset($relativepath))
{
	$relativepath = '';
}

$fullscreenhref = RELATIVEPATH.'leagues/map.php?isfullscreen=1&amp;leagueid='.$leagueid;

if(empty($get_league)):
?>
League not found.

<?php elseif(!empty($_GET['isfullscreen'])):
	util::prepend_title($get_league->get_Title() . ' League Map');
	$relativepath = '../../';
	?><html>
	<head>
	<title><?php echo util::get_title(); ?></title>
	<meta name="Author" lang="en-us" content="Cliff Gordon" />
	<meta name="keywords" lang="en-us" content="Hard Light Productions Source Code Project SquadWar Port PXO FS2NetD" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href="<?php echo RELATIVEPATH; ?>squadwar.css" rel="stylesheet" type="text/css" />
	<!--[if IE]>
	<link href="<?php echo RELATIVEPATH; ?>squadwar-ie.css" rel="stylesheet" type="text/css" />
	<![endif]-->
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo RELATIVEPATH; ?>squadwar.js"></script>
	</head>
	<body style="background-color:#000000">
	<center>

		<!-- URL's used in the movie -->
		<!-- text used in the movie -->

<!--		<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"-->
<!--			codebase="http://active.macromedia.com/flash2/cabs/swflash.cab#version=4,0,0,0"-->
<!--			id="leaguemap2" width="100%" height="100%">-->
<!--			<param name="movie" value="<?php echo $get_league->get_map_location(); ?>" />-->
<!--			<param name="quality" value="high" />-->
<!--			<param name="bgcolor" value="#000000" />-->
<!--			<embed src="<?php echo $get_league->get_map_location(); ?>"-->
<!--				quality="high" bgcolor="#000000"  width="100%" height="100%" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"></embed>-->
<!--		</object>-->
	</center>
	</body>
	</html>
<?php else: ?>

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
<!--					<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"-->
<!--						codebase="http://active.macromedia.com/flash2/cabs/swflash.cab#version=4,0,0,0"-->
<!--						id="leaguemap" width="427" height="321">-->
<!--						<param name="movie" value="<?php echo $get_league->get_map_location(); ?>" />-->
<!--						<param name="quality" value="high" />-->
<!--						<param name="bgcolor" value="#000000" />-->
<!--						<embed src="<?php echo $get_league->get_map_location(); ?>"-->
<!--							quality="high" bgcolor="#000000" width="427" height="321" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"></embed>-->
<!--					</object>-->
				</td>
			</tr>
			<tr>
				<td valign="top"><img src="<?php echo RELATIVEPATH; ?>images/leagues/3/bottom.gif" width="450" height="69" alt=" " border="0" usemap="#fullscreen" /><br /></td>
			</tr>
		</table>
<?php endif; ?>

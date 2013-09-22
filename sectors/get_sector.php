<?php /*
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
include('../bootstrap.php');

util::prepend_title('Get Sector');

if(!empty($_GET['id']))
{
	$rec = squad_api::new_sector_search_record();
	$rec->set_SWSectors_ID($_GET['id']);
	$ret = squad_api::search($rec);
	$ret = squad_api::populate_sector_squad($ret, true);
	$get_sector_info = reset($ret->get_results());

	
}
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title><?php echo util::get_title(); ?></title>
	<link href="<?php echo RELATIVEPATH; ?>squadwar.css" rel="stylesheet" type="text/css" />	
</head>
<body text="#ffffff" bgcolor="#000000" alink="#8AFFA5" link="#5CAA6E" vlink="#5CAA6E">

<?php if(!empty($get_sector_info)): ?>
	<div class="title">System <?php echo $_GET['id']; ?>: <?php echo $get_sector_info->get_SectorName(); ?></div>
	<table>
		<tr>
			<td align="right"><div class="copy"><b>Owner:</b></div></td>
			<td><div class="copy"><?php if(!$get_sector_info->get_SectorSquad()): ?>Unclaimed<?php else: echo $get_sector_info->get_squad()->get_SquadName(); endif; ?></div></td>
		</tr>
	</table>
<?php endif; ?>

</body>
</html>

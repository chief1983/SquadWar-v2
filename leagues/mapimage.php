<?php

define('NOWRAPPER', true);
include('../bootstrap.php');

$map = new nodemap($_GET['leagueid']);

if(!empty($_GET['image']))
{
	$map->output_image($_GET['image']);
	exit;
}

$xml = $map->fetch_graph('svg');
$xml = preg_replace("/^<\?.+?<!--/ms", "<!--", $xml, 1);

$league = league_api::get($_GET['leagueid']);

$style = null;
if(!empty($_GET['bgimage']))
{
	$src = $_GET['bgimage'];
}
elseif($image = $league->get_map_graphics())
{
	$src = RELATIVEPATH."images/{$image}";
}
if(!empty($src))
{
	$repeat = "background-repeat : repeat;";
	if(!empty($_GET['bgrepeat']))
	{
		$repeat = "background-repeat:{$_GET['bgrepeat']};";
	}
	$style = " style=\"background-image:url('{$src}');{$repeat}\"";
}

?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Node Map</title>
	<meta name="description" content="Node Map">
	<meta name="author" content="Cliff Gordon">
	<link rel="icon" href="<?=RELATIVEPATH;?>favicon.ico" type="image/x-icon" />
	<link href="<?=RELATIVEPATH;?>css/squadwar.css" rel="stylesheet" type="text/css" />
	<!--[if IE]>
	<link href="<?=RELATIVEPATH;?>css/squadwar-ie.css" rel="stylesheet" type="text/css" />
	<![endif]-->
	<link href="<?=RELATIVEPATH;?>css/tooltipster.css" rel="stylesheet" type="text/css" />
	<link href="<?=RELATIVEPATH;?>css/tooltipster-light.css" rel="stylesheet" type="text/css" />
	<link href="<?=RELATIVEPATH;?>css/tooltipster-noir.css" rel="stylesheet" type="text/css" />
	<link href="<?=RELATIVEPATH;?>css/tooltipster-punk.css" rel="stylesheet" type="text/css" />
	<link href="<?=RELATIVEPATH;?>css/tooltipster-shadow.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.min.js"></script>
	<script type="text/javascript" src="<?=RELATIVEPATH;?>js/jquery.panzoom.min.js"></script>
	<script type="text/javascript" src="<?=RELATIVEPATH;?>js/jquery.tooltipster.min.js"></script>
	<script type="text/javascript" src="<?=RELATIVEPATH;?>js/squadwar.js"></script>
	<!--[if lt IE 9]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<base target="_parent" />
</head>
<body>
	<div class="svg_wrapper"<?=$style?>>
		<?=$xml;?>
	</div>
</body>
</html>

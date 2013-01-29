<?php

require('../bootstrap.php');
require_once(BASE_PATH.'inc/tga_v2/tga.php');

$squadid = $_SESSION['squadid'];
$squadpassword = $_SESSION['squadpassword'];

$squad = squad_api::get($squadid);

if($squad->get_SquadPassword() != $squadpassword)
{
	// Not authenticate to perform this action.
	$message = urlencode("You are not authenticated properly to claim a match for this squad.");
	util::location(RELATIVEPATH.'error/error.php?message='.$message.'&refer='.$_SERVER['HTTP_REFERER']);
}

$code = $_POST['code'];

$rec = match_api::new_search_record();
$rec->set_SWCode($code);
$ret = match_api::search($rec);
$ret = match_api::populate_info($ret);
$results = $ret->get_results();

$match = reset($results);

if(!$match)
{
	$message = urlencode("Could not find the requested match.  It may have already been decided.");
	util::location(RELATIVEPATH.'error/error.php?message='.$message.'&refer='.$_SERVER['HTTP_REFERER']);
}

// If we found the match, we really better have an info.
$info = $match->get_info();

if(!$info)
{
	$message = urlencode("Found the match, but could not find its extended info.  Something went wrong, please contact admin.");
	util::location(RELATIVEPATH.'error/error.php?message='.$message.'&refer='.$_SERVER['HTTP_REFERER']);
}

$current_phase = $match->get_info()->get_current_phase();

if($current_phase != 4)
{
	$message = urlencode("This match is not in the correct phase of progress to be claimed.");
	util::location(RELATIVEPATH.'error/error.php?message='.$message.'&refer='.$_SERVER['HTTP_REFERER']);
}

$sector = $match->get_SWSector_ID();
$league = $match->get_League_ID();
$first = $match->get_SWSquad1();
$second = $match->get_SWSquad2();

if($first == $squadid)
{
	$winner = $first;
	$loser = $second;
}
elseif($second == $squadid)
{
	$winner = $second;
	$loser = $first;
}
else
{
	$message = urlencode("The squad you are authenticated as was not a belligerent in this match.");
	util::location(RELATIVEPATH.'error/error.php?message='.$message.'&refer='.$_SERVER['HTTP_REFERER']);
}

$name = $_FILES['uploadedfile']['tmp_name'];

$src_img = imagecreatefromtga($name);

if(!$src_img)
{
	$src_img = @imagecreatefromjpeg($name);
}
if(!$src_img)
{
	$src_img = @imagecreatefrompng($name);
}
if(!$src_img)
{
	$src_img = @imagecreatefromgif($name);
}

if(!$src_img)
{
	$message = urlencode("Could not process screenshot.");
	util::location(RELATIVEPATH.'error/error.php?message='.$message.'&refer='.$_SERVER['HTTP_REFERER']);
}

imagepng($src_img, BASE_PATH.'upload/'.$code.'.png');
imagedestroy($src_img);

$result = match_api::award_match(
	$code,
	$sector,
	$first,
	$second,
	$winner,
	$loser,
	$league
);

if(!$result)
{
	$message = urlencode("Match claim was not saved successfully.");
	util::location(RELATIVEPATH.'error/error.php?message='.$message.'&refer='.$_SERVER['HTTP_REFERER']);
}

util::location(RELATIVEPATH.'squads/squadlogin_validate.php?id='.$squadid.'&leagueid='.$league);

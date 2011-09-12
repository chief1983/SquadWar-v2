<?php
include('bootstrap.php');

if(!empty($_POST['login']) && !empty($_POST['password']))
{
	$status = user_api::login($_POST['login'], $_POST['password']);
	switch($status)
	{
		case 0:
			util::location('error/error.php?message=Invalid%20password.&refer='.$_SERVER['HTTP_REFERER']);
			break;
		case 2:
			util::location('error/error.php?message=Invalid%20login.&refer='.$_SERVER['HTTP_REFERER']);
			break;
		case 3:
			util::location('error/error.php?message=User%20not%20validated.&refer='.$_SERVER['HTTP_REFERER']);
			break;
		case 1:
			util::location('index.php');
			break;
	}
}
else
{
	util::location('error/error.php?message=Empty%20login%20or%20password.&refer='.$_SERVER['HTTP_REFERER']);
}
?>

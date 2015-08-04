<?php
function __autoload($class_name)
{
	include_once str_replace('_', '/', $class_name) . '.php';
}

if(function_exists("__autoload")) spl_autoload_register("__autoload");

// do ini override here to set include path
ini_set('include_path',BASE_PATH."inc/:".ini_get('include_path'));

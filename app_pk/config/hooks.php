<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/
$hook['post_controller_constructor'][] = array(
	'class' => 'onload',
	'function' => 'chk_login',
	'filename' => 'onload.php',
	'filepath' => 'hooks'
);
/*
$hook['post_controller_constructor'][] = array(
	'class' => 'onload',
	'function' => 'chk_www',
	'filename' => 'onload.php',
	'filepath' => 'hooks'
);

// compress output
$hook['display_override'][] = array(
	'class' => '',
	'function' => 'compress',
	'filename' => 'compress.php',
	'filepath' => 'hooks'
	);
		*/
/*
$hook['post_controller_constructor'][] = array(
	'function' => 'redirect_ssl',
	'filename' => 'ssl.php',
	'filepath' => 'hooks'
	);
	*/

<?php

define("DEFAULT_SUB_DOMAIN", "www");

function get_sub_domain()
{
	// Does not handle second-level subdomains
	// TODO: Return numeric instead of string?

	$host = explode('.', $_SERVER['HTTP_HOST']);
	$hnum = count($host);
	$is_localhost = false;

	// Workaround for localhost without domain
	if ($host[$hnum-1] == "localhost") {
		$is_localhost = true;
	}

	if (is_numeric($host[0]) ||
	    $hnum < (($is_localhost) ? 2 : 3) ||
	    $host[0] === DEFAULT_SUB_DOMAIN) {
		return DEFAULT_SUB_DOMAIN;
	}

	return $host[0];
}

function get_full_path($file)
{
	$path = dirname(__DIR__);

	// No need to sanitize path here
	return $path . "/data/" . $file;
}

function get_fatal_error()
{
	$err = error_get_last();
	die($err["message"]);
}

function is_local_instance()
{
	return $_SERVER['REMOTE_ADDR'] == '127.0.0.1';
}

function send_file($filename, $inline = true)
{
	$path = get_full_path($filename);
	$type = ($inline) ? "inline" : "attachment";

	$file = fopen($path, "rb");
	if (!$file) {
		http_response_code(404);
		//get_fatal_error();
		exit;
	}

	header("Content-Type: " . mime_content_type($path));
	header("Content-Length: " . filesize($path));
	header("Content-Disposition: " . $type . "; " .
	       "filename= " . basename($filename));
	header('Expires: 0');
	header('Cache-Control: must-revalidate');
	header('Pragma: public');

	fpassthru($file);
	exit;
}

?>

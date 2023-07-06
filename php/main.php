<?php
include "common.php";

function main()
{
	$host  = $_SERVER["HTTP_HOST"];
	$agent = $_SERVER["HTTP_USER_AGENT"];
	$type  = $_SERVER["REQUEST_METHOD"];
	$url   = $_SERVER["REQUEST_URI"];
	$sub   = get_sub_domain();

	if (substr($agent, 0, 4) == "curl") {
		send_file("install.sh");
	}

	if ($type == "GET") {
		switch ($url) {
		case "/git":
		case "/ftp":
		case "/rss":
			return 501;
		case "/key":
		case "/pgp":
			send_file("public.key");
		case "/favicon.ico":
			if (is_local_instance()) {
				send_file("/static/icons/local.ico");
			} else {
				send_file("/static/icons/sparc.ico");
			}
		// Just for testing
		case "/latest.tar.gz":
		case "/latest.sha256":
			if ($sub == "ftp") {
				send_file($url);
			} // Fallthrough
		default:
			// TODO: README parser (cached)
			send_file("static/index.html");
		}
	} elseif ($type == "POST") {
		return 405; // Method not allowed
	}

	return 200;
}

function parse($filename)
{
	/*
	define('T_EMPTY',     0);
	define('T_HEADING',   1);
	define('T_PARAGRAPH', 2);
	define('T_CODE',      3);
	define('T_TODO',      4);
	define('T_URL',       5);

	$pos = 0;
	$max = get_file_size(filename);
	$buf = load($filename);
	$out = "";

	while ($pos < $max) {
		$c = $buf[$pos++];

		if ($c === false) {
			break;
		}

		switch ($c) {
		case "#":
			$n = 1;
			$t = T_HEADING;
		        while ($buf[$pos] == "#") {
				pos++; n++;
			}
			// TODO
			break;
		case " ":
		case "\t":
			$t = T_CODE;
			echo "T_CODE";
			while ($c == " " || $c == "\t") {
				pos++;
			}
			// TODO
			break;
		}
	}

	return $out;
	*/
}

$status = main();
http_response_code($status);
exit($status);
?>

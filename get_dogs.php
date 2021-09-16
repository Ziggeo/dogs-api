<?php
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Headers: *");
	error_reporting(E_ERROR);
	require __DIR__ . '/vendor/autoload.php';

	$config = json_decode(file_get_contents("./config.json"), TRUE);
	$ziggeo = new Ziggeo($config["APPTOKEN"], $config["APPSECRET"]);

	$videos = $ziggeo->videos()->index(array("approved" => "APPROVED", "reverse" => TRUE, "tags" => $config["TAGS"]));

	$result = array();

	foreach ($videos as $video) {
		if (@$video["default_stream"]["effect_profile"])
			$result[] = array(
				"description" => $video["description"] ?: $video["data"]["description"],
				"token" => $video["token"],
				"stream" => $video["default_stream"]["token"],
				"title" => $video["title"] ?: $video["data"]["title"],
				"email" => $video["data"]["email"]
			);
	}

	echo json_encode($result);
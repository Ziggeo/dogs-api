<?php
	//We keep it simple for this example allowing CORS and preventing errors to show up in the API response.
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Headers: *");
	error_reporting(E_ERROR);

	//Loading vendors
	require __DIR__ . '/vendor/autoload.php';

	//Fetching configuration from config.json file
	$config = json_decode(file_get_contents("./config.json"), TRUE);
	//Creating Ziggeo object using the Ziggeo PHP SDK
	$ziggeo = new Ziggeo($config["APPTOKEN"], $config["APPSECRET"]);

	/*
	 * Querying the video objects from Ziggeo. The query is asking for videos that match the following criteria:
	 *  - Videos that has been moderated and approved
	 *  - Videos that match the tags configured in the config.json file
	 *  - We are asking them in reverse order, that means from newest to oldest
	 */
	$videos = $ziggeo->videos()->index(array("approved" => "APPROVED", "reverse" => TRUE, "tags" => implode(",", $config["TAGS"])));

	$result = array();

	foreach ($videos as $video) {
		if (@$video["default_stream"]["effect_profile"]) //This is not strictly necessary. Just checking an attribute according to our configuration
			$result[] = array(
				"description" => $video["description"] ?: $video["data"]["description"],
				"token" => $video["token"],
				"stream" => $video["default_stream"]["token"],
				"title" => $video["title"] ?: $video["data"]["title"],
				"email" => $video["data"]["email"]
			);
	}

	echo json_encode($result);
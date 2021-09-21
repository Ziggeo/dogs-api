<?php
	//We keep it simple for this example allowing CORS and preventing errors to show up in the API response.
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Headers: *");
	error_reporting(E_ERROR);

	//Loading vendors
	require __DIR__ . '/vendor/autoload.php';
	/*
	 * We have configured our webhook in our dashboard to send the information as json_encoded in the request body.
	 * Here we're getting the webhook data and creating an associative array from it.
	 */
	$content = json_decode(file_get_contents("php://input"), TRUE);
	$video = $content["data"]["video"];

	if ($video["approved"] !== NULL) //Video has not been moderated yet
		return;

	//Fetching configuration from config.json file
	$config = json_decode(file_get_contents("./config.json"), TRUE);
	//Creating Ziggeo object using the Ziggeo PHP SDK
	$ziggeo = new Ziggeo($config["APPTOKEN"], $config["APPSECRET"]);

	$stream = $video["default_stream"];
	$desired_tags = $config["TAGS"];
	$desired_tags[] = "cat";
	$desired_tags[] = "human";
	$desired_tags[] = "bird";

	$score_threshold = $config["TAGS_SCORE_THRESHOLD"];
	$found_tag = NULL;
	/*
	 * We check here if the analysis information is present and if it matches our restrictions for our website.
	 * If it does, we approve the video and add a tag to it
	 */
	if (@$stream["video_analysis"] && is_array($stream["video_analysis"])) {
		foreach ($stream["video_analysis"]["frames"] as $frame) {
			foreach ($frame["tags"] as $tag) {
				if (in_array($tag["tag"], $desired_tags) && $tag["score"] > $score_threshold) {
					$found_tag = $tag["tag"];
					break;
				}
			}
			if (@$found_tag)
				break;
		}
	}

	if ($found_tag) {
		$ziggeo->videos()->update($video["token"], array(
			"tags" => $found_tag,
			"approved" => TRUE
		));
	}

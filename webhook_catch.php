<?php
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Headers: *");

	require __DIR__ . '/vendor/autoload.php';

	$content = json_decode(file_get_contents("php://input"), TRUE);
	$config = json_decode(file_get_contents("./config.json"), TRUE);
	$ziggeo = new Ziggeo($config["APPTOKEN"], $config["APPSECRET"]);

	$video = $content["data"]["video"];
	$stream = $video["default_stream"];

	if ($video["approved"] !== NULL) //Video has not been moderated yet
		return;
	$desired_tags = $config["TAGS"];
	$score_threshold = $config["TAGS_SCORE_THRESHOLD"];
	$found_tag = NULL;
	if (@$stream["video_analysis"] && is_array($stream["video_analysis"])) {
		foreach ($stream["video_analysis"]["frames"] as $frame) {
			foreach ($frame["tags"] as $tag) {
				if (in_array($tag["tag"], $desired_tags) && $tag["score"] > $score_threshold) {
					$found_tag = $tag["tag"];
					break;
				}
				if (@$found_tag)
					break;
			}
		}
	}

	if ($found_tag) {
		$ziggeo->videos()->update($video["token"], array(
			"tags" => $found_tag,
			"approved" => TRUE
		));
	}

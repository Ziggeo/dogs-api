# DogLovers API for DogLovers website
Test API for Techsylvania 2021 Ziggeo's Workshop

## Installation
1. Clone the repo.
2. Run `composer install` inside of the repo folder. This will install the [Ziggeo PHP SDK](https://github.com/Ziggeo/ZiggeoPhpSdk)

A list with all of our server side SDKs can be found [here](https://ziggeo.com/docs/sdks/server-side)

### Create config.json file

For this to work, you should create a `config.json` file with the following info

```json
 {
  "APPTOKEN": "Application Token from Ziggeo",
  "APPSECRET": "Application Private key from Ziggeo",
  "TAGS": ["dog", "dogs", "cat", "cats", "capybara", "capybaras", "whatever you need"],
  "TAGS_SCORE_THRESHOLD": 0.7
}
```

## get_dogs.php

API Endpoint to retrieve the videos list from Ziggeo. Check the file comments for more information on how we're doing it.

## webhook_catch.php

[Webhook](https://ziggeo.com/docs/api/webhooks) destination. Ziggeo provide the ability to add webhooks to list to different [events](https://ziggeo.com/docs/api/webhooks/list).

For this example, we're using webhooks to automatically moderate videos when they match one of the desired categories.
This is done by automatically listening to the `video_analysis` event after an event is processed. The `webhook_catch.php` file must be accessible from the internet for this to work.

This is not strictly necessary as you can moderate videos through Ziggeo's Dashboard too. Just remember to also tag them how you want.

## Who is this for?

This is designed as demo for a workshop event that is to happen on 22nd of September 2021. It does not show all of our features, however shows some interesting ways how you could use it.

We invite you to join the event to see more and ask questions you might have.

Anyone can of course use it, even if you are not taking part in the event, just know that there are more features that you could use than shown here.

Please note that this is used together with this website demo repo: https://github.com/Ziggeo/doglovers-website

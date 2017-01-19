# AWeber-PHP
This repo explains and simplifies authentication with the AWeber API.

Included is a helper library and a command line tool to assist with authentication.

## Setup
Install the dependencies with [Composer](https://getcomposer.org/).
```
composer install
```

## Command Line Authentication Tool
This tool is useful if you're trying to manually generate the OAuth credentials to access the AWeber API.


### 1. Determine Authorization URL
This will generate the URL that the AWeber customer uses to authenticate the integration with their account. It is intended to visited in a browser.


* `app_id`: Found on the [My Apps](https://labs.aweber.com/apps) page within your AWeber Labs account.
* `callback_url`: The location the user will be redirected to after authenticating with your integration.

```
php aweber_auth_cli.php app_id=<APP_ID> callback_url=<CALLBACK_URL>
```

Sample:
```
$ php aweber_auth_cli.php app_id=abcd1234 callback_url=http://localhost
https://auth.aweber.com/1.0/oauth/authorize_app/abcd1234?oauth_callback=http%3A%2F%2Flocalhost
```

### 2. Parse redirected URL
The output of this step will give you the correct OAuth credentials needed to access the AWeber API.

* `redirect_url`: This is the URL AWeber directed the user to after authenticating. It must include the query string AWeber included in the redirect.

```
php aweber_auth_cli.php redirect_url=<REDIRECT_URL>
```

Sample:
```
$ php aweber_auth_cli.php redirect_url='http://localhost/?authorization_code=abcd1234%7Cabcd1234%7Cabcd1234%7Cabcd1234%7Cabcd1234%7C'
$consumer_key = "ckey1234";
$consumer_secret = "csecret1234";
$access_key = "akey1234";
$access_secret = "asecret1234";
```

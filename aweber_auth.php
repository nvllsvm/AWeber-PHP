<?php

#require_once('aweber_api/aweber_api.php');
require_once('vendor/autoload.php');


function get_auth_url($app_id, $callback_url = NULL) {
    $auth_url = 'https://auth.aweber.com/1.0/oauth/authorize_app/' . $app_id;
    if ($callback_url) {
        $auth_url = $auth_url . '?oauth_callback=' . urlencode($callback_url);
    }

    return $auth_url;
}


function parse_redirect_url($redirect_url) {
    $parts = parse_url($redirect_url);
    parse_str($parts['query'], $query);

    return $query['authorization_code'];
}


function get_oauth_creds($auth_code) {
    $intermediate_creds = parse_auth_code($auth_code);
    $access_creds = get_access_tokens($intermediate_creds);

    $final_creds = array('consumer_key' => $intermediate_creds['consumer_key'],
                         'consumer_secret' => $intermediate_creds['consumer_secret'],
                         'access_key' => $access_creds['access_key'],
                         'access_secret' => $access_creds['access_secret']);

    return $final_creds;
}


function parse_auth_code($auth_code) {
    $pieces = explode('|', $auth_code);
    $tokens = array('consumer_key' => $pieces[0],
                    'consumer_secret' => $pieces[1],
                    'request_token' => $pieces[2],
                    'token_secret' => $pieces[3],
                    'oauth_verifier' => $pieces[4]);
    return $tokens;
}


function get_access_tokens($intermediate_creds) {
    $aweber = new AWeberAPI($intermediate_creds['consumer_key'], $intermediate_creds['consumer_secret']);
    $aweber->user->requestToken = $intermediate_creds['request_token'];
    $aweber->user->tokenSecret = $intermediate_creds['token_secret'];
    $aweber->user->verifier = $intermediate_creds['oauth_verifier'];

    $creds = $aweber->getAccessToken();

    return array('access_key' => $creds[0],
                 'access_secret' => $creds[1]);
}
